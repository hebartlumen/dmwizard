<?php 
	if(isset($_POST['enquiry']))

	{

		if(isset($_POST["hsignup_security_code"]))

		{
			//echo $_SESSION['hsignup_security_code'];


			//session_start();

	       // if($_SESSION['hsignup_security_code'] == ($_POST['hsignup_security_code']) && !empty($_SESSION['hsignup_security_code'])) {

	

			    $toemail       =  "info@dmwizard.in";

				//$toemail       =  "hebern.artlumen@gmail.com";

				$websitename   =  "DMWIZARD";

				$websiteemail  =  'info@dmwizard.in';

				$subject       =  "DMWizard - Home Page Enquiry";

				$message       =  "Below person is interested :"."<br>";

				$message  	  .=  "Name: ".$_POST['name']."<br>";

				$message  	  .=  "Email: ".$_POST['email']."<br>";

				$message  	  .=  "Contact: ".$_POST['contact']."<br>";

				

				
				$cc = "artlumen.enquiry@gmail.com";
				$headers  = "MIME-Version: 1.0" . "\r\n";

				$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";

				$headers .= "From: " . $_POST['name'] . "<" . $_POST['email'] . ">" ."\r\n";//From:  " . $websiteemail . "\r\n"

				$headers .= "Reply-To: ".$websiteemail."\r\n";

				$headers .= "Return-Path: ".$websiteemail."\r\n";
				$headers .= "CC: ".$cc."\r\n";
				

				//$headers .= "BCC: qcmorecochin@gmail.com\r\n";

				if(mail($toemail,$subject,$message,$headers)) { 

					header('Location: thankyou.php');

				}

				else{

					 echo ("<script language='javascript'>

					    window.alert('Sorry, Not Subscribed.')

					    window.location.href='http://www.qcmore.com';

					    </script>");

				}

			//}

			/*else{

				echo ("<script language='javascript'>

					    window.alert('Incorrect Captcha')

					    window.location.href='http://www.qcmore.com';

					    </script>");

			}*/

		}

	}

?>