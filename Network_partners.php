<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Network_partners extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->database();	
		$this->load->model('Model_home','model_home');	
		$this->load->model('Model_servicepartners','servicepartners');	
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
		$config['base_url'] = base_url() ."network-partners";
		$city= $this->session->userdata('city');
	    if($city =="")
	    	{
	       		$data= array(
	           'city' =>'Bangalore',
	          	);  
	       		$this->session->set_userdata($data);    
	  		}
		$slug='network-partners';	
	  	$head= $this->seo->get_metadata($slug);
	  	$head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);  	
	     $data['provinc'] = $this->servicepartners->get_services_query($city);
	        $config["total_rows"] = $this->servicepartners->networkpartner_count();
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
	  	$data['hospitals']=$this->servicepartners->get_empanelment1($config["per_page"], $page);
	  	  $data["links"] = $this->pagination->create_links(); 

	  	$service = $this->db->get_where('services', array('isActive' => 1,'category!='=>'Home Health Check'
));
    	$data['services']=$service->result(); 

		$this->load->view('network_partners/network_partners_list',$data);	
		$this->load->view('footer');
	}

 public function search()
	{
		$location =$this->uri->segment(2);	
		if($location=="search"){
		    $location =$this->uri->segment(3);
		}

		$data['s']=$location;
		$location_adds=$this->servicepartners->get_lat_lan($location);

				foreach ($location_adds as $key ) {
					$lat=$key->latitude;
					$lan=$key->longitude;
				}

		if($location)
	  	{
		    $head['title'] = 'hospitals nearby ' .$location.'|welezo service partners nearby '.$location.'  ';
		    $head['description'] ='Hospital,clinics,Dental hospitals,Diagnostic centre,Labs near by'.$location.' '; 
		    $head['keywords'] = 'hospital near by '.$location.',Diagnostic center near by '.$location.',Labs near by '.$location.',Dental service near by '.$location.',hospital near by '.$location.' ';
		    $head['robots'] = 'index, follow';
		    $head['canonical'] = base_url().'search/'.$location;
	  	}
	  	else
	  	{
		    $head['title'] = 'Welezo service partners';
		    $head['description'] = '';
		    $head['keywords'] = 'welezo service hospitals,welezo service diagnostic center,welezo service Dental hospitals. ';
		    $head['robots'] = 'index, follow';
		    $head['canonical'] = base_url().'search/'.$location;
		} 
			$city= $this->session->userdata('city');
	    if($city =="")
	    	{
	       		$data= array(
	           'city' =>'Bangalore',
	          	);  
	       		$this->session->set_userdata($data);    
	  		}
	  		
	     $data['provinc'] = $this->servicepartners->get_services_query($city);

		
			$config['base_url'] = base_url().'search/'.$location;
			 $query= $this->servicepartners->get_nearby_service_partners_count($lat,$lan);
			 foreach ($query as $row) {
                  $total= $row->count;
            }
         
		    $config["total_rows"] =$total; 
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
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
	  	//$data['result']= $this->servicepartners->get_nearby_service_partners($config["per_page"], $page,$lat,$lan);
	  	  $data["links"] = $this->pagination->create_links(); 



		$data['result'] = $this->servicepartners->get_nearby_service_partners($lat,$lan,$config["per_page"], $page);
	  	$service = $this->db->get_where('services', array('isActive' => 1,'category!='=>'Home Health Check'
     ));
    	$data['services']=$service->result(); 
    	
	  	
	  	$head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
		$this->load->view('network_partners/location_hospitals',$data);	
		$this->load->view('footer');
	}
		function service_network_hospitals(){
		$id =$this->uri->segment(2);
			$city= $this->session->userdata('city');
	    if($city =="")
	    	{
	       		$data= array(
	           'city' =>'Bangalore',
	          	);  
	       		$this->session->set_userdata($data);    
	  		}
	  		
	     $data['provinc'] = $this->servicepartners->get_services_query($city);
		$data['s']=$id;

		    $config['base_url'] = base_url().'service-network-partners/'.$id;
		    $config["total_rows"] = $this->servicepartners->get_service_hospitals_count($id);
            $config["per_page"] = 5;
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
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
	  	$data["links"] = $this->pagination->create_links(); 


		$data['hospitals']=$this->servicepartners->get_service_hospitals($id,$config["per_page"], $page); 
		$service = $this->db->get_where('services', array('isActive' => 1,'category!='=>'Home Health Check'));
		$data['services']=$service->result(); 
		$slug='faq';	
	  	if($id)
	  	{
		    $head['title'] = 'Service ' .$id.'|welezo service partners of '.$id.'  ';
		    $head['description'] ='Welezo partner with Hospital,clinics,Dental hospitals,Diagnostic centre,Labs providing '.$id.' service '; 
		    $head['keywords'] = 'hospital near by '.$id.',Diagnostic center near by '.$id.',Labs near by '.$id.',Dental service near by '.$id.',hospital near by '.$id.' ';
		    $head['robots'] = 'index, follow';
		    $head['canonical'] = base_url().'service-network-partners/'.$id;
	  	}
	  	else
	  	{
		    $head['title'] = 'Welezo service partners';
		    $head['description'] = '';
		    $head['keywords'] = 'welezo service hospitals,welezo service diagnostic center,welezo service Dental hospitals. ';
		    $head['robots'] = 'index, follow';
		    $head['canonical'] = base_url().'service-network-partners/'.$id;
		} 
	  	$head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
		$this->load->view('network_partners/service_network_partners',$data);	
		$this->load->view('footer');
		}
	function locations(){
		$city = $this->input->post('district_city');       
        $provinc = $this->servicepartners->get_services_query($city);
        if(count($provinc)>0)
        {
            $pro_select_box = '';
            $pro_select_box .= '<option class="sort-by-name" value="all">Select Location</option>';
            foreach ($provinc as $provinc) {
                $pro_select_box .=' <option class="sort-by-name" value="'.base_url().'network_partners/search/'.$provinc->slug.'">'.$provinc->location.'</option>';
            }
            echo json_encode($pro_select_box);
        }
	}
	public function join_us(){
		$city= $this->session->userdata('city');
	    if($city =="")
	    	{
	       		$data= array(
	           'city' =>'Bangalore',
	          	);  
	       		$this->session->set_userdata($data);    
	  		}
		$slug='join_us';	
	  	$head= $this->seo->get_metadata($slug);
	  	$head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
	  	$this->load->view('network_partners/join_as_service_partner');	
		$this->load->view('footer');

	} 

	function get_hospitals(){
		$hospital_id = $this->input->post('hospital_id');
		$hospital = $this->servicepartners->get_autocomplete_hospital($hospital_id);
		echo json_encode($hospital);   

	}

		public function autocomplete()
    {         
        $city = $this->input->post('city');
        $search_data = $this->input->post('search_data');         
        $result = $this->model_home->get_autocomplete($search_data,$city);
       	if (!empty($result))
        {
            echo"<ul style='padding: 4px;'>";
            foreach ($result as $row):
              
                  echo "<li value=".$row->hospital_id." style='list-style-type: none;' onclick='showpharmacy(this.value);''>" . $row->name_hcc . "</li><br>";            
            endforeach;
               echo"<ul>";
        }
        else
        {
            echo "<li> <em> Result Not found ... </em> </li>";
        }
            
    }



