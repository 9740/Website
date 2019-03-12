<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Marketing extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->database();	
		$this->load->model('Model_home','model_home');
		$this->load->model('Model_homehealth','homehealth');
		$this->load->model('Welezo_Authentication','welezo_authentication');
		$this->load->helper(array('url','language'));
	}
	
function offers()
	{
		$slug='home';	
		$id='53';
		$city= $this->session->userdata('city');
	  	$head= $this->seo->get_metadata($slug);
	  	$head['cart_session'] = $this->session->userdata('cart_session');
	  	$data['package'] = $this->model_home->get_all_packages();
	  	$this->load->view('header',$head);
	  	$data['s']=$this->model_home->get_product_services($id); 
	  	$data['res']=$this->model_home->show_detail($id);	    
		$data['category']=$this->model_home->get_service_category($id);
		$data['hospital']=$this->model_home->get_service_hospital($city,$id);
		$benifit=$this->db->get_where('package_benifits', array('product_id' => $id));
		$data['benifits']=$benifit->result();
		$data['services']=$data['s']->result();		
		$data['testimonials'] = $this->model_home->testimonials();
		$data['package1'] = $this->model_home->get_dm_diapack();


		$id='56';
	  	$data['package'] = $this->model_home->get_all_packages();
	  	$data['s']=$this->model_home->get_product_services($id); 
	  	$data['res']=$this->model_home->show_detail($id);	    
		$data['category1']=$this->model_home->get_service_category($id);
		
		$benifit=$this->db->get_where('package_benifits', array('product_id' => $id));
		$data['benifits1']=$benifit->result();
		$data['services1']=$data['s']->result();		
		$data['package2'] = $this->model_home->get_dm_diapack1();
		$this->load->view('marketing/offers',$data);	
		$this->load->view('footer');
	}


	function grabon(){
		$slug='home';	
		$id='53';
		$city= $this->session->userdata('city');
	  	$head= $this->seo->get_metadata($slug);
	  	$head['cart_session'] = $this->session->userdata('cart_session');
	  	$data['package'] = $this->model_home->get_all_packages();
	  	$this->load->view('header',$head);
	  	$data['s']=$this->model_home->get_product_services($id); 
	  	$data['res']=$this->model_home->show_detail($id);	    
		$data['category']=$this->model_home->get_service_category($id);
		$data['hospital']=$this->model_home->get_service_hospital($city,$id);
		$benifit=$this->db->get_where('package_benifits', array('product_id' => $id));
		$data['benifits']=$benifit->result();
		$data['services']=$data['s']->result();		
		$data['testimonials'] = $this->model_home->testimonials();
		$data['package1'] = $this->model_home->grabon_master();


		$id='56';
	  	$data['package'] = $this->model_home->get_all_packages();
	  	$data['s']=$this->model_home->get_product_services($id); 
	  	$data['res']=$this->model_home->show_detail($id);	    
		$data['category1']=$this->model_home->get_service_category($id);
		$data['hospital']=$this->model_home->get_service_hospital($city,$id);
		$benifit=$this->db->get_where('package_benifits', array('product_id' => $id));
		$data['benifits1']=$benifit->result();
		$data['services1']=$data['s']->result();		
		$data['testimonials'] = $this->model_home->testimonials();
		$data['package2'] = $this->model_home->grabon_Executive();


		$id='43';
	  	$data['package'] = $this->model_home->get_all_packages();
	  	$data['s']=$this->model_home->get_product_services($id); 
	  	$data['res']=$this->model_home->show_detail($id);	    
		$data['category2']=$this->model_home->get_service_category($id);
		$data['hospital']=$this->model_home->get_service_hospital($city,$id);
		$benifit=$this->db->get_where('package_benifits', array('product_id' => $id));
		$data['benifits2']=$benifit->result();
		$data['services2']=$data['s']->result();		
		$data['testimonials'] = $this->model_home->testimonials();
		$data['package3'] = $this->model_home->grabon_Wellwomenplus();


		$data['healthpackage'] = $this->model_home->get_all_packages_grabon_offer();
		$this->load->view('marketing/grabon',$data);	
		$this->load->view('footer');

	}
	
	function womensday_offer(){
		$slug='home';	
		$id='43';
		$city= $this->session->userdata('city');
	  	$head= $this->seo->get_metadata($slug);
	  	$head['cart_session'] = $this->session->userdata('cart_session');
	  	$data['package'] = $this->model_home->get_all_packages();
	  	$this->load->view('header',$head);
	  	$data['s']=$this->model_home->get_product_services($id); 
	  	$data['res']=$this->model_home->show_detail($id);	    
		$data['category']=$this->model_home->get_service_category($id);
		$data['hospital']=$this->model_home->get_service_hospital($city,$id);
		$benifit=$this->db->get_where('package_benifits', array('product_id' => $id));
		$data['benifits']=$benifit->result();
		$data['services']=$data['s']->result();		
		$data['testimonials'] = $this->model_home->testimonials();
		$data['package1'] = $this->model_home->grabon_Wellwomenplus();
		$this->load->view('marketing/womensday-offer',$data);
		$this->load->view('footer');	

	}
		
