<?php

	require_once( APPPATH . "models/general_model.php" );

	class Consults_symptoms_model extends General_model {

		/**
		 * Function called before do a get action
		 * @param $arguments
		 */
		public function onBeforeGet( $arguments ) {
			$this->db->select( $this->tableName . '.*, msf_symptoms.name AS symptom' );
			$this->db->join( 'msf_symptoms', $this->tableName . '.id_symptom = msf_symptoms.id AND msf_symptoms.active = 1' );
		}
		/**
		 * Returns a list with active elements
		 * @return mixed
		 */
		public function getActiveList( ) {
			return $this->get_order_by_symptom( );
		}

	}