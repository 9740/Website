<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Billing extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->database();	
		$this->load->model('Model_home','model_home');	
		$this->load->helper(array('url','language'));
	}

}