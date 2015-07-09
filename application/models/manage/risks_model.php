<?php

	require_once( APPPATH . "models/general_model.php" );

	class Risks_model extends General_model {

		/**
		 * Function called before do a get action
		 * @param $arguments
		 */
		public function onBeforeGet( $arguments ) {
			$this->db->select( $this->tableName . '.*, msf_risks_categories.name AS category, msf_risks_categories.id AS id_category' );
			$this->db->join( 'msf_risks_categories', $this->tableName . '.id_category = msf_risks_categories.id AND msf_risks_categories.active = 1', 'LEFT' );
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