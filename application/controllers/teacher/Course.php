<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Course extends CI_Controller
{
    function __construct()
	{
		parent::__construct();

		$this->load->helper('string');
		$this->load->model(array(
			'Crud_model'		=>'crud',
			'Teacher_model'		=>'m_teacher',
			'E_class_model'		=>'m_class'
		));
		$this->load->library('upload');
		is_login();
	}

    public function index()
    {
		is_ajax();
        if ($this->form_validation->run('teacher/course/index') == FALSE){
			
			$data = array(
				'course' 		=> form_error('course')
			);
			$this->output->set_output(json_encode($data));
			
        }
        else{
            $data = array(
                'kelas_id'			=> $this->input->post('course_kelas_id'),
                'nama_sub_kelas'	=> $this->input->post('course')
            );
			$this->crud->create("sub_kelas",$data);
			$this->output->set_output(json_encode('sukses'));         
		}
    }

    	function course_check(){
    		$where = array(
	            'kelas_id'    		=> $this->input->post('course_kelas_id'),
	            'nama_sub_kelas'	=> $this->input->post('course')
	        );

	        $record = $this->crud->read("sub_kelas",$where);

    	    $check = TRUE;
    	    if ($this->input->post('course') == NULL || $this->input->post('course') == "") {
    	        $this->form_validation->set_message('course_check', 'The Course field is required.');
    	        $check = FALSE;
    	    }
    	    else if ($record->num_rows() >= 1) {
    	    	$this->form_validation->set_message('course_check', 'The Course field must contain a unique value.');
    	        $check = FALSE;
    	    }
    	    return $check;
    	}


}