<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Home extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();		
		$this->load->database();	
		$this->load->model('Model_home','model_home');
		$this->load->model('Model_homehealth','homehealth');
		$this->load->model('Welezo_Authentication','welezo_authentication');   
    $this->load->model('Model_servicepartners','servicepartners');
		$this->load->model('Model_seo','Model_seo');	
		$this->load->helper(array('url','language'));
		$this->load->library('seo');
		//$this->load->library("geolib/geolib");
	
	}

	public function index()
	{    
		$city= $this->session->userdata('city');
	    if($city =="")
	    	{
	       		$data= array(
	           'city' =>'Bangalore',
	          	);  
	       		$this->session->set_userdata($data);    
	  		}
	  	$slug='home';	

	  	$head= $this->seo->get_metadata($slug);
	  //	$ip = $this->input->ip_address();
      //  $head['loc'] = $this->geolib->ip_info($ip);
      //  $head['agentdata'] = $this->geolib->user_agent();
        
	  	 $head['cart_session'] = $this->session->userdata('cart_session');
		  $today=date("Y-m-d");
	  	$data['healthpackage'] = $this->model_home->get_all_packages_home();	
	  	$data['network']=$this->model_home->network_partner_logo(); 
	  	$data['testimonials'] = $this->model_home->testimonials();
        $data['helathplan'] = $this->model_home->get_all_packages($city);
        $data['provinc'] = $this->servicepartners->get_services_query($city);
         $data['hospital'] = $this->model_home->serch_package_service_hospital();	  
          $data['package']= $this->model_home->get_autocomplete_healthcheckup();		  	
	    $this->load->view('header',$head);
		$this->load->view('home/home',$data);		
		$this->load->view('footer');
	}
  
	public function autocomplete()
    {         
        $city = $this->input->post('city');
        $search_data = $this->input->post('search_data');         
        $result = $this->model_home->get_autocomplete($search_data,$city);
       	if (!empty($result))
        {
            echo"<ul style='padding: 4px;'>";
            foreach ($result as $row):
              
                  echo "<li value=".$row->hospital_id." style='list-style-type: none;' onclick='showpharmacy(this.value);''>" . $row->name_hcc . "</li><br>";
               
            endforeach;
               echo"<ul>";
        }
        else
        {
            echo "<li> <em> Result Not found ... </em> </li>";
        }
            
    }


public function homehealthpackages()
    {         
        $city = $this->input->post('city');
        $search_data = $this->input->post('search_data');         
        $result = $this->model_home->get_autocomplete($search_data,$city);
        if (!empty($result))
        {
            foreach ($result as $row):
                echo"<ul>";
                  echo " <a href='".base_url()."network-partners/".$row->slug."' class='search_result'><li style='height:29px;border-bottom:1px solid aliceblue;'><div>" . $row->name_hcc . "</div></li></a><br>";
                  echo"<ul>";
            endforeach;
        }
        else
        {
            echo "<li> <em> Result Not found ... </em> </li>";
        }
            
    }
    
     
    public function homehealth_search(){
    $package= $this->model_home->get_autocomplete_healthcheckup();  
    $data=$package;
    echo $data=json_encode($data);

  }
    public function default_search(){
    $package= $this->model_home->get_autocomplete_healthcheckup();  
    $data=$package;
    echo $data=json_encode($data);

  }
public function city()
        {         
                  
               $city = $this->input->post('city');
               //$this->session->unset_userdata('city');
                 $session_data= array(
			 	          'city' =>$city,
				
		  	);	
           $this->session->set_userdata($session_data);
        }


	function faq()
	{
		$slug='faq';	
	  	$head= $this->seo->get_metadata($slug);
	  	 $head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
		$this->load->view('home/faq');	
		$this->load->view('footer');
	}

    public function coming_soon(){
		$this->load->view('header');
		$this->load->view('services/coming_soon');
		$this->load->view('footer');

	}
	function terms_conditions()
	{
		$slug='terms-and-conditions';	
	  	$head= $this->seo->get_metadata($slug);
	  	 $head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
		$this->load->view('home/terms_conditions');
		$this->load->view('footer');	
	}

	function contact_us()
	{
		$slug='contact-us';	
	  	$head= $this->seo->get_metadata($slug);
	  	 $head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
		$this->load->view('home/contact_us');
		$this->load->view('footer');	
	}

	function careers()
	{
		$slug='careers';	
	  	$head= $this->seo->get_metadata($slug);
	  	 $head['cart_session'] = $this->session->userdata('cart_session');
	  	 $this->load->view('header',$head);
		$this->load->view('home/careers');	
		$this->load->view('footer');
	}
