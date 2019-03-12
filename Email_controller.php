<?php 
   class Email_controller extends CI_Controller { 
 
      function __construct() { 
         parent::__construct(); 
         $this->load->library('session'); 
         	$this->load->model('Model_home','model_home');	
		$this->load->helper(array('url','language'));
         $this->load->helper('form');
         $this->load->helper('html');
      } 
        
      public function index() {     
         $this->load->helper('form');         
         $this->load->view('header');
         $this->load->view('network_partners/empanel_with_us');
         $this->load->view('footer'); 
      } 
  
      public function send_mail() { 
         $from_email = "support@welezohealth.com"; 
         $to_email = "prabhakar@welezohealth.com"; 
        

         $name= $this->input->post('name');
         $email_address= $this->input->post('email');
         $contact= $this->input->post('contact');
         //$message=$this->load->load->view('customer/sample');
         
             $message= $this->input->post('message'); 
         $city= $this->input->post('city'); 
         $speciality= $this->input->post('speciality');    
         
         //Load email library 
         $this->load->library('email');
          $this->email->set_mailtype("html");
         $this->email->from($from_email, 'Register Form'); 
         $this->email->to($to_email);  

         $this->email->subject('Empanelment Register'); 
         
         $message_content='Here are the details of Empanelment';
         
           $message_content .='<br/>Name:'. $name;
          $message_content .='<br/>Email:'. $email_address;
          $message_content .='<br/>contact_number:'.$contact;
            $message_content .='<br/>City:'.$city;
           $message_content .='<br/>Speciality:'.$speciality;
            $message_content .='<br/>message:'.$message;
           
             $this->email->message($message_content);
        
         //Send mail 
         if($this->email->send()) 
         $this->session->set_flashdata("email_sent","Email sent successfully."); 
         else 

         $this->session->set_flashdata("email_sent","Error in sending Email.");
         $this->load->view('header');
         $this->load->view('network_partners/empanel_with_us'); 
         $this->load->view('footer');
      } 

      public function send_customer_mail(){
         $from_email = "support@welezohealth.com"; 
         $to_email = "harsha.ds@welezohealth.com"; 
         $name= $this->input->post('name');
         $email_address= $this->input->post('email');
         $contact= $this->input->post('contact');
         $message= $this->input->post('message'); 
        
         $this->load->library('email');    
          $this->email->set_mailtype("html");
         $this->email->from($from_email, 'Register Form'); 
         $this->email->to($to_email);
         $this->email->cc('shiv@welezohealth.com , support@welezohealth.com');  //important         

         $this->email->subject('Here are the details of Customer '); 
         $message_content='Here are the details of Empanelment';
         
         $message_content .='<br/>Name:'. $name;
         $message_content .='<br/>Email:'. $email_address;
         $message_content .='<br/>contact_number:'.$contact;         $message_content .='<br/>message:'.$message;
           
             $this->email->message($message_content);
         
         //Send mail 
         if($this->email->send()) {
            $this->session->set_flashdata('success', 'Email sent successfully');
            redirect($_SERVER['HTTP_REFERER']);
            }
      
         else {
         $this->load->view('header');
         $this->session->set_flashdata("success","Error in sending Email."); 
         $this->load->view('home/contact_us');
          $this->load->view('footer');
      }
      }
      
      public function send_corporate_mail(){
         $from_email = "support@welezohealth.com"; 
         $to_email = "rameshbolla@welezohealth.com";  //important  
         $name= $this->input->post('company_name');
         $name= $this->input->post('name');
         $email_address= $this->input->post('email');
         $contact= $this->input->post('contact');
         // $message= $this->input->post('emp_no'); 
        
         $this->load->library('email');    
         $this->email->set_mailtype("html");
         $this->email->from($from_email, 'Corporate Register Form'); 
         $this->email->to($to_email);
         $this->email->cc('anoop@welezohealth.com, manjeet@welezohealth.com , support@welezohealth.com');   
         $this->email->subject('Here are the details of Corporate '); 
         $message_content='Here are the details of Corporate';
         $message_content .='<br/>Company Name:'. $name;
         $message_content .='<br/>Contact Person:'. $name;
         $message_content .='<br/>Email:'. $email_address;
         $message_content .='<br/>contact_number:'.$contact;
         // $message_content .='<br/>No.Of employee:'.$message;
           
             $this->email->message($message_content);
         
         //Send mail 
         if($this->email->send()) {
           $this->session->set_flashdata('success', 'Email sent successfully');
            redirect($_SERVER['HTTP_REFERER']);
        }
         else {
         $this->session->set_flashdata("success","Error in sending Email."); 
        $slug='corporate';	
	  	$head= $this->seo->get_metadata($slug);
	  	 $head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
	  	$data['row'] = $this->model_home->get_all_packages();
		$this->load->view('channels/corporate',$data);
		$this->load->view('footer');
    }
      }
      
       public function send_diagnostic_request(){
           
        $from_email = "info@weolezohealth.com"; 
        $to_email = "prabhakar@welezohealth.com"; //important  
        $name= $this->input->post('name');
        $diagnostic_name= $this->input->post('diagnostic_name');
        $email_address= $this->input->post('diagnostic_email');         
        $contact= $this->input->post('contact');
        $address= $this->input->post('address'); 
        $is_accredated=$this->input->post('accreditation');
        $year_accreditation=$this->input->post('year_accreditation');

        $this->load->library('email');    
        $this->email->set_mailtype("html");
        $this->email->from($from_email, 'Empanelment Register Form'); 
        $this->email->to($to_email);
         $this->email->cc('support@welezohealth.com, harikrishnan@welezohealth.com'); //important         

        $this->email->subject(' Diagnostic request form '); 
        $message_content='<b>Hi Team,</b>';
        $message_content .='<br/><br/>A new Diagnostic Lab wishes to join us.';
        $message_content .='<br/><br/>Please find the details from the registration form';
        $message_content .='<br/><br/>Diagnostic Name:'. $diagnostic_name;
        $message_content .='<br/>Contact Person:'. $name;
        $message_content .='<br/>Email:'. $email_address;
        $message_content .='<br/>Contact Number:'.$contact;
        $message_content .='<br/>Address:'.$address;
        $message_content .='<br/>NABL accredited:'.$is_accredated;
        $message_content .='<br/>NABL accredited Year:'.$year_accreditation;
         $message_content .='<br/><br/>We hope this leads to a successful partnership.<br/><br/><br/><b>Thanks and Regards,<br/>Welezo IT Team</b>';
           
        $this->email->message($message_content);
         
         //Send mail 
         if($this->email->send()) {
         $this->session->set_flashdata('success', 'Email sent successfully');
            redirect($_SERVER['HTTP_REFERER']); 
        }
         else {
         $this->session->set_flashdata("error","Error in sending request."); 
        $slug='corporate';  
      $head= $this->seo->get_metadata($slug);
       $head['cart_session'] = $this->session->userdata('cart_session');
     redirect('network_partners/join_us');
 }
      }
      
      public function send_hospital_request(){
          
          $from_email = "info@welezohealth.com"; 
        $to_email = "prabhakar@welezohealth.com";  //important  

        $name= $this->input->post('name');
        $diagnostic_name= $this->input->post('hospital_name');
        $email_address= $this->input->post('hospital_email');         
        $contact= $this->input->post('hospital_contact');
        $address= $this->input->post('hospital_address'); 
        $is_accredated=$this->input->post('accreditation');
        $year_accreditation=$this->input->post('year_accreditation');

        $this->load->library('email');    
        $this->email->set_mailtype("html");
        $this->email->from($from_email, 'Empanelment Register Form'); 
        $this->email->to($to_email);
         $this->email->cc('support@welezohealth.com , harikrishnan@welezohealth.com');   //important        

        $this->email->subject(' Hospital request form '); 
        $message_content='<b>Hi Team,</b>';
        $message_content .='<br/><br/>A new Hospital wishes to join us.';
        $message_content .='<br/><br/>Please find the details from the registration form';
        $message_content .='<br/><br/>Hospital Name:'. $diagnostic_name;
        $message_content .='<br/>Contact Person:'. $name;
        $message_content .='<br/>Email:'. $email_address;
        $message_content .='<br/>Contact Number:'.$contact;
        $message_content .='<br/>Address:'.$address;
        $message_content .='<br/>NABH accredited:'.$is_accredated;
        $message_content .='<br/>NABH accredited Year:'.$year_accreditation;
         $message_content .='<br/><br/>We hope this leads to a successful partnership.<br/><br/><br/><b>Thanks and Regards,<br/>Welezo IT Team</b>';
           
        $this->email->message($message_content);
         
         //Send mail 
         if($this->email->send()){ 
        $this->session->set_flashdata('success', 'Email sent successfully');
            redirect($_SERVER['HTTP_REFERER']);
        }
         else {
         $this->session->set_flashdata("error","Error in sending request."); 
        $slug='corporate';  
      $head= $this->seo->get_metadata($slug);
       $head['cart_session'] = $this->session->userdata('cart_session');
     redirect('network_partners/join_us');
 }
          
      }
     
     public function send_clinic_request(){
         
          $from_email = "info@welezohealth.com"; 
          $to_email = "prabhakar@welezohealth.com"; //important  
        $name= $this->input->post('name');
        $diagnostic_name= $this->input->post('clinic_name');
        $email_address= $this->input->post('clinic_email');         
        $contact= $this->input->post('clinic_contact');
        $address= $this->input->post('clinic_address'); 
        $speciality=$this->input->post('speciality');
       

        $this->load->library('email');    
        $this->email->set_mailtype("html");
        $this->email->from($from_email, 'Empanelment Register Form'); 
        $this->email->to($to_email);
         $this->email->cc('support@welezohealth.com, harikrishnan@welezohealth.com');  //important         

        $this->email->subject(' Clinic request form '); 
        $message_content='<b>Hi Team,</b>';
        $message_content .='<br/><br/>A new Clinic wishes to join us.';
        $message_content .='<br/><br/>Please find the details from the registration form';
        $message_content .='<br/><br/>Clinic Name:'. $diagnostic_name;
        $message_content .='<br/>Contact Person:'. $name;
        $message_content .='<br/>Email:'. $email_address;
        $message_content .='<br/>Contact Number:'.$contact;
        $message_content .='<br/>Address:'.$address;
        $message_content .='<br/>Specialization:'.$speciality;
      
         $message_content .='<br/><br/>We hope this leads to a successful partnership.<br/><br/><br/><b>Thanks and Regards,<br/>Welezo IT Team</b>';
           
        $this->email->message($message_content);
         
         //Send mail 
         if($this->email->send()) {
        $this->session->set_flashdata('success', 'Email sent successfully');
            redirect($_SERVER['HTTP_REFERER']);
        }
         else{ 
         $this->session->set_flashdata("error","Error in sending request."); 
        $slug='corporate';  
      $head= $this->seo->get_metadata($slug);
       $head['cart_session'] = $this->session->userdata('cart_session');
     redirect('network_partners/join_us');
 }
         
     }
     public function send_pharmacy_request(){
         $from_email = "info@welezohealth.com"; 
        //$to_email = "ganesh@welezohealth.com"; //important  
            $to_email = "raksha@welezohealth.com"; 
        $name= $this->input->post('name');
        $pharmacy_name= $this->input->post('pharmacy_name');
        $email_address= $this->input->post('pharmacy_email');         
        $contact= $this->input->post('pharmacy_contact');
        $address= $this->input->post('pharmacy_address'); 
        
        $this->load->library('email');    
        $this->email->set_mailtype("html");
        $this->email->from($from_email, 'Pharmacy Register Form'); 
        $this->email->to($to_email);
        $this->email->cc('support@welezohealth.com , harikrishnan@welezohealth.com');   //important        

        $this->email->subject(' Pharmacy request form '); 
        $message_content='<b>Hi Team,</b>';
        $message_content .='<br/><br/>A new Pharmacy wishes to join us.';
        $message_content .='<br/><br/>Please find the details from the registration form';
        $message_content .='<br/><br/>Pharmacy Name:'. $pharmacy_name;
        $message_content .='<br/>Contact Person:'. $name;
        $message_content .='<br/>Email:'. $email_address;
        $message_content .='<br/>Contact Number:'.$contact;
        $message_content .='<br/>Address:'.$address;
       
      
         $message_content .='<br/><br/>We hope this leads to a successful partnership.<br/><br/><br/><b>Thanks and Regards,<br/>Welezo IT Team</b>';
           
        $this->email->message($message_content);
         
         //Send mail 
         if($this->email->send()) {
         $this->session->set_flashdata('success', 'Email sent successfully');
            redirect($_SERVER['HTTP_REFERER']);
        }
         else {
         $this->session->set_flashdata("error","Error in sending request."); 
        $slug='corporate';  
      $head= $this->seo->get_metadata($slug);
       $head['cart_session'] = $this->session->userdata('cart_session');
     redirect('network_partners/join_us');
 }
     }
     
      public function email_welezo_kit(){

          $from_email = "info@welezohealth.com"; 
         $to_email = "ankitsagar@welezohealth.com"; 
          $message = $this->load->view('customer/welezo_package_kit');
           $this->load->library('email');    
         $this->email->from($from_email, 'Welezo Package Kit'); 
         $this->email->to($to_email);

         $email='ankitsagar@welezohealth.com';
    $this->load->library('email');

    $mydata = array('message' => 'Welezo Package Kit');
    $message = $this->load->view('customer/welezo_package_kit',  true);    
    $this->email->subject('Welezo Kit');
    $this->email->message($message);  

     if($this->email->send()) 
         $this->session->set_flashdata("email_sent","Email sent successfully."); 
         else 
         $this->session->set_flashdata("email_sent","Error in sending Email."); 
      $this->load->view('dashboard/contact_us');   

    // $this->email->mailtype('html');
    // $this->email->from('info@welezohealth.com', 'Welezo Package Kit');
    // $this->email->to($email); 

    // $this->email->subject('Welezo Kit');
    // $this->email->message($message);    

    // $this->email->send();
}
   } 
?>