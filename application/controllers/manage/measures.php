<?php

	require_once( APPPATH . "controllers/general_controller.php" );

	/**
	 * Class Measures
	 *
	 * @property Sources_model $sources_model
	 */
	class Measures extends GeneralController {
		protected $scope = 'manage';

		function __construct() {
			parent::__construct();

			$this->load->model( array(
				                    'manage/sources_model'
			                    ) );
		}
		/**
		 * List Measures
		 */
		function index() {

			$data = $this->auth( 'manage/measures', array(
				'retrieve_measures' => 'account/account_profile'
			) );

			$data['measures'] = $this->model->get_order_by_name();

			$this->view( $data );
		}

		/**
		 * Manage Measures
		 * @param null|int $id
		 */
		function save( $id = null ) {
			// Keep track if this is a new object
			$is_new = !$id;

			$data = $this->auth( 'manage/measures',
				(
					$is_new
					? array( 'create_measures' => 'manage/measures' )
					: array( 'update_measures' => 'manage/measures' )
				)
			);

			// Set action type (create or update)
			$data['action'] = 'create';

			// Get the object
			if ( ! $is_new ) {
				$data['measure'] = $this->model->get_one_by_id( $id );
				$data['action'] = 'update';
			}
			$data['sources'] = $this->sources_model->getActiveList( );
			$data['source'] = null;
			$data['measures'] = array();

			foreach( $data['sources'] as $source ) {
				if( \sources\DataSource::checkClass( $source['base_class'] ) ) {
					$data['measures'][$source['id']] = array( );

					$sourceObj = \sources\DataSource::create( $source['base_class'] );
					$sourceObj->init( );
					if( isset( $data['measure'] ) && isset( $data['measure']['id_source'] ) && $data['measure']['id_source'] && $data['measure']['id_source'] == $source['id'] ) {
						$sourceObj->setId( $source['id'] );
						$data['source'] = $sourceObj;
					}
					$keys = $sourceObj->getMeasures();

					foreach( $keys as $key ) {
						$name = ( lang( $key ) ? lang( $key ) : ucwords( preg_replace( '/([^a-z0-9])/', ' ', strtolower( $key ) ) ) );
						$data['measures'][$source['id']][$key] = $name;
					}
				}
			}


			// Setup form validation
			$this->form_validation->set_rules(
				array(
					array(
						'field' => 'measures_field_name',
						'label' => 'lang:measures_field_name',
						'rules' => 'trim|required|max_length[80]'
					),
					array(
						'field' => 'measures_field_id_source',
						'label' => 'lang:measures_field_id_source',
						'rules' => 'numeric|required'
					),
					array(
						'field' => 'measures_field_source_measure',
						'label' => 'lang:measures_field_source_measure',
						'rules' => 'trim|required'
					),
					array(
						'field' => 'measures_field_description',
						'label' => 'lang:measures_field_description_error',
						'rules' => 'trim|optional|max_length[160]'
					)
				) );

			// Run form validation
			if ( $this->form_validation->run() ) {
				$name_taken = $this->name_check( $this->input->post( 'measures_field_name', true ) );

				if ( ( !$is_new && strtolower( $this->input->post( 'measures_field_name', true ) ) != strtolower( $data['measure']['name'] ) && $name_taken ) || ( $is_new && $name_taken ) ) {
					$data['measures_field_name_error'] = lang( 'measures_field_name_taken' );
				}
				else {
					// Create/Update
					$attributes = array();

					$attributes['name'] = $this->input->post( 'measures_field_name', true ) ? $this->input->post( 'measures_field_name', true ) : null;
					$attributes['description'] = $this->input->post( 'measures_field_description', true ) ? $this->input->post( 'measures_field_description', true ) : null;
					$attributes['id_source'] = $this->input->post( 'measures_field_id_source', true ) ? $this->input->post( 'measures_field_id_source', true ) : null;
					$attributes['source_measure'] = $this->input->post( 'measures_field_source_measure', true ) ? $this->input->post( 'measures_field_source_measure', true ) : null;

					if( $is_new )
						$id = $this->model->insert( $attributes );
					else
						$this->model->update_by_id( $id, $attributes );

					// Check if the permission should be disabled
					if ( $this->authorization->is_permitted( 'delete_measures' ) ) {
						if ( $this->input->post( 'deactivate', true ) ) {
							$this->model->update_by_id( $id, array( 'active' => 0 ) );
							$data['measure']['active'] = 0;
						} elseif ( $this->input->post( 'activate', true ) ) {
							$this->model->update_by_id( $id, array( 'active' => 1 ) );
							$data['measure']['active'] = 1;
						}
					}

					$data['measure']['source_measure'] = $attributes['source_measure'];
				}

				if( $is_new )
					redirect( 'manage/measures/save/' . $id );

				$data['action_info'] = ( $is_new ? lang('measures_created') : lang('measures_updated') );

			}
			$this->viewSave( $data );
		}

		/**
		 * Check the name
		 * @param $name
		 *
		 * @return bool
		 */
		function name_check( $name ) {
			//$name = preg_replace( '/([^a-zA-z0-9])/', '', strtolower( $name ) );
			return $this->model->count_by_name( $name ) > 0;
		}
	}

