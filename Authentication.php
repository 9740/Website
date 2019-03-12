<?php if (!defined('BASEPATH')) exit('No di
  t script access allowed');

class Authentication extends CI_Model {

  public function __construct()
  {
    //$this->load->database();
  }


function is_mobile_available($mobile)
	{
	
	 $query =$this->db->query("SELECT customer_deatils.customer_id from customer_deatils LEFT JOIN 
		address ON address.entity_id=customer_deatils.customer_id WHERE address.primary_mob='$mobile' || address.alt_mob='$mobile' ");		
	if ($query->num_rows() >= 1) 
		return true;
	return false;
	}


function is_user_mobile_available($mobile)
	{
	
	 $query =$this->db->query("SELECT id from credentials WHERE contact='$mobile' ");		
	if ($query->num_rows() >= 1) 
		return true;
	return false;
	}


}