// 	function offers()
// 	{
// 		$slug='home';	
// 	  	$head= $this->seo->get_metadata($slug);
// 	  	$head['cart_session'] = $this->session->userdata('cart_session');
// 	  	$data['row'] = $this->model_home->get_all_packages();
// 	  	$this->load->view('header',$head);
// 	  	$data['testimonials'] = $this->model_home->testimonials();
// 	  	$data['package'] = $this->model_home->get_dm_diapack();
// 		$this->load->view('marketing/offers1',$data);	
// 		$this->load->view('footer');
// 	}
	function landing_page1()
	{
	    $city= $this->session->userdata('city');
	    if($city =="")
	    	{
	       		$data= array(
	           'city' =>'Bangalore',
	          	);  
	       		$this->session->set_userdata($data);    
	    	}
	   $data['healthpackage'] = $this->model_home->get_all_packages_home();
		$data['testimonials'] = $this->model_home->testimonials();

	    $data['package'] = $this->model_home->get_all_market_packages();
	     $data['homehealthheader'] = $this->homehealth->get_all_market_homehealthpackages($city);
		$this->load->view('marketing/page',$data);	
	
	}
	
		function landing_page_home_health()
	{
	    $city= $this->session->userdata('city');
	    if($city =="")
	    	{
	       		$data= array(
	           'city' =>'Bangalore',
	          	);  
	       		$this->session->set_userdata($data);    
	  		}
	   $data['healthpackage'] = $this->model_home->get_all_packages_home();
		$data['testimonials'] = $this->model_home->testimonials();

	    $data['package'] = $this->model_home->get_all_market_packages();
	     $data['homehealthheader'] = $this->homehealth->get_all_market_homehealthpackages($city);
		$this->load->view('marketing/home-health',$data);	
	
	}
	
	function master_health_checkup_landing()
	{
		$slug='master-health-checkup-in-bangalore-offer';	
	  	$data= $this->seo->get_metadata($slug);
	  	$data['row'] = $this->model_home->get_all_packages();
	  	$data['testimonials'] = $this->model_home->testimonials();
	  	$data['package'] = $this->model_home->get_dm_diapack();
		$this->load->view('marketing/new',$data);	
		
	}

function senior_health_checkup_landing()
	{
		$slug='sinior-citizen-health-checkup-in-bangalore';	
	  	$data= $this->seo->get_metadata($slug);
	  	$data['row'] = $this->model_home->get_all_packages();
	  	$data['testimonials'] = $this->model_home->testimonials();
	  	$data['package'] = $this->model_home->get_dm_diapack();
		$this->load->view('marketing/senior-citizen',$data);	
		
	}

