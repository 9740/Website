<?php if (!defined('BASEPATH')) exit('No di
  t script access allowed');

class Welezo_Authentication extends CI_Model {

  public function __construct()
  {
    //$this->load->database();
  }

function is_mobile_available($mobile,$email)
	{
	
	 $query =$this->db->query("SELECT id from credentials WHERE contact='$mobile' OR login_name='$email'");		
	if ($query->num_rows() >= 1) 
		return true;
	return false;
	}
// function is_mobile_available($mobile)
// 	{
	
// 	 $query =$this->db->query("SELECT customer_deatils.customer_id from customer_deatils LEFT JOIN 
// 		address ON address.entity_id=customer_deatils.customer_id WHERE address.primary_mob='$mobile' || address.alt_mob='$mobile' ");		
// 	if ($query->num_rows() >= 1) 

// 		return true;
// 	return false;
// 	}

function is_offer_mobile_available($mobile){
    $query =$this->db->query("SELECT id from walkin_customer WHERE phone='$mobile'");		
	if ($query->num_rows() >= 1) 
		return true;
	return false;
}
function is_user_mobile_available($mobile)
	{
	
	 $query =$this->db->query("SELECT id from credentials WHERE contact='$mobile' ");		
	if ($query->num_rows() >= 1) {
		$otp=mt_rand(1000,9999);
		$this->db->set('validationKey', $otp);     
    	$this->db->where('contact', $mobile); 
    	$update=$this->db->update('credentials');
        $this->load->helper('sendsms_helper');
		 sendsms( '91'.$mobile, 'The OTP :'.$otp.' to change your password has been sent your registered mail Id / mobile number' );		
		return true;
	}
	return false;
	}
	
	public function is_user_offer_mobile_available($mobile)
	{
	
	 $query =$this->db->query("SELECT id from walkin_customer WHERE phone='$mobile' ");		
	if ($query->num_rows() >= 1) {
		$otp=mt_rand(1000,9999);
		$this->db->set('otp', $otp);     
    	$this->db->where('phone', $mobile); 
    	$update=$this->db->update('walkin_customer');
        $this->load->helper('sendsms_helper');
		sendsms( '91'.$mobile, 'your Health day offer verification OTP:'.$otp );
		return $otp;
	}
	return false;
	}
	
function send_sms_regisered_user( $mobile_num,$fullname){
      $this->load->helper('sendsms_helper');
                                sendsms( '91'.$mobile_num, 'Dear '.$fullname.', Congratulations, You have successfully registered. Please check your email for more details.' );
    
}


function is_username_available($email){
	 $query =$this->db->query("SELECT id from credentials WHERE login_name='$email' ");		
	if ($query->num_rows() >= 1) {
		$otp=mt_rand(1000,9999);
		$this->db->set('validationKey', $otp);     
    	$this->db->where('login_name', $email); 
    	$update=$this->db->update('credentials');
         $this->load->library('email');
         $fromemail="info@welezohealth.com";
          $message_content='find Forgot password OTP from welezo health to update your password
          OTP is:'.$otp;
$toemail=$email;
$this->email->initialize($config);
$this->email->from($fromemail, "Forgot password");
$this->email->to($toemail); 
$config=array(
'charset'=>'utf-8',
'wordwrap'=> TRUE,
'mailtype' => 'html'
);

$this->email->subject('Forgot Password Welezo');
$this->email->message($message_content);
$mail = $this->email->send();
if($mail){     
	$data['message'] = "Mail sent...";   
		return true;
	} 
	   
		return false;
	}
	return false;

}

function is_otp_valid($forgot_info,$otp){
	 $query =$this->db->query("SELECT id from credentials WHERE (contact='$forgot_info' AND  validationKey='$otp') OR
							(login_name='$forgot_info' AND   validationKey='$otp')");

	 if ($query->num_rows() >= 1) {
			return true;
	 }
	return false;	

}
function is_offer_otp_valid($mobile,$otp){
	 $query =$this->db->query("SELECT id from walkin_customer WHERE (phone='$mobile' AND  otp='$otp')");

	 if ($query->num_rows() >= 1) {
	     $this->db->set('is_mobile_validated', 1);     
    	$this->db->where('phone', $mobile); 
    	$update=$this->db->update('walkin_customer');
	     return true;
	 }
			
	return false;	

}



function forgot_password($uname,$password){

	$query =$this->db->query("UPDATE credentials SET password='$password' WHERE contact='$uname'  OR login_name='$uname' ");
	if($query)		
		return true;	
	return false;

}

function change_password($user,$new_password){
	$query =$this->db->query("UPDATE credentials SET password='$new_password' WHERE userId='$user' ");
	if($query)		
		return true;	
	return false;

}
function walkin($mobile,$name,$massage){
	
	 $data=array('phone'=>$mobile,'walkin_customer_name'=>$name,'walkin_customer_msg'=>$massage);
	 $this->db->insert('walkin_customer',$data);

	return;
}
function walkin1($mobile,$name,$email){
	
	 $data=array('phone'=>$mobile,'walkin_customer_name'=>$name,'email'=>$email);
	 $this->db->insert('walkin_customer',$data);

	return;
}
    function get_customer_address($id)
    {
    	$query= $this->db->query(" SELECT * FROM address WHERE address_type_id IN (1,2) AND entity_id ='$id' ; ");
    	return $query->result();

    }

    function get_customer_address_count($id){
    	$query= $this->db->query(" SELECT count(aid) as COUNT FROM address WHERE address_type_id IN (1,2) AND entity_id ='$id'; ");
    	return $query->result();
    }

}
