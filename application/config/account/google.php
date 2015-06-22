<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Google+ API
|--------------------------------------------------------------------------
|
| Google Projects - https://console.developers.google.com/project
|
*/
$config['google_app_name'] 	= "";

	// OAuth - Client ID for web application
$config['google_clientid'] 	= "";
$config['google_client_secret'] 	= "";
$config['google_redirect_URI'] 	= base_url() . 'account/connect_google';

	// Public API access - Key for server applications
$config['google_developer_key'] 	= "";


	// The project OAuth must have the REDIRECT URIS pointing to: http://myhost/account/connect_google

/* End of file twitter.php */
/* Location: ./application/account/config/google.php */
