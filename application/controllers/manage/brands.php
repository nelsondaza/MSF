<?php

	require_once( APPPATH . "controllers/general_controller.php" );

	/**
	 * Class Brands
	 *
	 * @property Clients_model $clients_model
	 */
	class Brands extends GeneralController {
		protected $scope = 'manage';

		function __construct() {
			parent::__construct();

			$this->load->model( array(
				                    'manage/clients_model'
			                    ) );
		}
		/**
		 * List Brands
		 */
		function index() {

			$data = $this->auth( 'manage/brands', array(
				'retrieve_brands' => 'account/account_profile'
			) );

			$data['brands'] = $this->model->get_order_by_name();

			$this->view( $data );
		}

		/**
		 * Manage Brands
		 * @param null|int $id
		 */
		function save( $id = null ) {
			// Keep track if this is a new object
			$is_new = !$id;

			$data = $this->auth( 'manage/brands',
				(
					$is_new
					? array( 'create_brands' => 'manage/brands' )
					: array( 'update_brands' => 'manage/brands' )
				)
			);

			// Set action type (create or update)
			$data['action'] = 'create';

			// Get the object
			if ( ! $is_new ) {
				$data['brand'] = $this->model->get_one_by_id( $id );
				$data['action'] = 'update';
			}
			$data['clients'] = $this->clients_model->getActiveList( );

			// Setup form validation
			$this->form_validation->set_rules(
				array(
					array(
						'field' => 'brands_field_name',
						'label' => 'lang:brands_field_name',
						'rules' => 'trim|required|max_length[80]'
					),
					array(
						'field' => 'brands_field_id_client',
						'label' => 'lang:brands_field_id_client',
						'rules' => 'numeric|required'
					),
					array(
						'field' => 'brands_field_description',
						'label' => 'lang:brands_field_description_error',
						'rules' => 'trim|optional|max_length[160]'
					)
				) );

			// Run form validation
			if ( $this->form_validation->run() ) {
				$name_taken = $this->name_check( $this->input->post( 'brands_field_name', true ) );

				if ( ( !$is_new && strtolower( $this->input->post( 'brands_field_name', true ) ) != strtolower( $data['brand']['name'] ) && $name_taken ) || ( $is_new && $name_taken ) ) {
					$data['brands_field_name_error'] = lang( 'brands_field_name_taken' );
				}
				else {
					// Create/Update
					$attributes = array();

					$attributes['name'] = $this->input->post( 'brands_field_name', true ) ? $this->input->post( 'brands_field_name', true ) : null;
					$attributes['description'] = $this->input->post( 'brands_field_description', true ) ? $this->input->post( 'brands_field_description', true ) : null;
					$attributes['id_client'] = $this->input->post( 'brands_field_id_client', true ) ? $this->input->post( 'brands_field_id_client', true ) : null;
					if( $is_new )
						$id = $this->model->insert( $attributes );
					else
						$this->model->update_by_id( $id, $attributes );

					// Check if the permission should be disabled
					if ( $this->authorization->is_permitted( 'delete_brands' ) ) {
						if ( $this->input->post( 'deactivate', true ) ) {
							$this->model->update_by_id( $id, array( 'active' => 0 ) );
							$data['brand']['active'] = 0;
						} elseif ( $this->input->post( 'activate', true ) ) {
							$this->model->update_by_id( $id, array( 'active' => 1 ) );
							$data['brand']['active'] = 1;
						}
					}

					// If user has uploaded a file
					if ( isset( $_FILES['brands_field_logo'] ) && $_FILES['brands_field_logo']['error'] != 4 ) {
						// Load file uploading library - http://codeigniter.com/user_guide/libraries/file_uploading.html
						$this->load->library( 'upload', array(
							'overwrite'     => true,
							'upload_path'   => FCPATH . RES_DIR . '/brand/profile',
							'allowed_types' => 'jpg|png|gif',
							'max_size'      => '800' // kilobytes
						) );

						/// Try to upload the file
						if ( !$this->upload->do_upload( 'brands_field_logo' ) ) {
							$data['brands_field_logo_error'] = $this->upload->display_errors( '', '' );
						} else {
							// Get uploaded picture data
							$picture = $this->upload->data();

							// Create picture thumbnail - http://codeigniter.com/user_guide/libraries/image_lib.html
							$this->load->library( 'image_lib' );
							$this->image_lib->clear();
							$this->image_lib->initialize( array( 'image_library'  => 'gd2',
							                                     'source_image'   => FCPATH . RES_DIR . '/brand/profile/' . $picture['file_name'],
							                                     'new_image'      => FCPATH . RES_DIR . '/brand/profile/pic_' . md5( 'brand' . $id ) . $picture['file_ext'],
							                                     'maintain_ratio' => false,
							                                     'quality'        => '80%',
							                                     'width'          => 100,
							                                     'height'         => 100
							                              ) );

							// Try resizing the picture
							if ( ! $this->image_lib->resize() ) {
								$data['brands_field_logo_error'] = $this->image_lib->display_errors();
							} else {
								$data['brand']['logo'] = 'pic_' . md5( 'brand' . $id ) . $picture['file_ext'];
								$this->model->update_by_id( $id, array( 'logo' => $data['brand']['logo'] ) );
							}

							// Delete original uploaded file
							unlink( FCPATH . RES_DIR . '/brand/profile/' . $picture['file_name'] );
						}
					}
				}

				if( $is_new )
					redirect( 'manage/brands/save/' . $id );

				$data['action_info'] = ( $is_new ? lang('brands_created') : lang('brands_updated') );

			}
			$this->viewSave( $data );
		}

		/**
		 * Removes an image
		 * @param null|int $id
		 */
		function remove_image( $id = null ) {
			$data = $this->auth( 'manage/brands',
				array( 'update_brands' => 'manage/brands' )
			);

			$data['brand'] = $this->model->get_one_by_id( $id );

			unlink( FCPATH . RES_DIR . '/brand/profile/' . $data['brand']['logo'] ); // delete previous picture
			$this->model->update_by_id( $id, array( 'logo' => null ) );

			redirect( 'manage/brands/save/' . $id );
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

