<?php

if (!defined('BASEPATH'))exit('No direct script access allowed');


class Model_corporate extends CI_Model
{
	 var $table = 'welezohe_corp.pre_employment'; 
	 var $column_order = array('emp_name','contact_no','age','email','address','city','pincode','corporate_id','corporate_offer_id','status');
	 var  $column_search=array('emp_name','contact_no','age','email','address');
	 var $order = array('id' => 'desc');
	
	public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();

}

public function get_datatables_query($product_offer_id , $id)
    {
       $column_search=array('emp_name','contact_no','age','gender','email','address');
     
        
        $this->db->from($this->table);
        $this->db->where('corporate_id',$id);
        $this->db->where('corporate_offer_id',$product_offer_id);
         $this->db->order_by("id","DESC"); 
         $query = $this->db->get();
        return $query->result();
       }


    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
    	$id= $this->session->userdata('Corporate_id');
        $this->db->from($this->table);
        $this->db->where('corporate_id',$id);
        return $this->db->count_all_results();
    }
 function get_corporate_appoinment($id){
        $query= $this->db->query(" SELECT health_appointment.* ,customer_deatils.*,customer_family.*, services.* ,empanellment.* FROM health_appointment
LEFT JOIN services ON health_appointment.service_id=services.service_id
LEFT JOIN empanellment ON health_appointment.hospital_id=empanellment.hospital_id
LEFT JOIN customer_family ON health_appointment.family_id=customer_family.family_id 
LEFT JOIN customer_deatils ON health_appointment.customer_id=customer_deatils.customer_id
 WHERE   health_appointment.customer_id ='$id' 
 GROUP BY health_appointment.appointment_id LIMIT 1; ");
        return $query->result();

    }
    function get_monthly_report($id,$product_offer_id){
        $query= $this->db->query(" SELECT LEFT(doa, 7) AS 'Month', 
SUM(CASE WHEN STATUS IN ('In Progress') THEN 1 ELSE 0 END) 'InProgress',
SUM(CASE WHEN STATUS  IN ('Confirmed') THEN 1 ELSE 0 END) 'Confirmed',
SUM(CASE WHEN STATUS  IN ('Availed') THEN 1 ELSE 0 END) 'Availed',
COUNT(1) 'Total'
FROM welezohe_corp.pre_employment WHERE corporate_id='$id' AND corporate_offer_id='$product_offer_id' AND doa >= (CURRENT_DATE - 365) GROUP BY 1
UNION 
SELECT 'Total' AS 'Month',
SUM(CASE WHEN STATUS IN ('In Progress') THEN 1 ELSE 0 END) 'InProgress',
SUM(CASE WHEN STATUS  IN ('Confirmed') THEN 1 ELSE 0 END) 'Confirmed',
SUM(CASE WHEN STATUS  IN ('Availed') THEN 1 ELSE 0 END) 'Availed',
COUNT(1) 'Total'
FROM welezohe_corp.pre_employment WHERE corporate_id='$id'  AND corporate_offer_id='$product_offer_id' AND doa >= (CURRENT_DATE - 365);

");
        return $query->result();

    }
    function get_monthly_report_excel($id){

        $query= $this->db->query(" SELECT LEFT(doa, 7) AS 'Month', 
SUM(CASE WHEN STATUS IN ('In Progress') THEN 1 ELSE 0 END) 'InProgress',
SUM(CASE WHEN STATUS  IN ('Confirmed') THEN 1 ELSE 0 END) 'Confirmed',
SUM(CASE WHEN STATUS  IN ('Availed') THEN 1 ELSE 0 END) 'Availed',
COUNT(1) 'Total'
FROM welezohe_corp.pre_employment WHERE corporate_id='$id' AND doa >= (CURRENT_DATE - 365) GROUP BY 1
UNION 
SELECT 'Total' AS 'Month',
SUM(CASE WHEN STATUS IN ('In Progress') THEN 1 ELSE 0 END) 'In Progress',
SUM(CASE WHEN STATUS  IN ('Confirmed') THEN 1 ELSE 0 END) 'Confirmed',
SUM(CASE WHEN STATUS  IN ('Availed') THEN 1 ELSE 0 END) 'Availed',
COUNT(1) 'Total'
FROM welezohe_corp.pre_employment WHERE corporate_id='$id' AND doa >= (CURRENT_DATE - 365);
              
");
        return $query->result();
    

    }
    
    
    
    function get_product_offer($id1,$id){
        
        $query = $this->db->query("SELECT offers_id FROM corporate_offers WHERE corporate_id='$id1' AND product_id='$id'");
        return $query->result();
    }
    function get_corporate_report($id){
         $query = $this->db->query("SELECT * FROM documents_upload WHERE entity_id='$id' AND is_active=1");
        return $query->result();
    }

function get_employee_list($corporate_id){
         $query = $this->db->query("SELECT * FROM welezohe_corp.pre_employment WHERE corporate_id='$corporate_id'");
        return $query->result();
    }
   function get_corporate_branches($id){
         $query = $this->db->query("SELECT * FROM corporate_details corp, contact_address cadr
WHERE cadr.address_type IN (11) AND cadr.entity_id=corp.corporate_id AND (  corp.corporate_id = '$id' OR corp.parent_id = '$id')
");
        return $query->result();
    }
     function get_corporate_package($id){
         $query = $this->db->query("SELECT * FROM corporate_offers LEFT JOIN product_master ON corporate_offers.product_id=product_master.product_id WHERE corporate_offers.corporate_id='$id'");
        return $query->result();
    }
  
     function get_corporate_detail($id){
         $query = $this->db->query("SELECT * FROM corporate_details
LEFT JOIN contact_address ON contact_address.entity_id=corporate_details.corporate_id
where corporate_details.corporate_id='$id';");
        return $query->result();
    }  
    
     public function get_by_id($id)
    {
        $query = $this->db->query("SELECT * FROM welezohe_corp.pre_employment where id='$id';");
 
       return $query->row();
    }
 
    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
 
    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }
 
    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }
    
    function get_corporate_product_name($id){
        
     $query =$this->db->query("SELECT * from product_master WHERE product_id='$id'");
        return $query->result_array();
    }
 
 
  function get_customer_appoinment($id)
    {
  $offer_id= $this->session->userdata('product_offer_id');
        $query= $this->db->query(" SELECT health_appointment.*,empanellment.*,customer_deatils.*,welezohe_corp.pre_employment.fitness_status FROM health_appointment

                LEFT JOIN services ON health_appointment.service_id=services.service_id
                LEFT JOIN empanellment ON health_appointment.hospital_id=empanellment.hospital_id
                LEFT JOIN customer_deatils ON health_appointment.customer_id=customer_deatils.customer_id
                LEFT JOIN corporate_customer ON health_appointment.customer_id=corporate_customer.customer_id 
                LEFT JOIN welezohe_corp.pre_employment ON welezohe_corp.pre_employment.id=corporate_customer.pre_emp_id
                LEFT JOIN corporate_offers ON corporate_offers.service_id=health_appointment.service_id
                WHERE   corporate_customer.corporate_id=$id AND health_appointment.status_appointment!='Cancelled' AND health_appointment.report_status IS NOT NULL
                GROUP BY health_appointment.appointment_id; ");
        return $query->result();

    }
    
        function get_customer_appoinment_email($id)
    {
  $offer_id= $this->session->userdata('product_offer_id');
        $query= $this->db->query(" SELECT services.*, health_appointment.*,empanellment.*,customer_deatils.*,welezohe_corp.pre_employment.fitness_status FROM health_appointment

                LEFT JOIN services ON health_appointment.service_id=services.service_id
                LEFT JOIN empanellment ON health_appointment.hospital_id=empanellment.hospital_id
                LEFT JOIN customer_deatils ON health_appointment.customer_id=customer_deatils.customer_id
                LEFT JOIN corporate_customer ON health_appointment.customer_id=corporate_customer.customer_id 
                LEFT JOIN welezohe_corp.pre_employment ON welezohe_corp.pre_employment.id=corporate_customer.pre_emp_id
                LEFT JOIN corporate_offers ON corporate_offers.service_id=health_appointment.service_id
                WHERE    health_appointment.appointment_id='$id';");
        return $query->result();

    }
 
//   function get_customer_appoinment($id, $service_id)
//     {
//   $offer_id= $this->session->userdata('product_offer_id');
//         $query= $this->db->query(" SELECT health_appointment.*,empanellment.*,customer_deatils.*,welezohe_corp.pre_employment.fitness_status FROM health_appointment

//                 LEFT JOIN services ON health_appointment.service_id=services.service_id
//                 LEFT JOIN empanellment ON health_appointment.hospital_id=empanellment.hospital_id
//                 LEFT JOIN customer_deatils ON health_appointment.customer_id=customer_deatils.customer_id
//                 LEFT JOIN corporate_customer ON health_appointment.customer_id=corporate_customer.customer_id 
//                 LEFT JOIN welezohe_corp.pre_employment ON welezohe_corp.pre_employment.id=corporate_customer.pre_emp_id
//                 WHERE   corporate_customer.corporate_id='$id' AND  health_appointment.service_id ='$service_id'  
//                 GROUP BY health_appointment.appointment_id ; ");
//         return $query->result();

//     }

//      function get_filter_customer_appoinment($id, $service_id,$startdate,$enddate)
//     {
//          $offer_id= $this->session->userdata('product_offer_id');
//          if(empty($startdate) && empty( $enddate)){
//   $query= $this->db->query(" SELECT health_appointment.* , services.* ,empanellment.*,customer_deatils.* FROM health_appointment
//                 LEFT JOIN services ON health_appointment.service_id=services.service_id
//                 LEFT JOIN empanellment ON health_appointment.hospital_id=empanellment.hospital_id
//                 LEFT JOIN customer_deatils ON health_appointment.customer_id=customer_deatils.customer_id
//                 LEFT JOIN corporate_customer ON health_appointment.customer_id=corporate_customer.customer_id 
//                 WHERE   corporate_customer.corporate_id='$id' AND  health_appointment.service_id ='$service_id'  
//                 GROUP BY health_appointment.appointment_id ; ");
//         return $query->result();

//          }
//  else {
//         $query= $this->db->query(" SELECT health_appointment.*,empanellment.*,customer_deatils.*,welezohe_corp.pre_employment.* FROM health_appointment

//                 LEFT JOIN services ON health_appointment.service_id=services.service_id
//                 LEFT JOIN empanellment ON health_appointment.hospital_id=empanellment.hospital_id
//                 LEFT JOIN customer_deatils ON health_appointment.customer_id=customer_deatils.customer_id
//                 LEFT JOIN corporate_customer ON health_appointment.customer_id=corporate_customer.customer_id 
//                 LEFT JOIN welezohe_corp.pre_employment ON welezohe_corp.pre_employment.id=corporate_customer.pre_emp_id
//                 WHERE   corporate_customer.corporate_id='$id' AND  health_appointment.service_id ='$service_id' AND 
//                  appointment_date BETWEEN '$startdate' AND '$enddate'
 
//                 GROUP BY health_appointment.appointment_id ; ");
//         return $query->result();
// }
//     }

    function get_filter_customer_appoinment($id,$startdate,$enddate)
    {
         $offer_id= $this->session->userdata('product_offer_id');
         if(empty($startdate) && empty( $enddate)){
  $query= $this->db->query(" SELECT health_appointment.* , services.* ,empanellment.*,customer_deatils.* FROM health_appointment
                LEFT JOIN services ON health_appointment.service_id=services.service_id
                LEFT JOIN empanellment ON health_appointment.hospital_id=empanellment.hospital_id
                LEFT JOIN customer_deatils ON health_appointment.customer_id=customer_deatils.customer_id
                LEFT JOIN corporate_customer ON health_appointment.customer_id=corporate_customer.customer_id 
                LEFT JOIN corporate_offers ON corporate_offers.service_id=health_appointment.service_id
                WHERE   corporate_customer.corporate_id='$id' AND health_appointment.report_status IS NOT NULL
                GROUP BY health_appointment.appointment_id ; ");
        return $query->result();

         }
 else {
        $query= $this->db->query(" SELECT health_appointment.*,empanellment.*,customer_deatils.*,welezohe_corp.pre_employment.* FROM health_appointment

                LEFT JOIN services ON health_appointment.service_id=services.service_id
                LEFT JOIN empanellment ON health_appointment.hospital_id=empanellment.hospital_id
                LEFT JOIN customer_deatils ON health_appointment.customer_id=customer_deatils.customer_id
                LEFT JOIN corporate_customer ON health_appointment.customer_id=corporate_customer.customer_id 
                LEFT JOIN welezohe_corp.pre_employment ON welezohe_corp.pre_employment.id=corporate_customer.pre_emp_id
                LEFT JOIN corporate_offers ON corporate_offers.service_id=health_appointment.service_id
                WHERE   corporate_customer.corporate_id='$id' AND health_appointment.report_status IS NOT NULL AND 
                 appointment_date BETWEEN '$startdate' AND '$enddate'
 
                GROUP BY health_appointment.appointment_id ; ");
        return $query->result();
}
    }

      function get_employee_count($id){
        $query= $this->db->query(" SELECT
SUM(CASE WHEN STATUS IN ('In Progress') THEN 1 ELSE 0 END) 'InProgress',
SUM(CASE WHEN STATUS  IN ('Actived') THEN 1 ELSE 0 END) 'Actived',
SUM(CASE WHEN STATUS  IN ('Booked','Confirmed') THEN 1 ELSE 0 END) 'Booked',
SUM(CASE WHEN STATUS  IN ('Availed') THEN 1 ELSE 0 END) 'Availed',
SUM(CASE WHEN STATUS IN ('Deleted') then 1 else 0 end) 'Deleted',
sum(case when STATUS in ('Cancelled') then 1 else 0 end) 'Cancelled',
COUNT(1) 'Total'
FROM welezohe_corp.pre_employment WHERE corporate_id='$id';
              

");
        return $query->result();

    }
function getdata($id){
      
         $query= $this->db->query(" SELECT LEFT(doa, 7) AS 'Month', 
SUM(CASE WHEN STATUS IN ('In Progress') THEN 1 ELSE 0 END) 'InProgress',
SUM(CASE WHEN STATUS  IN ('Confirmed') THEN 1 ELSE 0 END) 'Confirmed',
SUM(CASE WHEN STATUS  IN ('Availed') THEN 1 ELSE 0 END) 'Availed',
COUNT(1) 'Total'
FROM welezohe_corp.pre_employment WHERE corporate_id='$id' AND doa >= (CURRENT_DATE - 365) GROUP BY 1
UNION 
SELECT 'Total' AS 'Month',
SUM(CASE WHEN STATUS IN ('In Progress') THEN 1 ELSE 0 END) 'InProgress',
SUM(CASE WHEN STATUS  IN ('Confirmed') THEN 1 ELSE 0 END) 'Confirmed',
SUM(CASE WHEN STATUS  IN ('Availed') THEN 1 ELSE 0 END) 'Availed',
COUNT(1) 'Total'
FROM welezohe_corp.pre_employment WHERE corporate_id='$id' AND doa >= (CURRENT_DATE - 365);

");
        return $query->result_array();
 
    }
    function is_corporate_branch($parent_id)
	{
	
	 $query =$this->db->query("SELECT * from corporate_details where parent_id='$parent_id' ");		
	if ($query->num_rows() > 1) 
		return true;
	return false;
	}
  
     function get_custmer_family($id)
    {
    
     $query =$this->db->query("SELECT transaction_master.transaction_id,customer_family.f_name,customer_family.family_id,corporate_customer.customer_id,pre_employment.contact_no FROM customer_family LEFT JOIN
corporate_customer ON corporate_customer.customer_id=customer_family.customer_id
LEFT JOIN welezohe_corp.pre_employment ON welezohe_corp.pre_employment.id=corporate_customer.pre_emp_id
LEFT JOIN transaction_master ON transaction_master.customer_id=corporate_customer.customer_id
WHERE corporate_customer.pre_emp_id='$id' LIMIT 1");
        return $query->result_array();
 
    }
function update_mobile_number($corporate_mobile,$pre_empaloiment_id){
      $userId=$this->session->userdata('userId');
    
    
       $query =$this->db->query("UPDATE welezohe_corp.pre_employment SET contact_no = '$corporate_mobile' WHERE id = '$pre_empaloiment_id';");
       $query =$this->db->query("UPDATE address SET primary_mob = '$corporate_mobile' WHERE entity_id = (SELECT customer_id FROM corporate_customer WHERE pre_emp_id = $pre_empaloiment_id) ;");
       $query =$this->db->query("UPDATE credentials SET contact = '$corporate_mobile' WHERE userId = (SELECT user_id FROM user_roles 
WHERE entity_id = (SELECT customer_id FROM corporate_customer WHERE pre_emp_id = '$pre_empaloiment_id'))");
       // return $query->result_array();
    
}
    function get_hospital_query($service_id,$city)
   {

         $query = $this->db->query("SELECT empanellment.name_hcc,empanellment.hospital_id,hospital_service.service_id from corporate_offers
LEFT join hospital_service ON hospital_service.service_id=corporate_offers.service_id
LEFT JOIN empanellment ON empanellment.hospital_id=hospital_service.hospital_id
WHERE corporate_offers.offers_id='$service_id'");
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

            $this->db->query("INSERT INTO transaction_offers(transaction_id, service_id, quantity,      voucher_no, track_id)VALUES ('$transaction_id','$customer_services','$qty','NULL','$id');"
               );

         }
     }
 }
}
function update_pre_empaloiment($pre_empaloiment_id,$appointment_id){
   $this->db->query("UPDATE welezohe_corp.pre_employment SET status ='Booked' , health_appt_id ='$appointment_id' WHERE id = '$pre_empaloiment_id'; ");

}

function is_mobile_available($mobile,$email)
    {
    
     $query =$this->db->query("SELECT id from  welezohe_corp.pre_employment WHERE contact_no='$mobile' OR email='$email'");     
    if ($query->num_rows() >= 1) 
        return true;
    return false;
    }
    
    function get_hospital_query2($service_id)
   {

         $query = $this->db->query("SELECT empanellment.city, empanellment.name_hcc,empanellment.hospital_id from empanellment  ,hospital_service where
         empanellment.hospital_id =hospital_service.hospital_id AND hospital_service.service_id='44' Group by city");
            return $query->result();
   }
   
   function get_hospital_query1($city,$service_id)
   {

         $query = $this->db->query("SELECT empanellment.city, empanellment.name_hcc,empanellment.hospital_id from empanellment  ,hospital_service where
         empanellment.hospital_id =hospital_service.hospital_id AND hospital_service.service_id='$service_id' AND empanellment.city='$city'");
            return $query->result();
   }
   
   function get_hospital_parameter($hospital,$service_id){
       
       $query =$this->db->query("SELECT id from hospital_service  WHERE hospital_id='$hospital' AND  service_id='$service_id' AND none_parameter IS NOT NULL");

	 if ($query->num_rows() >= 1) {
	    
	     return true;
	 }
	 return false;	
   }

}