// symptoms functioon start

public function select_data() {

        $Age = $this->input->post('Age');
        $gender = $this->input->post('gender');
        $session_data= array(
          'Age' =>$Age,
          'gender'=>$gender
         
          );  
        $this->session->set_userdata($session_data);
      redirect('symptoms'); 
      }


    public function symptom() 
   {

   
     $year= $this->session->userdata('Age');
      if($year =="")
        {
            $data= array(
             'gender' =>'Male',
             'Age' =>'1'
              );  
            $this->session->set_userdata($data);    
        }
         $gender= $this->session->userdata('gender');
     $year= $this->session->userdata('Age');
    	$slug='symptoms';	
	  	$head= $this->seo->get_metadata($slug);
	  	 $head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
        $data['body_part']=$this->model_home->get_body_parts($gender);
        $Age=$this->model_home->get_Age($year);
        $session_data= array(
          'Age1' =>$Age,  
          );  
        $this->session->set_userdata($session_data);
        $this->load->view('Symptom',$data );
        $this->load->view('footer');

      }

   public function backsymptom() {
      $gender= $this->session->userdata('gender');
      $year= $this->session->userdata('Age');
      if($gender =="")
        {
            $data= array(
             'gender' =>'Male',
             'Age' =>'1'
              );  
            $this->session->set_userdata($data);    
        }
      	$slug='Backsymptoms';	
	  	$head= $this->seo->get_metadata($slug);
	  	 $head['cart_session'] = $this->session->userdata('cart_session');
	  	$this->load->view('header',$head);
        $Age=$this->model_home->get_Age($year);
        $session_data= array(
          'Age1' =>$Age,  
          );  
        $this->session->set_userdata($session_data);
        $data['body_part']=$this->model_home->get_body_parts_back($gender);
         $s_id=$this->db->get_where('seo_on_page',array('slug' => $slug))->row();
        $this->load->view('Symptom_back',$data);
            $this->load->view('footer');

      }
  public function sub_symptom_detail(){
    $name = $this->input->post('clickedBtn');
    $area=$this->input->post('side');
    $gender= $this->session->userdata('gender');
    $body_id = $this->model_home->get_body_id($name,$area,$gender);
    $year= $this->session->userdata('Age');
    $provinces= $this->model_home->get_all_symptoms($body_id,$year);
    if(count($provinces)>0)
        {
               $pro_select_box = ''; 

               $pro_select_box = '<ul class="result">';               
                $pro_select_box .=' <Strong><li style="font-size:15px; ">'.$name.' Symptoms </li></strong><hr style="width:100%;">';               
            foreach ($provinces as $province) {

                $pro_select_box .='<li style="color: #01b7f2;" onclick="getcouse(this.value)" value="'.$province->id.'" id="'.$province->id.'"><a href="#" >'.$province->symptom_name.'</a></li>';

			//	$pro_select_box .="<a href='".base_url()."dashboard/subsymptom_couses/".$province->id."'><li>" . $province->symptom_name . "</li></a>";
                         
            }
             $pro_select_box .='</ul>';
            echo json_encode($pro_select_box);

        }
  }
 

 public function subsymptom_couses(){
    $symptom_id = $this->input->post('symptomid');
    $name= $this->model_home->get_symptoms_name($symptom_id);
     if(count($name)>0)
        {
 foreach ($name as $name) {
                $symptom_name= $name->symptom_name;

        }
      }
    $provinces= $this->model_home->get_all_symptoms_sub_couses($symptom_id);
    if(count($provinces)>0)
        {
               $pro_select_box = ''; 

               $pro_select_box = '<ul class="result">';               
                $pro_select_box .=' <Strong><li style="font-size:15px;">'.$symptom_name.' </li></strong><hr style="width:100%;">';               
            foreach ($provinces as $province) {
                $pro_select_box .='<li style="color: #01b7f2;">'.$province->cause_name.'</li>';              
            }
             $pro_select_box .='</ul>';
            echo json_encode($pro_select_box);

        }
  }
