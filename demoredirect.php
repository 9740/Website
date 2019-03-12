<?php 
session_start();
include('Crypto.php');
$workingKey='EF359C6EABDCCCCA049973CF5E05BD6F';		//Working Key should be provided here.
	$encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server
	$rcvdString=decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
	$order_status="";
	$decryptValues=explode('&', $rcvdString);
	$dataSize=sizeof($decryptValues);
	echo "<center>";

	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
		if($i==0)	$order_id=$information[1];
		if($i==1)	$tracking_id=$information[1];
		if($i==2)	$bank_ref_no=$information[1];
		if($i==3)	$order_status=$information[1];
		if($i==5)	$payment_mode=$information[1];
		if($i==6)	$card_name=$information[1];
		if($i==8)	$status_message=$information[1];
		
	}
// $order_id=545464;
// $bank_ref_no=567576;
// $card_name='kjdhskj';
// $order_status='Success';

	if($order_status==="Success")
	{
	?>
<html>
 <body bgcolor="white" onload="setTimeout(function() { document.customerData.submit() }, 1)">
	<form method="post" name="customerData" action="http://localhost/website/dashboard/ccavRequestHandler_demo" style="display:none">
	     <input type="text" name="order" value="<?php echo $order_id; ?>" >
	     <input type="text" name="bank_ref_no" value="<?php echo $bank_ref_no; ?>" >
	     <input type="text" name="card_name" value="<?php echo $card_name; ?>" >
  </form>
</html>
        <?php 
	}
	else if($order_status==="Aborted")
	{
		echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
	
	}
	else if($order_status==="Failure")
	{
		echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
	}
	else
	{
		echo "<br>Security Error. Illegal access detected";
	
	}
	?>
	



