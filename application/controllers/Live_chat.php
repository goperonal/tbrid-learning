<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Live_chat extends CI_Controller {

    function __construct()
	{
		parent::__construct();

		$this->load->helper('string');
		$this->load->model(array('Live_chat_model'=>'live_chat'));
		is_login();
	}

	public function index($id)
	{
		$data = array(
			'live_chat'		=> $this->live_chat->get_chat($id),
		);
		$this->load->view('fetch_live_chat',$data);
	}
}
