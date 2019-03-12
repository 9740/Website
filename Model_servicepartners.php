<?php if (!defined('BASEPATH')) exit('No di
  t script access allowed');

class Model_servicepartners extends CI_Model {

  public function __construct()
  {
   
   }

function get_nearby_service_partners_count($lat,$lan){
 $query = $this->db->query("SELECT COUNT(*) as count, 3956 * 2 * ASIN(SQRT(POWER(SIN(('$lat' -abs(dest.latitude)) * pi()/180 / 2),2) + COS('$lat' * pi()/180 ) * COS(abs(dest.latitude) *  pi()/180) * POWER(SIN(('$lan' - abs(dest.longitude)) *  pi()/180 / 2), 2))
) as distance
FROM empanellment as dest
WHERE isActive=1
having distance > 5

ORDER BY distance;");
        return $query->result();
}

function get_nearby_service_partners($lat,$lan,$limit, $start){
 $query = $this->db->query("SELECT dest.*,   3956 * 2 * ASIN(SQRT(POWER(SIN(('$lat' -abs(dest.latitude)) * pi()/180 / 2),2) + COS('$lat' * pi()/180 ) * COS(abs(dest.latitude) *  pi()/180) * POWER(SIN(('$lan' - abs(dest.longitude)) *  pi()/180 / 2), 2))
) as distance
FROM empanellment as dest
WHERE isActive=1
having distance > 5

ORDER BY distance limit $limit ;");
 if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
}

function get_lat_lan($location){
    $query=$this->db->query("SELECT * FROM service_locations WHERE slug='$location'");

   return $query->result();

}

function get_service_hospitals_count($service){
  $city= $this->session->userdata('city');  
       $query = $this->db->query("SELECT COUNT(*) as count from v_city_services  
        LEFT JOIN services ON services.service_id =v_city_services.service_id 
        LEFT JOIN empanellment ON empanellment.hospital_id =v_city_services.hospital_id where  services.slug ='$service' AND
        empanellment.city = '$city' AND empanellment.isActive IS TRUE

        ");
      
            foreach ($query->result() as $row) {
                  $data= $row->count;
            }
            return $data;
     

    }

function get_service_hospitals($service,$limit, $start){
  $city= $this->session->userdata('city');  
       $query = $this->db->query("SELECT v_city_services.*,services.slug,empanellment.longitude,empanellment.latitude ,empanellment.slug AS hospital_slug from v_city_services  LEFT JOIN services ON services.service_id =v_city_services.service_id 
        LEFT JOIN empanellment ON empanellment.hospital_id =v_city_services.hospital_id where  services.slug ='$service' AND
        empanellment.city = '$city' AND empanellment.isActive IS TRUE  
        GROUP BY v_city_services.hospital_id limit $start,$limit ");
       if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;

    }

  function networkpartner_count(){
    $city= $this->session->userdata('city'); 
     $hospital=$this->db->query("SELECT COUNT(*) as count FROM empanellment WHERE city='$city' AND isActive=1");
      foreach ($hospital->result() as $row) {
                $data= $row->count;
            }
             return $data;
  }
  function get_empanelment1($limit, $start){
   $city= $this->session->userdata('city'); 
    $this->db->limit($limit, $start);
    $this->db->where('city',$city);
    $this->db->where('isActive',1);
   $hospital=$this->db->get("empanellment");
    if ($hospital->num_rows() > 0) {
            foreach ($hospital->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
  }

 function get_empanelment(){
  
  $city= $this->session->userdata('city');  
    $hospital=$this->db->query("SELECT * FROM empanellment WHERE city='$city' AND isActive=1");
    if ($hospital->num_rows() > 0) {
            foreach ($hospital->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
  }



   public function get_services_query($city)
    { 
            $query = $this->db->query("SELECT * FROM service_locations WHERE  is_active>0 AND district_city='$city' ORDER BY `location`;  ");
            return $query->result();
        
    }

    public function get_autocomplete($search_data) 
        {
          $city= $this->session->userdata('city');  
 $query = $this->db->query("SELECT hospital_id, name_hcc,location,slug from empanellment where isActive>0 AND  city='$city' AND  name_hcc LIKE'$search_data%'  ORDER BY name_hcc ");
  return $query->result(); 
             
        }

         public function get_autocomplete_hospital($hospital_id) 
  {
    $query = $this->db->query("SELECT * from empanellment where hospital_id='$hospital_id'; ");
    return $query->result(); 
             
  }

}