<?php 
	if(isset($_POST['Email']))
	{
		$to      = 'targetleadbox@gmail.com, canetistechnologies@gmail.com';
		$subject = 'DMWizard 2 Month Course Enquiry(Manu)';
		$message = 'Sir,' . "\r\n\r\n" . 
					'I am interested to know more about DMWizard and the courses available, my details are given below' . "\r\n\r\n" .

					'Name:          ' . $_POST['StudentName'] . "\r\n" .

					'Email:          ' . $_POST['Email'] . "\r\n" .

					'Mobile No:     ' . $_POST['MobileNumber'] . "\r\n" .

					'Qualification: ' . $_POST['qualification'] . "\r\n" .

					'Experience:    ' . $_POST['yearofexperience'] . "\r\n\r\n\r\n" . 

					'With regards' . "\r\n\r\n" . $_POST['StudentName'];		

		
		$cc = "arunnair@artlumen.in, artlumen.enquiry@gmail.com";
		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		$headers .= "From: " . $_POST['StudentName'] . "<" . $_POST['Email'] . ">" ."\r\n";//From:  " . $websiteemail . "\r\n"
		$headers .= "Reply-To: ".$websiteemail."\r\n";
		$headers .= "Return-Path: ".$websiteemail."\r\n";
		$headers .= "CC: ".$cc."\r\n";
		

		//$headers .= "BCC: qcmorecochin@gmail.com\r\n";

		if(mail($to,$subject,$message,$headers)) { 

			header('Location: thankyou.php');

		}
		else{

			 echo ("<script language='javascript'>

			    window.alert('Sorry, Not Subscribed.')

			    window.location.href='https://www.dmwizard.in';

			    </script>");

		}

	}

?>
