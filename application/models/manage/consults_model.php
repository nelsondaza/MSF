<?php

	require_once( APPPATH . "models/general_model.php" );

	class Consults_model extends General_model {

		/**
		 * Function called before do a get action
		 * @param $arguments
		 */
		public function onBeforeGet( $arguments ) {
			$this->db->select( $this->tableName . '.*,
				msf_symptoms_categories.name AS symptoms_category,
				msf_risks_categories.name AS risks_category,
				msf_consults_types.name AS consults_type,
				msf_diagnostics.name AS diagnostic,
				msf_references.name AS referenced_to,
				msf_closures.name AS closure,
				msf_closures_conditions.name AS closure_condition,
				msf_interventions_types.name AS interventions_type' );
			$this->db->join( 'msf_symptoms_categories', $this->tableName . '.id_symptoms_category = msf_symptoms_categories.id', 'LEFT' );
			$this->db->join( 'msf_risks_categories', $this->tableName . '.id_risks_category = msf_risks_categories.id', 'LEFT' );
			$this->db->join( 'msf_consults_types', $this->tableName . '.id_consults_type = msf_consults_types.id', 'LEFT' );
			$this->db->join( 'msf_interventions_types', $this->tableName . '.id_interventions_type = msf_interventions_types.id', 'LEFT' );
			$this->db->join( 'msf_diagnostics', $this->tableName . '.id_diagnostic = msf_diagnostics.id', 'LEFT' );
			$this->db->join( 'msf_references', $this->tableName . '.id_referenced_to = msf_references.id', 'LEFT' );
			$this->db->join( 'msf_closures', $this->tableName . '.id_closure = msf_closures.id', 'LEFT' );
			$this->db->join( 'msf_closures_conditions', $this->tableName . '.id_closure_condition = msf_closures_conditions.id', 'LEFT' );
		}
		/**
		 * Returns a list with active elements
		 * @return mixed
		 */
		public function getActiveList( ) {
			$this->db->where($this->tableName . '.active', 1);
			return $this->get_order_by_creation( );
		}

		/**
		 * @param $idPatient
		 *
		 * @return mixed
		 */
		public function getOpenedFor( $idPatient ) {
			$this->db->where($this->tableName . '.id_closure IS NULL');
			return $this->get_by_id_patient_order_by_creation( $idPatient );
		}

		/**
		 * @param $idPatient
		 *
		 * @return mixed
		 */
		public function getLastClosedFor( $idPatient ) {
			$this->db->where($this->tableName . '.id_closure IS NOT NULL');
			return $this->get_one_by_id_patient_order_by_creation_desc( $idPatient );
		}

	}