<?php

	require_once( APPPATH . "controllers/services_controller.php" );

	/**
	 * Class Search
	 *
	 * @property Patients_model $patients_model
	 */
	class Patient extends ServicesController {
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

		function create( ) {

			$patient = array(
				'first_name' => ucwords( strtolower( trim( $this->input->post('first_name',true) ) ) ),
				'last_name' => ucwords( strtolower( trim( $this->input->post('last_name',true) ) ) ),
				'code' => strtoupper( trim( $this->input->post('code',true) ) ),
				'PID' => strtoupper( trim( $this->input->post('PID',true) ) ),
			);

			if( !$patient['code'] ) {
				$this->data['error'] = array(
					'code' => 20,
					'type' => 'CodeError',
					'msg' => 'Se debe ingresar un código.',
					'scope' => 'code'
				);
			}
			else if( $this->code_check( $patient['code'] ) ) {
				$this->data['error'] = array(
					'code' => 20,
					'type' => 'CodeError',
					'msg' => 'El código ya existe.',
					'scope' => 'code'
				);
			}
			else if( $patient['PID'] && $this->PID_check( $patient['PID'] ) ) {
				$this->data['error'] = array(
					'code' => 20,
					'type' => 'PIDError',
					'msg' => 'La identificación ya existe.',
					'scope' => 'PID'
				);
			}
			else {

				$patient['search_text'] = implode(' ', array_values( $patient ) );

				$idPatient = $this->patients_model->insert($patient);
				$patient['id'] = $idPatient;
				$this->data = $patient;

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
	}

