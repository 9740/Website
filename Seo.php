<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Shopping Cart class for manage products
 */

class Seo 
{

	public function __construct()
    {
    	
        $this->CI = & get_instance();
        $this->CI->load->driver('session');        
        $this->CI->load->model('Model_seo');
       // $this->CI->load->library("geolib/geolib");
    }

    public function get_metadata($slug){
    	
		$arrSeo = $this->CI->Model_seo->get_seo($slug);
			//$ip = $this->CI->input->ip_address();
	//	$device=$this->CI->geolib->user_agent();
    //	$data = $this->CI->geolib->ip_info($ip);
    //	$exist_ip=$this->CI->Model_seo->is_banned_ip($ip);
    
    // if($exist_ip==0){
    // 	$user_info=   array(
    // 		'ip_address'=>$data['geoplugin_request'],
    // 		'latitude'=>$data['geoplugin_latitude'],
    // 		'longitude'=>$data['geoplugin_longitude'],
    // 		'city'=>$data['geoplugin_city'],
    // 		'region'=>$data['geoplugin_region'],
    // 		'country_code'=>$data['geoplugin_countryCode'],
    // 		'country_name'=>$data['geoplugin_countryName'],
    // 	    'is_mobile'=>$device['is_mobile'],
    // 	    'is_browser'=>$device['is_browser'],
    // 	    'is_referral'=>$device['is_referral'],
    // 		'url_link'=>base_url().$slug,
    // 		'visited_date_time'=>date('Y-m-d H:i:s'),
    // 		'exit_date_time'=>''
    // 	);
    // 	$this->CI->db->insert('online_users', $user_info);
    	
    // 	}
	  	  
	  	if($arrSeo)
	  	{
		    $head['title'] = @$arrSeo['title'];
		    $head['description'] = @$arrSeo['description'];
		    $head['keywords'] = @$arrSeo['keyword'];
		    $head['robots'] = @$arrSeo['robots'];
		    $head['canonical'] = base_url().$slug;
	  	}
	  	else
	  	{
		    $head['title'] = '';
		    $head['description'] = '';
		    $head['keywords'] = '';
		    $head['robots'] = '';
		    $head['canonical'] = base_url().$slug;
		} 

		return  $head;  
    }

    


}

