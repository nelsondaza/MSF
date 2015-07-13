<?php

	require_once( APPPATH . "models/general_model.php" );

	class Districts_model extends General_model {

		/**
		 * Function called before do a get action
		 * @param $arguments
		 */
		public function onBeforeGet( $arguments ) {
			$this->db->select( $this->tableName . '.*,
					msf_villages.name AS village, msf_villages.id AS id_village,
					msf_cities.name AS city, msf_cities.id AS id_city
					'
			);
			$this->db->join( 'msf_villages', $this->tableName . '.id_village = msf_villages.id AND msf_villages.active = 1' );
			$this->db->join( 'msf_cities', 'msf_villages.id_city = msf_cities.id AND msf_cities.active = 1' );
			$this->db->where('msf_cities.id_region', 1);
		}
		/**
		 * Returns a list with active elements
		 * @return mixed
		 */
		public function getActiveList( ) {
			$this->db->where($this->tableName . '.active', 1);

			return $this->get_order_by_city_and_village_and_name( );
		}

	}