<?php
	/**
	 * Created by PhpStorm.
	 * User: nelson.daza
	 * Date: 09/12/2014
	 * Time: 02:38 PM
	 */

	require_once( APPPATH . "models/general_model.php" );

	class Brands_model extends General_model {
		protected $tableName = 'dsh_brand';

		/**
		 * Function called before do a get action
		 * @param $arguments
		 */
		public function onBeforeGet( $arguments ) {
			$this->db->select( $this->tableName . '.*, dsh_client.name AS client_name, dsh_client.logo AS client_logo' );
			$this->db->join( 'dsh_client', $this->tableName . '.id_client = dsh_client.id' );
		}

		/**
		 * Returns a list with active elements
		 * @return mixed
		 */
		public function getActiveList( ) {
			$this->db->select( $this->tableName . '.id, ' . $this->tableName . '.name, ' . $this->tableName . '.logo, dsh_client.name AS client_name, dsh_client.logo AS client_logo' );
			$this->db->where($this->tableName . '.active', 1);

			return $this->get_order_by_name( );
		}

	}