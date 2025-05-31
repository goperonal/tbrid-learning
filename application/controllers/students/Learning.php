<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Learning extends CI_Controller
{
    function __construct()
	{
		parent::__construct();

		$this->load->helper('string');
		$this->load->model(array('Crud_model'=>'crud','Students_model'=>'m_student','Live_chat_model'=>'live_chat','common_model'));
		$this->load->library(array('upload','zoom_lib'));
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
		    'record'		=> $this->m_student->get_kelas($where),
		    'sub_kelas'		=> $this->crud->read('sub_kelas',array('sub_kelas_id'=>$id))->row(),
		    'learning'		=> $this->crud->read('sub_kelas_learning',array('sub_kelas_id'=>$id))->row(),
		    'tasks'			=> $this->crud->read('task',array('sub_kelas_id'=>$id)),
		    'answer'		=> $this->crud->read('student_response',array('sub_kelas_id'=>$id,'akun_id' => $this->session->user_id))->result(),
		    'live_chat'		=> $this->live_chat->get_chat($id),
		    'students'		=> $this->m_student->get_mhs_live_zoom($id),
		    'live_zoom'		=> zoom_meeting_check($id)
		);
		$this->template->load('template', 'students/kelas_learning', $data);
	}

    function add_learning()
    {
		is_ajax();
        if ($this->form_validation->run('teacher/learning/add_learning') == FALSE){
			
			$data = array(
				'learning_goal' 	=> form_error('learning_goal'),
				'topic' 	        => form_error('topic')
			);
			$this->output->set_output(json_encode($data));
			
        }
        else{
            $data = array(
                'sub_kelas_id'	=> $this->input->post('sub_kelas_id'),
                'learning_goal'	=> $this->input->post('learning_goal'),
                'topic'			=> $this->input->post('topic'),
                'tampil'		=> $this->input->post('tampil')
            );

            $check = $this->crud->read('sub_kelas_learning',array('sub_kelas_id'=>$data['sub_kelas_id']))->row();
            if(empty($check)){
            	$this->crud->create("sub_kelas_learning",$data);
            }
            else{
            	$where = array(
            		'sub_kelas_id'=>$data['sub_kelas_id']
            	);
            	$this->crud->update("sub_kelas_learning",$where,$data);
            }
			
			$this->output->set_output(json_encode('sukses'));       
		}
    }

    function upload_image(){
    	if(isset($_FILES["image"]["name"])){
    		$config['upload_path'] = './uploads/';
    		$config['allowed_types'] = '*';
    		$this->upload->initialize($config);
    		if(!$this->upload->do_upload('image')){
    			$this->upload->display_errors();
    			return FALSE;
    		}else{
    			$data = $this->upload->data();
    			$this->output->set_output(base_url().'uploads/images/'.$data['file_name']);
    		}
    	}
    }

    function delete_image(){
    	$src = $this->input->post('src');
    	$file_name = str_replace(base_url(), '', $src);
    	if(unlink($file_name))
    	{
    		echo 'File Delete Successfully';
    	}
    }

    public function upload_file() {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = '*';
        $config['max_size'] = 100;

        if (!$this->upload->do_upload('file')) {
        	$error = array('error' => $this->upload->display_errors());
        	echo json_encode($error);
        } else {
        	$data = array('upload_data' => $this->upload->data());
        	echo json_encode(array('status' => true, 'filename' => base_url('uploads/' . $data['upload_data']['file_name'])));
        }
    }


    public function video_conference()
    {
    	$id = $this->uri->segment(4);

    	$where = array(
    	    'akun_id'    => $this->session->user_id
    	);

    	$user_name = $this->session->user_name;
    	$user_email = $this->session->user_email;
    	$duration = 30;
    	$zoom_user_id = ''; 
    	$client_id = $meeting_id = $meeting_password = $signature = $zak_token = '';
    	
    	if($this->session->level_akses == 'teacher'):
    	    $host = 1;
    	else:
    	    $host = 0;
    	endif;

    	$active_conference = get_active_conference($id);
    	if (!empty($active_conference)) {
    	    $meetings = $active_conference[0];
    	    $meeting_id = $meetings['vc_room_id'];
    	    $meeting_password = $meetings['vc_room_password'];
    	    $duration = $meetings['vc_duration'];
    	}

    	if ($meeting_id != '' && $duration > 0) {

    	    if ($host == 1) {
    	        $zak_token = generate_zoom_access_key($zoom_user_id);
    	    }

    	    $client_id = $this->zoom_lib->zoom_sdk_client_id();
    	    //required for start and join meeting
    	    $signature = $this->zoom_lib->generate_signature($meeting_id, $host, $duration); 
    	}

    	$data = array(
    	    'title'			=> 'Class (Live Zoom)',
    	    'record'		=> $this->m_student->get_kelas($where),
    	    'sub_kelas'		=> $this->crud->read('sub_kelas',array('sub_kelas_id'=>$id))->row(),
    	    'learning'		=> $this->crud->read('sub_kelas_learning',array('sub_kelas_id'=>$id))->row(),
    	    'tasks'			=> $this->crud->read('task',array('sub_kelas_id'=>$id)),
    	    'answer'		=> $this->crud->read('student_response',array('sub_kelas_id'=>$id))->result(),
    	    'live_chat'		=> $this->live_chat->get_chat($id),
    	    'students'		=> $this->m_student->get_mhs_live_zoom($id),
    	    'user_list'			=> get_zoom_user_list(),
    	    'client_id'			=> $client_id,
	        'signature'			=> $signature,
	        'meeting_id'			=> $meeting_id,
	        'meeting_password'	=> $meeting_password,
	        'zak_token' 			=> $zak_token,
	        'host' 				=> $host,
	        'user_name' 			=> $user_name,
	        'user_email' 		=> $user_email,
    	);

    	// pr($data);
    	$this->template->load('template', 'students/kelas_learning_live', $data);
    }

    public function student_response()
    {
    	$akun_id = $this->session->user_id;
        $sub_kelas_id = $this->input->post('sub_kelas_id');
        $task_id = $this->input->post('task_id');
        $input_name = $this->input->post('input_name');
        $input_value = $this->input->post('input_value');

        $data_insert = $this->prepare_data_insert($akun_id, $task_id, $input_name, $input_value, $sub_kelas_id);
        $data_update = $this->prepare_data_update($input_value);

        $where = $this->prepare_where_clause($akun_id, $task_id, $input_name);

        $task_exists = $this->m_student->task_exists($where);

        if ($task_exists){
            $this->m_student->update_task($where, $data_update);
            $response = array('status' => 'updated', 'message' => 'Data berhasil diperbarui.');
        }
        else{
            $this->m_student->insert_task($data_insert);
            $response = array('status' => 'inserted', 'message' => 'Data berhasil ditambahkan.');
        }

        $this->output->set_output(json_encode($response));
    }

	    private function prepare_data_insert($akun_id, $task_id, $input_name, $input_value, $sub_kelas_id)
	    {
	        return array(
	            'akun_id' 		=> $akun_id,
	            'sub_kelas_id' 	=> $sub_kelas_id,
	            'task_id' 		=> $task_id,
	            'input_name' 	=> $input_name,
	            'input_value' 	=> $input_value
	        );
	    }

	    private function prepare_data_update($input_value)
	    {
	        return array(
	            'input_value' => $input_value
	        );
	    }

	    private function prepare_where_clause($akun_id, $task_id, $input_name)
	    {
	        return array(
	        	'akun_id' 		=> $akun_id,
	            'task_id' 		=> $task_id,
	            'input_name' 	=> $input_name
	        );
	    }


    public function send_chat()
    {
		is_ajax();
	    if ($this->form_validation->run('teacher/learning/send_chat') == FALSE){
			
			$data = array(
				'live_chat_message' => form_error('live_chat_message')
			);
			$this->output->set_output(json_encode($data));
			
	    }
	    else{
	    	$data = array(
	            'user_id' 		=> $this->session->user_id,
	            'sub_kelas_id' 	=> $this->input->post('sub_kelas_id'),
	            'message' 		=> $this->input->post('live_chat_message')
	        );
			$result = $this->crud->create('live_chat',$data);
			if($result == true)
				$this->output->set_output(json_encode('sukses'));     
		}
    }


}