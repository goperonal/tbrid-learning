<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Assignment extends CI_Controller
{
    function __construct()
	{
		parent::__construct();

		$this->load->helper('string');
		$this->load->model(array(
			'Crud_model'		=>'crud',
			'Teacher_model'		=>'m_teacher',
			'E_class_model'		=> 'm_class',
			'Assignment_model'	=> 'm_assignment'
		));
		$this->load->library('upload');
		is_login();
	}

    function index()
    {
        $where = array(
            'teacher_id'    => $this->session->user_id
        );

        $data = array(
            'title' => 'Assignment',
            'record'=> $this->m_teacher->get_kelas($where)
        );
        $this->template->load('template', 'teacher/assignment_index', $data);
    }

    function ajax_datatables()
    {
    	is_ajax();
    	$list = $this->m_assignment->get_datatables();

    	$data = array();
    	$no = $_POST['start'];
    	foreach ($list as $record) {
    		$no++;
    		$row = array();
    		$row[] = $no;
    		$row[] = anchor("teacher/assignment/detail/" . $record->assignment_id,$record->assignment);
    		$row[] = $record->nama_kelas;
    		$row[] = $record->aktif;
    		// $row[] = date('d/m/Y H:m:s',strtotime($record->crated_date));
    		// $row[] = date('d/m/Y H:m:s', strtotime($record->due_date));
    		$row[] = $record->crated_date;
    		$row[] = $record->due_date;
    		// $row[] = "Dell";
    		$data[] = $row;
    	}

    	$output = array(
    		"draw" => $_POST['draw'],
    		"recordsTotal" => $this->m_assignment->count_all(),
    		"recordsFiltered" => $this->m_assignment->count_filtered(),
    		"data" => $data,
    	);

    	$this->output->set_output(json_encode($output));
    }

    public function add()
    {
    	$where = array(
    	    'teacher_id'    => $this->session->user_id
    	);
    	
    	$data = array(
    	    'title' => 'Assignment',
    	    'record'=> $this->m_teacher->get_kelas($where)
    	);
    	$this->template->load('template', 'teacher/assignment_add', $data);
    }

    function save_assignment()
    {
		is_ajax();
        if ($this->form_validation->run('teacher/assignment/add_assignment') == FALSE){
			
			$data = array(
				'assignment' 			=> form_error('assignment'),
				'assignment_intruksi' 	=> form_error('assignment_intruksi'),
				'tanggal_mulai' 		=> form_error('tanggal_mulai'),
				'tanggal_akhir' 		=> form_error('tanggal_akhir'),
				'total_score' 			=> form_error('total_score')
			);
			$this->output->set_output(json_encode($data));
			
        }
        else{
            $data = array(
                'assignment' 	=> $this->input->post('assignment'),
                'teacher_id'    => $this->session->user_id,
                'kelas_id' 		=> $this->input->post('kelas'),
                'intruksi' 	    => $this->input->post('assignment_intruksi'),
                'crated_date' 	=> $this->input->post('tanggal_mulai'),
                'due_date' 		=> $this->input->post('tanggal_akhir'),
                'total_score' 	=> $this->input->post('total_score'),
                'questions'		=> $this->input->post('json_data')
            );
			$this->crud->create("assignment",$data);
			$this->output->set_output(json_encode('sukses'));      
		}
    }

        function update_assignment()
        {
    		is_ajax();
            $data = array(
                'crated_date' 	=> $this->input->post('tanggal_mulai'),
                'due_date' 		=> $this->input->post('tanggal_akhir'),
                'aktif'			=> 'Yes'
            );

            $where = array(
            	'teacher_id'	=> $this->session->user_id,
            	'assignment_id' => $this->input->post('assignment_id'),
            );
			$this->crud->update("assignment", $where, $data);
			$this->output->set_output(json_encode('sukses')); 
        }

	function detail()
	{
	    $id = $this->uri->segment(4);

	    $where = array(
	        'teacher_id' => $this->session->user_id
	    );

	    $assignment_data = $this->m_teacher->get_assignment_by_ID($id);

	    if (!empty($assignment_data)) {
	        $assignment = (object) $assignment_data[0]; 

	        $responses = [];
	        foreach ($assignment_data as $response) {
	            if (!empty($response['question_id'])) {
	                $responses[$response['question_id']] = $response;
	            }
	        }
	    } else {
	        $assignment = null;
	        $responses = [];
	    }

	    $data = array(
	        'title'			=> 'Assignment',
	        'record'		=> $this->m_teacher->get_kelas($where),
	        'assignment'	=> $assignment,
	        'responses'		=> $responses,
	        'ass_mahasiswa' => $this->m_teacher->get_assignment_mhs($assignment->kelas_id, $assignment->assignment_id)
	    );

	    // pr($data['responses']);

	    $this->template->load('template', 'teacher/assignment_detail', $data);
	}


	public function get_student_response()
	{
		$id 		= $this->input->post('assignment_id');
		$user_id 	= $this->input->post('user_id');

		$where = array(
		    'teacher_id' => $this->session->user_id
		);

		$assignment_data = $this->m_teacher->get_assignment_by_student_ID($id,$user_id);

		if (!empty($assignment_data)) {
		    $assignment = (object) $assignment_data[0]; 

		    $responses = [];
		    foreach ($assignment_data as $response) {
		        if (!empty($response['question_id'])) {
		            $responses[$response['question_id']] = $response;
		        }
		    }
		} else {
		    $assignment = null;
		    $responses = [];
		}

		$data = array(
		    'title'			=> 'Assignment',
		    'record'		=> $this->m_teacher->get_kelas($where),
		    'assignment'	=> $assignment,
		    'responses'		=> $responses,
		    'ass_mahasiswa' => $this->m_teacher->get_assignment_mhs($assignment->kelas_id, $assignment->assignment_id)
		);
		
		$this->load->view('teacher/assignment_scoring', $data);
	}


	public function set_nilai_student_response()
	{
		$assresid 	= $this->input->post('assresid');
        $nilai 		= $this->input->post('nilai');

        $result = $this->crud->update('assignment_response',array('as_id'=>$assresid),array('nilai'=>$nilai));
        $this->output->set_output(json_encode(array('assresid'=>$assresid,'nilai'=>$nilai,'result'=>$result)));
	}


}