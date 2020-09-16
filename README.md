# YOURLS-SMTP-contact
Public contact page for YOURLS using PHPMailer

### Features
- Bootstrap based contact page
- Simple captcha
- Integration: Enables SMTP mailing for other plugins
- Integration: uses [httpbl](https://github.com/joshp23/YOURLS-httpBL) honeypots on the contact page

### Requirements
- YOURLS 1.7.9
- The [PHPMailer](https://github.com/PHPMailer/PHPMailer) library
- `composer` for installation
- Access to an SMTP server

### Installation
1. Download this repo and extract the `SMTP-contact` folder into `YOURLS/user/plugins/`
2. `cd` to the directory you just created
3. Run `composer install` in that directory to fetch the PHPMailer library
4. Define SMTP server parameters (see below)
5. Enable in Admin 
6. Verify that `contact.php` was copied to the `YOURLS/user/pages/` directory

Configuration
-------------
Config: `YOURLS/user/config.php` file.
```
/*
	SMTP Contact
*/
// REQUIRED
define('YOURLS_SMTP_SERVER', 'smtp.example.com' );
define('YOURLS_SMTP_LOGIN', '<username>' );
define('YOURLS_SMTP_PASSWORD', '<password>' );
define('YOURLS_SMTP_CONTACT_RECIPIENT_ADDRESS', 'user@example.com');
//OPTIONAL
define('YOURLS_SMTP_SERVER_PORT', '25' ); 			// DEFAULT: 25, 465 (SMTPS), 587 (TLS)
define('YOURLS_SMTP_SECURE', '' ); 					// DEFAULT: NULL, STARTTLS, SMTPS
define('YOURLS_SMTP_SKIP_CERT_CHECKS', ); 			// DEFAULT: NULL, true
define('YOURLS_SMTP_CA_FILE', '' );					// DEFAULT: NULL, path/to/CA_file.crt
define('YOURLS_SMTP_DEFAULT_FROM_NAME', '');
define('YOURLS_SMTP_DEFAULT_FROM_ADDRESS', '');
define('YOURLS_SMTP_CONTACT_RECIPIENT_NAME', '');
define('YOURLS_SMTP_CONTACT_EMAIL_SUBJECT', '');
```
### In Development
- Admin Page
- Alternate contact page styles

License
-------
Copyright 2020 Joshua Panter 
