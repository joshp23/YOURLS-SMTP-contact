<?php
/*
Plugin Name: SMTP Contact
Plugin URI: https://github.com/joshp23/YOURLS-SMTP-Contact
Description: Enables Contact Page using PHPMailer
Version: 1.0.0
Author: Josh Panter
Author URI: https://unfettered.net
*/
// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();
// namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__.'/vendor/autoload.php';
/*
 *
 *	Admin Page
 *
 *
*/
// Register admin forms
yourls_add_action( 'plugins_loaded', 'ysc_add_pages' );
function ysc_add_pages() {
        yourls_register_plugin_page( 'ysc', 'SMTP Contact', 'ysc_do_page' );
}
// Admin page
function ysc_do_page() {
	ysc_update_ops();
	$opt = ysc_config();
	$nonce = yourls_create_nonce( 'ysc' );
	
	// neccessary values for display
	$port = array("25" => "", "465" => "", "587" => "");
	switch ($opt[1]) {
		case '587': $port['587'] = 'selected'; break;
		case '465': $port['465'] = 'selected'; break;
		default: 	$port['25']  = 'selected'; break;
	}
	$sec = array("none" => "", "SMTPS" => "", "STARTTLS" => "");
	switch ($opt[2]) {
		case 'SMTPS': 		$sec['SMTPS'] 		= 'selected'; break;
		case 'STARTTLS': 	$sec['STARTTLS'] 	= 'selected'; break;
		default: 			$sec['none']  		= 'selected'; break;
	}
	$skip_ca = ( $opt[3] == 'true' ? 'checked' : null );
	
	echo <<<HTML
		<div id="wrap">
			<h2>SMTP Server Settings</h2>
			<form method="post">
				<input type="hidden" name="nonce" value="$nonce" />
				<p>
					<label for="ysc_server">SMTP Server  (Required) </label> <br>
					<input type="text" size=40 id="ysc_server" name="ysc_server" value="$opt[0]" />
				</p>
				<p>
					<label for="ysc_server_port">Server Port </label><br>
					<select name="ysc_server_port" size="1" >
						<option value="25" {$port['25']}>25</option>
						<option value="465" {$port['465']}>465</option>
						<option value="587" {$port['587']}>587</option>
					</select>
				</p>
				<p>
					<label for="ysc_server_secure">Security Type </label><br>
					<select name="ysc_server_secure" size="1" >
						<option value="none" {$sec['none']}>None</option>
						<option value="SMTPS" {$sec['SMTPS']}>SMTPS</option>
						<option value="STARTTLS" {$sec['STARTTLS']}>STARTTLS</option>
					</select>
				</p>
				<div class="checkbox">
				  <label>
				  	Skip Cert Checks? </br>
					<input type="hidden" name="ysc_skip_cert_chk" value="false" />
					<input name="ysc_skip_cert_chk" type="checkbox" value="true" $skip_ca >
				  </label>
				</div>
				<p>
					<label for="ysc_custom_ca">Absolute path on this server to a custom CA file - leave blank for none</label> <br>
					<input type="text" size=40 id="ysc_custom_ca" name="ysc_custom_ca" value="$opt[4]" />
				</p>
				
				<h2>SMTP Login Credentials</h2>
				
				<p>
					<label for="ysc_username">Username</label> <br>
					<input type="text" size=40 id="ysc_username" name="ysc_username" value="$opt[5]" />
				</p>
				<p>
					<label for="ysc_password">Password</label> <br>
					<input type="password" size=40 id="ysc_password" name="ysc_password" value="$opt[6]" />
				</p>
				
				<br><hr><br>
				
				<h2>Sending</h2>
				
				<p>
					<label for="ysc_default_from_name">Default sender address (reply to)</label> <br>
					<input type="text" size=40 id="ysc_default_from_name" name="ysc_default_from_name" value="$opt[8]" />
				</p>
				<p>
					<label for="ysc_default_from_addr">Default sender name</label> <br>
					<input type="text" size=40 id="ysc_default_from_addr" name="ysc_default_from_addr" value="$opt[7]" />
				</p>
				
				<h2>Contact Page</h2>
				
				<p>
					<label for="ysc_recipient_addr">Recipient address (Required)</label> <br>
					<input type="text" size=40 id="ysc_recipient_addr" name="ysc_recipient_addr" value="$opt[9]" />
				</p>
				<p>
					<label for="ysc_recipient_name">Recipient name</label> <br>
					<input type="text" size=40 id="ysc_recipient_name" name="ysc_recipient_name" value="$opt[10]" />
				</p>
				<p>
					<label for="ysc_subject">Email Subject </label> <br>
					<input type="text" size=40 id="ysc_subject" name="ysc_subject" value="$opt[11]" />
				</p>
				
				<p><input type="submit" value="Submit" /></p>
			</form>
		</div>
HTML;
}
// Options updater
function ysc_update_ops() {
	if(isset( $_POST['ysc_server'])) {
		// Check nonce
		yourls_verify_nonce( 'ysc' );
		yourls_update_option( 'ysc_server', $_POST['ysc_server'] );
		if(isset( $_POST['ysc_server_port'] )) 
			yourls_update_option( 'ysc_server_port', $_POST['ysc_server_port'] );
		if(isset( $_POST['ysc_server_secure'] )) 
			yourls_update_option( 'ysc_server_secure', $_POST['ysc_server_secure'] );
		if(isset( $_POST['ysc_skip_cert_chk'] )) 
			yourls_update_option( 'ysc_skip_cert_chk', $_POST['ysc_skip_cert_chk'] );
		if(isset( $_POST['ysc_custom_ca'] )) 
			yourls_update_option( 'ysc_custom_ca', $_POST['ysc_custom_ca'] );
		if(isset( $_POST['ysc_username'] )) 
			yourls_update_option( 'ysc_username', $_POST['ysc_username'] );
		if(isset( $_POST['ysc_password'] )) 
			yourls_update_option( 'ysc_password', $_POST['ysc_password'] );
		if(isset( $_POST['ysc_default_from_name'] )) 
			yourls_update_option( 'ysc_default_from_name', $_POST['ysc_default_from_name'] );
		if(isset( $_POST['ysc_default_from_addr'] )) 
			yourls_update_option( 'ysc_default_from_addr', $_POST['ysc_default_from_addr'] );
		if(isset( $_POST['ysc_recipient_addr'] )) 
			yourls_update_option( 'ysc_recipient_addr', $_POST['ysc_recipient_addr'] );
		if(isset( $_POST['ysc_recipient_name'] )) 
			yourls_update_option( 'ysc_recipient_name', $_POST['ysc_recipient_name'] );
		if(isset( $_POST['ysc_subject'] )) 
			yourls_update_option( 'ysc_subject', $_POST['ysc_subject'] );
	}
}
// Get options and set defaults
function ysc_config() {

	// Get values from DB
	$server 	= yourls_get_option( 'ysc_server' );
	$port 		= yourls_get_option( 'ysc_server_port' );
	$secure 	= yourls_get_option( 'ysc_server_secure' );
	$skip_ca 	= yourls_get_option( 'ysc_skip_cert_chk' );
	$ca_path 	= yourls_get_option( 'ysc_custom_ca' );
	$uname 		= yourls_get_option( 'ysc_username' );
	$pass 		= yourls_get_option( 'ysc_password' ); 
	$dfn 		= yourls_get_option( 'ysc_default_from_name' );
	$dfa 		= yourls_get_option( 'ysc_default_from_addr' );
	$to_addr 	= yourls_get_option( 'ysc_recipient_addr' );
	$to_name 	= yourls_get_option( 'ysc_recipient_name' );
	$subject 	= yourls_get_option( 'ysc_subject' );
	
	// Set defaults if necessary
	if( $port 		== null ) $port 	= 25;
	if( $dfn 		== null ) $dfn 		= 'YOURLS Contact Bot';
	if( $subject 	== null ) $subject 	= 'YOURLS Contact Page message';

	return array(
	$server,	// opt[0]
	$port,		// opt[1]
	$secure,	// opt[2]
	$skip_ca,	// opt[3]
	$ca_path,	// opt[4]
	$uname,		// opt[5]
	$pass,		// opt[6]
	$dfn,		// opt[7]
	$dfa,		// opt[8]
	$to_addr,	// opt[9]
	$to_name,	// opt[10]
	$subject	// opt[11]
	);
}
/*
 *
 *	Core functions
 *
 *
*/
function ysc_send ( $vars ) {

	$opt = ysc_config();
	
	if ( $opt[0] !== null ) try {
		// Instantiation and passing `true` enables exceptions
		$phpmailer = new PHPMailer(true);
		
		// Server settings
		$phpmailer->isSMTP();
		$phpmailer->Host = $opt[0];
		$phpmailer->Port = $opt[1];

		if ( $opt[5] !== null ) {
			$phpmailer->SMTPAuth = true;
			$phpmailer->Username = $opt[5];
			$phpmailer->Password = $opt[6];
		}

		$sec = $opt[2];
		switch ( $sec ) {
			case 'STARTTLS': 
				$phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				break;
			case 'SMTPS':
				$phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
				break;
			default: 
				$phpmailer->SMTPAutoTLS = false;;
		}

		if ( $opt[3] !== "false" ) {
			$phpmailer->SMTPOptions = array(
			    'ssl' => array(
			        'verify_peer' => false,
			        'verify_peer_name' => false,
			        'allow_self_signed' => true
			    )
			);
		} elseif ( $opt[4] !== null && $opt[4] !== ''  ) {
			$phpmailer->SMTPOptions = array(
			    'ssl' => array(
                    'cafile' => YOURLS_SMTP_CA_FILE
			    )
			);
        }

		// Sender and recipient
		$from_name = $vars["from_name"] ? $vars["from_name"] : $opt[7];
		$from_address = $vars["from_address"] ? $vars["from_address"] : $opt[8];

		$phpmailer->setFrom($from_address, $from_name);
		$phpmailer->addAddress($vars["to_address"], $vars["to_name"]);
		$phpmailer->Subject = $vars["subject"];
		
		// Content
		$phpmailer->CharSet = "UTF-8";
		if ($vars["message_html"]) {
			$phpmailer->isHTML(true);
			$phpmailer->Body($vars["message_html"]);
			$phpmailer->AltBody = $vars["message"];
		} else {
			$phpmailer->Body = $vars["message"];
		}

		foreach ($vars['headers'] as $header) {
			$phpmailer->addCustomHeader($header);
		}

		$phpmailer->send();

		return 200;

	} catch (Exception $e) {
		return "Message could not be sent. ".$phpmailer->ErrorInfo;
	}
}

// Copy/update contact.php when activated
yourls_add_action( 'activated_SMTP-contact/plugin.php', 'ysc_activated' );
function ysc_activated() {
	$loc = YOURLS_PAGEDIR.'/contact.php';
	if ( !file_exists( $loc ) ) {
		copy( YOURLS_PLUGINDIR . '/SMTP-contact/assets/contact.php', $loc );
	} else { 
		$thisFile = dirname( __FILE__ )."/plugin.php";
		$thisData = yourls_get_plugin_data( $thisFile );
		$thisV = $thisData['Version'];
		$thatData = yourls_get_plugin_data( $loc );
		$thatV = $thatData['Version'];
		$status = version_compare($thisV, $thatV);
		if($status === 1 ) copy( YOURLS_PLUGINDIR. '/SMTP-contact/assets/contact.php', $loc );
	}
}
// Clean up when plugin is deactivated
yourls_add_action('deactivated_SMTP-contact/plugin.php', 'ysc_deactivate');
function ysc_deactivate() {
	// remove contact.php
	$loc = YOURLS_PAGEDIR.'/contact.php';
	if ( file_exists( $loc ) ) {
		unlink( $loc );
	}
}
?>
