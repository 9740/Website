<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Channels extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->database();	
		$this->load->model('Model_home','model_home');	
		$this->load->helper(array('url','language'));
	}
	
	public function corporate()
	{                         
		$slug='corporate';	
	  	$head= $this->seo->get_metadata($slug);
	  	 $head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
	  	$data['row'] = $this->model_home->get_all_packages();
	  	$data['network']=$this->model_home->corporate_partner_logo(); 
		$this->load->view('channels/corporate',$data);
		$this->load->view('footer');
	}
	
	public function channels(){

		$slug='channels';	
	  	$head= $this->seo->get_metadata($slug);
	  	 $head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
	   
		$this->load->view('channels/channels');
		$this->load->view('footer');

	}
	
	public function retail(){

		$slug='retail';	
	  	$head= $this->seo->get_metadata($slug);
	  	$head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
	    $data['healthpackage'] =$this->model_home->get_all_packages($city);	
		$this->load->view('channels/retail',$data);
		$this->load->view('footer');

	}
	public function school(){

		$slug='school';	
	  	$head= $this->seo->get_metadata($slug);
	  	 $head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
	    $data['healthpackage'] = $this->model_home->get_all_packages($city);
		$this->load->view('channels/school', $data);
		$this->load->view('footer');

	}


}