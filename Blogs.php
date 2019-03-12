<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Blogs extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->database();	
		$this->load->model('Model_home','model_home');	
		$this->load->helper(array('url','language'));
	}
	
	function index(){
	   $slug='tax-reduction-on-preventive-health-checkup';	
	  	$head= $this->seo->get_metadata($slug);
	  	 $head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
		$this->load->view('blogs/tax-reduction-on-preventive-health-checkup');
		$this->load->view('footer');

	}

}