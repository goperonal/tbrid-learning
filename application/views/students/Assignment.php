<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Assignment extends CI_Controller
{
    function __construct()
	{
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
	    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	    header('Access-Control-Allow-Headers: Content-Type, Authorization');

		$this->load->helper('string');
		$this->load->model(array(
			'Crud_model'		=>'crud',
			'Students_model'	=>'m_students',
			'E_class_model'		=> 'm_class',
			'Assignment_model'	=> 'm_assignment'
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
            'title' => 'Assignment',
            'record'=> $this->m_students->get_kelas($where)
        );
        $this->template->load('template', 'students/assignment_index', $data);
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
    		$row[] = anchor("students/assignment/detail/" . $record->assignment_id,$record->assignment);
    		$row[] = $record->nama_kelas;
    		$row[] = $record->aktif;
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

    	// $this->output->set_output(json_encode($output));
        echo json_encode($output, JSON_PRETTY_PRINT);
        exit;
    }

    function detail()
    {
        $id = $this->uri->segment(4);

        $where = array(
            'akun_id' => $this->session->user_id
        );

        $assignment_data = $this->m_students->get_assignment_by_ID($id);

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
            'title'      => 'Assignment',
            'record'     => $this->m_students->get_kelas($where),
            'assignment' => $assignment,
            'responses'  => $responses
        );

        $this->template->load('template', 'students/assignment_detail', $data);
    }

    public function update_status() {
        $status = $this->input->post('status');
        $assignment_id = $this->input->post('assignment_id');

        if ($status == 'finished') {

            $result = $this->crud->update("assignment",array("assignment_id"=>$assignment_id),array("aktif"=>"No"));

            if ($result) {
                $response = ['status' => 'success', 'message' => 'Status updated to no'];
            } else {
                $response = ['status' => 'error', 'message' => 'Failed to update status'];
            }

            $this->output->set_output(json_encode($response));
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid status'];
            $this->output->set_output(json_encode($response));
        }
    }


    public function upload_audio_video() {
        header("Access-Control-Allow-Origin: *");
        
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        set_error_handler([$this, 'error_handler']);

        $audio_filename	= $this->input->post('audio-filename');
        $assignment_id	= $this->input->post('assignment_id');
        $question_id	= $this->input->post('question_id');

        if (empty($audio_filename)) {
            $this->output->set_output(json_encode('Empty file name.'));
            return;
        }

        $file_name = '';
        $file_tmp = '';
        $file_idx = '';

        if (!empty($_FILES['audio-blob']['name'])) {
            $file_idx = 'audio-blob';
            $file_name = $audio_filename;
            $file_tmp = $_FILES[$file_idx]['tmp_name'];
        }

        if (empty($file_name) || empty($file_tmp)) {
            $this->output->set_output(json_encode('Invalid file name or temp file.'));
            return;
        }

        $config['upload_path'] = './uploads/audio/';
        $config['allowed_types'] = '*';
        $config['file_name'] = $file_name;
        $config['overwrite'] = TRUE;

        $this->upload->initialize($config);

        if($this->upload->do_upload($file_idx)){
        	$data = array(
        		'akun_id'		=> $this->session->user_id,
        		'assignment_id' => $assignment_id,
        		'question_id'	=> $question_id,
        		'file_name'		=> $file_name

        	);

        	$where_chack = array(
        		'akun_id'		=> $this->session->user_id,
        		'assignment_id' => $assignment_id,
        		'question_id'	=> $question_id

        	);
        	
        	$check = $this->crud->read("assignment_response",$where_chack);

        	if($check->num_rows() >= 1) {
        	    $check_result = $check->row();
        	    $file_path = "./uploads/audio/".$check_result->file_name;

        	    if(file_exists($file_path)) {
        	        unlink($file_path);
        	        $this->crud->delete("assignment_response",$where_chack);
        	    }
        	    else {
        	        $this->crud->delete("assignment_response",$where_chack);
        	    }
        	}


        	$this->crud->create("assignment_response",$data);
        }
        else{
            $error = array('error' => $this->upload->display_errors());
            $this->output->set_output(json_encode($error));
        }

        $this->output->set_output(json_encode('success'));
    }

    public function error_handler($errno, $errstr) {
        $this->output->set_output(json_encode('<h2>Upload failed.</h2><br>'));
        $this->output->set_output(json_encode($errstr));
    }


}