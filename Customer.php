<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Customer extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
		$this->load->database();	
		$this->load->model('Model_customer','customer');		
		$this->load->helper(array('url','language','date'));
		$this->load->model('Model_seo','Model_seo');	
        $this->load->helper('download');
        $this->load->helper('directory');
		$this->load->library('seo');
		$this->load->library("geolib/geolib");
		$this->load->library("geolib/geolib");
	}
	
public function index()
	{                         
       
		$data['today']= $date = date('Y-m-d');
		$id= $this->session->userdata('entity_id');			
		if($id){
	       	redirect('user-profile');			
			}
		else
		{
               	redirect('user');	
							
		}		
	}
	
	
		function user()
	{
		$userId=$this->session->userdata('userId');	
		$data['credintials']=$this->customer->get_customer_credentials($userId);
		$data['user']=$this->customer->get_user_detail_first($userId);
		$this->load->view('customer/header1');	
		$this->load->view('customer/index',$data);
		$this->load->view('customer/footer');	        	
    }
	
	function customer_dashboard(){
        $today= $date = date('Y-m-d');
		$slug='home';	
	  	$head= $this->seo->get_metadata($slug);
	    $data['today']= $date = date('Y-m-d');
		$id= $this->session->userdata('entity_id');	
		$userId=$this->session->userdata('userId');	

                $head['cart_session'] = $this->session->userdata('cart_session');
		        $this->load->view('customer/header',$head);

		if($id){

		$data['credintials']=$this->customer->get_customer_credentials($userId);
		$data['customer_transaction']=$this->customer->get_customer_transaction($id);
		$data['count_transaction']=$this->customer->count_customer_transaction($id);
		$data['appointment']=$this->customer->get_customer_appoinment_futher($id , $today);
		$data['appointment_count']=$this->customer->count_customer_appoinment($id);
		$data['appointment_Availed']=$this->customer->count_customer_appoinment_Availed($id);
		$data['appointment_New']=$this->customer->count_customer_appoinment_New($id);
		
		 $sidebar['customer']=$this->customer->get_customer($id);	
		  $this->load->view('customer/sidebar',$sidebar);
		        $this->load->view('customer/index',$data);
				$this->load->view('customer/footer');	
			}
		else{
			 echo "<script>
                  alert('You Dont Have Any package Activated Click Ok To See Package Details');
                  window.location.href='http://localhost/website/packages';
                  </script>";
			redirect('');
		}	
		
	}

	


	function profile()
	{
		$today= $date = date('Y-m-d');
		$id= $this->session->userdata('entity_id');	
		 $userId=$this->session->userdata('userId');		 
	   $data['appointment']=$this->customer->get_customer_appoinment_futher($id , $today);
	   $data['credintials']=$this->customer->get_customer_credentials($userId); 
	 	$family_query=$this->customer->get_customer_family($id);
	    $data['customer']=$this->customer->get_customer($id);
	    $data['customer_family'] = $family_query->result();
		$data['customer_family_count']=$family_query->num_rows();
		$data['customer_address']= $this->customer->get_customer_address($id);

		$this->load->view('customer/header');	
		$this->load->view('customer/profile',$data);
		$this->load->view('customer/footer');	        	
    }

    function customer_family()
	{
		
		$id= $this->session->userdata('entity_id');
		$userId=$this->session->userdata('userId');		
	        
		     $this->load->view('customer/header');
	         
	         $family_query=$this->customer->get_customer_family($id);
	         $data['customer_family'] = $family_query->result();
	       	 $data['customer_family_count']=$family_query->num_rows();
		     $data['customer']=$this->customer->get_customer($id);
		     $sidebar['customer']=$this->customer->get_customer($id);	

		    $this->load->view('customer/family_member',$data); 
			$this->load->view('customer/footer');	       	
    }

    function Packages_detail(){
	    
		$this->load->view('customer/header');
	    $id= $this->session->userdata('entity_id');	
		$data['customer_transaction']=$this->customer->get_Payment_transaction_detail($id);
		  $data['customer']=$this->customer->get_customer($id);
		$this->load->view('customer/Packages_detail',$data); 
			$this->load->view('customer/footer');	       	
    
	}

	  function setting(){
	   $userId=$this->session->userdata('userId');		 
	   $head['cart_session'] = $this->session->userdata('cart_session');
		        $this->load->view('customer/header',$head);
	    $id= $this->session->userdata('entity_id');	
	   $data['credintials']=$this->customer->get_customer_credentials($userId);
		
		 if ($id)
		 {	
		 
		 $sidebar['customer']=$this->customer->get_customer($id);
		 $this->load->view('customer/sidebar',$sidebar);
		 $this->load->view('customer/customer_setting',$data); 
		 $this->load->view('customer/footer');	    
	        }
	     else {
	     	 $this->load->view('customer/sidebar_empty');
		     $this->load->view('customer/customer_setting',$data);  
			$this->load->view('footer');
			}	     	
    
	}
    function view_Packages_detail(){
	    
	    $transaction_id=$this->input->post('transaction_id'); 
	     $transaction= array(
				           'transaction_id' =>$transaction_id,
				          );  
				        $this->session->set_userdata($transaction);    
	    $id= $this->session->userdata('entity_id');

	    $data1['customer_transaction']=$this->customer->get_packade_transaction($transaction_id);
		$data1['utilized_service']=$this->customer->get_customer_utilized_service_details($transaction_id);
		$data1['available_service']=$this->customer->get_customer_service_details($transaction_id);
		$data1['city']= $this->customer->get_city_search();
		$data1['customer_family']= $this->customer->get_customer_family_name($id);
		
 //echo json_encode($data1) ;
		$data=$this->load->view('customer/view_package_detail',$data1, TRUE);  
       echo $data;
     	
	}

	function add_family_member(){	
	$value=1;	
   $customerName=$this->input->post('customerName');
      $age=$this->input->post('age');
         $relationship=$this->input->post('relationship');
	   $id= $this->session->userdata('entity_id');
		$family=array(
				 		'f_name'=>$this->input->post('customerName'),
				 		'age'=>$this->input->post('age'),
				 		'gender'=> $this->input->post('gender'),
				 		'relationship'=>$this->input->post('relationship'),
				 		'customer_id'=> $id,
				 		'isactive'=>$value
				 		);
		$this->db->insert('customer_family',$family);
	    redirect('customer-family');

		

	}


	function add_family_member1(){	
	$value=1;	
   $customerName=$this->input->post('customerName');
      $age=$this->input->post('age');
         $relationship=$this->input->post('relationship');
	   $id= $this->session->userdata('entity_id');
		$family=array(
				 		'f_name'=>$this->input->post('customerName'),
				 		'age'=>$this->input->post('age'),
				 		'gender'=> $this->input->post('gender'),
				 		'relationship'=>$this->input->post('relationship'),
				 		'customer_id'=> $id,
				 		'isactive'=>$value
				 		);
				 		
		$this->db->insert('customer_family',$family);
		
		$data= array(
	           'corporate_gender' =>$this->input->post('gender')
	          	);  
	       		$this->session->set_userdata($data); 
	    redirect('https://www.welezohealth.com/trelleborg-package');

		

	}
		
