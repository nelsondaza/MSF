<?php

	require_once( APPPATH . "models/general_model.php" );

	class Patients_references_model extends General_model {

		/**
		 * Function called before do a get action
		 * @param $arguments
		 */
		public function onBeforeGet( $arguments ) {
			$this->db->select( $this->tableName . '.*, msf_references.name AS reference, msf_references_categories.id AS id_category, msf_references_categories.name AS category' );
			$this->db->join( 'msf_references', $this->tableName . '.id_reference = msf_references.id AND msf_references.active = 1' );
			$this->db->join( 'msf_references_categories', 'msf_references.id_category = msf_references_categories.id' );
		}
		/**
		 * Returns a list with active elements
		 * @return mixed
		 */
		public function getActiveList( ) {
			return $this->get_order_by_reference( );
		}

	}