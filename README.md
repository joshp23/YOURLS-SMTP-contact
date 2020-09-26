# YOURLS-SMTP-contact
Public contact page for YOURLS using PHPMailer

### Features
- Bootstrap based contact page
- Simple captcha
- Disallows contacts with links in the body
- Integration: Enables SMTP mailing for other plugins
- Integration: Can use [httpbl](https://github.com/joshp23/YOURLS-httpBL) honeypots on the contact page

### Requirements
- YOURLS 1.7.9 (+)
- `composer` for the installation of the [PHPMailer](https://github.com/PHPMailer/PHPMailer) library
- Access to an SMTP server

### Installation
1. Download this repo and extract the `SMTP-contact` folder into `YOURLS/user/plugins/`
2. `cd` to the directory you just created
3. Run `composer install` in that directory to fetch the PHPMailer library
4. Enable in Admin 
5. Set parameters in SMTP Contact Admin page
6. Verify that `contact.php` was copied to the `YOURLS/user/pages/` directory

### In Development
- Alternate contact page styles

License
-------
Copyright 2020 Joshua Panter 
