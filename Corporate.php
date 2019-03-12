<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Corporate extends CI_Controller {
  function __construct()
  {
    
    parent::__construct();    
    $this->load->database();  
    $this->load->model('Model_home','model_home');  
    $this->load->model('Model_corporate','corporate');
    $this->load->model('Model_hospital','hospital'); 
    $this->load->helper(array('url','language'));
    $this->load->library("pagination");
    $this->load->helper('date');
    $this->load->helper('directory');
        
  }


  
  function index()
  { 
         $data['today']= $date = date('Y-m-d');
         $id= $this->session->userdata('corporate_id');
         $userId=$this->session->userdata('userId');  
         $data['corporate']=$this->corporate->get_corporate_detail($id);
         $result['corporate']=$this->corporate->get_corporate_detail($id);
         $result['row'] = $this->corporate->get_corporate_package($id);
         $data['row'] = $this->corporate->get_corporate_package($id); 
         $data['employee_count']=$this->corporate->get_employee_count($id);     
         $s_id=$this->db->get_where('corporate_details',array('corporate_id' => $id))->row();           
         $this->load->view('corporate/header',$result);
         $this->load->view('corporate/index',$data);
         $this->load->view('corporate/footer');       
  }
  
    
 function corporate_service()
    {

        $id1= $this->session->userdata('corporate_id');
        $slug=$this->uri->segment(2);      
        $data['today']= $date = date('Y-m-d');
        $id=$this->db->get_where('product_master',array('slug' => $slug))->row();
        if($id){
        $id=$id->product_id;
       // $product_name=$id->product_name;

         $offer_id=$this->corporate->get_product_offer($id1,$id);
         foreach($offer_id as $o) {
             $product_offer_id= $o->offers_id; 
         }
      $data['product_id']=$id;
        $productdata= array(
           'pid' =>$id,
           'product_offer_id'=>$product_offer_id
          ); 
        $this->session->set_userdata($productdata);  
         $quary=$this->db->get_where('corporate_offers',array('offers_id' => $product_offer_id))->row();
          
        if($quary){
        $service_id=$quary->service_id;
          }
          $city= $this->session->userdata('city');
          $data['corporate']=$this->corporate->get_corporate_detail($id1);
          $data['category']=$this->model_home->get_service_category($id);
          $data['employee']=$this->corporate->get_datatables_query($product_offer_id ,$id1);
          $data['Report']=$this->corporate->get_monthly_report($id1,$product_offer_id);
          $data['hospital']=$this->corporate->get_hospital_query($product_offer_id,$city);
          $data['service_avalible']=$this->model_home->get_product_services($id);
          $result['corporate']=$this->corporate->get_corporate_detail($id1);
          $result['row'] = $this->corporate->get_corporate_package($id1); 
          $data['category1']= $this->corporate->get_corporate_product_name($id); 
          $data['appointment']=$this->corporate->get_customer_appoinment($id1,$service_id);
        
          $this->load->view('corporate/header',$result);
          $this->load->view('corporate/Pre-Employment-Health-Check',$data);
          $this->load->view('corporate/footer');
      
        }
    }
    
    
       function get_customer(){
       $pre_empaloiment_id=$this->input->post('pre_empaloiment_id');
        $session_data= array(
          'pre_empaloiment_id' =>$pre_empaloiment_id
         
          );            
                
        $this->session->set_userdata($session_data);
        $data=$this->corporate->get_custmer_family($pre_empaloiment_id);
         echo json_encode($data);
    }
    
   function customer_appointment()
    {    
        $corporate_mobile=$this->input->post('corporate_mobile');
        $pre_empaloiment_id= $this->session->userdata('pre_empaloiment_id');
        $id= $this->input->post('customer_id');
        $this->load->helper('string'); 
        $uid= random_string('alnum',8);
        $transaction_id=$this->input->post('transaction_id');
        $customer_services= $this->input->post('service');
        $province=$this->input->post('province');
        $family=$this->input->post('family'); 
        $startDate=$this->input->post('startDate'); 
        $today= $date = date('Y-m-d');
        
            $appointment_id=$this->corporate->get_Appointment_detail($id, $customer_services,$province,$transaction_id,$family,$startDate,$today,$uid);
            $result=$this->corporate->put_transaction_offer($id, $customer_services,$transaction_id,$appointment_id);
            $result1= $this->corporate->update_pre_empaloiment($pre_empaloiment_id,$appointment_id);
           
            
            if(empty($corporate_mobile)){
                  $id=$this->db->get_where('welezohe_corp.pre_employment',array('id' => $pre_empaloiment_id))->row();
                    if($id){
                    $mobile=$id->contact_no;    
                        }
            }
            else{
             $result2= $this->corporate->update_mobile_number($corporate_mobile,$pre_empaloiment_id);
             $mobile= $corporate_mobile;
            }
             $this->load->helper('sendsms_helper');
             sendsms( '91'.$mobile, 'Thank you for your request. Your appointment will be confirmed shortly. Please reach out to your account manager @ 7349796718 if you have any queries.' );
            $data['appointment']= $this->corporate->get_customer_appoinment_email($appointment_id);
            $this->load->library('email');
            $fromemail="support@welezohealth.com";
            $toemail = "ankitsagar@welezohealth.com";
            $subject = "New Appointment Booked Detail";
            $mesg = $this->load->view('corporate/book_appointment_employee',$data,true);

            $config=array(
            'charset'=>'utf-8',
            'wordwrap'=> TRUE,
            'mailtype' => 'html'
            );
            
            $this->email->initialize($config);            
            $this->email->to($toemail);
            $this->email->cc('lohith@welezohealth.com');  
            $this->email->from($fromemail);
            $this->email->subject($subject);
            $this->email->message($mesg);
            $mail = $this->email->send();
            redirect($_SERVER['HTTP_REFERER']);
     }
     
        public function get_province1()
    {
            $service_id= $this->input->post('service_id');
    	  
	        $provinces = $this->corporate->get_hospital_query2($service_id);
	        if(count($provinces)>0)
	        {
	            $pro_select_box = '';
	            $pro_select_box .= '<option class="sort-by-name" value="">Select City</option>';
	            foreach ($provinces as $province) {
	                $pro_select_box .='<option class="sort-by-name" value="'.$province->city.'">'.$province->city.'</option>';
	            }
	            echo json_encode($pro_select_box);
	        }
	        
    }

    public function get_province()
    {
             $service_id= $this->input->post('service_id');
    
            $city= $this->input->post('city');
                    
	        $provinces = $this->corporate->get_hospital_query1($city,$service_id);
	         if(count($provinces)>0)
	        {
	            $pro_select_box = '';
	            $pro_select_box .= '<option class="sort-by-name" value="">Select Hospital</option>';
	            foreach ($provinces as $province) {
	                $pro_select_box .='<option class="sort-by-name" value="'.$province->hospital_id.'">'.$province->name_hcc.'</option>';
	            }
	            echo json_encode($pro_select_box);
	        }
	        
    }
    
    
    
public function get_parameter()
    
    {
            $service_id= $this->input->post('service_id');
            $hospital= $this->input->post('hospital');
	        if( $this->corporate->get_hospital_parameter($hospital,$service_id))
	        {
	            echo 1;
	        }
	        else{
	            echo 0;
	        }
	       
    }
    
     function corporate_branch()

     {
       
        $id= $this->session->userdata('corporate_id');
        $data['branch']=$this->corporate->get_corporate_branches($id);
         $result['corporate']=$this->corporate->get_corporate_detail($id);
        $result['row'] = $this->corporate->get_corporate_package($id);
        $this->load->view('corporate/header',$result);
        $this->load->view('corporate/Corporate_branch_detail',$data);
         $this->load->view('corporate/footer');
     }


    function demo()

     {
       
      
        $this->load->view('corporate/add_employee_email');
         //$this->load->view('corporate/footer');
     }
 
    public function ajax_edit($id)
    {

        $data = $this->corporate->get_by_id($id);
        echo json_encode($data);
    }

     public function ajax_update()
    {
       // $this->_validate();
        $data = array(
                'emp_name' => $this->input->post('emp_name'),
                'contact_no' => $this->input->post('contact_no'),
                'gender' => $this->input->post('gender'),
                'address' => $this->input->post('address'),
                'email' => $this->input->post('email'),
                'city' => $this->input->post('city'),
                'pincode' => $this->input->post('pincode'),
                'age' => $this->input->post('age'),
            );
        $this->corporate->update(array('id' => $this->input->post('id')), $data);
        echo 1;
    }
 
   public function ajax_delete($id)
    {
    $status='Deleted'; 
    $this->db->set('status',$status);  
    $this->db->where('id', $id); 
    $update_trans=$this->db->update('welezohe_corp.pre_employment'); 
        echo json_encode(array("status" => TRUE));
    
    }
    
    public function ajax_add()
    {
     
       $id= $this->session->userdata('corporate_id');
      $contact_no= $this->input->post('contact_no');
       $email= $this->input->post('email');
        if((strlen($contact_no) > 0) AND $this->corporate->is_mobile_available($contact_no,$email)){
            echo 2;
           }
          else{
        $data = array(
                'emp_name' => $this->input->post('emp_name'),
                'contact_no' => $this->input->post('contact_no'),
                'email' => $this->input->post('email'),
                'gender' => $this->input->post('gender'),
                'address' => $this->input->post('address'),
                'age' => $this->input->post('age'),
                'city' => $this->input->post('city'),
                'status' => 'In Progress',
                'corporate_offer_id'=>$this->input->post('session'),
                'corporate_id' => $id,
                'pincode' => $this->input->post('pincode')
            );
       $this->db->insert('welezohe_corp.pre_employment',$data); 
       if($this->db->insert_id()){
              $quary=$this->db->get_where('corporate_details',array('corporate_id' => $id))->row();
          
        if($quary){
        $corporate_name=$quary->corporate_name;
          }         
            $this->load->library('email');
            $fromemail="support@welezohealth.com";
            $toemail = "ankitsagar@welezohealth.com";
            $subject = "Corporate Add Employee Detail '$corporate_name'";
            $mesg = $this->load->view('corporate/add_employee_email',$data,true);

            $config=array(
            'charset'=>'utf-8',
            'wordwrap'=> TRUE,
            'mailtype' => 'html'
            );
            
            $this->email->initialize($config);            
            $this->email->to($toemail);
            $this->email->cc('manjeet@welezohealth.com');  
            $this->email->from($fromemail);
            $this->email->subject($subject);
            $this->email->message($mesg);
            $mail = $this->email->send();
          echo 1;
               }
          else
             echo 0;
     }
     
    }
 
   
public function dir_to_array($dir, $separator = DIRECTORY_SEPARATOR, $paths = 'relative') 
{
    $result = array();
    $cdir = scandir($dir);
    foreach ($cdir as $key => $value)
    {
        if (!in_array($value, array(".", "..")))
        {
            if (is_dir($dir . $separator . $value))
            {
                $result[$value] = $this->dir_to_array($dir . $separator . $value, $separator, $paths);
            }
            else
            {
                if ($paths == 'relative')
                {
                    $result[] = $dir . '/' . $value;                    
                }
                elseif ($paths == 'absolute')
                {
                    $result[] = base_url() . $dir . '/' . $value;
                }
            }
        }
    }
    return $result;
}  
 public function Person_deatil()
    { 
       
       $customer_id=$this->input->post('customer_id');            
       $product_offer_id=$this->input->post('product_offer_id'); 
       $id1= $this->session->userdata('corporate_id'); 
          $customer=$this->db->get_where('customer_deatils',array('customer_id' => $customer_id))->row();
         if($customer){
        $customer_name=$customer->customer_name;
        
          } 
            $quary=$this->db->get_where('corporate_offers',array('offers_id' => $product_offer_id))->row();
        if($quary){
        $service_id=$quary->service_id;
         $id=$quary->product_id;
            }
       $data['customer_name']=$customer_name;
       $data['corporate']=$this->corporate->get_corporate_detail($id1);
       $data['category']=$this->model_home->get_service_category($id);
       $data['health'] = $this->corporate->get_corporate_appoinment($customer_id);
       $data['module_files']= directory_map('/home/welezohealth/whms/apptReports/', FALSE, TRUE);
       $result['corporate']=$this->corporate->get_corporate_detail($id1);
          $result['row'] = $this->corporate->get_corporate_package($id1); 
        $data1=$this->load->view('corporate/person_deatil',$data, TRUE);  
       echo $data1;
 
    }

 public function reports()
    {
          
       $slug=$this->uri->segment(2);
       $id1= $this->session->userdata('corporate_id'); 
        $id=$this->db->get_where('product_master',array('slug' => $slug))->row();
        if($id){
        $id=$id->product_id;
         }
       
       $offer_id=$this->corporate->get_product_offer($id1,$id);
         foreach($offer_id as $o) {
             $product_offer_id= $o->offers_id; 
         }
        $data['product_id']=$id;
        $productdata= array(
           'pid' =>$id,
           'product_offer_id'=>$product_offer_id
          ); 
        $this->session->set_userdata($productdata);  
         
         
          $quary=$this->db->get_where('corporate_offers',array('offers_id' => $product_offer_id))->row();
          
        if($quary){
        $service_id=$quary->service_id;
          }
          $quary=$this->db->get_where('corporate_offers',array('offers_id' => $product_offer_id))->row();
          
        if($quary){
        $service_id=$quary->service_id;
         $id=$quary->product_id;
          }
        $data['corporate']=$this->corporate->get_corporate_detail($id1);
        $data['category']=$this->model_home->get_service_category($id);
        $result['corporate']=$this->corporate->get_corporate_detail($id1);
        $result['row'] = $this->corporate->get_corporate_package($id1); 
        $data['appointment']=$this->corporate->get_customer_appoinment($id1,$service_id);
     $this->load->view('corporate/header',$result);
        $this->load->view('corporate/employee_reports',$data);
        $this->load->view('corporate/footer');
  
    }
    
    public function misreport()
    {
    
        $id1= $this->session->userdata('corporate_id'); 
        $data['corporate']=$this->corporate->get_corporate_detail($id1);
        //$data['category']=$this->model_home->get_service_category($id);
        $result['corporate']=$this->corporate->get_corporate_detail($id1);
        $result['row'] = $this->corporate->get_corporate_package($id1); 
         $data['slug']= "misreport";
        $data['appointment']=$this->corporate->get_customer_appoinment($id1);
        $this->load->view('corporate/header',$result);
        $this->load->view('corporate/misreport',$data);
        $this->load->view('corporate/footer');
  
    }
    
    
// public function misreport()
//     {
          
//       $slug=$this->uri->segment(2);
   
//       $id1= $this->session->userdata('corporate_id'); 
//         $id=$this->db->get_where('product_master',array('slug' => $slug))->row();
//         if($id){
//         $id=$id->product_id;
//          }
       
//       $offer_id=$this->corporate->get_product_offer($id1,$id);
//          foreach($offer_id as $o) {
//              $product_offer_id= $o->offers_id; 
//          }
//         $data['product_id']=$id;
//         $productdata= array(
//           'pid' =>$id,
//           'product_offer_id'=>$product_offer_id
//           ); 
//         $this->session->set_userdata($productdata);  
         
         
//           $quary=$this->db->get_where('corporate_offers',array('offers_id' => $product_offer_id))->row();
          
//         if($quary){
//         $service_id=$quary->service_id;
//           }
//           $quary=$this->db->get_where('corporate_offers',array('offers_id' => $product_offer_id))->row();
          
//         if($quary){
//         $service_id=$quary->service_id;
//          $id=$quary->product_id;
//           }
//         $data['corporate']=$this->corporate->get_corporate_detail($id1);
//         $data['category']=$this->model_home->get_service_category($id);
//         $result['corporate']=$this->corporate->get_corporate_detail($id1);
//         $result['row'] = $this->corporate->get_corporate_package($id1); 
//          $data['slug']= $slug;
//         $data['appointment']=$this->corporate->get_customer_appoinment($id1,$service_id);
//      $this->load->view('corporate/header',$result);
//         $this->load->view('corporate/misreport',$data);
//         $this->load->view('corporate/footer');
  
//     }

public function filter_data(){
$slug='mis';
$startdate = $this->input->post('startdate');
$enddate = $this->input->post('enddate');
$id1= $this->session->userdata('corporate_id'); 

       
        $data['corporate']=$this->corporate->get_corporate_detail($id1);
      //  $data['category']=$this->model_home->get_service_category($id);
        $result['corporate']=$this->corporate->get_corporate_detail($id1);
        $result['row'] = $this->corporate->get_corporate_package($id1); 
         $data['slug']= $slug;
        $data['appointment']=$this->corporate->get_filter_customer_appoinment($id1,$startdate,$enddate);
     $this->load->view('corporate/header',$result);
        $this->load->view('corporate/misreport',$data);
        $this->load->view('corporate/footer');

   
}
 
     
      public function logout()
      {
      $this->session->unset_userdata('corporate_id');
      $this->session->unset_userdata('product_offer_id');
      $this->session->unset_userdata('userId');
      $this->session->unset_userdata('Branch_id');
      $this->session->unset_userdata('packages');
      $this->session->sess_destroy();

    redirect('https://www.welezohealth.com/'); 
    
      }
   
    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
 
        if($this->input->post('emp_name') == '')
        {
            $data['inputerror'][] = 'emp_name';
            $data['error_string'][] = 'First name is required';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('contact_no') == '')
        {
            $data['inputerror'][] = 'contact_no';
            $data['error_string'][] = 'Last name is required';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('doa') == '')
        {
            $data['inputerror'][] = 'doa';
            $data['error_string'][] = 'Date of Birth is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('gender') == '')
        {
            $data['inputerror'][] = 'gender';
            $data['error_string'][] = 'Please select Gender';
            $data['status'] = FALSE;
        }
        if($this->input->post('email') == '')
        {
            $data['inputerror'][] = 'email';
            $data['error_string'][] = 'Email Address is required';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('age') == '')
        {
            $data['inputerror'][] = 'age';
            $data['error_string'][] = 'Age is required';
            $data['status'] = FALSE;
        }
         if($this->input->post('age') == '')
        {
            $data['inputerror'][] = 'age';
            $data['error_string'][] = 'Age is required';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('address') == '')
        {
            $data['inputerror'][] = 'address';
            $data['error_string'][] = 'Addess is required';
            $data['status'] = FALSE;
        }
         if($this->input->post('city') == '')
        {
            $data['inputerror'][] = 'city';
            $data['error_string'][] = 'city is required';
            $data['status'] = FALSE;
        }
         if($this->input->post('pincode') == '')
        {
            $data['inputerror'][] = 'pincode';
            $data['error_string'][] = 'pincode is required';
            $data['status'] = FALSE;
        }
       
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
}
      
    
