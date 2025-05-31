<?php

function setatus_login_name()
{
	return "user_login";
}

function pr($array)
{
  echo "<pre>";
  print_r($array);
  echo "</pre>";
  die;
}

if(!function_exists('json_minify'))
{
	function json_minify($json) {
    $json = preg_replace('/\s+/', '', $json);
    return $json;
	}
}

if(!function_exists('zoom_meeting_check'))
{
	function zoom_meeting_check($sub_kelas_learning_id)
	{
		$_this =& get_instance();
		$_this->load->model("crud_model");
		$record = $_this->crud_model->read("video_conference",array("sub_kelas_learning_id"=>$sub_kelas_learning_id));
		if($record->num_rows() >= 1){
			return true;
		}
		else{
			return false;
		}
	}
}

if(!function_exists('get_login_name'))
{
	function get_login_name()
	{
		$_this =& get_instance();
		$login_id = $_this->session->user_id;

		$_this->load->model("crud_model");
		$record = $_this->crud_model->read("akun",array("akun_id"=>$login_id))->row();

		$nama_depan = $record->nama_depan;
		$nama_belakang = $record->nama_belakang;

		return $nama_depan." ".$nama_belakang;
	}
}

if(!function_exists('is_ajax'))
{
	function is_ajax()
	{
		$_this =& get_instance();
		if(!$_this->input->is_ajax_request()) {
			show_error('No direct script access allowed', 403);
		}
	}
}


if ( ! function_exists('is_login') ) {

	function is_login()
	{
		$CI =& get_instance();
		if ( $CI->session->setatus_login !== setatus_login_name()) {
			redirect(base_url("auth/login"));
		}
	}

}

if ( ! function_exists('input_name_toJson') ) {
	function input_name_toJson($template, $dataArray, $responseData = null, $currentTaskId = null) {
    foreach ($dataArray as $key => $value) {
      
      $input_value = '';

      if (!is_null($responseData) && !is_null($currentTaskId)) {

          $found = false;
          foreach ($responseData as $response) {

              if ($response->input_name == $key && $response->task_id == $currentTaskId) {
                  $input_value = $response->input_value;
                  $found = true;
                  break;
              }
          }
      }

      $inputField = '<input type="text" id="' . $key . '" name="' . $key . '" value="' . htmlspecialchars($input_value) . '" />';

      $template = str_replace('{' . $key . '}', $inputField, $template);
    }

    return $template;
	}
}

if ( ! function_exists('input_name_toJson_by_user') ) {
	function input_name_toJson_by_user($template, $dataArray, $responseData = null, $currentTaskId = null) {
    foreach ($dataArray as $key => $value) {
      
      $input_value = '';

      if (!is_null($responseData) && !is_null($currentTaskId)) {

          $found = false;
          foreach ($responseData as $response) {

              if ($response->input_name == $key && $response->task_id == $currentTaskId) {
                  $input_value = $response->input_value;
                  $found = true;
                  break;
              }
          }
      }


      $inputField = '<span class="badge badge-light" id="' . $key . '" data-name="' . $key . '">' . $input_value . '</span>';
      // $inputField = '<input type="text" id="' . $key . '" name="' . $key . '" value="' . htmlspecialchars($input_value) . '" />';

      $template = str_replace('{' . $key . '}', $inputField, $template);
    }

    return $template;
	}



}