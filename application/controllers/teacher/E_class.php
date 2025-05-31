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
			'Teacher_model'		=>'m_teacher',
			'E_class_model'		=> 'm_class',
			'E_class_detail_model' => 'm_students'
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
            'title' => 'Class list',
            'record'=> $this->m_teacher->get_kelas($where)
        );
        $this->template->load('template', 'teacher/kelas_view', $data);
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
    		$row[] = anchor("teacher/e_class/detail/".$record->kelas_id,$record->nama_kelas);
    		// $row[] = $record->materi;
    		$row[] = $record->jumlah_mahasiswa;
    		$row[] = $record->tutup;
    		$row[] = "<button type='button' class='btn btn-danger btn-sm rounded-0 font-weight-normal py-0 class_dell' data-id='$record->kelas_id'><i class='fas fa fa-trash'></i> Delete</button type='button'>";
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

    function add_class()
    {
		is_ajax();
        if ($this->form_validation->run('teacher/e_class/add_class') == FALSE){
			
			$data = array(
				'nama_kelas' 		=> form_error('nama_kelas'),
				'materi' 	        => form_error('materi')
			);
			$this->output->set_output(json_encode($data));
			
        }
        else{
        	$this->db->trans_begin();
            $data = array(
                'teacher_id'    => $this->session->user_id,
                'nama_kelas'    => $this->input->post('nama_kelas'),
                'materi'        => $this->input->post('materi')
            );
			// $this->crud->create("kelas",$data);
			$kelas_id = $this->m_teacher->simpan_kelas($data);

			$sub_kelas = array(
		        array(
		            'kelas_id'			=> $kelas_id,
            		'nama_sub_kelas'    => "First meeting"
		        )
			);
			$this->db->insert_batch("sub_kelas", $sub_kelas);

			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
			}
			else{
				$this->db->trans_commit();
				$this->output->set_output(json_encode('sukses')); 
			}         
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
    	    'teacher_id'    => $this->session->user_id
    	);

    	$data = array(
    	    'title' => 'Class',
    	    'record'=> $this->m_teacher->get_kelas($where),
    	    'oRecord' => $this->crud->read('kelas',array('kelas_id'=>$kelas_id))->row(),
            'mum_of_students' => $this->crud->read('mahasiswa',array('kelas_id'=>$kelas_id))
    	);
    	$this->template->load('template', 'teacher/kelas_detail', $data);
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
    		$row[] = "<a href='#' class='badge badge-info class_pass_update' data-id='$record->akun_id'><i class='fas fa fa-key'></i></a> <a href='#' class='badge badge-danger class_dell' data-id='$record->nim'><i class='fas fa fa-trash'></i></a>";
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

    function delete_class()
	{
		is_ajax();
		$this->db->trans_begin();
		$id  = $this->input->post('id',true);
		$this->crud->delete('kelas',array('kelas_id'=>$id));
		$this->crud->delete('sub_kelas',array('kelas_id'=>$id));
		// $this->crud->delete('sub_kelas_learning',array('kelas_id'=>$id));
		$this->crud->delete('mahasiswa',array('kelas_id'=>$id));

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}
		else{
			$this->db->trans_commit();
			$this->output->set_output(json_encode(true)); 
		} 
	}

    function lock_unlock_class()
	{
		is_ajax();
		$kelas_id  = $this->input->post('id',true);
		$kelas_status  = $this->input->post('status',true);

		$status = 1;

		if($kelas_status == 1)
			$status = 2;

		$result = $this->crud->update('kelas',array('kelas_id'=>$kelas_id),array('tutup'=>$status));
		$this->output->set_output(json_encode($result));
	}


    function update_password()
    {
		is_ajax();
        if ($this->form_validation->run('teacher/e_class/update_password') == FALSE){
			
			$data = array(
				'password' 				=> form_error('password'),
				'konfirmasi_password' 	=> form_error('konfirmasi_password')
			);
			$this->output->set_output(json_encode($data));
			
        }
        else{
        	$akun_id = $this->input->post('update_pass_akun_id');
            $data = array(
                'password' 	=> password_hash($this->input->post('password'),PASSWORD_DEFAULT)
            );
            $where = array('akun_id'=>$akun_id);
			$result = $this->crud->update('akun',$where,$data);
			if($result == true)
				$this->output->set_output(json_encode('sukses'));     
		}
    }


}