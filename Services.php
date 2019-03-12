<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Services extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->database();	
		$this->load->model('Model_home','model_home');
		$this->load->model('Model_service','model_service');
		$this->load->helper(array('url','language'));
	}
	public function index()
	{                         
		$slug='services';	
	  	$head= $this->seo->get_metadata($slug);
	  	 $head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
		$this->load->view('home');
		$this->load->view('footer');
	}
     public function health_check(){
		$slug='health-check';	
	  	$head= $this->seo->get_metadata($slug);
	  	$head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
		$data['health_check_services']=$this->model_service->health_check();
	    $data['health_service']=$this->model_service->health_check_service();
		$data['health_check_packages']=$this->model_service->health_check_packages();
    	$data['main_services']=$this->model_service->main_services();
		$this->load->view('services/health-check',$data);
		$this->load->view('footer');

	}

	public function consultation(){
		$slug='consultation';	
	  	$head= $this->seo->get_metadata($slug);
	  	$head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
	  	$data['row'] = $this->model_home->get_all_packages();
		$this->load->view('services/consultation',$data);
		$this->load->view('footer');

	}

	public function dentistry(){
		$slug='dentistry';	
	  	$head= $this->seo->get_metadata($slug);
	  	$head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
		$this->load->view('services/dentistry');
		$this->load->view('footer');

	}
		public function coming_soon(){
		$slug='coming-soon';	
	  	$head= $this->seo->get_metadata($slug);
	  	$head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
		$this->load->view('services/coming_soon');
		$this->load->view('footer');

	}

}