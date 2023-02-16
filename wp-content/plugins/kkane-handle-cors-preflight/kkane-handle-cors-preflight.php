<?php
/*
Plugin Name: Handle CORS Preflight for REST API Basic Auth
Description: For use of REST API with Basic Authentication (Application Passwords). Authorizes OPTIONS requests from origin http://127.0.0.1:8080 with HTTP 200 OK success status response code.
Version: 1.0
Author: Kevin Kane
*/

/*
Authorizes OPTIONS requests with HTTP 200 OK success status response code.

From:
Proper status code on preflight OPTIONS request
https://wordpress.org/support/topic/proper-status-code-on-preflight-options-request/

See also, but code not used from here:
How to handle CORS preflight OPTIONS requests from your WordPress Plugin
https://www.wpeform.io/blog/handle-cors-preflight-php-wordpress/
*/
add_action( 'init', 'kkane_handle_preflight' );
function kkane_handle_preflight() {
	
	$origin = get_http_origin();
 	if ( $origin == 'http://127.0.0.1:8080' /*|| $origin == 'https://yourapp.com'*/) {
		// You can set more specific domains if you need by using 'or' operator above
    	header("Access-Control-Allow-Origin: " . $origin);
		header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Headers: Authorization");

		if ( 'OPTIONS' == $_SERVER['REQUEST_METHOD'] ) {
			status_header(200);
			exit();
		}
    }

}