function book_appointment(){

      $id= $this->session->userdata('entity_id');	
	  $userId=$this->session->userdata('userId');		 
	   $data['city']= $this->customer->get_city_search();
	   $data['credintials']=$this->customer->get_customer_credentials($userId); 
	 	$family_query=$this->customer->get_customer_family($id);
	    $data['customer']=$this->customer->get_customer($id);
	    $data['customer_family'] = $family_query->result();
		$data['customer_family_count']=$family_query->num_rows();
		$data['customer_address']= $this->customer->get_customer_address($id);
		$data['available_service']= $this->customer->get_customer_service_available($id);

		$this->load->view('customer/header');	
		$this->load->view('customer/book_appointment',$data);
		$this->load->view('customer/footer');	        	

}
    function customer_appointment()
    {      
	  		$id= $this->session->userdata('entity_id');
	  		$this->load->helper('string'); 
	  		$uid= random_string('alnum',8);
	  		$data1=$this->input->post('service'); 
            $valore=explode("|",$data1);
	  		$transaction_id=$valore[0];
            $customer_services= $valore[1];
             
            $province=$this->input->post('province');
            //$transaction_id=$this->session->userdata('transaction_id'); 
            $family=$this->input->post('family'); 
            $startDate1=$this->input->post('startDate'); 
            $startDate = str_replace('/', '-', $startDate1);
            $startDate= date('Y-m-d', strtotime($startDate));
            $today= $date = date('Y-m-d');

            $appointment_id=$this->customer->get_Appointment_detail($id, $customer_services,$province,$transaction_id,$family,$startDate,$today,$uid);
            $result=$this->customer->put_transaction_offer($id, $customer_services,$transaction_id,$appointment_id);
            $result1= $this->customer->update_pre_empaloiment($id,$appointment_id);
            $corporate_mobile=$this->input->post('corporate_mobile'); 
           
           if ($this->session->userdata('user_mobile')==0){
                $this->customer->update_mobile_number($corporate_mobile);
                $session_data= array(
                   'user_mobile'=>$corporate_mobile,
                   );            
              $this->session->set_userdata($session_data);
           }
            $role_type= $this->session->userdata('role_type');  
                if(  $role_type=="Corporate user")
                {
             $result=$this->customer->put_transaction_offer1($transaction_id);  
             $mobile=$this->session->userdata('user_mobile');
             $this->load->helper('sendsms_helper');
             sendsms( '91'.$mobile, 'Thank you for your request. Your appointment will be confirmed shortly. Please reach out to your account manager @ 7349796718 if you have any queries.' );
                  }
                 else{
              $this->load->helper('sendsms_helper');
             sendsms( '91'.$mobile, 'Thank you for your request. Your appointment will be confirmed within 24 hours.');
                 }
                 
            $data['appointment']= $this->customer->get_customer_appoinment_email($appointment_id);
            $this->load->library('email');
            $fromemail="support@welezohealth.com";
            $toemail = "ankitsagar@welezohealth.com";
            $subject = "New Appointment Booked Detail";
            $mesg = $this->load->view('customer/book_appointment_employee',$data,true);

            $config=array(
            'charset'=>'utf-8',
            'wordwrap'=> TRUE,
            'mailtype' => 'html'
            );
            
            $this->email->initialize($config);            
            $this->email->to($toemail);
           // $this->email->cc('lohith@welezohealth.com');  
            $this->email->from($fromemail);
            $this->email->subject($subject);
            $this->email->message($mesg);
            $mail = $this->email->send();
           redirect('customer/index'); 
     }

