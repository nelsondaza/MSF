<?php
	/**
	 * Created by PhpStorm.
	 * User: nelson.daza
	 * Date: 09/12/2014
	 * Time: 02:38 PM
	 */

	require_once( APPPATH . "models/general_model.php" );

	class Reports_model extends General_model {
		protected $tableName = 'dsh_report';

		/**
		 * Function called before do a get action
		 * @param $arguments
		 */
		public function onBeforeGet( $arguments ) {
			$this->db->join( 'dsh_project', $this->tableName . '.id_project = dsh_project.id' );
			$this->db->join( 'dsh_brand', 'dsh_project.id_brand = dsh_brand.id' );
			$this->db->join( 'dsh_client', 'dsh_brand.id_client = dsh_client.id' );
			$this->db->select( $this->tableName . '.*, dsh_project.name AS project_name, dsh_brand.name AS brand_name, dsh_brand.logo AS brand_logo, dsh_client.name AS client_name, dsh_client.logo AS client_logo' );
		}

	}