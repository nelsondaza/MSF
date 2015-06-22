<?php
	/**
	 * Created by PhpStorm.
	 * User: nelson.daza
	 * Date: 09/12/2014
	 * Time: 02:38 PM
	 */

	require_once( APPPATH . "models/general_model.php" );

	class Sources_model extends General_model {
		protected $tableName = 'dsh_source';

		/**
		 * Returns a list with active elements
		 * @return mixed
		 */
		public function getActiveList( ) {

			$this->db->select( 'id, name, logo, base_class' );
			$this->db->where('active', 1);

			return $this->get_order_by_name( );

		}
	}