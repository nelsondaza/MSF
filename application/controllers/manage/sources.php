<?php

	require_once( APPPATH . "controllers/general_controller.php" );

	class Sources extends GeneralController {
		protected $scope = 'manage';

		/**
		 * List Sources
		 */
		function index() {

			$data = $this->auth( 'manage/sources', array(
				'retrieve_sources' => 'account/account_profile'
			) );

			$data['sources'] = $this->model->get_order_by_name();

			$this->view( $data );
		}

		/**
		 * Manage Sources
		 * @param null|int $id
		 */
		function save( $id = null ) {
			// Keep track if this is a new object
			$is_new = !$id;

			$data = $this->auth( 'manage/sources',
				(
					$is_new
					? array( 'create_sources' => 'manage/sources' )
					: array( 'update_sources' => 'manage/sources' )
				)
			);

			// Set action type (create or update)
			$data['action'] = 'create';

			// Get the object
			if ( ! $is_new ) {
				$data['source'] = $this->model->get_one_by_id( $id );
				$data['action'] = 'update';
			}

			$formRules = array(
				array(
					'field' => 'sources_field_name',
					'label' => 'lang:sources_field_name_error',
					'rules' => 'trim|required|max_length[80]'
				),
				array(
					'field' => 'sources_field_base_class',
					'label' => 'lang:sources_field_base_class_error',
					'rules' => 'trim|required|max_length[80]'
				),
				array(
					'field' => 'sources_field_description',
					'label' => 'lang:sources_field_description_error',
					'rules' => 'trim|optional|max_length[250]'
				)
			);

			$sourceClass = $this->class_check( ( $this->input->post( 'sources_field_base_class', true ) ? $this->input->post( 'sources_field_base_class', true ) : ( isset( $data['source'] ) ? $data['source']['base_class'] : null ) ) );
			if( $sourceClass ) {
				$sourceClass->init( $id );
				$formRules = array_merge( $formRules, $sourceClass->getManageFormRules( ) );
				$data['dataSource'] = $sourceClass;
			}

			// Setup form validation
			$this->form_validation->set_rules( $formRules );

			// Run form validation
			if ( $this->form_validation->run() ) {
				$name_taken = $this->name_check( $this->input->post( 'sources_field_name', true ) );

				if ( !$sourceClass ) {
					$data['sources_field_base_class_error'] = lang( 'sources_field_base_class_instance_error' );
					if( !$is_new ) {
						$this->model->update_by_id( $id, array( 'active' => 0, 'base_class' => null ) );
						$data['source']['base_class'] = '';
						$data['source']['active'] = 0;
					}
				}
				else if ( ( !$is_new && strtolower( $this->input->post( 'sources_field_name', true ) ) != strtolower( $data['source']['name'] ) && $name_taken ) || ( $is_new && $name_taken ) ) {
					$data['sources_field_name_error'] = lang( 'sources_field_name_taken' );
				}
				else {
					// Create/Update
					$attributes = array();

					$attributes['name'] = $this->input->post( 'sources_field_name', true ) ? $this->input->post( 'sources_field_name', true ) : null;
					$attributes['description'] = $this->input->post( 'sources_field_description', true ) ? $this->input->post( 'sources_field_description', true ) : null;
					$attributes['base_class'] = ( $sourceClass && $this->input->post( 'sources_field_base_class', true ) ? $this->input->post( 'sources_field_base_class', true ) : null );
					$attributes['active'] = ( $data['source']['active'] == 1 && $sourceClass ? 1 : 0 );
					if( $is_new )
						$id = $this->model->insert( $attributes );
					else
						$this->model->update_by_id( $id, $attributes );

					// Check if the permission should be disabled
					if ( $this->authorization->is_permitted( 'delete_sources' ) ) {
						if ( $this->input->post( 'deactivate', true ) ) {
							$this->model->update_by_id( $id, array( 'active' => 0 ) );
							$data['source']['active'] = 0;
						} elseif ( $this->input->post( 'activate', true ) && $sourceClass ) {
							$this->model->update_by_id( $id, array( 'active' => 1 ) );
							$data['source']['active'] = 1;
						}
					}

					// If user has uploaded a file
					if ( isset( $_FILES['sources_field_logo'] ) && $_FILES['sources_field_logo']['error'] != 4 ) {
						// Load file uploading library - http://codeigniter.com/user_guide/libraries/file_uploading.html
						$this->load->library( 'upload', array(
							'overwrite'     => true,
							'upload_path'   => FCPATH . RES_DIR . '/source/profile',
							'allowed_types' => 'jpg|png|gif',
							'max_size'      => '800' // kilobytes
						) );

						/// Try to upload the file
						if ( !$this->upload->do_upload( 'sources_field_logo' ) ) {
							$data['sources_field_logo_error'] = $this->upload->display_errors( '', '' );
						} else {
							// Get uploaded picture data
							$picture = $this->upload->data();

							// Create picture thumbnail - http://codeigniter.com/user_guide/libraries/image_lib.html
							$this->load->library( 'image_lib' );
							$this->image_lib->clear();
							$this->image_lib->initialize( array( 'image_library'  => 'gd2',
							                                     'source_image'   => FCPATH . RES_DIR . '/source/profile/' . $picture['file_name'],
							                                     'new_image'      => FCPATH . RES_DIR . '/source/profile/pic_' . md5( 'source' . $id ) . $picture['file_ext'],
							                                     'maintain_ratio' => false,
							                                     'quality'        => '80%',
							                                     'width'          => 100,
							                                     'height'         => 100
							                              ) );

							// Try resizing the picture
							if ( ! $this->image_lib->resize() ) {
								$data['sources_field_logo_error'] = $this->image_lib->display_errors();
							} else {
								$data['source']['logo'] = 'pic_' . md5( 'source' . $id ) . $picture['file_ext'];
								$this->model->update_by_id( $id, array( 'logo' => $data['source']['logo'] ) );
							}

							// Delete original uploaded file
							unlink( FCPATH . RES_DIR . '/source/profile/' . $picture['file_name'] );
						}
					}

					if( $sourceClass ) {
						$sourceClass->setId( $id );
						$sourceClass->save( );
						if( $data['source']['active'] && !$sourceClass->canActivate( ) ) {
							$this->model->update_by_id( $id, array( 'active' => 0 ) );
							$data['source']['active'] = 0;
						}
					}
				}

				if( $is_new && $id )
					redirect( 'manage/sources/save/' . $id );

				$data['action_info'] = ( $is_new ? lang('sources_created') : lang('sources_updated') );

			}
			$this->viewSave( $data );
		}

		/**
		 * Removes an image
		 * @param null|int $id
		 */
		function remove_image( $id = null ) {
			$data = $this->auth( 'manage/sources',
				array( 'update_sources' => 'manage/sources' )
			);

			$data['source'] = $this->model->get_one_by_id( $id );

			unlink( FCPATH . RES_DIR . '/source/profile/' . $data['source']['logo'] ); // delete previous picture
			$this->model->update_by_id( $id, array( 'logo' => null ) );

			redirect( 'manage/sources/save/' . $id );
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

