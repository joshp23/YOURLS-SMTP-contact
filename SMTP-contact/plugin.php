<?php
/*
Plugin Name: SMTP Contact
Plugin URI: https://github.com/joshp23/YOURLS-SMTP-Contact
Description: Enables Contact Page using PHPMailer
Version: 0.2.1
Author: Josh Panter
Author URI: https://unfettered.net
*/
// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__.'/vendor/autoload.php';

function ysc_send ( $vars ) {

	if (defined('YOURLS_SMTP_SERVER') && YOURLS_SMTP_SERVER) try {
		// Instantiation and passing `true` enables exceptions
		$phpmailer = new PHPMailer(true);
		
		// Server settings
		$phpmailer->isSMTP();
		$phpmailer->Host = YOURLS_SMTP_SERVER;
		
		if (defined('YOURLS_SMTP_SERVER_PORT') && YOURLS_SMTP_SERVER_PORT)
			/*
				STARTTLS: 	587
				SMTPS:		465
			*/
			$phpmailer->Port = YOURLS_SMTP_SERVER_PORT;
		else
			$phpmailer->Port = 25;

		if (defined('YOURLS_SMTP_LOGIN') && YOURLS_SMTP_LOGIN) {
			$phpmailer->SMTPAuth = true;
			$phpmailer->Username = YOURLS_SMTP_LOGIN;
			$phpmailer->Password = YOURLS_SMTP_PASSWORD;
		}

		$sec = defined('YOURLS_SMTP_SECURE') ? YOURLS_SMTP_SECURE : null;
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

		if (defined('YOURLS_SMTP_SKIP_CERT_CHECKS') && YOURLS_SMTP_SKIP_CERT_CHECKS) {
			$phpmailer->SMTPOptions = array(
			    'ssl' => array(
			        'verify_peer' => false,
			        'verify_peer_name' => false,
			        'allow_self_signed' => true
			    )
			);
		} elseif (defined('YOURLS_SMTP_CA_FILE') && YOURLS_SMTP_CA_FILE) {
			$phpmailer->SMTPOptions = array(
			    'ssl' => array(
                    'cafile' => YOURLS_SMTP_CA_FILE
			    )
			);
        }

		// Sender and recipient
		$from_name = $vars["from_name"] ? $vars["from_name"] : YOURLS_SMTP_DEFAULT_FROM_NAME;
		$from_address = $vars["from_address"] ? $vars["from_address"] : YOURLS_SMTP_DEFAULT_FROM_ADDRESS;

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
