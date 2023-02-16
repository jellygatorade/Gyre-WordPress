<?php
/*
Plugin Name: Enable Application Passwords for non https/ssl
Description: Enables Application Passwords for WordPress instances not served over https/ssl
Version: 1.0
Author: Kevin Kane
*/

/*
WordPress 5.6 by default adds the section ‘Application Password’ under the Users->Profile page. 
This feature is available to all sites served over SSL/HTTPS. 
If your site is not on HTTPS then you can make this feature enabled using the below filter.
https://artisansweb.net/how-to-use-application-passwords-in-wordpress-for-rest-api-authentication/
*/
add_filter( 'wp_is_application_passwords_available', '__return_true' );
