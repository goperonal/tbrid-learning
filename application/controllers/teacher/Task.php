<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task extends CI_Controller
{
    function __construct()
	{
		parent::__construct();

		$this->load->helper('string');
		$this->load->model(array('Crud_model'=>'crud','Teacher_model'=>'m_teacher'));
		$this->load->library('upload');
		is_login();
	}

    function add($id)
    {
    	$where = array(
    	    'teacher_id'    => $this->session->user_id
    	);

    	$data = array(
    	    'title'			=> 'Class',
    	    'record'		=> $this->m_teacher->get_kelas($where),
    	    'sub_kelas'		=> $this->crud->read('sub_kelas',array('sub_kelas_id'=>$id))->row(),
    	    'learning'		=> $this->crud->read('sub_kelas_learning',array('sub_kelas_id'=>$id))->row()
    	);
    	$this->template->load('template', 'teacher/task_add', $data);
    }

    function save()
    {
    	if(isset($_POST['submit'])){

    		$content_type = $this->input->post('content_type');

    		/*if($content_type == 'multiple-choices'){
    			$json = array(
    				'question' => $this->input->post('question'),
    				'option_a' => $this->input->post('option_a'),
    				'option_b' => $this->input->post('option_b'),
    				'option_c' => $this->input->post('option_c'),
    				'option_d' => $this->input->post('option_d')
    			);

    			$data = array(
    				'sub_kelas_id' 	=> $this->uri->segment(4),
    				'content' 		=> json_encode($json),
    				'jenis_task'		=> 'multiple-choices'
    			);

    			$this->task_save_proses($data);
    		}*/
            if($content_type == 'multiple-choices'){
                $uniq_id = uniqid();  // Menghasilkan task_id unik
                $json = array(
                    'question' => $this->input->post('question'),
                    'option_a' => $this->input->post('option_a'),
                    'option_b' => $this->input->post('option_b'),
                    'option_c' => $this->input->post('option_c'),
                    'option_d' => $this->input->post('option_d')
                );

                // Menyimpan nama opsi dengan task_id agar unik
                $data = array(
                    'sub_kelas_id'  => $this->uri->segment(4),
                    'content'       => json_encode(array_merge($json, ['uniqid' => $uniq_id])),
                    'jenis_task'    => 'multiple-choices'
                );

                $this->task_save_proses($data);
            }

    		elseif($content_type == 'fill-the-blank'){

    			$data = array(
    				'sub_kelas_id' 	=> $this->uri->segment(4),
    				'content' 		=> $this->input->post('intruksi'),
    				'form_task' 	=> $this->input->post('json_data'),
    				'jenis_task'	=> 'fill-the-blank'
    			);
    			$this->task_save_proses($data);
    		}
    		else{
    			$data = array(
    				'sub_kelas_id' 	=> $this->uri->segment(4),
    				'content' 		=> $this->input->post('content')
    			);
    			$this->task_save_proses($data);
    		}
    		
    		redirect('teacher/learning/index/' . $this->uri->segment(4));
    	}
    }
    	private function task_save_proses($data){
    		if($this->input->post('task_id') != ""){
    			$where = array(
    				'task_id'	=> $this->input->post('task_id')
    			);
    			$this->crud->update("task",$where,$data);
    		}
    		else{
    			$this->crud->create("task",$data);
    		}
    	}

		/*private function input_name_toJson($template, $dataArray) {
	        foreach ($dataArray as $key => $value) {
	            $inputField = '<input type="text" id="' . $key . '" name="' . $key . '" class="w-100" />';
	            $template = str_replace('{' . $key . '}', $inputField, $template);
	        }
	        return $template;
	    }*/

    function edit($id)
    {
    	$where = array(
    	    'teacher_id'    => $this->session->user_id
    	);

    	$data = array(
    	    'title'			=> 'Class',
    	    'tab_active'	=> $this->input->get('act'),
    	    'record'		=> $this->m_teacher->get_kelas($where),
    	    'sub_kelas'		=> $this->crud->read('sub_kelas',array('sub_kelas_id'=>$id))->row(),
    	    'learning'		=> $this->crud->read('sub_kelas_learning',array('sub_kelas_id'=>$id))->row(),
    	    'task'			=> $this->crud->read('task',array('task_id'=>$id))->row()
    	);
    	$this->template->load('template', 'teacher/task_edit', $data);
    }

    function delete()
	{
		is_ajax();
		$id  = $this->input->post('id',true);
		$result = $this->crud->delete('task',array('task_id'=>$id));

		$this->output->set_output(json_encode($result));
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


}