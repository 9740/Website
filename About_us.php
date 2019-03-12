<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class About_us extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->database();	
		$this->load->model('Model_home','model_home');	
		$this->load->helper(array('url','language'));
	}

	function index(){
		$slug='about-us';	
	  	$head= $this->seo->get_metadata($slug);
	  	$head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
		$this->load->view('about_us/about_us');	
		$this->load->view('footer');

	}

	function management_team(){
		$slug='management-team';	
	  	$head= $this->seo->get_metadata($slug);
	    $head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
		$this->load->view('about_us/management_team');	
		$this->load->view('footer');

	}

	function advisary_board(){
		$slug='advisory-board';	
	  	$head= $this->seo->get_metadata($slug);
	  	$head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
		$this->load->view('about_us/advisary_board');	
		$this->load->view('footer');

	}

	

}