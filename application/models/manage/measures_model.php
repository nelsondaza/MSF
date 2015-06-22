<?php
	/**
	 * Created by PhpStorm.
	 * User: nelson.daza
	 * Date: 09/12/2014
	 * Time: 02:38 PM
	 */

	require_once( APPPATH . "models/general_model.php" );

	class Measures_model extends General_model {
		protected $tableName = 'dsh_measure';

		/**
		 * Function called before do a get action
		 * @param $arguments
		 */
		public function onBeforeGet( $arguments ) {
			$this->db->select( $this->tableName . '.*, dsh_source.name AS source_name, dsh_source.logo AS source_logo' );
			$this->db->join( 'dsh_source', $this->tableName . '.id_source = dsh_source.id' );
		}

		/**
		 * Returns a list with active elements
		 * @return mixed
		 */
		public function getActiveList( ) {
			$this->db->select( $this->tableName . '.id, ' . $this->tableName . '.name, ' . $this->tableName . '.logo, dsh_source.name AS source_name, dsh_source.logo AS source_logo' );
			$this->db->where($this->tableName . '.active', 1);

			return $this->get_order_by_name( );
		}

	}