<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExcelDataInsert extends CI_Controller
{

public function __construct() {
        parent::__construct();
                $this->load->library('excel');//load PHPExcel library 
	 
                $this->load->model('excel_data_insert_model');
}	


public  function ExcelDataAdd() {  

      $id= $this->session->userdata('corporate_id');
      $product_offer_id= $this->input->post('product_offer_id');
//Path of files were you want to upload on localhost (C:/xampp/htdocs/ProjectName/uploads/excel/)  
         $configUpload['upload_path'] = FCPATH.'uploads/excel/';
         $configUpload['allowed_types'] = 'xls|xlsx|csv';
         $configUpload['max_size'] = '5000';
         $this->load->library('upload', $configUpload);
         $this->upload->do_upload('userfile');  
         $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
         $file_name = $upload_data['file_name']; //uploded file name
     $extension=$upload_data['file_ext'];    // uploded file extension
    
//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
 $objReader= PHPExcel_IOFactory::createReader('Excel2007'); // For excel 2007     
          //Set to read only
          $objReader->setReadDataOnly(true);      
        //Load excel file
     $objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);    
         $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel         
         $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);                
          //loop from first data untill last data
          for($i=2;$i<=$totalrows;$i++)
          {
              $emp_name= $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();     
              $contact_no= $objWorksheet->getCellByColumnAndRow(1,$i)->getValue(); //Excel Column 1
              $email= $objWorksheet->getCellByColumnAndRow(2,$i)->getValue(); //Excel Column 2
              $gender=$objWorksheet->getCellByColumnAndRow(3,$i)->getValue(); //Excel Column 3
              $Address=$objWorksheet->getCellByColumnAndRow(4,$i)->getValue(); //Excel Column 4
              $age=$objWorksheet->getCellByColumnAndRow(5,$i)->getValue(); //Excel Column 5
              $city=$objWorksheet->getCellByColumnAndRow(6,$i)->getValue(); //Excel Column 6
              $pincode=$objWorksheet->getCellByColumnAndRow(7,$i)->getValue(); //Excel Column 7
             
   $data_user=array('emp_name'=>$emp_name, 'contact_no'=>$contact_no ,'email'=>$email ,'gender'=>$gender , 'address'=>$Address ,'age'=>$age ,'city'=>$city,'pincode'=>$pincode, 'status' => 'In Progress','corporate_offer_id'=>$product_offer_id,'corporate_id' => $id);
              $this->excel_data_insert_model->Add_User($data_user);
              
              
          }

          $quary=$this->db->get_where('corporate_details',array('corporate_id' => $id))->row();
          
        if($quary){
        $corporate_name=$quary->corporate_name;
          }         
            $this->load->library('email');
            $fromemail="support@welezohealth.com";
            $toemail = "care@welezohealth.com";
            $subject = "'$corporate_name' Add Employee list through excel";
            $mesg = "View attached excel sheet and download it for further use";
            
            $config=array(
            'charset'=>'utf-8',
            'wordwrap'=> TRUE,
            'mailtype' => 'html'
            );
            
            $this->email->initialize($config);
            
            $this->email->to($toemail);
            $this->email->from($fromemail);
            $this->email->subject($subject);
            $this->email->cc('harikrishnan@welezohealth.com');  
            $this->email->message($mesg);
            $this-> email -> attach('././uploads/excel/'.$file_name);
            $mail = $this->email->send();

             unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .       
             redirect($_SERVER['HTTP_REFERER'], 'refresh');
             
       
     }


      function download_excel()
    {
       $id= $this->session->userdata('corporate_id');
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Corporate_Emp_Detail');
        $this->excel->getActiveSheet()->setCellValue('A1', 'emp_name');
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('B1', 'contact_no');
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('C1', 'email');
        $this->excel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('D1', 'gender');
        $this->excel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('E1', 'address');
        $this->excel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

          $this->excel->getActiveSheet()->setCellValue('F1', 'age');
        $this->excel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);

          $this->excel->getActiveSheet()->setCellValue('G1', 'city');
        $this->excel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);

          $this->excel->getActiveSheet()->setCellValue('H1', 'pincode');
        $this->excel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

        
        $this->excel->getActiveSheet()->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $id= $this->session->userdata('corporate_id');
          //$query=$this->corporate->get_monthly_report($id);
           $query = $this->db->get('welezohe_corp.pre_employment');
           $fields = $query->list_fields();




$filename='Corporate_Emp_Detail.xlsx'; //save our workbook as this file name
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
             
//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
//force user to download the Excel file without writing it to server's HD
$objWriter->save('php://output');
//redirect('corporate/pre_employment');
    }
 
	
}
?>