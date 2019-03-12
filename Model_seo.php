<?php if (!defined('BASEPATH')) exit('No di
  t script access allowed');

class Model_seo extends CI_Model {

  public function __construct()
  {
    
  }

  function  get_seo($slug){
    $array = array('slug' => $slug, 'isActive' => 1);
  	$this->db->where($array);
  	
    $query = $this->db->get('seo_on_page');
    $arr = array();
    if ($query !== false) {
        foreach ($query->result_array() as $row) {
            $arr['title'] = $row['title'];
            $arr['description'] = $row['description'];
            $arr['keyword'] = $row['keyword'];
            $arr['robots'] = $row['robots'];
            //$arr['slug1'] = $row['slug'];
            }
        }
        return $arr;

  }
  function is_banned_ip($ip){
     $query =$this->db->query("SELECT ip_address from banned_ip WHERE ip_address='$ip'");   
  if ($query->num_rows() >= 1) {
    return 1;
  }
  return 0;

  }

}