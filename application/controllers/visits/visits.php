<?php

	require_once( APPPATH . "controllers/general_controller.php" );

	class Visits extends GeneralController {
		protected $scope = 'visits';

		/**
		 * List Brands
		 */
		function index() {

			$data = $this->auth( 'manage/brands', array(
				//'retrieve_brands' => 'account/account_profile'
			) );

			$this->load->model( array(
				'manage/regions_model',
				'manage/interventions_places_model',
				'manage/educations_model',
				'manage/origin_places_model',
			) );

			$data['regions'] = $this->regions_model->getActiveList();
			$data['interventions_places'] = $this->interventions_places_model->getActiveList();
			$data['educations'] = $this->educations_model->getActiveList();
			$data['origin_places'] = $this->origin_places_model->getActiveList();

			$this->view( $data );
		}

	}

