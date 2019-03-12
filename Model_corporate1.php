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
// function getofferid($id){
// $query = $this->db->query("SELECT offers_id from welezohe_corp.corporate_offers WHERE corporate_id='$id'");
//     return $query->result(); 
// }
public function _get_datatables_query()
    {
       $column_search=array('emp_name','contact_no','age','gender','email','address');
        $id= $this->session->userdata('entity_id');
        $this->db->from($this->table);
        $this->db->where('corporate_id',$id);
        $order = array('id' => 'desc'); 
 	
        $i = 0;
     
        foreach ($column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
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
    	$id= $this->session->userdata('entity_id');
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
    function get_monthly_report($id){
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
    function get_corporate_report($id){
         $query = $this->db->query("SELECT * FROM documents_upload WHERE entity_id='$id' AND is_active=1");
        return $query->result();
    }

     function get_corporate_package($id){
         $query = $this->db->query("SELECT * FROM corporate_offers LEFT JOIN product_master ON corporate_offers.product_id=product_master.product_id WHERE corporate_offers.corporate_id='$id'");
        return $query->result();
    }
  
     function get_corporate_detail($id){
         $query = $this->db->query("SELECT * FROM corporate_details where corporate_id='$id'");
        return $query->result();
    }  
    
    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id',$id);
        $query = $this->db->get();
 
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
 
 

}