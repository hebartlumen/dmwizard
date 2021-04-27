<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>QCMORE</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/layout.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <?php
	if(isset($_POST["StudentName"]))
	{
		$msg = '';
		$content = '';
		$to      = 'qcmorecochin@gmail.com,info@qcmore.com';
		$subject = 'Registration For Software Testing Training';
		$message = 'Sir,' . "\r\n\r\n" . 
					'I am interested to know more about QCMore and Software Testing, my details are given below' . "\r\n\r\n" .
					'Name:          ' . $_POST['StudentName'] . "\r\n" .
					'Mobile No:     ' . $_POST['MobileNumber'] . "\r\n" .
					'Qualification: ' . $_POST['qualification'] . "\r\n" .
					'Experience:    ' . $_POST['yearofexperience'] . "\r\n\r\n\r\n" . 
					'With regards' . "\r\n\r\n" . $_POST['StudentName'];
					
		$headers = 'From: ' . $_POST['Email'] . "\r\n" .
			'Reply-To: ' . $_POST['Email'] . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
		
		if(mail($to, $subject, $message, $headers))
		{
			$msg = "<h1>Succesfully Registered!</h1>";
			$content = "Congratulations! You have been succesfully registered. Our friendly consultant will contact you shortly.";
		}
		else
		{
			$msg = "<h1>Registration Failed!</h1>";
		}
	}

?>
   <div class="container">
   		<div class="row">
        <div class="col-lg-6" style="margin:0 auto;float:none;">
        <div style="margin:50% 0px;">
        <div class="row">
        <div class="col-lg-12" style="padding:0px 26px;">
        <div class="form-top-boxx">
        	<?php echo @$msg; ?>
        </div>
        </div>
        </div> 
        <?php if(@$content) { ?>
        <div class="col-lg-12">
        	 <form class="form-horizontal" role="form">
               <p style="font-size:16px;" class="text-center"><?php echo @$content; ?></p>
                
            </form> <!-- /form -->
        </div>
        <?php } ?>
        </div>
        </div>
        </div>
   </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html> 