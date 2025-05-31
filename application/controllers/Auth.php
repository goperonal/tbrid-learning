<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('Auth_model'=>'a_model','Crud_model'=>'crud'));
	}

	public function index()
	{
		$data = array(
			'title' => 'Registrasi',
		);
		$this->template->load('template', 'regist_view', $data);
	}

	public function regist_proses()
	{
		if ($this->form_validation->run('auth/regist_proses') == FALSE){
			
			$data = array(
				'nama_depan' 		=> form_error('nama_depan'),
				'nama_belakang'     => form_error('nama_belakang'),
				'institusi' 	    => form_error('institusi'),
				'nim' 	    		=> form_error('nim'),
				'email' 		    => form_error('email'),
				'password' 	        => form_error('password'),
				'confirm_password'  => form_error('confirm_password'),
				'level'             => form_error('level')
			);
			$this->output->set_output(json_encode($data));
			
		}
		else{
        	// $nim = $this->input->post('nim');
			$data = array(
				'nama_depan'    => $this->input->post('nama_depan'),
				'nama_belakang' => $this->input->post('nama_belakang'),
				'institusi'     => $this->input->post('institusi'),
                // 'nim'         	=> $nim != ""?$nim:null,
				'email'         => $this->input->post('email'),
				'password'      => password_hash($this->input->post('password'),PASSWORD_DEFAULT),
				'level_akses'   => $this->input->post('level')
			);
			$this->a_model->registrasi($data);
			$this->output->set_output(json_encode('sukses'));
		}
	}

	public function login()
	{
		$data = array(
			'title' => 'Login',
		);
		$this->template->load('template', 'login_view', $data);
	}

	public function login_proses()
	{
		if ($this->form_validation->run('auth/login_proses') == FALSE){
			
			$data = array(
				'validation'        => false,
				'email' 		    => form_error('email'),
				'password' 	        => form_error('password')
			);
			$this->output->set_output(json_encode($data));
			
		}
		else{
			$email      = $this->input->post('email',true);
			$password   = $this->input->post('password',true);

			$record = $this->crud->read("akun",array('email'=>$email))->row();

			if ($record){
				if (password_verify($password, $record->password)){
					setcookie("kcfinder_login", "true", time() + 6 * 3600, "/");
					$this->session->set_userdata([
						'setatus_login'	=> setatus_login_name(),
						'user_id'	    => $record->akun_id,
						'user_email'	=> $record->email,
						'user_name'	    => $record->nama_depan . ' ' . $record->nama_belakang,
						'level_akses'   => $record->level_akses
                    ]);

					$data_log = array(
						'user_id' 	    => $record->akun_id,
						'os'            => $this->agent->platform(),
						'browser'       => $this->agent->browser().' '.$this->agent->version(),
						'ip_address'    => $this->input->ip_address(),
						'waktu'		    => date('Y-m-d h:m:s') 
					);

					$this->crud->create('riwayat_login',$data_log);
					$this->output->set_output(json_encode(array('login'=>true,'message'=>'Welcame back!')));
				}
				else{
					$data = array(
						'login'     => false,
						'message'   => 'Login failed, wrong email or password!'
					);
					$this->output->set_output(json_encode($data));
				}
			}
			else{
				$data = array(
					'login'     => false,
					'message'   => 'Login failed, wrong email or password!'
				);
				$this->output->set_output(json_encode($data));
			}

		}
	}

	public function nim_check(){
		$check = TRUE;
		if ($this->input->post('level') == 'student' && $this->input->post('nim') == NULL) {
			$this->form_validation->set_message('nim_check', 'The Nim field is required.');
			$check = FALSE;
		}
		else{
			$check = TRUE;
		}
		return $check;
	}


	public function login_success()
	{
		if($this->session->level_akses == 'teacher')
		{
			redirect("teacher/e_class");
		}
		else{
			redirect("students/e_class");
		}
	}


	public function logout()
	{
		// destroy peserta login session
		// $this->session->unset_userdata(array('setatus_login', 'akun_id', 'user_email', 'user_name', 'level_akses'));
		$this->session->sess_destroy();
		setcookie("kcfinder_login", "", time() - 3600, "/");
		redirect('auth/login');
	}
}