<?php
defined('BASEPATH') or exit('No direct script access allowed');

class E_class extends CI_Controller
{
    function __construct()
	{
		parent::__construct();

		$this->load->helper('string');
		$this->load->model(array(
			'Crud_model'		=>'crud',
			'Students_model'	=>'m_student',
			'E_class_model'		=> 'm_class',
			'E_class_detail_model' => 'm_students'
		));
		$this->load->library('upload');
		is_login();
	}

    function index()
    {
        $where = array(
            'akun_id'    => $this->session->user_id
        );

        $data = array(
            'title' => 'Class list',
            'record'=> $this->m_student->get_kelas($where)
        );
        $this->template->load('template', 'students/kelas_view', $data);
    }

    function class_ajax_datatables()
    {
    	is_ajax();
    	$list = $this->m_class->get_datatables();

    	$data = array();
    	$no = $_POST['start'];
    	foreach ($list as $record) {
    		$no++;
    		$row = array();
    		$row[] = $no;
    		$row[] = anchor("students/e_class/detail/".$record->kelas_id,$record->nama_kelas);
    		// $row[] = $record->materi;
    		$row[] = $record->jumlah_mahasiswa;
    		$row[] = $record->tutup;
    		// $row[] = "<button type='button' class='btn btn-danger btn-sm rounded-0 font-weight-normal py-0 class_dell' data-id='$record->kelas_id'><i class='fas fa fa-trash'></i> Delete</button type='button'>";
    		$data[] = $row;
    	}

    	$output = array(
    		"draw" => $_POST['draw'],
    		"recordsTotal" => $this->m_class->count_all(),
    		"recordsFiltered" => $this->m_class->count_filtered(),
    		"data" => $data,
    	);

    	$this->output->set_output(json_encode($output));
    }

    function join_class()
    {
		is_ajax();
        if ($this->form_validation->run('students/join_class') == FALSE){
			
			$data = array(
				'kode_kelas' 	=> form_error('kode_kelas'),
				'nim' 	        => form_error('nim'),
				'validation'	=> 'error'
			);
			$this->output->set_output(json_encode($data));
			
        }
        else{

            $data = array(
                'kelas_id'    	=> $this->input->post('kode_kelas'),
                'akun_id'    	=> $this->session->user_id,
                'nim'        	=> $this->input->post('nim')
            );

            
        	$kelas = $this->crud->read('kelas',array('kelas_id'=>$data['kelas_id']))->row();

        	// cek apakah kode kelas ada atau tidak
        	if(empty($kelas)){
        		$this->output->set_output(json_encode('empty'));
        		return FALSE;
        	}
        	
			// cek apakah kelas terkunci
        	if($kelas->tutup == 2){
        		$this->output->set_output(json_encode('locked')); // 2=lock
        		return FALSE;
        	}

        	// cek apakah mahasiswa sudah terdaftar di kelas ini
        	$mahasiswa = $this->crud->read('mahasiswa',$data);
        	if($mahasiswa->num_rows() >= 1){
        		$this->output->set_output(json_encode('joinded'));
        		return FALSE;
        	}

			$result = $this->crud->create("mahasiswa",$data);
			$this->output->set_output(json_encode($result));         
		}
    }

    	function nama_kelas_check(){
    		$where = array(
	            'teacher_id'    => $this->session->user_id,
	            'nama_kelas'	=> $this->input->post('nama_kelas')
	        );

	        $record = $this->m_teacher->get_kelas($where);

    	    $check = TRUE;
    	    if ($this->input->post('nama_kelas') == NULL || $this->input->post('nama_kelas') == "") {
    	        $this->form_validation->set_message('nama_kelas_check', 'The Class name field is required.');
    	        $check = FALSE;
    	    }
    	    else if (!empty($record)) {
    	    	$this->form_validation->set_message('nama_kelas_check', 'The Class name field must contain a unique value.');
    	        $check = FALSE;
    	    }
    	    return $check;
    	}

    function detail($kelas_id)
    {
    	$where = array(
    	    'akun_id'    => $this->session->user_id
    	);

    	$data = array(
    	    'title' => 'Class',
    	    'record'=> $this->m_student->get_kelas($where),
    	    'oRecord' => $this->crud->read('kelas',array('kelas_id'=>$kelas_id))->row(),
            'mum_of_students' => $this->crud->read('mahasiswa',array('kelas_id'=>$kelas_id))
    	);
    	$this->template->load('template', 'students/kelas_detail', $data);
    }

    function students_ajax_datatables()
    {
    	is_ajax();
    	$list = $this->m_students->get_datatables();

    	$data = array();
    	$no = $_POST['start'];
    	foreach ($list as $record) {
    		$no++;
    		$row = array();
    		$row[] = $no;
    		$row[] = $record->nim;
    		$row[] = $record->nama_depan . ' ' .$record->nama_belakang;
    		$row[] = $record->email;
    		$data[] = $row;
    	}

    	$output = array(
    		"draw" => $_POST['draw'],
    		"recordsTotal" => $this->m_students->count_all(),
    		"recordsFiltered" => $this->m_students->count_filtered(),
    		"data" => $data,
    	);

    	$this->output->set_output(json_encode($output));
    }


}