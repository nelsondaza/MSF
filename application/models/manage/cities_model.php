<?php

	require_once( APPPATH . "models/general_model.php" );

	class Cities_model extends General_model {

		/**
		 * Function called before do a get action
		 * @param $arguments
		 */
		public function onBeforeGet( $arguments ) {
			$this->db->select( $this->tableName . '.*, msf_regions.name AS region, msf_regions.id AS id_region' );
			$this->db->join( 'msf_regions', $this->tableName . '.id_region = msf_regions.id AND msf_regions.active = 1' );
		}
		/**
		 * Returns a list with active elements
		 * @return mixed
		 */
		public function getActiveList( ) {
			$this->db->where($this->tableName . '.active', 1);
			return $this->get_order_by_name( );
		}


	}