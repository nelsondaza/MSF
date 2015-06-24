<?php

	require_once( APPPATH . "models/general_model.php" );

	class References_model extends General_model {

		/**
		 * Returns a list with active elements
		 * @return mixed
		 */
		public function getActiveList( ) {
			$this->db->where($this->tableName . '.active', 1);

			return $this->get_order_by_name( );
		}

	}