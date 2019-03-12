<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function sendsms($number, $message_body, $return = '0') {
$sender = 'TESTIN';  // Need to change
$smsGatewayUrl = 'https://trans.kapsystem.com/';
$apikey = 'A52c3248f2xxxxxxxxxxxxxxxxxxxxxxxxx';  // Need to change   
$textmessage = urlencode($message_body);

$api_element = '/api/mt/SendSMS';

$smsgatewaydata = 'http://trans.kapsystem.com/api/web2sms.php?workingkey=A38f8461aefcd9699b45c83dc6084f9f9&to='.$number.'&sender=WELEZO&message='.$textmessage;


$url = $smsgatewaydata;
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
curl_close($ch);
if(!$output){
   $output =  file_get_contents($smsgatewaydata);
}

if($return == '1'){
    return $output;        
}
else{
    return;
}
}

function sendemail($mailid,$message_body, $return = '0'){
                $this->load->library('email');
$fromemail="info@welezohealth.com";
$toemail = $mailid;
$subject="Forgot password OTP";
$this->email->to($toemail);
$this->email->from($fromemail, "OTP");
$this->email->subject($subject);
$this->email->message($message_body);
$mail = $this->email->send();
$data['message'] = "Sorry Unable to send email..."; 
if($this->email->send()){     
   $data['message'] = "Mail sent...";   
   return ;
  } 
 

  }





?>

