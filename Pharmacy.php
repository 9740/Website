<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Pharmacy extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->database();	
		$this->load->model('Model_home','model_home');	
		$this->load->model('Model_pharmacy','pharmacypartners');	
		$this->load->helper(array('url','language'));
		$this->load->library("pagination");
		$city= $this->session->userdata('city');
	    if($city =="")
	    	{
	       		$data= array(
	           'city' =>'Bangalore',
	          	);  
	       		$this->session->set_userdata($data);    
	  		}
	}

	function index(){
		$config['base_url'] = base_url() ."pharmacy-partners";
		$city= $this->session->userdata('city');
	    if($city =="")
	    	{
	       		$data= array(
	           'city' =>'Bangalore',
	          	);  
	       		$this->session->set_userdata($data);    
	  		}
		$slug='pharmacy-partners';	
	  	$head= $this->seo->get_metadata($slug);
	  	$head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);  	
	   
	        $config["total_rows"] = $this->pharmacypartners->pharmacy_count();
            $config["per_page"] = 10;
            $config["uri_segment"] = 2;        
            $config['full_tag_open'] = '<ul class="pagination">';
		    $config['full_tag_close'] = '</ul>';
		    $config['prev_link'] = '&laquo;';
		    $config['prev_tag_open'] = '<li>';
		    $config['prev_tag_close'] = '</li>';
		    $config['next_tag_open'] = '<li>';
		    $config['next_tag_close'] = '</li>';
		    $config['cur_tag_open'] = '<li class="active"><a href="#">';
		    $config['cur_tag_close'] = '</a></li>';
		    $config['num_tag_open'] = '<li>';
		    $config['num_tag_close'] = '</li>';
      
        $config['next_link'] = '&raquo;';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
	  	$data['hospitals']=$this->pharmacypartners->get_pharmacy($config["per_page"], $page);
	  	$data["links"] = $this->pagination->create_links(); 
    	$data['services']=$this->pharmacypartners->get_pharmacy_location($city); 
    	$data['provinc']=$this->pharmacypartners->get_pharmacy_list();

		$this->load->view('pharmacy/pharmacy_partners_list',$data);	
		$this->load->view('footer');
	}

 function get_pharmacy(){

 	  $pharmacy_id= $this->input->post('pharmacy_id');

       $product_service=$this->pharmacypartners->get_pharmacy_detail($pharmacy_id);
 
            echo json_encode($product_service);
 }
	

		

}