<?php if (!defined('BASEPATH')) exit('No di
  t script access allowed');

class Model_homehealth extends CI_Model {

  public function __construct()
  {
    //$this->load->database();
  }

  function  get_all_homehealthpackages($city){
  	 $query = $this->db->query("SELECT * FROM target_cities WHERE is_active>0 AND welezo_city = '$city' AND category='Home Health Check' GROUP BY product_name ORDER by product_id;");
    return $query->result(); 
  }
 function  get_all_homehealthservice(){
     $query = $this->db->query("SELECT * FROM services WHERE  isActive>0 AND  category!='Health Check'   ORDER BY service_id;");
    return $query->result(); 
  }

   function  get_all_homehealthparameter(){
     $query = $this->db->query("SELECT * FROM service_parameters ;");
    return $query->result(); 
  }
// function  get_all_homehealthparameter(){
//      $query = $this->db->query("SELECT soff.*,serSu.service_subcategory_name,serSu.service_description FROM service_offers soff
// LEFT JOIN services ser ON ser.service_id = soff.service_id
// LEFT JOIN service_subcategory serSu ON serSu.service_subcategory_id = soff.service_subcategory_id
// WHERE ser.category NOT IN ('Health Check');");
//     return $query->result(); 
//   }

    function  get_all_market_homehealthpackages($city){
     $query = $this->db->query("SELECT * FROM target_cities WHERE is_active>0 AND welezo_city = '$city' AND category='Home Health Check' GROUP BY product_name ORDER by product_id DESC LIMIT 6;");
    return $query->result(); 
  }
  function  get_popular_homehealthpackages($city){
     $query = $this->db->query("SELECT * FROM target_cities WHERE is_active>0 AND welezo_city = '$city' AND category='Home Health Check' GROUP BY product_name ORDER by product_id;");
    return $query->result(); 
  }

public function get_autocomplete($search_data,$city) 
        {
 $query = $this->db->query("SELECT * FROM service_subcategory 
INNER JOIN service_offers ON service_offers.service_subcategory_id=service_subcategory.service_subcategory_id
INNER JOIN services ON services.service_id=service_offers.service_id
INNER JOIN target_cities ON target_cities.service_id=services.service_id
INNER JOIN product_master ON product_master.product_id=target_cities.product_id
where
target_cities.city='$city' AND product_master.is_active>0 AND  service_subcategory.service_subcategory_name LIKE'$search_data%'
GROUP BY product_master.product_id ORDER BY product_master.is_active; ");
  return $query->result(); 
             
        }
}