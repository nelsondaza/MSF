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

		function instance( $idSource, $action, $subAction = null, $request = null ) {

			$source = $this->sources_model->get_one_by_id( $idSource );

			if( !empty( $source ) ) {
				if( \sources\DataSource::checkClass( $source['base_class'] ) ) {
					$sourceObj = \sources\DataSource::create( $source['base_class'] );
					$sourceObj->init( $idSource );

					if( method_exists( $sourceObj, 'service_' . $action ) ) {
						$action = 'service_' . $action;
						$this->data = $sourceObj->$action( $subAction, $request );
					}
					else {
						$this->data['error'] = array(
							'code' => 20,
							'msg' => sprintf( lang( 'services_action_not_found' ), 'service_' . $action )
						);
					}

				}
			}
			else {
				$this->data['error'] = array(
					'code' => 20,
					'msg' => lang( 'services_object_not_found' )
				);
			}

			$this->shapeResponse();
		}

		function patient( ) {

			$q = $this->input->get('q');
			$limit = min( abs( (int)$this->input->get('page_limit') ), 20 );
			$list = $this->patients_model->search( $q, $limit );

			$this->data = $list;

			$this->shapeResponse();
		}

	}

