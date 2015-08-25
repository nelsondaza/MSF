<?php

	require_once( APPPATH . "controllers/general_controller.php" );

	class Generate_db extends GeneralController {
		protected $scope = 'reports';

		function index() {

			$data = $this->auth( '', array(
				'generate_reports' => 'account/account_profile',
				'view_reports' => 'account/account_profile'
			) );

			$this->view( $data );
		}
	}
