<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Hospital_model extends Model_crud
{
	public function tableName() 
    {
        return 'empanellment';
    }
}