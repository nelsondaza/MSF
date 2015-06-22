<?php

	require_once( APPPATH . "controllers/general_controller.php" );

	/**
	 * Class Projects
	 *
	 * @property Brands_model $brands_model
	 * @property Sources_model $sources_model
	 * @property Projects_sources_properties_model $projects_sources_properties_model
	 */
	class Projects extends GeneralController {
		protected $scope = 'manage';

		function __construct() {
			parent::__construct();

			$this->load->model( array(
				                    'manage/brands_model',
				                    'manage/sources_model',
				                    'manage/projects_sources_properties_model'
			                    ) );
		}
		/**
		 * List Projects
		 */
		function index() {

			$data = $this->auth( 'manage/projects', array(
				'retrieve_projects' => 'account/account_profile'
			) );

			$data['projects'] = $this->model->get_order_by_name();

			$this->view( $data );
		}

		/**
		 * Manage Projects
		 * @param null|int $id
		 */
		function save( $id = null ) {
			// Keep track if this is a new object
			$is_new = !$id;

			$data = $this->auth( 'manage/projects',
				(
					$is_new
					? array( 'create_projects' => 'manage/projects' )
					: array( 'update_projects' => 'manage/projects' )
				)
			);

			// Set action type (create or update)
			$data['action'] = 'create';
			$data['project_sources'] = array();

			// Get the object
			if ( !$is_new ) {
				$data['project'] = $this->model->get_one_by_id( $id );
				$data['action'] = 'update';
				$properties = $this->projects_sources_properties_model->get_by_id_project( $id );
				foreach( $properties as $property ) {
					if( !isset( $data['project_sources'][$property['id_source']] ))
						$data['project_sources'][$property['id_source']] = array();
					$data['project_sources'][$property['id_source']][] = $property;
				}
			}
			$data['brands'] = $this->brands_model->getActiveList( );
			$data['sources'] = $this->sources_model->getActiveList( );

			// Setup form validation
			$this->form_validation->set_rules(
				array(
					array(
						'field' => 'projects_field_name',
						'label' => 'lang:projects_field_name',
						'rules' => 'trim|required|max_length[80]'
					),
					array(
						'field' => 'projects_field_id_brand',
						'label' => 'lang:projects_field_id_brand',
						'rules' => 'numeric|required'
					),
					array(
						'field' => 'projects_field_description',
						'label' => 'lang:projects_field_description_error',
						'rules' => 'trim|optional|max_length[160]'
					)
				) );

			// Run form validation
			if ( $this->form_validation->run() ) {
				$name_taken = $this->name_check( $this->input->post( 'projects_field_name', true ) );

				if ( ( !$is_new && strtolower( $this->input->post( 'projects_field_name', true ) ) != strtolower( $data['project']['name'] ) && $name_taken ) || ( $is_new && $name_taken ) ) {
					$data['projects_field_name_error'] = lang( 'projects_field_name_taken' );
				}
				else {
					// Create/Update
					$attributes = array();

					$attributes['name'] = $this->input->post( 'projects_field_name', true ) ? $this->input->post( 'projects_field_name', true ) : null;
					$attributes['description'] = $this->input->post( 'projects_field_description', true ) ? $this->input->post( 'projects_field_description', true ) : null;
					$attributes['id_brand'] = $this->input->post( 'projects_field_id_brand', true ) ? $this->input->post( 'projects_field_id_brand', true ) : null;
					if( $is_new )
						$id = $this->model->insert( $attributes );
					else
						$this->model->update_by_id( $id, $attributes );

					$project_sources = $this->input->post( 'projects_field_sources', true );
					$data['project_sources'] = array( );

					if( empty( $project_sources ) ) {
						$this->projects_sources_properties_model->delete_by_id_project( $id );
					}
					else {
						foreach ( $project_sources as $idSource ) {
							$this->projects_sources_properties_model->replace_by_id_source_and_id_project_and_name( $idSource, $id, 'added', array(
								'id_source' => $idSource,
								'id_project' => $id,
								'name' => 'added',
								'value' => '1'
							));
						}

						$properties = $this->projects_sources_properties_model->get_by_id_project( $id );

						foreach( $properties as $property ) {
							if( !in_array( $property['id_source'], $project_sources ) )
								$this->projects_sources_properties_model->delete_by_id_project_and_id_source( $id, $property['id_source'] );
							else {
								if( !isset( $data['project_sources'][$property['id_source']] ))
									$data['project_sources'][$property['id_source']] = array();
								$data['project_sources'][$property['id_source']][] = $property;
							}
						}
					}

					// Check if the permission should be disabled
					if ( $this->authorization->is_permitted( 'delete_projects' ) ) {
						if ( $this->input->post( 'deactivate', true ) ) {
							$this->model->update_by_id( $id, array( 'active' => 0 ) );
							$data['project']['active'] = 0;
						} elseif ( $this->input->post( 'activate', true ) ) {
							$this->model->update_by_id( $id, array( 'active' => 1 ) );
							$data['project']['active'] = 1;
						}
					}
				}

				if( $is_new )
					redirect( 'manage/projects/save/' . $id );
				$data['action_info'] = ( $is_new ? lang('projects_created') : lang('projects_updated') );

			}

			foreach( $data['sources'] as &$source ) {
				if( isset( $data['project_sources'][$source['id']] ) ) {
					$sourceClass = $this->class_check( $source['base_class'] );
					if( $sourceClass ) {
						$sourceClass->init( $source['id'] );
						$source['dataSource'] = $sourceClass;
					}
				}
			}

			$this->viewSave( $data );
		}

		/**
		 * Removes an image
		 * @param null|int $id
		 */
		function remove_image( $id = null ) {
			$data = $this->auth( 'manage/projects',
				array( 'update_projects' => 'manage/projects' )
			);

			$data['project'] = $this->model->get_one_by_id( $id );

			unlink( FCPATH . RES_DIR . '/project/profile/' . $data['project']['logo'] ); // delete previous picture
			$this->model->update_by_id( $id, array( 'logo' => null ) );

			redirect( 'manage/projects/save/' . $id );
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

		function class_check( $className ) {
			if( \sources\DataSource::checkClass( $className ) )
				return \sources\DataSource::create( $className );
			return null;
		}
	}

