<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->database();	
		$this->load->model('Model_home','model_home');	
        $this->load->model('Welezo_Authentication','welezo_authentication');
		$this->load->helper(array('url','language'));
		$this->load->library("pagination");
		$this->load->helper('date');
		$this->load->library('seo');	

	}
	public function index(){
		$slug='checkout';	
	  	$head= $this->seo->get_metadata($slug);
       
		if($this->input->post()){
			
			$this->session->unset_userdata('cart_session');
			$this->session->set_flashdata('alert','<div class="alert alert-success" role="alert">Your order has been sent.</div>');
			redirect('');
		}
		 $head['cart_session'] = $this->session->userdata('cart_session');   
	    $this->load->view('header',$head);
	    $head['package'] = $this->model_home->get_all_packages();	
	    $this->load->view('welezo-cart',$head);		
		$this->load->view('footer');
	
	}
	
	
	
	
}
