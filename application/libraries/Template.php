<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Template
{
	var $template_data = array();

	function set($name, $value)
	{
		$this->template_data[$name] = $value;
	}

	function load($template = '', $view = '', $view_data = array(), $return = FALSE)
	{
		$_this = &get_instance();
		$this->set('contents', $_this->load->view($view, $view_data, TRUE));
		return $_this->load->view($template, $this->template_data, $return);
	}
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */