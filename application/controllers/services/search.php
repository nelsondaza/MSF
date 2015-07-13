<?php

	require_once( APPPATH . "controllers/services_controller.php" );

	/**
	 * Class Search
	 *
	 * @property Patients_model $patients_model
	 */
	class Search extends ServicesController {
		protected $scope = 'services';

		function __construct() {
			parent::__construct();

			$this->load->model( array(
				'manage/patients_model'
			) );
		}

		function index( ) {
			$this->data['error'] = array(
				'code' => 10,
				'type' => 'AccessError',
				'msg' => lang( 'services_access_denied' )
			);
			$this->shapeResponse();
		}

		function patient( ) {

			$q = $this->input->get('q',true);
			$limit = min( abs( (int)$this->input->get('page_limit',true) ), 20 );
			$list = $this->patients_model->search( $q, $limit );

			$this->data = $list;

			$this->shapeResponse();
		}

	}

