<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Model_customer extends CI_Model
{
	public $_attr;
    private $_table;
	public $_with = '';

	public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        
    }
    function get_customer($id){
    	$query= $this->db->query("SELECT * FROM customer_deatils 
			WHERE customer_id='$id'  LIMIT 1 ");
    	return $query->result();

    }
    function get_new_customer($userid){
       $query= $this->db->query("SELECT * FROM user_master 
            WHERE userId='$userid'  LIMIT 1 ");
        return $query->result();
    }
    function delete_record($id)
{

     $query= $this->db->query("UPDATE documents_upload SET is_active=0 WHERE id='$id' ");
   // $this->db->where('id', $id);
   // $this->db->where('doc_category', 'Customer');
   // $this->db->delete('documents_upload'); 
}

function get_customer_credentials($userId){
     $query= $this->db->query("SELECT * FROM credentials 
            WHERE userId='$userId'  LIMIT 1 ");
        return $query->result();
}

function get_hospital_query($city,$service_id){
    $query = $this->db->query("SELECT empanellment.name_hcc,empanellment.hospital_id from empanellment  ,hospital_service where empanellment.hospital_id =hospital_service.hospital_id AND hospital_service.service_id='$service_id' AND empanellment.city='$city'");
      return $query->result();
}

    function add_family_member($family){
        $this->db->insert('customer_family', $family);    
        return $this->db->insert_id();
            }

    function get_voucher_no($service_id){
         $query= $this->db->query("SELECT `voucher_no` FROM `transaction_offers` WHERE `transaction_id`=0 AND`service_id`='$service_id' ");
        $query= $query->result();
        foreach ($query as $key) {
            $voucher_no=$key->voucher_no;
        }
        return $voucher_no;

    }
    
     function get_last_appln_no(){
         $query= $this->db->query("SELECT  lpad((application_No+1), 6, 0) AS application_No   FROM transaction_master WHERE (application_No>000000 AND application_No<100000) order by application_No DESC LIMIT 1; ");      
          $query= $query->result();
        foreach ($query as $key) {
            $application_No=$key->application_No;
        }

         return $application_No;
    }

    function get_ex_welezo_card_number($customer_id){
         $query= $this->db->query("SELECT card_number FROM customer_deatils WHERE customer_id='$customer_id' ");
        $query= $query->result();
        foreach ($query as $key) {
            $card_number=$key->card_number;
        }
        return $card_number;
    }

    


    function get_customer_family($id){
    	$query= $this->db->query(" SELECT * FROM customer_family WHERE customer_id = '$id'; ");
    	return $query;

    }

    function get_customer_address($id){
    	$query= $this->db->query(" SELECT * FROM address WHERE address_type_id IN (1,2,3) AND entity_id ='$id' ; ");
    	return $query->result();

    }
    function get_customer_package($id){
    	$query= $this->db->query("  SELECT * FROM customer_healthanalysis WHERE customer_id ='$id' ; ");
    	return $query->result();

    }
    function get_customer_transaction($id){
    	$query= $this->db->query(" SELECT * FROM product_master
LEFT JOIN transaction_master ON transaction_master.product_id=product_master.product_id
WHERE transaction_master.customer_id='$id' ; ");
    	return $query->result();

    }
                 function get_customer_service_details($transaction_id){
                $query= $this->db->query(" SELECT tx.service_id, ser.service_name, SUM(tx.quantity) AS 'Offered', SUM(CASE WHEN tx.usage_id > 0 THEN tx.quantity ELSE 0 END) 'Utilized',
            SUM( CASE WHEN tx.usage_id IS NULL THEN tx.quantity ELSE 0 END) 'Available' ,tx.voucher_no AS 'VoucherNo',ser.category AS 'Category'
FROM transaction_offers tx LEFT JOIN services ser ON tx.service_id = ser.service_id WHERE ser.category!='Pharmacy' AND tx.transaction_id = '$transaction_id' GROUP BY service_id ; ");
                return $query->result_array();


    }

function get_Appointment_detail($id, $service_id,$hospital_id,$transaction_id,$family,$rescheduleDate,$today,$uid){
    
    $data=array('customer_id'=>$id,'service_id'=>$service_id,'hospital_id'=>$hospital_id,'transaction_id'=>$transaction_id,'family_id'=>$family,'appointment_date'=>$rescheduleDate,
   'source'=>"Website",'booked_date'=>$today,'bookedBy'=>$id,
        'status_appointment'=>'New','tx_offers_voucher'=>$uid);
    $this->db->insert('health_appointment',$data);
   
    $appointment_id=$this->db->insert_id();   

    return $appointment_id;
}


 function   put_transaction_offer($id, $customer_services,$transaction_id,$appointment_id){ 
    $services=$this->db->query("SELECT * FROM transaction_offers WHERE  transaction_id='$transaction_id' AND service_id ='$customer_services';");
     if($services) {     


     foreach ($services->result() as $service) {
         $qty=$service->quantity;
         $usage=$service->usage_id ;
         $txoffer_id=$service->txoffer_id ;
         if ($qty==1 && $usage==NULL) {
            $this->db->query("UPDATE transaction_offers SET usage_id ='$appointment_id' WHERE txoffer_id = '$txoffer_id';");

         }
         elseif($qty>1 && $usage==NULL) {
            $qty=$qty-1;
            $this->db->query("UPDATE transaction_offers SET usage_id ='$appointment_id', quantity = 1 WHERE txoffer_id = '$txoffer_id'; ");

            $this->db->query("INSERT INTO transaction_offers(transaction_id, service_id, quantity,      voucher_no, track_id)VALUES ('$transaction_id','$customer_services','$qty','NULL','$id');"
               );

         }
     }
 }

      


}

  

function get_customer_utilized_service_details($transaction_id){
    $query= $this->db->query(" SELECT su.*,health_appointment.tx_offers_voucher FROM `health_appointment` 
    LEFT JOIN service_utilization su ON su.appointment_id=health_appointment.appointment_id
    WHERE su.transaction_id='$transaction_id' AND health_appointment.status_appointment!='Cancelled' AND health_appointment.status_appointment!='Rescheduled'  GROUP BY health_appointment.appointment_id ;");
                return $query->result_array();

}
function customer_appointment_reschedule($rescheduleDate,$rescheduleTime,$appointment_id){

     $this->db->set('status_appointment', 'Rescheduled');     
    $this->db->where('appointment_id', $appointment_id); 
    $update=$this->db->update('health_appointment');    

}

function update_userrole($userid,$customer_id){
    $this->db->set('entity_id', $customer_id);     
    $this->db->where('user_id', $userid); 
    $update=$this->db->update('user_roles'); 

}
function get_customer_appoinment($id){
        $query= $this->db->query(" SELECT health_appointment.* ,customer_deatils.*,customer_family.*, services.* ,empanellment.* FROM health_appointment

LEFT JOIN services ON health_appointment.service_id=services.service_id
LEFT JOIN empanellment ON health_appointment.hospital_id=empanellment.hospital_id
LEFT JOIN customer_family ON health_appointment.family_id=customer_family.family_id 
LEFT JOIN customer_deatils ON health_appointment.customer_id=customer_deatils.customer_id
 WHERE   health_appointment.customer_id='$id' 
 GROUP BY health_appointment.appointment_id ; ");
        return $query->result();

    }

function customer_appointment_data($appointment_id){

     $query= $this->db->query(" SELECT health_appointment.appointment_date,health_appointment.appointment_time ,customer_family.f_name, services.service_name ,empanellment.name_hcc FROM health_appointment,customer_deatils,customer_family,services,empanellment WHERE health_appointment.customer_id= customer_deatils.customer_id AND health_appointment.service_id=services.service_id AND health_appointment.hospital_id=empanellment.hospital_id AND  health_appointment.family_id= customer_family.family_id AND health_appointment.appointment_id='$appointment_id';");
             return $query->result();

}

function customer_appointment_cancel($appointment_id,$status){
    
    $this->db->set('status_appointment', $status);  
   
    $this->db->where('appointment_id', $appointment_id); 
    $update=$this->db->update('health_appointment'); 

    if($status=="Cancelled"){
    $this->db->set('usage_id',NULL);  
    $this->db->where('usage_id', $appointment_id); 
    $update_trans=$this->db->update('transaction_offers'); 
         if ($update_trans){
           return "success";
          }
        else{
           return "failed";
    }
}
else {
    return ;
 }
}

function update_voucher_no($service_id,$voucher_no){
     $this->db->query("UPDATE product_offers SET voucher_no ='$voucher_no' WHERE  service_id='$service_id'; ");
     

}

function get_service_based_hospitals($service_id,$date){
    $service_id=$service_id;   
     $query= $this->db->query(" SELECT schedule_service.*,empanellment.address FROM schedule_service 
LEFT JOIN empanellment ON empanellment.hospital_id=schedule_service.hospital_id
WHERE schedule_service.calendarDate='$date' and schedule_service.service_id='$service_id';");
             return $query->result();



}

function customer_appointment_reschedule_slot($hospital_id,$service_id,$date){
     $query= $this->db->query(" SELECT *,DATE_FORMAT(calendarDate,'%d-%m-%Y') AS 'app_date' FROM `schedule_service`  where hospital_id='$hospital_id' and service_id='$service_id' and  calendarDate>='$date' and DAYOFWEEK(calendarDate) != 1;");
             return $query;

}
function view_appointment($appointment_id){
     $query= $this->db->query(" SELECT health_appointment.appointment_id,health_appointment.appointment_time,health_appointment.appointment_date,health_appointment.status_appointment, customer_deatils.customer_name,customer_family.family_id,customer_family.f_name, services.service_id,services.service_name,services.service_description,empanellment.hospital_id, empanellment.name_hcc,empanellment.address FROM health_appointment

LEFT JOIN services ON health_appointment.service_id=services.service_id
LEFT JOIN empanellment ON health_appointment.hospital_id=empanellment.hospital_id
LEFT JOIN customer_family ON health_appointment.family_id=customer_family.family_id 
LEFT JOIN customer_deatils ON health_appointment.customer_id=customer_deatils.customer_id
WHERE   health_appointment.appointment_id='$appointment_id' 
GROUP BY health_appointment.appointment_id ;");
     return $query->result();
}
public function get_appointment_service_category($service_id){
   $query = $this->db->query("SELECT services.*,service_offers.*,service_subcategory.* FROM `service_subcategory` 

LEFT JOIN service_offers ON service_offers.service_subcategory_id=service_subcategory.service_subcategory_id
LEFT JOIN services  ON services.service_id=service_offers.service_id
WHERE services.service_id='$service_id'
GROUP BY service_subcategory.service_subcategory_id;");
  return $query->result();
}



}
?>