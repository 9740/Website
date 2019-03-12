<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Model_crud extends CI_Model
{
	public $_attr;
    private $_table;
	public $_with = '';

	public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->_table = $this->tableName();
    }


	function tableName() {
    }

	function get($param = array(), $count = '') {
        $data = $this->_select_parameters($param);
        $this->db->select($data['select']);
        $this->db->from($data['from']);
        $this->_where($data);
        $this->_joins($data);
        $this->_order_by($data);
        $this->_group_by($data);
        if ($data['limit'] != '') {
            $this->db->limit($data['limit'], $data['offset']);
        }

        $query = $this->db->get();
        if ($count == '') {
            return $this->get_result_data($query);
        } else {
            return $this->get_result_data($query, 'count');
        }
    }

	public function find($param)
	{
		if(is_array($param))
		{
			$data=$this->_select_parameters($param);
            //echo '<pre>'; print_r($data); echo '</pre>'; exit();
			$this->db->select($data['select']);
			$this->db->from($data['from']);
			$this->_where($data);
			$this->_joins($data);
        } else {
            $this->db->from($this->tableName());
            $this->db->where('id', $param);
        }
        $query = $this->db->get();
        return $this->get_result_data($query, 'single');
	}
	

	private function _select_parameters($param)
	{
		$data = array(

						'select'=>isset($param['select']) ? $param['select']:'*',
						'from'=>isset($param['from']) ? $param['from']:$this->_table,
						'where'=>isset($param['where']) ? $param['where']:'',
						'limit' => isset($param['limit']) ? $param['limit'] : '',
			            'offset' => isset($param['offset']) ? $param['offset'] : '',
			            'join' => isset($param['join']) ? $param['join'] : '',
			            'orderby' => isset($param['orderby']) ? $param['orderby'] : array('id' => 'asc'),
			            'groupby' => isset($param['groupby']) ? $param['groupby'] : '',
			 		);
		return $data;
	}

	private function _where($data) {
        if ($data['where'] != '') {
            if (is_array($data['where']) && count($data['where']) > 0) {
                foreach ($data['where'] as $column => $condition) {

                    // Ambiguous issue 
                    if (isset($data['join']) && $data['join'] != '') {
                        $this->db->where($data['from'] . '.' . $column, $condition);
                    } else {
                        $this->db->where($column, $condition);
                    }
                }
            } else {
                $this->db->where($data['where']);
            }
        }
    }

    private function _joins($data) 
    {
        if ($data['join'] != '') {
            $join_data = $data['join'];
            $this->db->join($join_data['table'], $join_data['table'] . '.' . $join_data['foreign_key'] . '=' . $data['from'] . '.' . $join_data['primary_key']);
        }
    }

    private function _order_by($data) 
    {
        foreach ($data['orderby'] as $key => $value) {
            $this->db->order_by($data['from'] . '.' . $key, $value);
        }
    }

    private function _group_by($data) 
    {
        if ($data['groupby'] != '') {
            $this->db->group_by($data['groupby']);
        }
    }

     private function get_result_data($query, $type = '') {
        if ($query->num_rows() > 0) {
            switch ($type) {
                case 'single':
                    $this->_attr = $query->row();
                    break;
                case 'count':
                    $this->_attr = $query->num_rows();
                    break;
                default:
                    $this->_attr = $query->result();
                    break;
            }
            if ($this->_with != '') {
                $this->get_relationship_table_data();
            }
            return $this->_attr;
        } else {
            return false;
        }
    }
    public function save($id,$data,$column='')
    {
        if($id!='' && $column!='')
        {
            $this->db->where($column, $id);
            if($this->db->update($this->tableName(), $data))
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            if($this->db->insert($this->tableName(), $data))
            {
                return $this->db->insert_id();
            }
            else
            {
                return FALSE;
            }
        }
    }
}

            