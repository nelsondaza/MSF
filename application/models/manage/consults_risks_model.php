<?php

	require_once( APPPATH . "models/general_model.php" );

	class Consults_risks_model extends General_model {

		/**
		 * Function called before do a get action
		 * @param $arguments
		 */
		public function onBeforeGet( $arguments ) {
			$this->db->select( $this->tableName . '.*, msf_risks.name AS risk' );
			$this->db->join( 'msf_risks', $this->tableName . '.id_risk = msf_risks.id AND msf_risks.active = 1' );
		}
		/**
		 * Returns a list with active elements
		 * @return mixed
		 */
		public function getActiveList( ) {
			return $this->get_order_by_risk( );
		}

	}