// symptoms functioon end

	
	function verify_coupencode(){
       $coupon_code = $this->input->post('coupon_code');
       if($result=$this->model_home->is_coupon_available($coupon_code)){
      foreach ($result as $r) {
            $offer_percent =$r->discount_perc;
            $product_price =$this->session->userdata('total');
            
            $campaign_id=$r->campaign_id;
            $offerresult['offer_price']=  round(($offer_percent/100)*$product_price);
            $offerresult['total']= $product_price-$offerresult['offer_price'];
            $offer_result=array(
                'campaign_id'=>$campaign_id, 
                'offer_pertcent'=> $offer_percent,
                'offer_price'=> $offerresult['offer_price'],
                'sub-total'=>$offerresult['total'],
                'offer_code'=>$coupon_code
                );
                $this->session->set_userdata($offer_result); 
            }
       echo json_encode($offerresult);
    }
    else{
        echo json_encode("invalid");
    }
       
  }
    public function get_service()
    {
        $service_id= $this->input->post('service_id');

       $product_service=$this->model_home->get_service_subcategory($service_id);
 
            echo json_encode($product_service);
          
    }

       public function get_product()
    {
        $product_id= $this->input->post('product_id');

       $product_service=$this->model_home->get_product_services_detail($product_id);
 
            echo json_encode($product_service);
          
    }
	    public function get_customise_package()
    {
        $parameter_id= $this->input->post('parameter_id');

       $product_service=$this->model_home->get_all_parmeter($parameter_id);
 
            echo json_encode($product_service);
          
    }

      function whats_new()
  {
    $slug='whats_new'; 
      $head= $this->seo->get_metadata($slug);
       $head['cart_session'] = $this->session->userdata('cart_session');
      $this->load->view('header',$head);
    $this->load->view('home/whats_new');
    $this->load->view('footer');  
  }   

       function how_it_works()
  {
    $slug='my-benefits'; 
      $head= $this->seo->get_metadata($slug);
       $head['cart_session'] = $this->session->userdata('cart_session');
      $this->load->view('header',$head);
    $this->load->view('home/how_it_works');
    $this->load->view('footer');  
  }          

      function health_tips()
  {
    $slug='health-tips'; 
      $head= $this->seo->get_metadata($slug);
       $head['cart_session'] = $this->session->userdata('cart_session');
      $this->load->view('header',$head);
    $this->load->view('home/health_tips');
    $this->load->view('footer');  
  }

function get_province(){

  $location = $this->input->post('location_name');
  $location_adds = $this->db->get_where('service_locations', array('id' => $location))->result();
        foreach ($$location_adds as $key ) {
          $lat=$key->latitude;
          $lan=$key->longitude;
        }
      $quarry = $this->servicepartners->get_empanelment($lat,$lan);
     
            echo json_encode($quarry);
        
}

function book_package(){
   $config=array(
               'charset'=>'utf-8',
               'wordwrap'=> TRUE,
                'mailtype' => 'html'
                );
  $mobile=$this->input->post('mobile');
    $name=$this->input->post('name');
    $city=$this->input->post('city');
    $product_id=$this->input->post('product_id');
    $this->load->library('email');
        $fromemail="info@welezohealth.com";
        $message_content='Here are the details of today walkin customer .<br><br>.
         Name:'. $name.'.<br><br>.
         Contact_number:'.$mobile.'.<br><br>.
         Product Name:'.$product_id;
        

        $toemail='ankitsagar@welezohealth.com';
        $this->email->initialize($config);
        $this->email->from($fromemail, "online-user");
        $this->email->to($toemail);
       // $this->email->cc('misba.tabassum@welezohealth.com');   
        $this->email->subject('online-user');
        $this->email->message($message_content);
        //$mail = $this->email->send();

     if($this->email->send()) {
            $this->session->set_flashdata('success1', 'Email sent successfully');
            redirect($_SERVER['HTTP_REFERER']);
            }
      


}
}
?>

    
