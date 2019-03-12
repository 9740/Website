<?php if (!defined('BASEPATH')) exit('No di
  t script access allowed');

class Model_home extends CI_Model {

  public function __construct()
  {
    //$this->load->database();
  }


  function testimonials()
  {
    $query = $this->db->query("SELECT * from Testimonials");
    return $query->result(); 
  }

 function get_all_market_packages()
  {
    $city=$this->session->userdata('city');
    $query= $this->db->query("SELECT * 
FROM target_cities 
WHERE is_active > 0 AND serviceCity ='$city' AND category NOT IN ('Home Health Check','Dentistry','Consultation','Pharmacy') GROUP BY product_name ORDER BY product_id DESC;
");
    return $query->result();
  }
  function get_all_packages()
  {
    $city=$this->session->userdata('city');
    $query= $this->db->query("SELECT * FROM target_cities 
WHERE is_active > 0 AND serviceCity ='Bangalore' 
AND category NOT IN ('Home Health Check','Dentistry','Consultation','Pharmacy') AND product_name != service_name 
GROUP BY product_name ORDER BY is_active;
");
    return $query->result();
  }

   function get_all_packages_home()
  {
    $city=$this->session->userdata('city');
    $query= $this->db->query("SELECT * FROM target_cities 
WHERE is_active > 0 AND serviceCity ='$city' 
AND category NOT IN ('Home Health Check','Dentistry','Consultation','Pharmacy') AND product_name = service_name 
GROUP BY product_name ORDER BY is_active;
");
    return $query->result();
  }
    function get_all_trelleborg_package($city,$id,$id1)
  {
    $city=$this->session->userdata('city');
    $query= $this->db->query("SELECT * FROM target_cities 
      WHERE  serviceCity ='Bangalore' 
      AND product_id IN ('$id','$id1')
      GROUP BY product_id  ORDER BY is_active;");
    return $query->result();
  }
      function get_all_trelleborg_package1($city,$id,$id1,$id2,$id3)
  {
    $city=$this->session->userdata('city');
    $query= $this->db->query("SELECT * FROM target_cities 
      WHERE  serviceCity ='Bangalore' 
      AND product_id IN ('$id','$id1','$id2','$id3')
      GROUP BY product_id  ORDER BY is_active;");
    return $query->result();
  }

  
public function get_autocomplete_healthcheckup() 
  {
   $city=$this->session->userdata('city');
    $query= $this->db->query("SELECT * FROM target_cities WHERE is_active>0 AND welezo_city = '$city' GROUP BY product_name ORDER by product_id;");
    return $query->result();
             
  }
  public function network_partner_logo()
  {
    $query = $this->db->query('SELECT * FROM `network_partner_logo` WHERE entity_type="Hospital";');
    return $query->result();
  }
    public function corporate_partner_logo()
  {
    $query = $this->db->query('SELECT * FROM `network_partner_logo`  WHERE entity_type="Corporate";');
    return $query->result();
  }

  public function get_autocomplete($search_data,$city) 
  {
    $query = $this->db->query("SELECT hospital_id, name_hcc,location,slug from empanellment where isActive>0 AND   city='$city' AND  name_hcc REGEXP '$search_data'  ORDER BY name_hcc ");
    return $query->result(); 
             
  }

    public function serch_package_service_hospital() 
  {
    $query = $this->db->query("SELECT service_name from services WHERE services.isActive>0
                UNION
                SELECT name_hcc from empanellment WHERE empanellment.city='Bangalore'
                UNION
                  SELECT product_name from product_master WHERE product_master.is_active>0;");
    return $query->result(); 
             
  }


  function show_detail($id)
    {
      $this->db->where("product_id",$id);
      $query=$this->db->get("product_master");
      return $query->result();
    }

  function get_product_services($id)
  {
  
   $query = $this->db->query("SELECT parameter_count.Parameter, services.service_id,services.category,services.service_name,services.service_description,product_offers.quantity
           FROM services
           INNER JOIN product_offers ON product_offers.service_id=services.service_id
           INNER JOIN parameter_count ON parameter_count.service_id=services.service_id
            WHERE product_offers.product_id='$id'
            ORDER BY services_ordering");
    return $query;

  }


  function get_product_services_detail($id)
  {
  
   $query = $this->db->query("SELECT product_master.*, services.service_id,services.category,services.service_name,services.service_description,product_offers.quantity
            FROM services
            INNER JOIN product_offers ON product_offers.service_id=services.service_id
            INNER JOIN product_master on product_master.product_id=product_offers.product_id
            WHERE product_offers.product_id='$id'
            ORDER BY services_ordering");
    return $query->result();

  }
  function get_service_category($id)
  {

   $query = $this->db->query("SELECT services.service_id,services.service_name,service_subcategory.service_subcategory_name, service_subcategory.service_description,service_subcategory.subcategory_img FROM service_subcategory
            INNER JOIN service_offers ON service_offers.service_subcategory_id =service_subcategory.service_subcategory_id
            INNER JOIN services ON services.service_id =service_offers.service_id 
            INNER JOIN product_offers ON product_offers.service_id=services.service_id  
            WHERE   product_offers.product_id='$id'
            ORDER BY service_offers.ordering");
    return $query->result();
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

// symptoms functioon start

function get_body_parts($gender){
  $query = $this->db->query("SELECT * from human_body_parts where body_side LIKE '%Front%' AND  gender = '$gender' ");
    return $query->result(); 
}

function get_body_parts_Back($gender){
  $query = $this->db->query("SELECT * from human_body_parts where body_side LIKE '%Back%' AND gender = '$gender'");
    return $query->result(); 
}
 public function get_all_symptoms($body_id,$year) 
        {
 $query = $this->db->query("SELECT * from symptom_category 
      LEFT JOIN symptom_part_age ON symptom_part_age.symptom_id = symptom_category.symptom_id      
      where symptom_part_age.part_id='$body_id' AND symptom_part_age.age_group ='$year';");
  return $query->result(); 
             
        }
         public function get_body_id($name,$area,$gender) 
        {

   $query =$this->db->query(" SELECT body_part_id from human_body_parts where body_sub_part LIKE '%$name%' AND body_side LIKE'%$area%' AND  gender LIKE '%$gender%'");

            $row  = $query->row();
            return $row->body_part_id;
             
        }
public function get_all_symptoms_sub_couses($symptom_id) 
        {
 $query = $this->db->query("SELECT * from symptom_part_age 
      LEFT JOIN symptom_causes ON symptom_part_age.id = symptom_causes.symptom_part_age_id 
      LEFT JOIN health_causes ON symptom_causes.cause_id = health_causes.cause_id 
      where symptom_part_age.id='$symptom_id';");
  return $query->result(); 
             
        }
public function get_symptoms_name($symptom_id) 
        {
 $query = $this->db->query(" SELECT symptom_name from symptom_category
      LEFT JOIN symptom_part_age ON symptom_part_age.symptom_id = symptom_category.symptom_id
      where symptom_part_age.id='$symptom_id';");
  return $query->result(); 
             
        }


    

// symptoms functioon end

  public function get_service_hospital($city,$id){
       $query = $this->db->query("SELECT v_city_services.hospital_id,v_city_services.hospital_name ,v_city_services.service_id,v_city_services.service_name
            FROM v_city_services
            LEFT JOIN product_offers ON product_offers.service_id =v_city_services.service_id           
            WHERE product_offers.product_id='$id' AND v_city_services.city='$city' GROUP BY v_city_services.hospital_id");
        return $query->result();    
    }
      public function is_coupon_available($coupon){
          $query =$this->db->query("SELECT * from campaign_offers WHERE offer_code='$coupon' ");   
  if ($query->num_rows() >= 1) {
  
    return $query->result();
  }
  return false;
         
     }
      function get_hospital_details($province){
      $query = $this->db->query(" SELECT empanellment.hospital_id,empanellment.name_hcc ,services.service_name,services.service_id
            FROM empanellment
            LEFT JOIN hospital_service ON hospital_service.hospital_id =empanellment.hospital_id
            LEFT JOIN services ON services.service_id =hospital_service.service_id 
            WHERE   empanellment.hospital_id='$province' AND services.isActive>0;");
        return $query->result();     

    }


     public function resolve_new_login($Username,$Password) {    
    $query = $this->db->query("SELECT  credentials.* ,user_roles.entity_id AS 'entity_id',user_master.fullName AS 'username',user_roles.role_type as'role_type' from credentials
     LEFT JOIN user_roles ON user_roles.user_id=credentials.userId 
     LEFT JOIN user_master ON user_master.userId=user_roles.user_id  
     where user_roles.role_type !='Employee' AND (credentials.login_name='$Username' AND credentials.password='$Password')
      OR
     (credentials.contact='$Username' AND credentials.password='$Password')");
     
    
    return $query->result(); 
  }

   function get_Payment_transaction_detail($id)
    {

         $query= $this->db->query(" SELECT * FROM product_master LEFT JOIN transaction_master ON transaction_master.product_id=product_master.product_id LEFT JOIN payment_details ON transaction_master.transaction_id=payment_details.transaction_id WHERE transaction_master.customer_id='$id' ; ");
        return $query->result();

    }
    function get_customer($id)
    {
      
        $query= $this->db->query("SELECT * FROM customer_deatils WHERE customer_id='$id'  LIMIT 1 ");
      return $query->result();

    }

    function get_customer_address($id)
    {
      $query= $this->db->query(" SELECT * FROM address WHERE address_type_id IN (1,2,3) AND entity_id ='$id' ; ");
      return $query->result();

    }
   function get_payment_detail($id)
    {
      $query= $this->db->query(" SELECT transaction_master.*,payment_details.* FROM `transaction_master`
   LEFT JOIN payment_details ON payment_details.`transaction_id`=transaction_master.`transaction_id` 
    where transaction_master.`customer_id`='$id'");
      return $query->result();

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
      function get_Age($year){
         $query= $this->db->query("SELECT * FROM `age_detail` WHERE `Age_id`='$year';");
          $query= $query->result();
        foreach ($query as $key) {
            $age_value=$key->age_value;
        }

         return $age_value;
    }
function get_all_homehealth_packages(){
      $city=$this->session->userdata('city');
     $query= $this->db->query(" SELECT * FROM target_cities WHERE is_active>0 AND welezo_city = '$city' AND category='Home Health Check' GROUP BY product_name ORDER by is_active;");
        return $query->result();    
    }
function update_userrole($userid,$customer_id){
    $this->db->set('entity_id', $customer_id);     
    $this->db->where('user_id', $userid); 
    $update=$this->db->update('user_roles'); 

}

 function get_dm_diapack()
  {
    $city=$this->session->userdata('city');
    $query= $this->db->query("SELECT * 
FROM target_cities 
WHERE product_id ='53' GROUP BY product_name ;
");
    return $query->result();
  }


   function get_dm_diapack1()
  {
    $city=$this->session->userdata('city');
    $query= $this->db->query("SELECT * 
FROM target_cities 
WHERE product_id ='56' GROUP BY product_name ;
");
    return $query->result();
  }

  function grabon_master()
  {
    $city=$this->session->userdata('city');
    $query= $this->db->query("SELECT * 
FROM target_cities 
WHERE product_id ='53' GROUP BY product_name ;
");
    return $query->result();
  }

   function grabon_Executive()
  {
    $city=$this->session->userdata('city');
    $query= $this->db->query("SELECT * 
FROM target_cities 
WHERE product_id ='56' GROUP BY product_name ;
");
    return $query->result();
  }

  function grabon_Wellwomenplus()
  {
    $city=$this->session->userdata('city');
    $query= $this->db->query("SELECT * 
FROM target_cities 
WHERE product_id ='43' GROUP BY product_name ;
");
    return $query->result();
  }



function get_all_parmeter($parameter_id)
  {  
    $query= $this->db->query("SELECT screening, our_price , market_price FROM service_parameters WHERE health_parameter_id in ($parameter_id) GROUP BY health_parameter_id;");
    return $query->result();
  }

function  update_residence($customer_id){

   $this->db->set('correspondence','WorkPlace');     
    $this->db->where('customer_id', $customer_id); 
    $update=$this->db->update('customer_deatils'); 
}

function show_all_deatil($switch,$entity_id){
    $query= $this->db->query("SELECT * FROM `customer_deatils`
   LEFT JOIN address ON address.entity_id=customer_deatils.customer_id
    where address.address_type_id='$switch' AND  address.entity_id='$entity_id'");
    return $query->result();
}

function get_all_packages_grabon_offer(){
$city=$this->session->userdata('city');
    $query= $this->db->query("SELECT * 
FROM target_cities 
WHERE product_id in (43,53,56) GROUP BY product_name ;
");
    return $query->result();

}
}