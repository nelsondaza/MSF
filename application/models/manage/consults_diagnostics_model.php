<?php

	require_once( APPPATH . "models/general_model.php" );

	class Consults_diagnostics_model extends General_model {

		/**
		 * Function called before do a get action
		 * @param $arguments
		 */
		public function onBeforeGet( $arguments ) {
			$this->db->select( $this->tableName . '.*, msf_diagnostics.name AS diagnostic' );
			$this->db->join( 'msf_diagnostics', $this->tableName . '.id_diagnostic = msf_diagnostics.id AND msf_diagnostics.active = 1' );
		}
		/**
		 * Returns a list with active elements
		 * @return mixed
		 */
		public function getActiveList( ) {
			return $this->get_order_by_diagnostic( );
		}

	}