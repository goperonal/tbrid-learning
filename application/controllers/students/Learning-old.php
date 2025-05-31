<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Learning extends CI_Controller
{
    function __construct()
	{
		parent::__construct();

		$this->load->helper('string');
		$this->load->model(array('Crud_model'=>'crud','Students_model'=>'m_students'));
		$this->load->library('upload');
		is_login();
	}

	function index()
	{
		$id = $this->uri->segment(4);

		$where = array(
            'akun_id'    => $this->session->user_id
        );

		$data = array(
		    'title'			=> 'Class',
		    'record'		=> $this->m_students->get_kelas($where),
		    'sub_kelas'		=> $this->crud->read('sub_kelas',array('sub_kelas_id'=>$id))->row(),
		    'learning'		=> $this->crud->read('sub_kelas_learning',array('sub_kelas_id'=>$id))->row(),
		    'tasks'			=> $this->crud->read('task',array('sub_kelas_id'=>$id))
		);
		$this->template->load('template', 'students/kelas_learning', $data);
	}


}