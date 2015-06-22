<?php

	require_once( FCPATH . APPPATH . "helpers/account/google/api/src/Google/Client.php" );
	require_once( FCPATH . APPPATH . "helpers/account/google/api/src/Google/Service/Plus.php" );

	class Google {
		protected $sharedSessionID;
		protected $client;

		public function __construct( $config ) {

			if ( ! session_id() )
				session_start();

			$this->sharedSessionID = md5( session_id( ) );

			$this->client = new Google_Client( );
			$this->client->setApplicationName( $config['google_app_name'] );
			$this->client->setClientId( $config['google_clientid'] );
			$this->client->setClientSecret( $config['google_client_secret'] );
			$this->client->setRedirectUri( $config['google_redirect_URI'] );
			$this->client->setDeveloperKey( $config['google_developer_key'] );

		}

		public function getState( )  {
			return $this->sharedSessionID;
		}

		public function getClient( )  {
			return $this->client;
		}

	}
