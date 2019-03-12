<?php if (!defined('BASEPATH')) exit('No di
  t script access allowed');

class Model_pharmacy extends CI_Model {

  public function __construct()
  {
   
   }


     function pharmacy_count(){
    $city= $this->session->userdata('city'); 
     $hospital=$this->db->query("SELECT COUNT(*) as count FROM pharmacies WHERE city='$city' AND isActive=1");
      foreach ($hospital->result() as $row) {
                $data= $row->count;
            }
             return $data;
  }
  function get_pharmacy($limit, $start){
   $city= $this->session->userdata('city'); 
    $this->db->limit($limit, $start);
    $this->db->where('city',$city);
    $this->db->where('isActive',1);

   $hospital=$this->db->get("pharmacies");
    if ($hospital->num_rows() > 0) {
            foreach ($hospital->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
  }
   function get_pharmacy_list(){
   $city= $this->session->userdata('city'); 
    $this->db->where('city',$city);
    $this->db->where('isActive',1);
    $this->db->Order_by('pharmacy_name');
   $pharmacy=$this->db->get("pharmacies");
   return $pharmacy->result(); 
     
  }

  function get_pharmacy_location(){
   $city= $this->session->userdata('city'); 
     $pharmacy=$this->db->query("SELECT `location` FROM `pharmacies` WHERE `city`= '$city' GROUP BY `location`;");
     return $pharmacy->result(); 

  }

  function get_pharmacy_detail($pharmacy_id){
          $city= $this->session->userdata('city'); 
     $pharmacy=$this->db->query("SELECT * FROM `pharmacies` WHERE `city`= '$city' AND 
     	pharmacy_id='$pharmacy_id';");
     return $pharmacy->result(); 


  }
}