<?php

	require_once( APPPATH . "controllers/services_controller.php" );

	/**
	 * Class Reports
	 *
	 * @property Projects_model $projects_model
	 */
	class Source extends ServicesController {
		protected $scope = 'services';

		function __construct() {
			parent::__construct();

			$this->load->model( array(
				                    'manage/sources_model'
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

	}

