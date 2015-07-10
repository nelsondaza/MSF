<?php

	require_once( APPPATH . "models/general_model.php" );

	class Patients_model extends General_model {

		/**
		 * Returns a list with active elements
		 * @return mixed
		 */
		public function getActiveList( ) {
			//$this->db->where($this->tableName . '.active', 1);
			return $this->get_order_by_last_name_and_first_name( );
		}

		public function search( $q, $limit ) {
			$parts = explode(' ', $q );
			foreach( $parts as $key ) {
				$this->db->like('search_text', $key );
			}
			$this->db->limit( $limit );

			return $this->get_order_by_last_name_and_first_name( );

		}

	}