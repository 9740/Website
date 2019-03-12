<?php if (!defined('BASEPATH')) exit('No di
  t script access allowed');

class Model_service extends CI_Model {

  public function __construct()
  {
   
   }

   function health_check(){
   	$query = $this->db->query("SELECT services.* FROM services 
	WHERE    services.isActive>0 AND category ='Health Check'
	GROUP BY services.service_id ORDER BY services.isActive;");
    return $query->result(); 
   }

    function health_check_service(){
   	$query = $this->db->query("SELECT service_subcategory.*,service_offers.service_id FROM service_subcategory 
	LEFT JOIN service_offers  ON service_offers.service_subcategory_id =service_subcategory.service_subcategory_id
	LEFT JOIN services  ON services.service_id =service_offers.service_id
	WHERE service_subcategory.isActive>0
 	ORDER BY service_offers.ordering;");
    return $query->result(); 
   }

     function  health_check_packages(){
   $city=$this->session->userdata('city');
     $query= $this->db->query("SELECT * FROM target_cities WHERE is_active>0 AND welezo_city = '$city' AND category !='Home Health Check' GROUP BY product_name ORDER by is_active;");
        return $query->result();  
   }

function main_services(){
  $query = $this->db->query("SELECT service_id, category, service_name,
SUM(CASE WHEN product_id = 1 THEN quantity ELSE 0 END) 'WelezoBasic',
SUM(CASE WHEN product_id = 2 THEN quantity ELSE 0 END) 'WelezoRegular',
SUM(CASE WHEN product_id = 3 THEN quantity ELSE 0 END) 'WelezoEconomy',
SUM(CASE WHEN product_id = 4 THEN quantity ELSE 0 END) 'WelezoPremium'
FROM v_prod_offers GROUP BY service_id
HAVING SUM( CASE WHEN product_id IN (1,2,3,4) THEN quantity ELSE 0 END) > 0;
");
    return $query->result(); 
}

   

}