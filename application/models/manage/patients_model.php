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
			$this->db->select(
				$this->tableName . ".*, a3m_account_details.fullname AS expert,
				( IF( " . $this->tableName . ".age IS NULL OR " . $this->tableName . ".age <= 5, '≤ 5', IF( " . $this->tableName . ".age >= 19, '≥ 19', '6-18' ) ) ) AS age_group
				", FALSE );
			$this->db->join( 'a3m_account_details', $this->tableName . '.id_expert = a3m_account_details.account_id', 'LEFT' );
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

			$this->db->select( '
				id, first_name, last_name, PID, code, last_session,
				(
					(
						SELECT COUNT(*) AS total
						FROM msf_consults
						WHERE msf_consults.id_patient = msf_patients.id
					) + 1
				) AS consults
			' );

			$parts = explode(' ', $q );
			foreach( $parts as $key ) {
				$this->db->like('search_text', $key );
			}
			$this->db->limit( $limit );
			$this->db->order_by('first_name,last_name,code,PID');

			return $this->db->get($this->tableName)->result_array( );
		}


		public function getAllBetween( $start, $end ) {

			$start = ( is_integer( $start ) ? $start : strtotime( $start ) );
			$end = ( is_integer( $end ) ? $end : strtotime( $end ) );

			$this->db->select(
				$this->tableName . ".*,
				a3m_account_details.fullname AS expert,
				( IF( " . $this->tableName . ".age IS NULL OR " . $this->tableName . ".age <= 5, '≤ 5', IF( " . $this->tableName . ".age >= 19, '≥ 19', '6-18' ) ) ) AS age_group,
				msf_localizations.name AS localization,
				msf_cities.name AS city,
				msf_regions.name AS region,
				msf_educations.name AS education,
				msf_origin_places.name AS origin_place
				"
			, FALSE );

			$this->db->join( 'a3m_account_details', $this->tableName . '.id_expert = a3m_account_details.account_id', 'LEFT' );
			$this->db->join( 'msf_localizations', $this->tableName . '.id_localization = msf_localizations.id', 'LEFT' );
			$this->db->join( 'msf_cities', 'msf_localizations.id_city = msf_cities.id', 'LEFT' );
			$this->db->join( 'msf_regions', 'msf_cities.id_region = msf_regions.id', 'LEFT' );
			$this->db->join( 'msf_educations', $this->tableName . '.id_education = msf_educations.id', 'LEFT' );
			$this->db->join( 'msf_origin_places', $this->tableName . '.id_origin_place = msf_origin_places.id', 'LEFT' );
			$this->db->where('first_session >= ', date("Y-m-d", $start) );
			$this->db->where('first_session < ', date("Y-m-d", strtotime( "+1 DAY", $end ) ) );

			return $this->db->get($this->tableName)->result_array( );
		}

		public function getMaxReferences( $start, $end, $category = null ) {

			$start = ( is_integer( $start ) ? $start : strtotime( $start ) );
			$end = ( is_integer( $end ) ? $end : strtotime( $end ) );

			$this->db->select( '
				COUNT(*) AS total
			' );

			$this->db->join( 'msf_patients_references', $this->tableName . '.id = msf_patients_references.id_patient', 'LEFT' );
			if( (int)$category )
				$this->db->join( 'msf_references', 'msf_patients_references.id_reference = msf_references.id AND msf_references.id_category = ' . (int)$category, 'LEFT' );

			$this->db->where('first_session >= ', date("Y-m-d", $start) );
			$this->db->where('first_session < ', date("Y-m-d", strtotime( "+1 DAY", $end ) ) );

			$this->db->group_by( $this->tableName . '.id' );
			$this->db->limit( 1 );
			$this->db->order_by( 'total DESC' );

			$result = $this->db->get($this->tableName)->row_array( );
			return $result['total'];
		}

		public function getMaxConsultsOpened( $start, $end ) {

			$start = ( is_integer( $start ) ? $start : strtotime( $start ) );
			$end = ( is_integer( $end ) ? $end : strtotime( $end ) );

			$this->db->select( '
				COUNT(*) AS total
			' );

			$this->db->join( 'msf_consults', $this->tableName . '.id = msf_consults.id_patient AND msf_consults.id_closure IS NULL ', 'LEFT' );
			$this->db->where('first_session >= ', date("Y-m-d", $start) );
			$this->db->where('first_session < ', date("Y-m-d", strtotime( "+1 DAY", $end ) ) );

			$this->db->group_by( $this->tableName . '.id' );
			$this->db->limit( 1 );
			$this->db->order_by( 'total DESC' );

			$result = $this->db->get($this->tableName)->row_array( );
			return $result['total'];
		}

		public function getMaxConsultsSymptoms( $start, $end ) {

			$start = ( is_integer( $start ) ? $start : strtotime( $start ) );
			$end = ( is_integer( $end ) ? $end : strtotime( $end ) );

			$this->db->select( '
				COUNT(*) AS total
			' );

			$this->db->join( 'msf_consults', $this->tableName . '.id = msf_consults.id_patient AND msf_consults.id_closure IS NULL ', 'LEFT' );
			$this->db->join( 'msf_consults_symptoms', 'msf_consults.id = msf_consults_symptoms.id_consult', 'LEFT' );
			$this->db->where('first_session >= ', date("Y-m-d", $start) );
			$this->db->where('first_session < ', date("Y-m-d", strtotime( "+1 DAY", $end ) ) );

			$this->db->group_by( $this->tableName . '.id' );
			$this->db->limit( 1 );
			$this->db->order_by( 'total DESC' );

			$result = $this->db->get($this->tableName)->row_array( );
			return $result['total'];
		}

		public function getMaxConsultsRisks( $start, $end ) {

			$start = ( is_integer( $start ) ? $start : strtotime( $start ) );
			$end = ( is_integer( $end ) ? $end : strtotime( $end ) );

			$this->db->select( '
				COUNT(*) AS total
			' );

			$this->db->join( 'msf_consults', $this->tableName . '.id = msf_consults.id_patient AND msf_consults.id_closure IS NULL ', 'LEFT' );
			$this->db->join( 'msf_consults_risks', 'msf_consults.id = msf_consults_risks.id_consult', 'LEFT' );
			$this->db->where('first_session >= ', date("Y-m-d", $start) );
			$this->db->where('first_session < ', date("Y-m-d", strtotime( "+1 DAY", $end ) ) );

			$this->db->group_by( $this->tableName . '.id' );
			$this->db->limit( 1 );
			$this->db->order_by( 'total DESC' );

			$result = $this->db->get($this->tableName)->row_array( );
			return $result['total'];
		}

	}