function get_location_hospital(){
	$location= $this->input->post('location');
		if($location=="all"){
			$result = $this->servicepartners->get_autocomplete($search_data);
		}
		else{
			$location_adds = $this->db->get_where('service_locations', array('id' => $location))->result();
				foreach ($$location_adds as $key ) {
					$lat=$key->latitude;
					$lan=$key->longitude;
				}
			$result = $this->servicepartners->get_nearby_service_partners($lat,$lan);
		}
}
	
		
		public function hospital_details(){ 
		    $id=$this->uri->segment(2);
   	
	  	 $head['cart_session'] = $this->session->userdata('cart_session');
	  	
    
 	$hospitals=$this->db->get_where("empanellment", array('slug' => $id))->row();
 	if($id)
	  	{
		    $head['title'] = ''.$hospitals->name_hcc.'';
		    $head['description'] =''.$hospitals->name_hcc.','.$hospitals->address.''; 
		    $head['keywords'] = ''.$hospitals->name_hcc.' ';
		    $head['robots'] = 'index, follow';
		    $head['canonical'] = base_url().'network-partners/'.$id;
	  	}
	  	else
	  	{
		    $head['title'] = 'Welezo service partner';
		    $head['description'] = '';
		    $head['keywords'] = 'welezo service hospitals,welezo service diagnostic center,welezo service Dental hospitals. ';
		    $head['robots'] = 'index, follow';
		    $head['canonical'] = base_url().$id;
		} 

 	$id=$hospitals->hospital_id;
 	$hospital=$this->db->get_where('empanellment',array('hospital_id' => $id));
	
 	$data['view_hospital']=$hospital->result();
 	
 	$this->load->view('header',$head);
 	
 
 	foreach ($hospital->result() as $hospital ) {
 
  $img_loc=$hospital->name_hcc;
  $this->load->helper('directory');
  $dir = "resources/Hospitals/".$img_loc."";
  $data['imagemap'] = directory_map($dir); 
   


 
 } 


 	
 	$service = $this->db->get_where('services', array('isActive' => 1));
	// $data['services']=$service->result();
	// $data['city'] =$this->model_home->get_city_search();	
	$data['hospital_detail'] = $this->model_home->get_hospital_details($id);
	$date = date('Y-m-d',strtotime("+2 days"));
	


	$this->load->view('network_partners/hospital_detail_page',$data);
	$this->load->view('footer');
 		

 }


		

}