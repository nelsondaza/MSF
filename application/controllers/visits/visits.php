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
				'manage/references_model',
				'manage/symptoms_model',
				'manage/diagnostics_model',
				'manage/risks_model',
			) );

			$data['regions'] = $this->regions_model->getActiveList();
			$data['interventions_places'] = $this->interventions_places_model->getActiveList();
			$data['educations'] = $this->educations_model->getActiveList();
			$data['origin_places'] = $this->origin_places_model->getActiveList();
			$data['references'] = $this->references_model->getActiveList();
			$data['symptoms'] = $this->symptoms_model->getActiveList();
			$data['diagnostics'] = $this->diagnostics_model->getActiveList();
			$data['risks'] = $this->risks_model->getActiveList();

			$this->view( $data );

		}

	}

