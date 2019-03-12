<?php 
class My404 extends CI_Controller 
{
 public function __construct() 
 {
    parent::__construct(); 
    $this->load->library('seo');
 } 

 public function index() 
 { 
    $this->output->set_status_header('404');
    $slug='home';	
	$head= $this->seo->get_metadata($slug); 
    $this->load->view('header',$head);
    $this->load->view('error.php');
    $this->load->view('footer');
 } 
 function error(){
 	$this->output->set_status_header('410'); 
 	$slug='home';	
	$head= $this->seo->get_metadata($slug); 
    $this->load->view('header',$head);
    $this->load->view('error.php');
    $this->load->view('footer');
 }
} 