function executive_health_checkup_landing()
	{
		$slug='executive-health-checkup-in-bangalore';	
	  	$data= $this->seo->get_metadata($slug);
	  	$data['row'] = $this->model_home->get_all_packages();
	  	$data['testimonials'] = $this->model_home->testimonials();
	  	$data['package'] = $this->model_home->get_dm_diapack();
		$this->load->view('marketing/exicutive',$data);	
		
	}

function platinum_health_checkup_landing()
	{
		$slug='platinum-health-checkup-in-bangalore';	
	  	$data= $this->seo->get_metadata($slug);
	  	$data['row'] = $this->model_home->get_all_packages();
	  	$data['testimonials'] = $this->model_home->testimonials();
	  	$data['package'] = $this->model_home->get_dm_diapack();
		$this->load->view('marketing/platinum',$data);	
		
	}

function well_women_health_checkup_landing()
	{
		$slug='well-women-health-checkup-in-bangalore';	
	  	$data= $this->seo->get_metadata($slug);
	  	$data['row'] = $this->model_home->get_all_packages();
	  	$data['testimonials'] = $this->model_home->testimonials();
	  	$data['package'] = $this->model_home->get_dm_diapack();
		$this->load->view('marketing/well-women',$data);	
		
	}

function family_health_checkup_landing()
	{
		$slug='family-health-checkup-in-bangalore';	
	  	$data= $this->seo->get_metadata($slug);
	  	$data['row'] = $this->model_home->get_all_packages();
	  	$data['testimonials'] = $this->model_home->testimonials();
	  	$data['package'] = $this->model_home->get_dm_diapack();
		$this->load->view('marketing/family',$data);	
		
	}

	function welezo_republic_day_offers(){
        $slug='welezo-republic-day-offers';	
		$id='6';
		$city= $this->session->userdata('city');
	  	$data= $this->seo->get_metadata($slug);
	  	$data['package'] = $this->model_home->get_all_packages();
	  	$data['res']=$this->model_home->show_detail($id);	
	  	$data['s']=$this->model_home->get_product_services($id);     
		$data['category']=$this->model_home->get_service_category($id);
		$data['hospital']=$this->model_home->get_service_hospital($city,$id);
		$benifit=$this->db->get_where('package_benifits', array('product_id' => $id));
		$data['benifits']=$benifit->result();
		$data['services']=$data['s']->result();		
		$data['testimonials'] = $this->model_home->testimonials();
		$data['package1'] = $this->model_home->get_dm_diapack();
		$this->load->view('marketing/republic-day',$data);	
		

	}
		function welezo_republic_day_offers_fb(){
        $slug='welezo-republic-day-offers';	
		$id='6';
		$city= $this->session->userdata('city');
	  	$data= $this->seo->get_metadata($slug);
	  	$data['package'] = $this->model_home->get_all_packages();
	  	$data['res']=$this->model_home->show_detail($id);	
	  	$data['s']=$this->model_home->get_product_services($id);     
		$data['category']=$this->model_home->get_service_category($id);
		$data['hospital']=$this->model_home->get_service_hospital($city,$id);
		$benifit=$this->db->get_where('package_benifits', array('product_id' => $id));
		$data['benifits']=$benifit->result();
		$data['services']=$data['s']->result();		
		$data['testimonials'] = $this->model_home->testimonials();
		$data['package1'] = $this->model_home->get_dm_diapack();
		$this->load->view('marketing/rebublic-day-fb',$data);	
		

	}

	
		function walkin(){
		        $config=array(
               'charset'=>'utf-8',
               'wordwrap'=> TRUE,
                'mailtype' => 'html'
                );
		$mobile=$this->input->post('mobile');
		$name=$this->input->post('name');
		$email=$this->input->post('email');
		
		$this->load->library('email');
        $fromemail="info@welezohealth.com";
        $message_content='Here are the details of today walkin customer .<br><br>.
         Name:'. $name.'.<br><br>.
         Contact_number:'.$mobile.'.<br><br>.
         Eamil Address:'.$email;
        

        $toemail='support@welezohealth.com';
        $this->email->initialize($config);
        $this->email->from($fromemail, "online-user");
        $this->email->to($toemail);
        $this->email->cc('misba.tabassum@welezohealth.com');   
        $this->email->subject('online-user');
        $this->email->message($message_content);
        $mail = $this->email->send();
		$walkin_customer=$this->welezo_authentication->walkin($mobile,$name,$massage);
	
	 redirect($_SERVER['HTTP_REFERER']);
	}
