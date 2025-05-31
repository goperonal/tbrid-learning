<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    function __construct()
	{
		parent::__construct();

		$this->load->helper('string');
		$this->load->model(array('Crud_model'=>'crud','Teacher_model'=>'m_teacher','Students_model'	=>'m_student','Auth_model'=>'m_auth'));
		$this->load->library('upload');
		is_login();
	}

	function index()
	{
		if($this->session->level_akses == 'teacher'):
			$where = array(
			    'teacher_id'    => $this->session->user_id
			);

			$data = array(
			    'title'			=> 'Profile',
			    'record'		=> $this->m_teacher->get_kelas($where),
			    'profile'		=> $this->m_auth->get_profile()
			);
			$this->template->load('template', 'teacher/profile', $data);
		else:
			$where = array(
			    'akun_id'    => $this->session->user_id
			);

			$data = array(
			    'title'			=> 'Profile',
			    'record'		=> $this->m_student->get_kelas($where),
			    'profile'		=> $this->m_auth->get_profile()
			);
			$this->template->load('template', 'students/profile', $data);
		endif;
	}

	public function update_proses()
	{
		if ($this->form_validation->run('profile/update_proses') == FALSE){
			
			$data = array(
				'nama_depan' 		=> form_error('nama_depan'),
				'nama_belakang'     => form_error('nama_belakang'),
				'email' 		    => form_error('email'),
				'password' 	        => form_error('password'),
				'konfirmasi_password'  => form_error('konfirmasi_password')
			);
			$this->output->set_output(json_encode($data));
			
		}
		else{
			if($this->input->post('password') !== ""):
				$data = array(
					'nama_depan'    => $this->input->post('nama_depan'),
					'nama_belakang' => $this->input->post('nama_belakang'),
					'email'         => $this->input->post('email'),
					'password'      => password_hash($this->input->post('password'),PASSWORD_DEFAULT)
				);
			else:
				$data = array(
					'nama_depan'    => $this->input->post('nama_depan'),
					'nama_belakang' => $this->input->post('nama_belakang'),
					'email'         => $this->input->post('email')
				);
			endif;
			$where = array('akun_id'=>$this->session->user_id);
			$this->crud->update('akun',$where,$data);
			$this->output->set_output(json_encode('sukses'));
		}
	}


}