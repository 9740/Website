<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Homehealth extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->database();	
		$this->load->model('Model_home','model_home');	
		$this->load->model('Model_homehealth','homehealth');  
		$this->load->helper(array('url','language'));
		$this->load->library("pagination");
		$this->load->helper('date');

	}
    function index()
    
    {

        $slug='home-health';	
	  	$head= $this->seo->get_metadata($slug);
        $head['cart_session'] = $this->session->userdata('cart_session');
          $city= $this->session->userdata('city');
          if($city==""){
             $data= array(
                 'city' =>'Bangalore',
                );  
              $this->session->set_userdata($data);    
          }
          $data['network']=$this->model_home->network_partner_logo();
          $data['testimonials'] = $this->model_home->testimonials();
          $data['homehealthpackage'] = $this->homehealth->get_popular_homehealthpackages($city);
          $data['homehealthheader'] = $this->homehealth->get_all_homehealthpackages($city); 

           $this->load->view('header',$head);
          $this->load->view('homehealth/home',$data);
          $this->load->view('footer');

    }

	function packages()

  {
      	$slug='homehealth-packages';	
	  	$head= $this->seo->get_metadata($slug);
        $head['cart_session'] = $this->session->userdata('cart_session');    
      		$city= $this->session->userdata('city');
          if($city==""){
             $data= array(
                 'city' =>'Bangalore',
                );  
              $this->session->set_userdata($data);    
          }      		
      		$data['row'] = $this->homehealth->get_all_homehealthpackages($city);
           $data['service'] = $this->homehealth->get_all_homehealthservice(); 
           $data['subcategory'] = $this->homehealth->get_all_homehealthparameter();
      		 $this->load->view('header',$head);
      		$this->load->view('homehealth/homehealth_packages',$data);
      		$this->load->view('footer');

	}
function home_health_detail(){
      $slug =$this->uri->segment(2);
      $head= $this->seo->get_metadata($slug);
      
      $id=$this->db->get_where('product_master',array('slug' => $slug ,'is_active' =>1))->row();
      $slug='packages';	
	  	
      $head['cart_session'] = $this->session->userdata('cart_session'); 
      $this->load->view('header',$head);
      if($id){
        $id=$id->product_id;
        //  echo $id;
      $city= $this->session->userdata('city');
        $result['package'] = $this->homehealth->get_all_homehealthpackages($city); 
        $result['res']=$this->model_home->show_detail($id); 
        $result['s']=$this->model_home->get_product_services($id);  
        $result['category']=$this->model_home->get_service_category($id);   
         $benifit=$this->db->get_where('package_benifits', array('product_id' => $id));
            $result['benifits']=$benifit->result();
        $result['services']=$result['s']->result();         
    
    $this->load->view('homehealth/product_detail',$result);
  
      }
      else{
            redirect('error');
      }
      
           
    $this->load->view('footer');
  }




}