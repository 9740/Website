<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once (APPPATH.'/libraries/email_validation.php'); 
class test_email_validation {

       	public function email_valid($email){

   $validator=new email_validation_class;

	/*
	 * If you are running under Windows or any other platform that does not
	 * have enabled the MX resolution function GetMXRR() , you need to
	 * include code that emulates that function so the class knows which
	 * SMTP server it should connect to verify if the specified address is
	 * valid.
	 */
	if(!function_exists("GetMXRR"))
	{
		/*
		 * If possible specify in this array the address of at least on local
		 * DNS that may be queried from your network.
		 */
		$_NAMESERVERS=array();
		include("getmxrr.php");
	}
	/*
	 * If GetMXRR function is available but it is not functional, you may
	 * use a replacement function.
	 */
	/*
	else
	{
		$_NAMESERVERS=array();
		if(count($_NAMESERVERS)==0)
			Unset($_NAMESERVERS);
		include("rrcompat.php");
		$validator->getmxrr="_getmxrr";
	}
	*/

	/* how many seconds to wait before each attempt to connect to the
	   destination e-mail server */
	$validator->timeout=10;

	/* how many seconds to wait for data exchanged with the server.
	   set to a non zero value if the data timeout will be different
		 than the connection timeout. */
	$validator->data_timeout=0;

	/* user part of the e-mail address of the sending user
	   (info@phpclasses.org in this example) */
	$validator->localuser="info";

	/* domain part of the e-mail address of the sending user */
	$validator->localhost="phpclasses.org";

	/* Set to 1 if you want to output of the dialog with the
	   destination mail server */
	$validator->debug=1;

	/* Set to 1 if you want the debug output to be formatted to be
	displayed properly in a HTML page. */
	$validator->html_debug=1;


	if(IsSet($email) && strcmp($email,""))
	{
		if(strlen($error = $validator->ValidateAddress($email, $valid)))
		{
			echo "<h2 align=\"center\">Error: ".HtmlSpecialChars($error)."</h2>\n";
		}
		elseif(!$valid)
		{
			echo "<h2 align=\"center\"><tt>$email</tt> is not a valid deliverable e-mail box address.</h2>\n";
			if(count($validator->suggestions))
			{
				$suggestion = $validator->suggestions[0];
				$link = '?email='.UrlEncode($suggestion);
				echo "<H2 align=\"center\">Did you mean <a href=\"".HtmlSpecialChars($link)."\"><tt>".HtmlSpecialChars($suggestion)."</tt></a>?</H2>\n";
			}
		}
		elseif(($result=$validator->ValidateEmailBox($email))<0)
			echo "<h2 align=\"center\">It was not possible to determine if <tt>$email</tt> is a valid deliverable e-mail box address.</h2>\n";
		else

			// echo "<h2 align=\"center\"><tt>$email</tt> is ".($result ? "" : "not ")."a valid deliverable e-mail box address.</h2>\n";
		return $result;
	}
	
}
}