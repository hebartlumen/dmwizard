<?php

	if(isset($_POST["signup_security_code"]))

	{

		session_start();

        if($_SESSION['signup_security_code'] == ($_POST['signup_security_code']) && !empty($_SESSION['signup_security_code'])) {

		

			if(isset($_POST["StudentName"]))

			{

				$msg = '';

				$content = '';

				$to      = 'info@dmwizard.in';

				//$to      = 'shiyas.artlumen@gmail.com';

				$subject = 'DMWizard 2 Month Course Enquiry-FB Ad';

				$message = 'Sir,' . "\r\n\r\n" . 

							'I am interested to know more about DMWizard and the courses available, my details are given below' . "\r\n\r\n" .

							'Name:          ' . $_POST['StudentName'] . "\r\n" .

							'Email:          ' . $_POST['Email'] . "\r\n" .

							'Mobile No:     ' . $_POST['MobileNumber'] . "\r\n" .

							'Qualification: ' . $_POST['qualification'] . "\r\n" .

							'Experience:    ' . $_POST['yearofexperience'] . "\r\n\r\n\r\n" . 

							'With regards' . "\r\n\r\n" . $_POST['StudentName'];

							

				$headers = 'From: ' . $_POST['Email'] . "\r\n" . 
				    'Reply-To: ' . $_POST['Email'] . "\r\n" . 
				    'CC:  artlumen.enquiry@gmail.com'. "\r\n" . 
				    'X-Mailer: PHP/' . phpversion();
				

				if(mail($to, $subject, $message, $headers))
				{
					header('Location: thankyou.php');
				}
				else
				{
					echo ("<script language='javascript'>

					    window.alert('Sorry, Registration Failed.')

					    window.location.href='https://www.dmwizard.in';

					    </script>");
				}

			}

			

		}

		else{

				

			echo ("<script language='javascript'>

					    window.alert('Incorrect Captcha')

					    window.location.href='https://www.dmwizard.in';

					    </script>");

		}

	} 



?>