public function get_parameter()
    
    {
            $service_id= $this->input->post('service_id');
            $hospital= $this->input->post('hospital');
	        if( $this->customer->get_hospital_parameter($hospital,$service_id))
	        {
	            echo 1;
	        }
	        else{
	            echo 0;
	        }
	       
    }

 public function get_service_parameter()
    {
        $service_id= $this->input->post('service_id');

       $product_service=$this->customer->get_service_subcategory($service_id);
 
            echo json_encode($product_service);
          
    }

    function customer_appointment_cancel()
    {

			$appointment_id=$this->input->post('appointment_id');
			$status="Cancelled";
			$cancel=$this->customer->customer_appointment_cancel($appointment_id,$status);
			 $mobile=$this->session->userdata('user_mobile');
             $this->load->helper('sendsms_helper');
             sendsms( '91'.$mobile, 'You have successfully canceled your appointment. To re-book call 1800-102-8364' );
         	if($cancel=="success"){
				echo "success";
			}
			else{
				echo  "failed";
			}

    }


   public function get_province1()
    {
            $data1= $this->input->post('service_id');
    	    $exp = explode("|" , $data1);
	    	 foreach($exp as $mark) {
                               $service_id=$mark;
                                 } 
            //$city=$this->session->userdata('city') ;   
                    
	        $provinces = $this->customer->get_hospital_query($service_id);
	        // if(count($provinces)>0)
	        // {
	        //     $pro_select_box = '';
	        //     $pro_select_box .= '<option class="sort-by-name" value="">Select City</option>';
	        //     foreach ($provinces as $province) {
	        //         $pro_select_box .='<option class="sort-by-name" value="'.$province->city.'">'.$province->city.'</option>';
	        //     }
	            echo json_encode($provinces);
	        //}
	        
    }

    public function get_province()
    {
            $service_id= $this->input->post('service_id');   
            $city= $this->input->post('city');
            $date= $this->input->post('date');
                    
	        $provinces = $this->customer->get_hospital_query1($city,$service_id,$date);
	         if(count($provinces)>0)
	          {
	            $pro_select_box = '';
	            $pro_select_box .= '<option class="sort-by-name" value="">Select Hospital</option>';
	            foreach ($provinces as $province) {
                    $count=$province->Day_Total;
                    $hospital_id=$province->hospital_id;
                    $service_id=$province->service_id;
                    if ($service_id== 42 || $service_id== 43 || $service_id== 44 || $service_id== 45 )
                      {
                     switch ($count>=0)
                               {
								    case ($hospital_id ==8 && $count>=15):    
								        break;
								    case ($hospital_id==9 && $count>=5):
								        break;
								    case ($hospital_id==10 && $count>=10):							       
								        break;
								    default:
								      $pro_select_box .='<option class="sort-by-name" value="'.$province->hospital_id.'">'.$province->ServiceCentre.'</option>';
								}
						}
						else
						{

				          $pro_select_box .='<option class="sort-by-name" value="'.$province->hospital_id.'">'.$province->ServiceCentre.'</option>';	
						}       
                    }
	            
	           
	            echo json_encode($pro_select_box);
	        }
	        }
	        
    

    public function get_service()
    {
	    	$service_id= $this->input->post('service_id');

	     $product_service=$this->db->get_where('services',array('service_id' => $service_id));
         $product_service=$product_service->result();
    foreach ($product_service as $pr) {
           $service=$pr->service_name;    
	          
	        }
	          echo json_encode($service);
	        
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

  public function reports()
    {
    	        $id= $this->session->userdata('entity_id');
                
               
		         $this->load->view('customer/header');
		         $data['customer']=$this->customer->get_customer($id);
                $data['health'] = $this->customer->get_customer_appoinment($id);
			   $data['module_files']= directory_map('/home/welezohealth/whms/apptReports/', FALSE, TRUE);	
			
				$this->load->view('customer/reports',$data);
				$this->load->view('customer/footer');	   
 	
}
  public function askquestion()
    {
    	       
				$this->load->view('customer/header');	
				$this->load->view('customer/help');
 	
}
public function download($fileName = NULL) {

   $url='/home/welezohealth/whms/apptReports/';
   $data = file_get_contents ( $url.'/'.$fileName );
      force_download ( $fileName, $data );

}
	function change_password(){	
	    $user=$this->session->userdata('userId');	
		$new_password=$this->input->post('new_password');
		if($this->customer->change_password($user,$new_password)){
		 $this->session->set_flashdata('success', 'Password updated successfully');
// 		 $mobile='8517027478';
//           $this->load->helper('sendsms_helper');
// 		   sendsms( '91'.$mobile, 'We have sussesfully updated your passworf:');
		   redirect('user-profile');
		}
		else
			redirect('logout');
	}
	 public function logout()
      {
      $this->session->unset_userdata('entity_id');
      $this->session->unset_userdata('userId');
      $this->session->unset_userdata('packages');
      $this->session->sess_destroy();
     
    redirect('https://www.welezohealth.com/'); 
    
      }
  
}
?>

