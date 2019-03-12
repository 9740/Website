<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Model_customer extends CI_Model
{
  public $_attr;
    private $_table;
  public $_with = '';

  public function __construct()
    {
       parent::__construct();
        
    }


    function get_customer($id)
    {
      
        $query= $this->db->query("SELECT * FROM `customer_deatils` LEFT JOIN address on address.entity_id=customer_deatils.customer_id WHERE customer_deatils.customer_id='$id' 
        AND  address.address_type_id in (1,2,3) LIMIT 1;");
      return $query->result();

    }

    public function get_city_search()
    {
       
         $query = $this->db->query('SELECT DISTINCT city from welezo_cities;');
         return $query->result();

    }
     public function get_user_detail_first($userId)
    {
       
         $query = $this->db->query("SELECT * FROM credentials 
          INNER JOIN user_master ON user_master.userId=credentials.userId
          INNER JOIN user_roles ON user_roles.user_id=credentials.userId

          WHERE credentials.userId='$userId'  LIMIT 1");
         return $query->result();

    }



    function get_customer_credentials($userId)
    {
        
         $query= $this->db->query("SELECT * FROM credentials WHERE userId='$userId'  LIMIT 1 ");
         return $query->result();

    }

function get_hospital_query($service_id)
   {

         $query = $this->db->query("SELECT empanellment.city, empanellment.name_hcc,empanellment.hospital_id from empanellment  ,hospital_service where
         empanellment.hospital_id =hospital_service.hospital_id AND hospital_service.service_id='$service_id' Group by city");
            return $query->result();
   }
   
//   function get_hospital_query1($city,$service_id)
//   {

//          $query = $this->db->query("SELECT empanellment.city, empanellment.name_hcc,empanellment.hospital_id from empanellment  ,hospital_service where
//          empanellment.hospital_id =hospital_service.hospital_id AND hospital_service.service_id='$service_id' AND empanellment.city='$city'");
//             return $query->result();
//   }
   
     function get_hospital_query1($city,$service_id,$date)
   {

   $query = $this->db->query("SELECT sch.ServiceCentre,sch.hospital_id,sch.service_id,sch.Day_Total,emp.city FROM schedule_service sch LEFT JOIN `empanellment` emp ON emp.`hospital_id` = sch.hospital_id 
       WHERE sch.calendarDate BETWEEN '$date' AND '$date' AND sch.service_id = '$service_id' 
       AND emp.`city` ='$city' ORDER BY sch.calendarDate,sch.hospital_id");
            return $query->result();
   }
   
   function get_hospital_appointment_count($date){
     
     $query = $this->db->query("SELECT hospital_id ,SUM(Day_Total)AS total FROM schedule_service WHERE calendarDate='$date' and hospital_id =9 Group By 1");
            return $query->result();
     
   }
   
   function update_mobile_number($corporate_mobile)
   {
      $userId=$this->session->userdata('userId');
      $entity_id=$this->session->userdata('entity_id');
     
       $query =$this->db->query("UPDATE address SET primary_mob = '$corporate_mobile' WHERE entity_id ='$entity_id'  ;");
       $query =$this->db->query("UPDATE credentials SET contact = '$corporate_mobile' WHERE userId = '$userId'");
       $query =$this->db->query("UPDATE welezohe_corp.pre_employment SET contact_no = '$corporate_mobile' WHERE id =(SELECT pre_emp_id FROM corporate_customer WHERE customer_id = $entity_id);");
    
       }

    function add_family_member($family)
    {
       
        $this->db->insert('customer_family', $family);    
        return $this->db->insert_id();
   
    }

  function get_customer_family($id)
    {
      
        $query= $this->db->query(" SELECT * FROM customer_family WHERE customer_id = '$id'; ");
      return $query;

    }

      function get_customer_family_name($id)
    {
      
        $query= $this->db->query(" SELECT f_name,family_id FROM customer_family WHERE customer_id = '$id'; ");
       return $query->result();

    }

    function get_customer_address($id)
    {
      $query= $this->db->query(" SELECT * FROM address WHERE address_type_id IN (1,2) AND entity_id ='$id' ; ");
      return $query->result();

    }

    function get_customer_package($id)
    {
      
        $query= $this->db->query("  SELECT * FROM customer_healthanalysis WHERE customer_id ='$id' ; ");
      return $query->result();

    }

    function get_customer_transaction($id)
    {
      
        $query= $this->db->query(" SELECT * FROM product_master LEFT JOIN transaction_master ON transaction_master.product_id= 
    product_master.product_id WHERE transaction_master.customer_id='$id' ; ");
      return $query->result();

    }
     function count_customer_transaction($id)
    {
        
        $query= $this->db->query(" SELECT count(transaction_master.product_id) AS NumberOfProducts FROM product_master LEFT JOIN transaction_master ON transaction_master.product_id=product_master.product_id WHERE transaction_master.customer_id='$id' ; ");
        return $query->result();

    }

    function get_Payment_transaction_detail($id)
    {

         $query= $this->db->query(" SELECT * FROM product_master LEFT JOIN transaction_master ON transaction_master.product_id=product_master.product_id LEFT JOIN payment_details ON transaction_master.transaction_id=payment_details.transaction_id WHERE transaction_master.customer_id='$id' ; ");
        return $query->result();

    }

      function get_packade_transaction($transaction_id)
      {
        $query= $this->db->query(" SELECT * FROM product_master LEFT JOIN transaction_master ON transaction_master.product_id=product_master.product_id WHERE transaction_master.transaction_id='$transaction_id' ;  ");
        return $query->result();

    }
 

  function get_Appointment_detail($id, $service_id,$hospital_id,$transaction_id,$family,$rescheduleDate,$today,$uid)
  {
    
    $data=array('customer_id'=>$id,'service_id'=>$service_id,'hospital_id'=>$hospital_id,'transaction_id'=>$transaction_id,'family_id'=>$family,'appointment_date'=>$rescheduleDate,
   'source'=>"Website",'booked_date'=>$today,'bookedBy'=>$id,
        'status_appointment'=>'New','tx_offers_voucher'=>$uid);
    $this->db->insert('health_appointment',$data);
    $appointment_id=$this->db->insert_id();   

    return $appointment_id;
  }


 function   put_transaction_offer($id, $customer_services,$transaction_id,$appointment_id)
  {

    $services=$this->db->query("SELECT * FROM transaction_offers WHERE  transaction_id='$transaction_id' AND service_id ='$customer_services' AND usage_id  IS NULL LIMIT 1");
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

            $this->db->query("INSERT INTO transaction_offers(transaction_id, service_id, quantity,voucher_no, track_id)VALUES ('$transaction_id','$customer_services','$qty','NULL','$id');"
               );

         }
     }
 }
}
       function   put_transaction_offer1($transaction_id)
        {

        $services=$this->db->query("UPDATE transaction_offers  SET usage_id =0 WHERE  transaction_id='$transaction_id' AND usage_id  IS NULL ");
  
       }

    function get_customer_service_details($transaction_id)
    {

                $query= $this->db->query(" SELECT tx.service_id, ser.service_name, SUM(tx.quantity) AS 'Offered', SUM(CASE WHEN tx.usage_id >                       0 THEN tx.quantity ELSE 0 END) 'Utilized',
                                           SUM( CASE WHEN tx.usage_id IS NULL THEN tx.quantity ELSE 0 END) 'Available' ,tx.voucher_no AS 'VoucherNo',ser.category AS 'Category' ,transaction_id
                                           FROM transaction_offers tx LEFT JOIN services ser ON tx.service_id = ser.service_id WHERE ser.category!='Pharmacy' AND tx.transaction_id = '$transaction_id' GROUP BY service_id ; ");
                return $query->result();


    }


      function get_customer_service_available($cutomer_id)
    {

                $query= $this->db->query("SELECT tx.service_id, ser.service_name, SUM(tx.quantity) AS 'Offered', SUM(CASE WHEN tx.usage_id >0 THEN tx.quantity ELSE 0 END) 'Utilized',
SUM( CASE WHEN tx.usage_id IS NULL THEN tx.quantity ELSE 0 END) 'Available' ,tx.voucher_no AS 'VoucherNo',ser.category AS 'Category',transaction_master.transaction_id
FROM transaction_offers tx LEFT JOIN services ser ON tx.service_id = ser.service_id
LEFT JOIN transaction_master on tx.transaction_id= transaction_master.transaction_id
WHERE ser.category!='Pharmacy' AND transaction_master.customer_id = '$cutomer_id' AND tx.usage_id IS NULL GROUP BY service_id ; ");
                return $query->result();


    }

    function get_customer_utilized_service_details($transaction_id){
    $query= $this->db->query(" SELECT su.* ,health_appointment.tx_offers_voucher FROM service_utilization su 
    LEFT JOIN `health_appointment` ON su.appointment_id=health_appointment.appointment_id
    WHERE su.transaction_id='$transaction_id' AND health_appointment.status_appointment!='Cancelled' AND health_appointment.status_appointment!='Rescheduled'  GROUP BY health_appointment.appointment_id ;");
              return $query->result();

    }
     

    function count_customer_appoinment($id)
    {

        $query= $this->db->query(" SELECT count(customer_id) as totalappoinment FROM health_appointment
                WHERE customer_id='$id'; ");
        return $query->result();

    }
  
     function count_customer_appoinment_Availed($id)
    {

        $query= $this->db->query(" SELECT count(customer_id) as totalappoinment FROM health_appointment
                WHERE customer_id='$id' AND status_appointment='Availed'; ");
        return $query->result();

    }
      function count_customer_appoinment_New($id)
    {

        $query= $this->db->query(" SELECT count(customer_id) as totalappoinment FROM health_appointment
                WHERE customer_id='$id' AND status_appointment='New'; ");
        return $query->result();

    }
       function get_customer_appoinment($id)
    {

        $query= $this->db->query(" SELECT health_appointment.* ,customer_deatils.*,customer_family.*, services.* ,empanellment.* FROM health_appointment

                LEFT JOIN services ON health_appointment.service_id=services.service_id
                LEFT JOIN empanellment ON health_appointment.hospital_id=empanellment.hospital_id
                LEFT JOIN customer_family ON health_appointment.family_id=customer_family.family_id 
                LEFT JOIN customer_deatils ON health_appointment.customer_id=customer_deatils.customer_id
                WHERE   health_appointment.customer_id='$id' 
                GROUP BY health_appointment.appointment_id ; ");
        return $query->result();

    }

         function get_customer_appoinment_futher($id,$today)
    {

        $query= $this->db->query(" SELECT health_appointment.* ,customer_deatils.*,customer_family.*, services.* ,empanellment.* FROM health_appointment

                LEFT JOIN services ON health_appointment.service_id=services.service_id
                LEFT JOIN empanellment ON health_appointment.hospital_id=empanellment.hospital_id
                LEFT JOIN customer_family ON health_appointment.family_id=customer_family.family_id 
                LEFT JOIN customer_deatils ON health_appointment.customer_id=customer_deatils.customer_id
                WHERE   health_appointment.customer_id='$id'
               ORDER BY health_appointment.appointment_id DESC; ");
        return $query->result();

    }

    function customer_appointment_cancel($appointment_id,$status)
    {
        $role_type=$this->session->userdata('role_type');
         if(  $role_type=="Corporate user")
                {
         $query=$this->db->query("SELECT * from health_appointment where appointment_id='$appointment_id'");
         $query= $query->result();
         foreach ($query as $key) {
                  $transaction_id=$key->transaction_id;
                 $services=$this->db->query("UPDATE transaction_offers SET usage_id = NULL WHERE transaction_id='$transaction_id' AND usage_id='0' ");
              }
       
               }
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



function get_service_based_hospitals($service_id,$date)
{
   
    $service_id=$service_id;   
     $query= $this->db->query(" SELECT schedule_service.*,empanellment.address FROM schedule_service 
LEFT JOIN empanellment ON empanellment.hospital_id=schedule_service.hospital_id
WHERE schedule_service.calendarDate='$date' and schedule_service.service_id='$service_id';");
             return $query->result();

}
function change_password($user,$new_password)
    {
    
     $query =$this->db->query("UPDATE `credentials` SET password ='$new_password' WHERE `userId`= '$user'; ");       
    if ($query) 
        return true;
    return false;
    }
    
    function is_mobile_available($mobile,$email)
    {
    
     $query =$this->db->query("SELECT id from  welezohe_corp.pre_employment WHERE contact_no='$mobile' OR email='$email'");     
    if ($query->num_rows() >= 1) 
        return true;
    return false;
    }
    
  function update_pre_empaloiment($id,$appointment_id){
   $this->db->query("UPDATE welezohe_corp.pre_employment SET status ='Booked' , health_appt_id ='$appointment_id' WHERE id =(SELECT pre_emp_id FROM corporate_customer 
WHERE customer_id ='$id') ");

}

      function get_customer_appoinment_email($id)
    {
  $offer_id= $this->session->userdata('product_offer_id');
        $query= $this->db->query(" SELECT services.*,health_appointment.*,empanellment.*,customer_deatils.*,welezohe_corp.pre_employment.fitness_status FROM health_appointment

                LEFT JOIN services ON health_appointment.service_id=services.service_id
                LEFT JOIN empanellment ON health_appointment.hospital_id=empanellment.hospital_id
                LEFT JOIN customer_deatils ON health_appointment.customer_id=customer_deatils.customer_id
                LEFT JOIN corporate_customer ON health_appointment.customer_id=corporate_customer.customer_id 
                LEFT JOIN welezohe_corp.pre_employment ON welezohe_corp.pre_employment.id=corporate_customer.pre_emp_id
                LEFT JOIN corporate_offers ON corporate_offers.service_id=health_appointment.service_id
                WHERE    health_appointment.appointment_id='$id';");
        return $query->result();

    }
    
       function get_hospital_parameter($hospital,$service_id){
       
       $query =$this->db->query("SELECT id from hospital_service  WHERE hospital_id='$hospital' AND  service_id='$service_id' AND none_parameter IS NOT NULL");

   if ($query->num_rows() >= 1) {
      
       return true;
   }
   return false;  
   }
   
   function get_service_subcategory($id)
  {

   $query = $this->db->query("SELECT services.service_name,service_subcategory.service_subcategory_name, service_subcategory.service_description FROM service_subcategory
            INNER JOIN service_offers ON service_offers.service_subcategory_id =service_subcategory.service_subcategory_id
            INNER JOIN services ON services.service_id =service_offers.service_id            
            WHERE   service_offers.service_id='$id'
            ORDER BY service_offers.ordering");
    return $query->result();
  }

}
?>