<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Hospital extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->database();	
		$this->load->model('Model_home','model_home');	
		$this->load->model('Model_customer','customer');
		$this->load->model('Model_hospital','model_hospital');
		$this->load->model('hospital_model','hospital_model');
		$this->load->helper(array('url','language'));
		$this->load->library("pagination");
		$this->load->helper('date');
         $this->load->helper('download');
	}
	
	public function index()
	{                         
		//$this->load->view('header');
		 // $url= "http://maps.googleapis.com/maps/api/geocode/json with parameters latlng=13.0184106826782,77.6578598022461&sensor=true" ;
		 $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=13.0367469787598,77.644027709961&sensor=false";
	    $today=date("Y-m-d");
	    $data['url'] = json_decode($url);
	   // $url = json_decode($url,true);
// 	   $url = $url['forecast']['txt_forecast']['forecastday'];

	   
// 	   $data['url']=$url['postalCode'];


// $parsed_json = json_decode($json_string, true);
// $parsed_json = $parsed_json['forecast']['txt_forecast']['forecastday'];
// //pr($parsed_json);

// foreach($parsed_json as $key => $value)
// {
//    echo $value['period'] . '<br>';
//    echo $value['icon'] . '<br>';
//    // etc
// }
	   
	   
	    $data['today']=$today;
	    $id=8;
	    $data['appointment']=$this->model_hospital->get_appointment_list_left($id,$today); 
	    $data['futured_appointment']= $this->model_hospital->get_futured_appointment_list($id,$today); 
        $data['customer']=$this->model_hospital->get_appointment_detail($id);  
		$data['hospital']=$this->model_hospital->get_hospital_detail($id);
		$data['Services'] = $this->model_hospital->get_service_details($id);
		
		$this->load->view('hospital/hospital',$data);		
		//$this->load->view('footer');
	}

	 public function uploadConfig(){
  // extract(populateform());
       $config = array(
       'upload_path' => "./csv_uploads/",
       'allowed_types' => "xls|XLSX",
       'overwrite' => TRUE,
       'max_size' => "2048000000", 
       'file_name' => 'blablabla'
       );      
      $this->load->library('upload', $config);
       $upload_data = $this->upload->data();
       if($this->upload->do_upload())           
       {
       echo "sukses";

       }else{
       	print_r($this->upload->data());
       echo $this->upload->display_errors();
       }
    
}

public function do_upload() {

$config['upload_path']      = './resources/customerreports/';
$config['allowed_types']    = 'xls|XLSX';
$config['max_size']         = '4096'; 
$config['overwrite']        = TRUE;

$this->load->library('upload', $config);
 if (!$this->upload->do_upload('userfile')){
        $uploadError = array('upload_error' => $this->upload->display_errors());       
     // redirect('dashboard/index'); 
    }
		print_r($this->upload->data());
    

}
public function download($fileName = NULL) {

$url='/home/welezohealth/whms/apptReports/';

$data = file_get_contents ( $url.'/'.$fileName );

force_download ( $fileName, $data );


}
	
		public function get_events(){
	$start = $this->input->get("start");
     $end = $this->input->get("end");
     $id=8;
     $startdt = new DateTime('now'); // setup a local datetime
     $startdt->setTimestamp($start); // Set the date based on timestamp
     $start_format = $startdt->format('Y-m-d');

     $enddt = new DateTime('now'); // setup a local datetime
     $enddt->setTimestamp($end); // Set the date based on timestamp
     $end_format = $enddt->format('Y-m-d');
     $hospital_id=8;
     $today=date("Y-m-d");
     $events = $this->model_hospital->get_events($today,$id);

     $data_events = array();

     foreach($events->result() as $r) {

         $data_events[] = array(
             "id" => $r->appointment_id,
             "title" => $r->appointment_time,
             "description" => $r->family_id,
             "end" => $r->appointment_date,
             "start" => $r->appointment_date
         );
     }

     echo json_encode(array("events" => $data_events));
     exit();

	}

	

public function customer_detail_page()
	{                         
	    
	     $vouchernumber = $this->input->post('vouchernumber');
	     $appointment_id = $this->input->post('appointment_id');
	     $service_id = $this->input->post('service_id');
	     $id=8;
	 if ( $result['user']=$this->model_hospital->resolve_customer($vouchernumber,$appointment_id)) {
	   foreach ($result['user'] as $key ) {
             		$session_data= array(
					'appointment_id' =>$key->appointment_id
					);	
				}
             	$this->session->set_userdata($session_data);

        $data['hospital']=$this->model_hospital->get_hospital_detail($id);
	    $data['appointment']=$this->model_hospital->get_appointment_list_individual_customer($id,$service_id,$appointment_id);  
		$data['Services'] = $this->model_hospital->get_service_subcategory($service_id);
		$data['Ser'] = $this->model_hospital->get_service($service_id);
		$status="Availed";
		$cancel=$this->customer->customer_appointment_cancel($appointment_id,$status);
		$report_status="Pending";
		$cancel=$this->model_hospital->customer_appointment_avail($appointment_id,$report_status);
		
		$this->load->view('hospital/customer_detail',$data);
              } 
           else {
                $this->session->set_flashdata('error','wrong value');
                redirect('hospital/index');      
            }
	}

	public function customer_detail_valid()
	{                         
	    $service_id = $this->input->post('service_id');
	    $appointment_id = $this->input->post('appointment_id');
	    $id=8;
	    echo  $appointment_id ;
	    //$data['customer']=$this->model_hospital->get_appointment_detail($id);  
	    $data['hospital']=$this->model_hospital->get_hospital_detail($id);
	    $data['appointment']=$this->model_hospital->get_appointment_list_individual_customer($id,$service_id,$appointment_id);  
	    
	     $data['Report']=$this->model_hospital->get_appointment_download_customer_report($appointment_id); 
        
		$data['Services'] = $this->model_hospital->get_service_subcategory($service_id);
		$data['Ser'] = $this->model_hospital->get_service($service_id);
		$this->load->view('hospital/customer_detail',$data);		
	 }
	  public function get_service_hospitals(){
	 	 $service_id = $this->input->post('service_id');
	 	 $city="Bangalore";
	 	 $shospital =$this->model_home->get_service_hospitals($service_id,$city);
	 	 echo json_encode($shospital);

	 }
}

?>