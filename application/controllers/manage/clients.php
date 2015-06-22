<?php

	require_once( APPPATH . "controllers/general_controller.php" );

	class Clients extends GeneralController {
		protected $scope = 'manage';

		/**
		 * List Clients
		 */
		function index() {

			$data = $this->auth( 'manage/clients', array(
				'retrieve_clients' => 'account/account_profile'
			) );

			$data['clients'] = $this->model->get_order_by_name();

			$this->view( $data );
		}

		/**
		 * Manage Clients
		 * @param null|int $id
		 */
		function save( $id = null ) {
			// Keep track if this is a new object
			$is_new = !$id;

			$data = $this->auth( 'manage/clients',
				(
					$is_new
					? array( 'create_clients' => 'manage/clients' )
					: array( 'update_clients' => 'manage/clients' )
				)
			);

			// Set action type (create or update)
			$data['action'] = 'create';

			// Get the object
			if ( ! $is_new ) {
				$data['client'] = $this->model->get_one_by_id( $id );
				$data['action'] = 'update';
			}

			// Setup form validation
			$this->form_validation->set_rules(
				array(
					array(
						'field' => 'clients_field_name',
						'label' => 'lang:clients_field_name',
						'rules' => 'trim|required|max_length[80]'
					),
					array(
						'field' => 'clients_field_description',
						'label' => 'lang:clients_field_description_error',
						'rules' => 'trim|optional|max_length[160]'
					)
				) );

			// Run form validation
			if ( $this->form_validation->run() ) {
				$name_taken = $this->name_check( $this->input->post( 'clients_field_name', true ) );

				if ( ( !$is_new && strtolower( $this->input->post( 'clients_field_name', true ) ) != strtolower( $data['client']['name'] ) && $name_taken ) || ( $is_new && $name_taken ) ) {
					$data['clients_field_name_error'] = lang( 'clients_field_name_taken' );
				}
				else {
					// Create/Update
					$attributes = array();

					$attributes['name'] = $this->input->post( 'clients_field_name', true ) ? $this->input->post( 'clients_field_name', true ) : null;
					$attributes['description'] = $this->input->post( 'clients_field_description', true ) ? $this->input->post( 'clients_field_description', true ) : null;
					if( $is_new )
						$id = $this->model->insert( $attributes );
					else
						$this->model->update_by_id( $id, $attributes );

					// Check if the permission should be disabled
					if ( $this->authorization->is_permitted( 'delete_clients' ) ) {
						if ( $this->input->post( 'deactivate', true ) ) {
							$this->model->update_by_id( $id, array( 'active' => 0 ) );
							$data['client']['active'] = 0;
						} elseif ( $this->input->post( 'activate', true ) ) {
							$this->model->update_by_id( $id, array( 'active' => 1 ) );
							$data['client']['active'] = 1;
						}
					}

					// If user has uploaded a file
					if ( isset( $_FILES['clients_field_logo'] ) && $_FILES['clients_field_logo']['error'] != 4 ) {
						// Load file uploading library - http://codeigniter.com/user_guide/libraries/file_uploading.html
						$this->load->library( 'upload', array(
							'overwrite'     => true,
							'upload_path'   => FCPATH . RES_DIR . '/client/profile',
							'allowed_types' => 'jpg|png|gif',
							'max_size'      => '800' // kilobytes
						) );

						/// Try to upload the file
						if ( !$this->upload->do_upload( 'clients_field_logo' ) ) {
							$data['clients_field_logo_error'] = $this->upload->display_errors( '', '' );
						} else {
							// Get uploaded picture data
							$picture = $this->upload->data();

							// Create picture thumbnail - http://codeigniter.com/user_guide/libraries/image_lib.html
							$this->load->library( 'image_lib' );
							$this->image_lib->clear();
							$this->image_lib->initialize( array( 'image_library'  => 'gd2',
							                                     'source_image'   => FCPATH . RES_DIR . '/client/profile/' . $picture['file_name'],
							                                     'new_image'      => FCPATH . RES_DIR . '/client/profile/pic_' . md5( 'client' . $id ) . $picture['file_ext'],
							                                     'maintain_ratio' => false,
							                                     'quality'        => '80%',
							                                     'width'          => 100,
							                                     'height'         => 100
							                              ) );

							// Try resizing the picture
							if ( ! $this->image_lib->resize() ) {
								$data['clients_field_logo_error'] = $this->image_lib->display_errors();
							} else {
								$data['client']['logo'] = 'pic_' . md5( 'client' . $id ) . $picture['file_ext'];
								$this->model->update_by_id( $id, array( 'logo' => $data['client']['logo'] ) );
							}

							// Delete original uploaded file
							unlink( FCPATH . RES_DIR . '/client/profile/' . $picture['file_name'] );
						}
					}
				}

				if( $is_new )
					redirect( 'manage/clients/save/' . $id );

				$data['action_info'] = ( $is_new ? lang('clients_created') : lang('clients_updated') );

			}
			$this->viewSave( $data );
		}

		/**
		 * Removes an image
		 * @param null|int $id
		 */
		function remove_image( $id = null ) {
			$data = $this->auth( 'manage/clients',
				array( 'update_clients' => 'manage/clients' )
			);

			$data['client'] = $this->model->get_one_by_id( $id );

			unlink( FCPATH . RES_DIR . '/client/profile/' . $data['client']['logo'] ); // delete previous picture
			$this->model->update_by_id( $id, array( 'logo' => null ) );

			redirect( 'manage/clients/save/' . $id );
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

