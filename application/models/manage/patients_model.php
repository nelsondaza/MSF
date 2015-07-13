<?php

	require_once( APPPATH . "models/general_model.php" );

	class Patients_model extends General_model {

		/**
		 * Function called before do a get action
		 * @param $arguments
		 *
		 * ≤ 5
		 * 6-18
		 * ≥ 19
		 */
		public function onBeforeGet( $arguments ) {
			$this->db->select( $this->tableName . ".*, ( IF( " . $this->tableName . ".age IS NULL OR " . $this->tableName . ".age <= 5, '≤ 5', IF( " . $this->tableName . ".age >= 19, '≥ 19', '6-18' ) ) ) AS age_group ", FALSE );
		}
		/**
		 * Returns a list with active elements
		 * @return mixed
		 */
		public function getActiveList( ) {
			return $this->get_order_by_first_name_and_last_name( );
		}

		public function getNextCode( ) {

			$this->db->select_max('id');
			$result = $this->db->get($this->tableName)->row_array( );
			return ((int)$result['id']) + 1;
		}

		public function search( $q, $limit ) {
			$parts = explode(' ', $q );
			foreach( $parts as $key ) {
				$this->db->like('search_text', $key );
			}
			$this->db->limit( $limit );

			return $this->get_order_by_first_name_and_last_name( );
		}

	}