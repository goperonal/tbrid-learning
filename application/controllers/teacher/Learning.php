<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Learning extends CI_Controller
{
    function __construct()
	{
		parent::__construct();

		$this->load->helper('string');
		$this->load->model(array('Crud_model'=>'crud','Teacher_model'=>'m_teacher','Live_chat_model'=>'live_chat','common_model'));
		$this->load->library(array('upload','zoom_lib'));
		is_login();
	}

	function index()
	{
		$id = $this->uri->segment(4);

		$where = array(
		    'teacher_id'    => $this->session->user_id
		);

		$data = array(
		    'title'			=> 'Class',
		    'record'		=> $this->m_teacher->get_kelas($where),
		    'sub_kelas'		=> $this->crud->read('sub_kelas',array('sub_kelas_id'=>$id))->row(),
		    'learning'		=> $this->crud->read('sub_kelas_learning',array('sub_kelas_id'=>$id))->row(),
		    'tasks'			=> $this->crud->read('task',array('sub_kelas_id'=>$id)),
		    'live_chat'		=> $this->live_chat->get_chat($id),
    	    'students'		=> $this->m_teacher->get_mhs_live_zoom($id),
		    'live_zoom'		=> zoom_meeting_check($id)
		);
		$this->template->load('template', 'teacher/kelas_learning', $data);
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

    //Upload image summernote
    function upload_image(){
    	if(isset($_FILES["image"]["name"])){
    		// $config['upload_path'] = './uploads/images/';
    		$config['upload_path'] = './uploads/';
    		// $config['allowed_types'] = 'jpg|jpeg|png|gif';
    		$config['allowed_types'] = '*';
    		$this->upload->initialize($config);
    		if(!$this->upload->do_upload('image')){
    			$this->upload->display_errors();
    			return FALSE;
    		}else{
    			$data = $this->upload->data();
                //Compress Image
    			/*$config['image_library']='gd2';
    			$config['source_image']='./uploads/images/'.$data['file_name'];
    			$config['create_thumb']= FALSE;
    			$config['maintain_ratio']= TRUE;
    			$config['quality']= '60%';
    			$config['width']= 800;
    			$config['height']= 800;
    			$config['new_image']= './uploads/images/'.$data['file_name'];
    			$this->load->library('image_lib', $config);
    			$this->image_lib->resize();*/
    			// echo base_url().'uploads/images/'.$data['file_name'];
    			$this->output->set_output(base_url().'uploads/images/'.$data['file_name']);
    		}
    	}
    }

    //Delete image summernote
    function delete_image(){
    	$src = $this->input->post('src');
    	$file_name = str_replace(base_url(), '', $src);
    	if(unlink($file_name))
    	{
    		echo 'File Delete Successfully';
    	}
    }

    public function upload_file() {
        $config['upload_path'] = './uploads/'; // Sesuaikan dengan folder penyimpanan Anda
        $config['allowed_types'] = '*'; // Sesuaikan dengan tipe file yang diperbolehkan
        $config['max_size'] = 100; // Sesuaikan dengan ukuran maksimum file (dalam kilobita)

        // $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            // Jika upload gagal
        	$error = array('error' => $this->upload->display_errors());
        	echo json_encode($error);
        } else {
            // Jika upload berhasil
        	$data = array('upload_data' => $this->upload->data());
        	echo json_encode(array('status' => true, 'filename' => base_url('uploads/' . $data['upload_data']['file_name'])));
        }
    }


    function create_room()
    {
    	is_ajax();
    	if ($this->form_validation->run('teacher/learning/create_room') == FALSE){
			
			$data = array(
				'validation'		=> false,
				'zoom_title' 		=> form_error('zoom_title'),
				'zoom_start_time'	=> form_error('zoom_start_time'),
				'zoom_duration'		=> form_error('zoom_duration')
			);
			$this->output->set_output(json_encode($data));
			
        }
    	else{
    		$title 			= $this->input->post('zoom_title');
    		$time_input		= $this->input->post('zoom_start_time');
    		$duration		= $this->input->post('zoom_duration');
    		$zoom_user_id	= '';
    		
    		$meeting_data	= create_zoom_meeting($title, $duration, $zoom_user_id);
    		if (!empty($meeting_data)) {
    			$room_id	= $meeting_data['id'];
    			$host_id	= $meeting_data['host_email'];
    			$password	= $meeting_data['encrypted_password'];
    			
    			date_default_timezone_set('Asia/Kolkata');
    			// $start_time = date('Y-m-d H:i:s');
    			// $end_time = date('Y-m-d H:i:s', strtotime('+' . $duration . ' minute'));
    			
    			$date		= new DateTime($time_input);
    			$start_time	= $date->format('Y-m-d H:i:s');
    			$end_time	= $date->modify("+{$duration} minutes")->format('Y-m-d H:i:s');

    			$data = array(
    				'sub_kelas_learning_id' => $this->input->post('sub_kelas_id'),
    				'vc_host_id' => $host_id,
    				'vc_room_id' => $room_id,
    				'vc_room_password' => $password,
    				'vc_title' => $title,
    				'vc_duration' => $duration,
    				'vc_start_time' => $start_time,
    				'vc_end_time' => $end_time,
    				'api_response' => json_encode($meeting_data),
    			);

    			$table = 'video_conference';
    			$result = $this->common_model->insert($data, $table);
    			
    			if ((int)$result > 0) {
    				$this->output->set_output(json_encode(array(
    					'validation'	=> true,
    					'result'		=> true,
    					'message'		=> 'Meeting Successfully Added',
    				)));
    			} else {
    				$this->output->set_output(json_encode(array(
    					'validation'	=> true,
    					'result'		=> false,
    					'message'		=> 'Something Went Wrong! Please Try Again',
    				)));
    			}
    		}
    		sleep(2);
    	}
    }


    public function delete_room()
    {
    	$sub_kelas_learning_id = $this->uri->segment(4);

    	$meeting_where = array('sub_kelas_learning_id'=>$sub_kelas_learning_id);
    	$meeting_id = $this->crud->read('video_conference',$meeting_where)->row()->vc_room_id;
    	$response = delete_zoom_meeting($meeting_id);

    	if (isset($response['error'])) {
    	    echo "Error: " . $response['error'];
    	}
    	else {
    	    // echo "Meeting deleted successfully!";
    	    $meeting_where = array('vc_room_id'=>$meeting_id);
    	    $this->crud->delete('video_conference',$meeting_where);
    	    redirect('teacher/learning/index/' . $sub_kelas_learning_id);
    	}
    }


    public function video_conference()
    {
    	$id = $this->uri->segment(4);

    	$where = array(
    	    'teacher_id'    => $this->session->user_id
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
    	    'record'		=> $this->m_teacher->get_kelas($where),
    	    'sub_kelas'		=> $this->crud->read('sub_kelas',array('sub_kelas_id'=>$id))->row(),
    	    'learning'		=> $this->crud->read('sub_kelas_learning',array('sub_kelas_id'=>$id))->row(),
    	    'tasks'			=> $this->crud->read('task',array('sub_kelas_id'=>$id)),
    	    'live_chat'		=> $this->live_chat->get_chat($id),
    	    'students'		=> $this->m_teacher->get_mhs_live_zoom($id),
    	    'user_list'			=> get_zoom_user_list(),
    	    'client_id'			=> $client_id,
	        'signature'			=> $signature,
	        'meeting_id'		=> $meeting_id,
	        'meeting_password'	=> $meeting_password,
	        'zak_token' 		=> $zak_token,
	        'host' 				=> $host,
	        'user_name' 		=> $user_name,
	        'user_email' 		=> $user_email,
    	);

    	// pr($data);
    	$this->template->load('template', 'teacher/kelas_learning_live', $data);
    }

    public function get_student_response()
    {
    	$user_id = $this->input->post('user_id');
    	$sub_kelas_id = $this->input->post('sub_kelas_id');

    	$where = array(
    		'akun_id'		=> $user_id,
    		'sub_kelas_id'	=> $sub_kelas_id,
    	);
    	$data = array(
    		'tasks'			=> $this->crud->read('task',array('sub_kelas_id'=>$sub_kelas_id)),
    		'answer'		=> $this->crud->read('student_response',$where)->result(),

    	);
    	
    	$this->load->view('teacher/kelas_learning_live_by_user', $data);
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