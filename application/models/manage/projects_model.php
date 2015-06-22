<?php
	/**
	 * Created by PhpStorm.
	 * User: nelson.daza
	 * Date: 09/12/2014
	 * Time: 02:38 PM
	 */

	require_once( APPPATH . "models/general_model.php" );

	class Projects_model extends General_model {
		protected $tableName = 'dsh_project';

		/**
		 * Function called before do a get action
		 * @param $arguments
		 */
		public function onBeforeGet( $arguments ) {
			$this->db->join( 'dsh_brand', $this->tableName . '.id_brand = dsh_brand.id' );
			$this->db->join( 'dsh_client', 'dsh_brand.id_client = dsh_client.id' );
			$this->db->select( $this->tableName . '.*, dsh_brand.name AS brand_name, dsh_brand.logo AS brand_logo, dsh_client.name AS client_name, dsh_client.logo AS client_logo' );
		}

		/**
		 * Returns a list with active elements
		 * @return mixed
		 */
		public function getActiveList( ) {
			$this->db->select( $this->tableName . '.id, ' . $this->tableName . '.name, dsh_client.name AS client_name, dsh_client.logo AS client_logo, dsh_brand.name AS brand_name, dsh_brand.logo AS brand_logo' );
			$this->db->where($this->tableName . '.active', 1);

			return $this->get_order_by_name( );
		}

	}