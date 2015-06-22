<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Google_lib {

	var $CI;
	var $gl;

	/**
	 * Constructor
	 */
	function __construct()
	{
		// Obtain a reference to the ci super object
		$this->CI =& get_instance();

		$this->CI->load->config('account/google');
		$this->CI->load->helper('account/google');

		// Require Google app keys to be configured
		if ( ! $this->CI->config->item('google_clientid') || ! $this->CI->config->item('google_client_secret'))
		{
			echo 'Visit '.anchor('https://console.developers.google.com/project', 'https://console.developers.google.com/project').' to create your project.'.'<br />The config file is located at "/application/config/account/google.php"';
			die;
		}

		// Create the Facebook object
		$this->gl = new Google( array( 'google_app_name'  => $this->CI->config->item( 'google_app_name' ),
		                               'google_clientid'  => $this->CI->config->item( 'google_clientid' ),
		                               'google_client_secret' => $this->CI->config->item( 'google_client_secret' ),
		                               'google_redirect_URI' => $this->CI->config->item( 'google_redirect_URI' ),
		                               'google_developer_key' => $this->CI->config->item( 'google_developer_key' )
		) );

	}
	// --------------------------------------------------------------------

	function getClientID( ) {
		return $this->CI->config->item('google_clientid');
	}

	function getClient( ) {
		return $this->gl->getClient();
	}

	function getClientSecret( ) {
		return $this->CI->config->item('google_client_secret');
	}

	function getRedirectURI( ) {
		return $this->CI->config->item('google_redirect_URI');
	}

	function getState( ) {
		return $this->gl->getState();
	}

}


/* End of file Google_lib.php */
/* Location: ./application/account/libraries/Google_lib.php */