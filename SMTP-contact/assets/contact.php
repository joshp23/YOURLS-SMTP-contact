<?php
/*
Plugin Name: SMTP Contact | Helper File
Plugin URI: https://github.com/joshp23/YOURLS-SMTP-Contact
Description: Enables Contact Page using PHPMailer
Version: 0.1.0
Author: Josh Panter
Author URI: https://unfettered.net
*/
// Make sure we're in YOURLS context
if( !defined( 'YOURLS_ABSPATH' ) ) {
?>
<html>
	<head>
		<meta http-equiv="refresh" content="5;url=../">
	</head>
	<body>
		<h2 style="position: absolute; top: 50%; left: 50%; transform: translateX(-50%) translateY(-50%);">You are trying to access an offlimits path. If you are not redirected automatically, please return to our <a href='/'>home</a> page. Thank you.</h2>
	</body>
</html>

<?php
	die();
}
// Resume normal functions
if ( isset( $_POST['submit'] ) ) {

	// First, check BotBox trap
	if ($_POST['botbox'] == '1') {
	
		$result='<div class="alert alert-danger">Please try again without checkign the box, or <a href="/">click here</a> to return to the home page.</div>';
		
	} else {

		// Check if name has been entered
		if( isset( $_POST['name'] ) && $_POST['name'] !== '' ) {
			$vars['from_name'] = $_POST['name'];
			$errName = null;
		} else {
			$errName = 'Please enter your name';
		}
		
		// Check if email has been entered and is valid
		if( isset( $_POST['email'] ) && $_POST['email'] !== '' 
			&& filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) ) {
			$vars['from_address'] = $_POST['email'];
			$errEmail = null;
		} else {
			$errEmail = 'Please enter a valid email address';
		}
	
		//Check if message has been entered
		if( isset( $_POST['message'] ) && $_POST['message'] !== '' ) {
			$vars['message'] = $_POST['message'];
			$errMessage = null;
		} else { 
			$errMessage = 'Please enter your message';
		}

		//Check if captcha is correct
		$x 	= $_POST['human'];
		$a 	= $_POST['firstNumber'];
		$b 	= $_POST['secondNumber'];
		$c 	= $a + $b;
		if ($x == $c)
			$errHuman = null; 
		else
			$errHuman = 'Your captcha math is incorrect';
	}

	// If there are no errors, send the email
	if (($_POST['botbox'] == '0') && !$errName && !$errEmail && !$errMessage && !$errHuman ) {
		$vars['to_name'] 	= YOURLS_SMTP_CONTACT_RECIPIENT_NAME;
		$vars['to_address']	= YOURLS_SMTP_CONTACT_RECIPIENT_ADDRESS;
		$vars['subject'] 	= YOURLS_SMTP_CONTACT_EMAIL_SUBJECT;

		if ( ysc_send ($vars) === 200 )
			$result='<div class="alert alert-success">Thank You! I will be in touch. Please <a href="/">click here</a> to return to the home page.</div>';
		else 
			$esult = '<div class="alert alert-danger">Sorry there was an error sending your message. Please try again later. <a href="/">Click here</a> to return to the home page.</div>';	
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contactu Us</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/spacelab/bootstrap.min.css">
  </head>
  <body>
  	<div class="container">
  		<div class="row">
  			<div style="padding:5px 0px 0px 0px;" class="col-md-6 col-md-offset-3">
			  	<div style="padding:0px 5px;"class="panel panel-default">
  				<h1 class="page-header text-center">Contact Us</h1>
				<form class="form-horizontal" role="form" method="post" action="">
					<div class="form-group<?php echo (isset($errName) ? (' has-error') : null); ?>">
						<label for="name" class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="name" name="name" placeholder="First & Last Name" value="<?php echo (isset($_POST['name']) ? htmlspecialchars($_POST['name']) : null); ?>">
							<?php echo (isset($errName) ? ("<p class='text-danger'> $errName</p>") : null);?>
						</div>
					</div>
					<div class="form-group<?php echo (isset($errEmail) ? (' has-error') : null); ?>">
						<label for="email" class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10">
							<input type="email" class="form-control" id="email" name="email" placeholder="example@domain.com" value="<?php echo (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : null); ?>">
							<?php echo (isset($errEmail) ? ("<p class='text-danger'> $errEmail</p>") : null);?>
						</div>
					</div>
					<div class="form-group<?php echo (isset($errMessage) ? (' has-error') : null); ?>">
						<label for="message" class="col-sm-2 control-label">Message</label>
						<div class="col-sm-10">
							<textarea class="form-control" rows="4" name="message"><?php echo (isset($_POST['message']) ? htmlspecialchars($_POST['message']) : null);?></textarea>
							<?php echo (isset($errMessage) ? ("<p class='text-danger'> $errMessage</p>") : null);?>
						</div>
					</div>
					<div class="form-group<?php echo (isset($errHuman) ? (' has-error') : null); ?>">
						<label for="human" class="col-sm-2 control-label"><?php 
							$min_number = 0;
							$max_number = 15;
							$random_number1 = mt_rand($min_number, $max_number);
							$random_number2 = mt_rand($min_number, $max_number);
							echo $random_number1 . ' + ' . $random_number2 . ' = '; ?></label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="human" name="human" placeholder="Your Answer">
							<input name="firstNumber" type="hidden" value="<?php echo $random_number1; ?>" />
							<input name="secondNumber" type="hidden" value="<?php echo $random_number2; ?>" />
							<?php echo (isset($errHuman) ? ("<p class='text-danger'> $errHuman</p>") : null);?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<div class="checkbox">
							  <label>
							    <input type="hidden" name="botbox" value="0" />
							    <input name="botbox" type="checkbox" value="1"> Leave this box unchecked.
							  </label>
							</div><br>
							<input id="submit" name="submit" type="submit" value="Send" class="btn btn-primary">
							<button type="reset" class="btn btn-default">Cancel</button>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<?php echo (isset($result) ? $result : null); ?>	
						</div>
					</div>
				</form> 
				</div>
			</div>
		</div>
	</div>   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  </body>
</html>
