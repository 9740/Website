<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Packages extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->database();	
		$this->load->model('Model_home','model_home');
		$this->load->library('seo');	
		$this->load->helper(array('url','language'));
	}

	public function index()
	{ 		
	  	$slug='packages';	
	  	$head= $this->seo->get_metadata($slug);
        $head['cart_session'] = $this->session->userdata('cart_session');        
		$today=date("Y-m-d");
	  	$data['row'] = $this->model_home->get_all_packages();	
	  	$data['network']=$this->model_home->network_partner_logo();  		  	
	    $this->load->view('header',$head);
		$this->load->view('packages/preventive_packages',$data);		
		$this->load->view('footer');
	}

	public function health_check_packages()
	{
	   $city= $this->session->userdata('city'); 		
	  	$slug='health-check-packages';	
	  	$head= $this->seo->get_metadata($slug);
        $head['cart_session'] = $this->session->userdata('cart_session');        
	
	  	$data['row'] = $this->model_home->get_all_packages_home($city);		  	
	    $this->load->view('header',$head);
		$this->load->view('packages/helath_check_packages',$data);		
		$this->load->view('footer');
	}
	
	public function trelleborg_package(){
$city= $this->session->userdata('city'); 
$corporate_gender= $this->session->userdata('corporate_gender');
if($corporate_gender=='Male'){
$id=51;
$id1=46;
	$data['row'] = $this->model_home->get_all_trelleborg_package($city,$id,$id1);
}
else if($corporate_gender=='Female'){
$id=52;
$id1=47;
	$data['row'] = $this->model_home->get_all_trelleborg_package($city,$id,$id1);
}
else{
$id=51;
$id1=47;
$id2=52;
$id3=46;
    	$data['row'] = $this->model_home->get_all_trelleborg_package1($city,$id,$id1,$id2,$id3);
}
		
	  	$slug='trelleborg-package';	
	  	$head= $this->seo->get_metadata($slug);
        $head['cart_session'] = $this->session->userdata('cart_session');        
	  		  	
	    $this->load->view('header',$head);
		$this->load->view('packages/trelleborg-package',$data);		
		$this->load->view('footer');

}

	public function product_detail()
	{
		$slug =$this->uri->segment(1);
	    $head= $this->seo->get_metadata($slug);
		$id=$this->db->get_where('product_master',array('slug' => $slug ,'is_active' =>1))->row();
		
		if($id)
			{
			
			$head['cart_session'] = $this->session->userdata('cart_session');  
				$this->load->view('header',$head);
		        $id=$id->product_id;
		        $city= $this->session->userdata('city');
				$result['package'] = $this->model_home->get_all_packages();	
		        $result['res']=$this->model_home->show_detail($id);	
		        $result['s']=$this->model_home->get_product_services($id);     
		        $result['category']=$this->model_home->get_service_category($id);
		        $result['hospital']=$this->model_home->get_service_hospital($city,$id);
		        $benifit=$this->db->get_where('package_benifits', array('product_id' => $id));
		        $result['benifits']=$benifit->result();
		        $result['services']=$result['s']->result();		
				$this->load->view('packages/product_detail',$result);	
				$this->load->view('footer');
			}
		else
			{
		       redirect('error');
		  	}
		

	}
		public function package_detail()
	{
		$slug =$this->uri->segment(2);
	    $head= $this->seo->get_metadata($slug);
		$id=$this->db->get_where('product_master',array('slug' => $slug ,'is_active' =>1))->row();
		
		if($id)
			{
			
			$head['cart_session'] = $this->session->userdata('cart_session');  
				$this->load->view('header',$head);
		        $id=$id->product_id;
		        $city= $this->session->userdata('city');
				$result['package'] = $this->model_home->get_all_packages_home();	
		        $result['res']=$this->model_home->show_detail($id);	
		        $result['s']=$this->model_home->get_product_services($id);     
		        $result['category']=$this->model_home->get_service_category($id);
		        $result['hospital']=$this->model_home->get_service_hospital($city,$id);
		        $benifit=$this->db->get_where('package_benifits', array('product_id' => $id));
		        $result['benifits']=$benifit->result();
		        $result['services']=$result['s']->result();		
				$this->load->view('packages/package_detail',$result);	
				$this->load->view('footer');
			}
		else
			{
		       redirect('error');
		  	}
		

	}

	

}