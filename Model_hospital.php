<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Model_hospital extends CI_Model
{
    

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();        
    }

    function get_events($today,$id){
        
        $this->db->where("hospital_id=",$id);
        return $this->db->get("health_appointment");
    }

    function get_appointment_list($today){
        $this->db->where("appointment_date>=",$today);
        $this->db->order_by("appointment_date","asc");
        $result=$this->db->get("health_appointment");
        return $result->result();
    }
    
    function get_appointment_list_customer($id , $service_id,$appointment){
         $query = $this->db->query("SELECT services.*,health_appointment.*,customer_deatils.*,customer_family.* FROM health_appointment,customer_deatils,services,customer_family where health_appointment.service_id=services.service_id AND health_appointment.customer_id=customer_deatils.customer_id AND health_appointment.family_id=customer_family.family_id AND health_appointment.hospital_id='$id' AND health_appointment.service_id='$service_id'");
        return $query->result();
    }
function get_report_list_customer($customer_id ,$file_name,$file_type,$today ,$file_size,$file_description,$url,$f_name){        
            $this->db->query("INSERT INTO `documents_upload`(`entity_id`, `doc_name`,`doc_type`,`doc_category`,`doc_time`,`doc_size`, `doc_description`,`url`,`file_name`) VALUES ('$customer_id','$file_name','$file_type','Customer','$today','$file_size','$file_description','$url','$f_name');"
               );   
    }

    
function get_appointment_download_customer_report($appointment_id){
         $query = $this->db->query("SELECT * FROM reports WHERE appointment_id='$appointment_id'");
        return $query->result();
    }

function get_appointment_list_individual_customer($id , $service_id,$appointment_id){
         $query = $this->db->query("SELECT services.*,health_appointment.*,customer_deatils.*,customer_family.* FROM health_appointment,customer_deatils,services,customer_family where health_appointment.service_id=services.service_id AND health_appointment.customer_id=customer_deatils.customer_id AND health_appointment.family_id=customer_family.family_id AND health_appointment.hospital_id='$id' AND health_appointment.service_id='$service_id' AND health_appointment.appointment_id='$appointment_id'");
        return $query->result();
    }

function get_service_subcategory($service_id){
         $query = $this->db->query("SELECT service_subcategory.*,services.* FROM service_offers,service_subcategory,services where service_offers.service_id=services.service_id AND service_offers.service_subcategory_id=service_subcategory.service_subcategory_id AND services.service_id='$service_id'");
        return $query->result();
    }



     function get_appointment_list_left($id,$today){
         $query = $this->db->query("SELECT services.*,health_appointment.*,customer_deatils.*,customer_family.* FROM services 
        LEFT JOIN health_appointment ON  health_appointment.service_id=services.service_id
        LEFT JOIN customer_deatils ON customer_deatils.customer_id=health_appointment.customer_id
        LEFT JOIN  customer_family ON customer_family.customer_id=customer_deatils.customer_id
        where   health_appointment.hospital_id='$id' AND health_appointment.appointment_date='$today' AND health_appointment.status_appointment='Pending' OR health_appointment.status_appointment='Confirmed'
        GROUP BY health_appointment.appointment_id ; ");
        return $query->result();
    }

    function get_futured_appointment_list($id,$today){
         $query = $this->db->query("SELECT services.*,health_appointment.*,customer_deatils.*,customer_family.* FROM services 
        LEFT JOIN health_appointment ON  health_appointment.service_id=services.service_id
        LEFT JOIN customer_deatils ON customer_deatils.customer_id=health_appointment.customer_id
        LEFT JOIN  customer_family ON customer_family.customer_id=customer_deatils.customer_id
        where   health_appointment.hospital_id='$id' AND health_appointment.appointment_date>'$today' AND health_appointment.status_appointment='Pending' OR health_appointment.status_appointment='Confirmed'
        GROUP BY health_appointment.appointment_id ; ");
        return $query->result();
    }
    


     function get_appointment_detail($id){
         $query = $this->db->query("SELECT services.*,health_appointment.*,customer_deatils.*,customer_family.* FROM health_appointment,customer_deatils,services,customer_family where health_appointment.service_id=services.service_id AND health_appointment.customer_id=customer_deatils.customer_id AND health_appointment.family_id=customer_family.family_id AND health_appointment.hospital_id='$id' AND health_appointment.status_appointment ='Availed'");
        return $query->result();
    }
function get_detail_list($customer_id){
        $query = $this->db->query("SELECT * FROM customer_deatils WHERE customer_id='$customer_id'");
        return $query->row_array();
    }


  function resolve_customer($vouchernumber,$appointment_id){
        $query = $this->db->query("SELECT * FROM health_appointment WHERE tx_offers_voucher='$vouchernumber' AND
            appointment_id='$appointment_id'");
        return $query->result();
    }  
function get_hospital_detail($id){
        $query = $this->db->query("SELECT * FROM empanellment where hospital_id='$id'");
        return $query->result();
    }
 function get_service($service_id){
        $query = $this->db->query("SELECT * FROM services where service_id='$service_id' AND isActive=1" );
             return $query->result();
    }   
function get_service_details($id){
      $query = $this->db->query(" SELECT empanellment.hospital_id,empanellment.name_hcc ,services.*
            FROM empanellment
            LEFT JOIN hospital_service ON hospital_service.hospital_id =empanellment.hospital_id
            LEFT JOIN services ON services.service_id =hospital_service.service_id 
          
            WHERE   empanellment.hospital_id='$id'");
        return $query->result();     

    }

function get_customer_report($customer_id){
         $query = $this->db->query("SELECT * FROM documents_upload WHERE entity_id='$customer_id' AND is_active=1");
        return $query->result();
    }

function customer_appointment_avail($appointment_id,$report_status){
    $this->db->set('report_status', $report_status);     
    $this->db->where('appointment_id', $appointment_id); 
    $update=$this->db->update('health_appointment'); 
    return;

}





}