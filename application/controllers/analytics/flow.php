<?php

	require_once( APPPATH . "controllers/general_controller.php" );

	/**
	 * Class Reports
	 *
	 * @property Projects_model $projects_model
	 */
	class Flow extends GeneralController {
		protected $scope = 'analytics';

		function __construct() {
			parent::__construct();

			$this->load->model( array(
				                    'manage/projects_model',
				                    'manage/reports_model',
				                    'manage/reports_pages_model'
			                    ) );
		}
		function index( ) {

			$data = $this->auth( 'analytics/flow/' , array(
				'view_reports' => 'account/account_profile',
				'dummy_permission_to_redirect' => 'account/account_profile',
			) );
			//$data['reports'] = $this->model->get_order_by_creation_desc();
			//$this->view( $data );
		}

		/**
		 * Function to show a Flow report
		 *
		 * @param $id int ID for report
		 */
		function shape( $id ) {

			$data = $this->auth( 'analytics/flow/' . $id , array(
				'view_reports' => 'account/account_profile'
			) );

			$report = $this->reports_model->get_one_by_id( $id );
			$pages = $this->reports_pages_model->get_by_id_report_order_by_position( $id );

			$data['report'] = $report;
			$data['pages'] = $pages;

			$this->view( $data );
		}

		/**
		 * Manage Reports
		 * @param null|int $id
		 */
		function save( $id = null ) {
			// Keep track if this is a new object
			$is_new = !$id;

			$data = $this->auth( 'manage/reports',
				(
				$is_new
					? array( 'create_reports' => 'manage/reports' )
					: array( 'update_reports' => 'manage/reports' )
				)
			);

			// Set action type (create or update)
			$data['action'] = 'create';

			// Get the object
			if ( ! $is_new ) {
				$data['report'] = $this->model->get_one_by_id( $id );
				$data['action'] = 'update';
			}
			$data['projects'] = $this->projects_model->getActiveList( );

			// Setup form validation
			$this->form_validation->set_rules(
				array(
					array(
						'field' => 'reports_field_name',
						'label' => 'lang:reports_field_name',
						'rules' => 'trim|required|max_length[80]'
					),
					array(
						'field' => 'reports_field_id_project',
						'label' => 'lang:reports_field_id_project',
						'rules' => 'numeric|required'
					)
				) );

			// Run form validation
			if ( $this->form_validation->run() ) {
				$name_taken = $this->name_check( $this->input->post( 'reports_field_name', true ) );

				if ( ( !$is_new && strtolower( $this->input->post( 'reports_field_name', true ) ) != strtolower( $data['report']['name'] ) && $name_taken ) || ( $is_new && $name_taken ) ) {
					$data['reports_field_name_error'] = lang( 'reports_field_name_taken' );
				}
				else {
					// Create/Update
					$attributes = array();

					$attributes['name'] = $this->input->post( 'reports_field_name', true ) ? $this->input->post( 'reports_field_name', true ) : null;
					$attributes['id_project'] = $this->input->post( 'reports_field_id_project', true ) ? $this->input->post( 'reports_field_id_project', true ) : null;
					if( $is_new )
						$id = $this->model->insert( $attributes );
					else
						$this->model->update_by_id( $id, $attributes );

					// Check if the permission should be disabled
					if ( $this->authorization->is_permitted( 'delete_reports' ) ) {
						if ( $this->input->post( 'deactivate', true ) ) {
							$this->model->update_by_id( $id, array( 'active' => 0 ) );
							$data['report']['active'] = 0;
						} elseif ( $this->input->post( 'activate', true ) ) {
							$this->model->update_by_id( $id, array( 'active' => 1 ) );
							$data['report']['active'] = 1;
						}
					}
				}

				if( $is_new )
					redirect( 'manage/reports/save/' . $id );

				$data['action_info'] = ( $is_new ? lang('reports_created') : lang('reports_updated') );

			}
			$this->viewSave( $data );
		}

		/**
		 * Removes an image
		 * @param null|int $id
		 */
		function remove_image( $id = null ) {
			$data = $this->auth( 'manage/reports',
			                     array( 'update_reports' => 'manage/reports' )
			);

			$data['report'] = $this->model->get_one_by_id( $id );

			unlink( FCPATH . RES_DIR . '/report/profile/' . $data['report']['logo'] ); // delete previous picture
			$this->model->update_by_id( $id, array( 'logo' => null ) );

			redirect( 'manage/reports/save/' . $id );
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

