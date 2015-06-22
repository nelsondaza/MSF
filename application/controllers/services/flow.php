<?php

	require_once( APPPATH . "controllers/services_controller.php" );

	/**
	 * Class Reports
	 *
	 * @property Projects_model $projects_model
	 * @property Reports_pages_model $reports_pages_model
	 *
	 */
	class Flow extends ServicesController {
		protected $scope = 'services';

		function __construct() {
			parent::__construct();

			$this->load->model( array(
				                    'manage/sources_model',
				                    'manage/reports_pages_model',
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

		function user_in( ) {
			$data = $this->auth( 'manage/reports', array(
				'retrieve_reports' => 'account/account_profile'
			) );

			$this->data['data'] = array( 'user' => (array)$data['account_details'] );
			$this->data['action'] = 'Session:access';
			$this->shapeResponse();
		}


		function flow_actions( ) {

			$data = $this->auth( 'manage/reports', array(
				'retrieve_reports' => 'account/account_profile'
			) );

			$this->data['data'] = array(
				'read'     => $this->authorization->is_permitted( 'view_reports' ),
				'create'   => $this->authorization->is_permitted( 'create_report_pages' ),
				'delete'   => $this->authorization->is_permitted( 'delete_report_pages' ),
				//'generate' => $this->authorization->is_permitted( 'generate_reports' ),
				//'export'   => $this->authorization->is_permitted( 'export_reports' ),
			);

			$this->data['action'] = 'Session:actions';
			$this->shapeResponse();

		}

		function flow_page_actions( ) {

			$data = $this->auth( 'manage/reports', array(
				'retrieve_reports' => 'account/account_profile'
			) );

			$this->data['data'] = array(
				'read'     => $this->authorization->is_permitted( 'view_reports' ),
				'create'   => $this->authorization->is_permitted( 'create_report_pages' ),
				'delete'   => $this->authorization->is_permitted( 'delete_report_pages' ),
				'move'   => $this->authorization->is_permitted( 'update_reports' ),
				//'generate' => $this->authorization->is_permitted( 'generate_reports' ),
				//'export'   => $this->authorization->is_permitted( 'export_reports' ),
			);

			$this->data['action'] = 'Page:actions';
			$this->shapeResponse();

		}

		function flow_page_add( ) {

			$idFlow = (int)$this->input->get('idFlow',true);

			$data = $this->auth( 'manage/reports', array(
				'retrieve_reports' => 'account/account_profile'
			) );

			$pages = $this->reports_pages_model->get_id_report_order_by_position( $idFlow );

			$newID = $this->reports_pages_model->insert(array(
				'id_report' => (int)$this->input->get('idFlow',true),
				'title' => 'Título Página #' . ( count( $pages ) + 1 ),
				'position' => 1
			));
			if( $newID ) {
				foreach( $pages as $pos => $page ) {
					$this->reports_pages_model->update_by_id($page['id'],array( 'position' => ( $pos + 2 ) ) );
				}
			}

			$pages = $this->reports_pages_model->get_id_report_order_by_position( $idFlow );
			$this->data['data'] = $pages;
			$this->data['action'] = 'Pages:list';

			$this->shapeResponse();

		}

		function flow_page_remove( ) {

			$idFlow = (int)$this->input->get('idFlow',true);
			$idPage = (int)$this->input->get('id',true);

			$data = $this->auth( 'manage/reports', array(
				'retrieve_reports' => 'account/account_profile'
			) );

			$page = $this->reports_pages_model->get_one_id_report_and_id( $idFlow, $idPage );

			if( $page ) {
				$this->reports_pages_model->delete_one_by_id( $idPage );
			}

			$pages = $this->reports_pages_model->get_id_report_order_by_position( $idFlow );
			foreach( $pages as $pos => $page ) {
				$this->reports_pages_model->update_by_id($page['id'],array( 'position' => ( $pos + 1 ) ));
			}

			$pages = $this->reports_pages_model->get_id_report_order_by_position( $idFlow );
			$this->data['data'] = $pages;
			$this->data['action'] = 'Pages:list';

			$this->shapeResponse();

		}

		function flow_page_title( ) {

			$idFlow = (int)$this->input->get('idFlow',true);
			$idPage = (int)$this->input->get('id',true);
			$title = $this->input->get('title',true);

			$data = $this->auth( 'manage/reports', array(
				'retrieve_reports' => 'account/account_profile'
			) );

			$page = $this->reports_pages_model->get_one_id_report_and_id( $idFlow, $idPage );

			if( $page )
				$this->reports_pages_model->update_by_id($idPage,array( 'title' => $title ));

			$this->shapeResponse();
		}

		function flow_pages_load( ) {

			$idFlow = (int)$this->input->get('idFlow',true);

			$data = $this->auth( 'manage/reports', array(
				'retrieve_reports' => 'account/account_profile'
			) );

			$pages = $this->reports_pages_model->get_id_report_order_by_position( $idFlow );
			$this->data['data'] = $pages;
			$this->data['action'] = 'Pages:list';

			$this->shapeResponse();

		}

	}

