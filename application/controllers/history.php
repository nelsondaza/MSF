<?php

	require_once( APPPATH . "controllers/general_controller.php" );

	class History extends GeneralController {
		protected $scope = 'history';

		/**
		 *
		 */
		function index( $id ) {

			$data = $this->auth( '', array(
				//'retrieve_brands' => 'account/account_profile'
			) );

			$this->load->model( array(
				'manage/patients_model',
				'manage/regions_model',
				'manage/districts_model',
				'manage/experts_model',
				'manage/educations_model',
				'manage/origin_places_model',
				'manage/references_model',
				'manage/patients_references_model',
				'manage/symptoms_model',
				'manage/diagnostics_model',
				'manage/risks_model',
				'manage/consults_types_model',
				'manage/interventions_types_model',

				'manage/consults_model',
				'manage/consults_symptoms_model',
				'manage/consults_diagnostics_model',
				'manage/consults_risks_model',

			) );

			$data['patient'] = $this->patients_model->get_one_by_id( $id );

			if( !$data['patient'] ) {
				redirect('');
				return;
			}

			if( !$data['patient']['id_expert'] ) {
				$data['patient']['id_expert'] = $data['account']->id;
				$data['patient']['expert'] = $data['account_details']->fullname;
			}

			$data['regions'] = $this->regions_model->getActiveList();
			$data['localizations'] = $this->districts_model->getActiveList();
			$data['experts'] = $this->experts_model->getActiveList();
			$data['educations'] = $this->educations_model->getActiveList();
			$data['origin_places'] = $this->origin_places_model->getActiveList();
			$data['references'] = $this->references_model->getActiveList();
			$data['patient_references'] = $this->patients_references_model->get_by_id_patient( $id );
			$data['symptoms'] = $this->symptoms_model->getActiveList();
			$data['diagnostics'] = $this->diagnostics_model->getActiveList();
			$data['risks'] = $this->risks_model->getActiveList();
			$data['consults_types'] = $this->consults_types_model->getActiveList();
			$data['interventions_types'] = $this->interventions_types_model->getActiveList();

			$data['consults'] = $this->consults_model->get_by_id_patient( $id );

			foreach( $data['consults'] as &$consult ) {
				$consult['symptoms'] = $this->consults_symptoms_model->get_by_id_consult($consult['id']);
				$consult['diagnostics'] = $this->consults_diagnostics_model->get_by_id_consult($consult['id']);
				$consult['risks'] = $this->consults_risks_model->get_by_id_consult($consult['id']);
			}



			$this->view( $data );

		}


		function first_visit( $id ) {

			$data = $this->auth( '', array(
				//'retrieve_brands' => 'account/account_profile'
			) );

			$this->load->model( array(
				'manage/patients_model',
				'manage/regions_model',
				'manage/districts_model',
				//'manage/experts_model',
				'manage/educations_model',
				'manage/origin_places_model',
				'manage/references_model',
				'manage/patients_references_model',
				'manage/symptoms_model',
				'manage/diagnostics_model',
				'manage/risks_model',
				'manage/consults_types_model',
				'manage/interventions_types_model',

				'manage/consults_model',
				'manage/consults_symptoms_model',
				'manage/consults_diagnostics_model',
				'manage/consults_risks_model',

			) );

			$data['patient'] = $this->patients_model->get_one_by_id( $id );

			if( !$data['patient'] ) {
				redirect('');
				return;
			}


			$data['regions'] = $this->regions_model->getActiveList();
			$data['localizations'] = $this->districts_model->getActiveList();
			//$data['experts'] = $this->experts_model->getActiveList();
			$data['educations'] = $this->educations_model->getActiveList();
			$data['origin_places'] = $this->origin_places_model->getActiveList();
			$data['references'] = $this->references_model->getActiveList();
			$data['patient_references'] = $this->patients_references_model->get_by_id_patient( $id );
			$data['symptoms'] = $this->symptoms_model->getActiveList();
			$data['diagnostics'] = $this->diagnostics_model->getActiveList();
			$data['risks'] = $this->risks_model->getActiveList();
			$data['consults_types'] = $this->consults_types_model->getActiveList();
			$data['interventions_types'] = $this->interventions_types_model->getActiveList();

			$data['consults'] = $this->consults_model->get_by_id_patient( $id );

			foreach( $data['consults'] as &$consult ) {
				$consult['symptoms'] = $this->consults_symptoms_model->get_by_id_consult($consult['id']);
				$consult['diagnostics'] = $this->consults_diagnostics_model->get_by_id_consult($consult['id']);
				$consult['risks'] = $this->consults_risks_model->get_by_id_consult($consult['id']);
			}


			$this->viewFirst_visit( $data );

		}
		function consult( $id ) {

			$data = $this->auth( '', array(
				//'retrieve_brands' => 'account/account_profile'
			) );

			$this->load->model( array(
				'manage/patients_model',
				'manage/regions_model',
				'manage/districts_model',
				'manage/experts_model',
				'manage/educations_model',
				'manage/origin_places_model',
				'manage/references_model',
				'manage/patients_references_model',
				'manage/symptoms_model',
				'manage/diagnostics_model',
				'manage/risks_model',
				'manage/consults_types_model',
				'manage/interventions_types_model',

				'manage/consults_model',
				'manage/consults_symptoms_model',
				'manage/consults_diagnostics_model',
				'manage/consults_risks_model',

			) );

			$data['consult'] = $this->consults_model->get_one_by_id( $id );
			if( !$data['consult'] ) {
				redirect('');
				return;
			}

			$data['patient'] = $this->patients_model->get_one_by_id( $data['consult']['id_patient'] );

			if( !$data['patient'] ) {
				redirect('');
				return;
			}

			$data['regions'] = $this->regions_model->getActiveList();
			$data['localizations'] = $this->districts_model->getActiveList();
			$data['experts'] = $this->experts_model->getActiveList();
			$data['educations'] = $this->educations_model->getActiveList();
			$data['origin_places'] = $this->origin_places_model->getActiveList();
			$data['references'] = $this->references_model->getActiveList();
			$data['patient_references'] = $this->patients_references_model->get_by_id_patient( $data['patient']['id'] );
			$data['symptoms'] = $this->symptoms_model->getActiveList();
			$data['diagnostics'] = $this->diagnostics_model->getActiveList();
			$data['risks'] = $this->risks_model->getActiveList();
			$data['consults_types'] = $this->consults_types_model->getActiveList();
			$data['interventions_types'] = $this->interventions_types_model->getActiveList();

			$data['consults'] = $this->consults_model->get_by_id_patient( $data['patient']['id'] );

			foreach( $data['consults'] as &$consult ) {
				$consult['symptoms'] = $this->consults_symptoms_model->get_by_id_consult($consult['id']);
				$consult['diagnostics'] = $this->consults_diagnostics_model->get_by_id_consult($consult['id']);
				$consult['risks'] = $this->consults_risks_model->get_by_id_consult($consult['id']);
			}

			$this->viewConsult( $data );

		}

	}
