<?php

	require_once( APPPATH . "controllers/services_controller.php" );

	/**
	 * Class Search
	 *
	 * @property Patients_model $patients_model
	 */
	class History extends ServicesController {
		protected $scope = 'services';

		function __construct() {
			parent::__construct();

			$this->load->model( array(
				'manage/patients_model',
				'manage/patients_references_model',

				'manage/consults_model',
				'manage/consults_symptoms_model',
				'manage/consults_diagnostics_model',
				'manage/consults_risks_model',
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

		function first_visit( ) {

			$id = (int)trim( $this->input->post('id_patient',true) );

			$patientInfo = array(
				'id_localization' => (int)trim( $this->input->post('id_localization',true)),
				'id_origin_place' => (int)trim( $this->input->post('id_origin_place',true)),
				'id_expert' => (int)trim( $this->input->post('id_expert',true)),
				'gender' => ucwords( strtolower( trim( $this->input->post('gender',true) ) ) ),
				'id_education' => (int)trim( $this->input->post('id_education',true)),
				'age' => (int)trim( $this->input->post('age',true)),
				'age_date' => date('Y-m-d H:i:s')
			);

			$patient = $this->patients_model->get_one_by_id( $id );

			if( !$patient ) {
				$this->data['error'] = array(
					'code' => 10,
					'type' => 'CodeError',
					'msg' => 'No se encontró el paciente.',
					'scope' => 'id_patient'
				);
			}
			else {

				if( !$patient['gender'] )
					$patientInfo['first_session'] = date('Y-m-d H:i:s');
				$patientInfo['last_session'] = date('Y-m-d H:i:s');


				$this->patients_model->update_by_id($id, $patientInfo);

				$this->patients_references_model->delete_by_id_patient($id);

				$id_references = $this->input->post('id_reference',true);
				if( is_array( $id_references ) ) {
					$references = array();
					foreach( $id_references as $key ) {
						$references[] = array(
							'id_reference' => $key,
							'id_patient'   => $id
						);
					}
					if( !empty($references) ) {
						$this->patients_references_model->insert_batch($references);
					}

				}

			}

			$this->shapeResponse();
		}

		/**
		 * Check if a code exist
		 *
		 * @access public
		 * @param string
		 * @return bool
		 */
		function code_check($code)
		{
			return $this->patients_model->get_by_code($code) ? TRUE : FALSE;
		}

		/**
		 * Check if a PID exist
		 *
		 * @access public
		 * @param string
		 * @return bool
		 */
		function PID_check($PID)
		{
			return $this->patients_model->get_by_PID($PID) ? TRUE : FALSE;
		}

		function code( ) {
			$this->data = array( 'code' => 10000 + $this->patients_model->getNextCode() );
			$this->shapeResponse();
		}

		function consult( ) {

			$consultInfo = array(
				'id_patient' => (int)trim( $this->input->post('id_patient',true)),
				'id_consults_type' => (int)trim( $this->input->post('id_consults_type',true)),
				'id_interventions_type' => (int)trim( $this->input->post('id_interventions_type',true)),
				'operation_reduction' => (int)trim( $this->input->post('operation_reduction',true)),
				'symptoms_severity' => (int)trim( $this->input->post('symptoms_severity',true))
			);

			$patient = $this->patients_model->get_one_by_id( $consultInfo['id_patient'] );

			if( !$patient ) {
				$this->data['error'] = array(
					'code' => 10,
					'type' => 'CodeError',
					'msg' => 'No se encontró el paciente.',
					'scope' => 'id_patient'
				);
			}
			else {

				$this->patients_model->update_by_id($consultInfo['id_patient'], array('last_session' => date('Y-m-d H:i:s')));

				$idConsult = $this->consults_model->insert( $consultInfo );


				$this->consults_symptoms_model->delete_by_id_consult( $idConsult );
				$id_symptoms = $this->input->post('id_symptom',true);
				if( is_array( $id_symptoms ) ) {
					$symptoms = array();
					foreach( $id_symptoms as $key ) {
						$symptoms[] = array(
							'id_symptom' => $key,
							'id_consult'   => $idConsult
						);
					}
					if( !empty($symptoms) )
						$this->consults_symptoms_model->insert_batch($symptoms);
				}
				$this->consults_diagnostics_model->delete_by_id_consult( $idConsult );
				$id_diagnostics = $this->input->post('id_diagnostic',true);
				if( is_array( $id_diagnostics ) ) {
					$diagnostics = array();
					foreach( $id_diagnostics as $key ) {
						$diagnostics[] = array(
							'id_diagnostic' => $key,
							'id_consult'   => $idConsult
						);
					}
					if( !empty($diagnostics) )
						$this->consults_diagnostics_model->insert_batch($diagnostics);
				}
				$this->consults_risks_model->delete_by_id_consult( $idConsult );
				$id_risks = $this->input->post('id_risk',true);
				if( is_array( $id_risks ) ) {
					$risks = array();
					foreach( $id_risks as $key ) {
						$risks[] = array(
							'id_risk' => $key,
							'id_consult'   => $idConsult
						);
					}
					if( !empty($risks) )
						$this->consults_risks_model->insert_batch($risks);
				}
			}

			$this->shapeResponse();
		}

	}