function walkin1(){
		        $config=array(
               'charset'=>'utf-8',
               'wordwrap'=> TRUE,
                'mailtype' => 'html'
                );
		$mobile=$this->input->post('mobile');
		$name=$this->input->post('name');
		$email=$this->input->post('email');
		
		$this->load->library('email');
        $fromemail="info@welezohealth.com";
        $message_content='Here are the details of today walkin customer from google add .<br><br>.
         Name:'. $name.'.<br><br>.
         Contact_number:'.$mobile.'.<br><br>.
         Eamil Address:'.$email;
        

        $toemail='ankitsagar@welezohealth.com';
        $this->email->initialize($config);
        $this->email->from($fromemail, "online-user");
        $this->email->to($toemail);
        //$this->email->cc('misba.tabassum@welezohealth.com');   
        $this->email->subject('online-user');
        $this->email->message($message_content);
          if($this->email->send()) {
            $this->session->set_flashdata('success1', 'Email Sent Successfully');
            redirect($_SERVER['HTTP_REFERER']);
            }
		$walkin_customer=$this->welezo_authentication->walkin1($mobile,$name,$email);
	
	 redirect($_SERVER['HTTP_REFERER']);
	}

	function walkin2(){
		        $config=array(
               'charset'=>'utf-8',
               'wordwrap'=> TRUE,
                'mailtype' => 'html'
                );
		$mobile=$this->input->post('mobile');
		$name=$this->input->post('name');
		$email=$this->input->post('email');
		
		$this->load->library('email');
        $fromemail="info@welezohealth.com";
        $message_content='Here are the details of today walkin customer from facebook.<br><br>.
         Name:'. $name.'.<br><br>.
         Contact_number:'.$mobile.'.<br><br>.
         Eamil Address:'.$email;
        

        $toemail='ankitsagar@welezohealth.com';
        $this->email->initialize($config);
        $this->email->from($fromemail, "online-user");
        $this->email->to($toemail);
        //$this->email->cc('misba.tabassum@welezohealth.com');   
        $this->email->subject('online-user');
        $this->email->message($message_content);
          if($this->email->send()) {
            $this->session->set_flashdata('success1', 'Email Sent Successfully');
            redirect($_SERVER['HTTP_REFERER']);
            }
		$walkin_customer=$this->welezo_authentication->walkin1($mobile,$name,$email);
	
	 redirect($_SERVER['HTTP_REFERER']);
	}

function republic_day_email_send(){
	 // $this->load->library('email');
  //           $fromemail="support@welezohealth.com";
  //           $toemail = "ankitsagar@welezohealth.com";
  //           $subject = "New Appointment Booked Detail";
  //           $mesg =
   $this->load->view('marketing/rebublic');

           //  $config=array(
           //  'charset'=>'utf-8',
           //  'wordwrap'=> TRUE,
           //  'mailtype' => 'html'
           //  );
            
           //  $this->email->initialize($config);            
           //  $this->email->to($toemail);
           // // $this->email->cc('lohith@welezohealth.com');  
           //  $this->email->from($fromemail);
           //  $this->email->subject($subject);
           //  $this->email->message($mesg);
           //  $mail = $this->email->send();
        }

}