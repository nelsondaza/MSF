<?php

	/**
	 * Created by PhpStorm.
	 * User: nelson.daza
	 * Date: 09/12/2014
	 * Time: 02:07 PM
	 */

	/**
	 * @property CI_DB_active_record|CI_DB_mysql_driver $db                           This is the platform-independent base Active Record implementation class.
	 * @property CI_DB_forge                            $dbforge                      Database Utility Class
	 * @property CI_Benchmark                           $benchmark                    This class enables you to mark points and calculate the time difference between them.<br />  Memory consumption can also be displayed.
	 * @property CI_Calendar                            $calendar                     This class enables the creation of calendars
	 * @property CI_Cart                                $cart                         Shopping Cart Class
	 * @property CI_Config                              $config                       This class contains functions that enable config files to be managed
	 * @property CI_Controller                          $controller                   This class object is the super class that every library in.<br />CodeIgniter will be assigned to.
	 * @property CI_Email                               $email                        Permits email to be sent using Mail, Sendmail, or SMTP.
	 * @property CI_Encrypt                             $encrypt                      Provides two-way keyed encoding using XOR Hashing and Mcrypt
	 * @property CI_Exceptions                          $exceptions                   Exceptions Class
	 * @property CI_Form_validation                     $form_validation              Form Validation Class
	 * @property CI_Ftp                                 $ftp                          FTP Class
	 * @property CI_Hooks                               $hooks                        Provides a mechanism to extend the base system without hacking.
	 * @property CI_Image_lib                           $image_lib                    Image Manipulation class
	 * @property CI_Input                               $input                        Pre-processes global input data for security
	 * @property CI_Lang                                $lang                         Language Class
	 * @property CI_Loader                              $load                         Loads views and files
	 * @property CI_Log                                 $log                          Logging Class
	 * @property CI_Model                               $model                        CodeIgniter Model Class
	 * @property CI_Output                              $output                       Responsible for sending final output to browser
	 * @property CI_Pagination                          $pagination                   Pagination Class
	 * @property CI_Parser                              $parser                       Parses pseudo-variables contained in the specified template view,<br />replacing them with the data in the second param
	 * @property CI_Profiler                            $profiler                     This class enables you to display benchmark, query, and other data<br />in order to help with debugging and optimization.
	 * @property CI_Router                              $router                       Parses URIs and determines routing
	 * @property CI_Session                             $session                      Session Class
	 * @property CI_Sha1                                $sha1                         Provides 160 bit hashing using The Secure Hash Algorithm
	 * @property CI_Table                               $table                        HTML table generation<br />Lets you create tables manually or from database result objects, or arrays.
	 * @property CI_Trackback                           $trackback                    Trackback Sending/Receiving Class
	 * @property CI_Typography                          $typography                   Typography Class
	 * @property CI_Unit_test                           $unit_test                    Simple testing class
	 * @property CI_Upload                              $upload                       File Uploading Class
	 * @property CI_URI                                 $uri                          Parses URIs and determines routing
	 * @property CI_User_agent                          $user_agent                   Identifies the platform, browser, robot, or mobile devise of the browsing agent
	 * @property CI_Xmlrpc                              $xmlrpc                       XML-RPC request handler class
	 * @property CI_Xmlrpcs                             $xmlrpcs                      XML-RPC server class
	 * @property CI_Zip                                 $zip                          Zip Compression Class
	 * @property CI_Javascript                          $javascript                   Javascript Class
	 * @property CI_Jquery                              $jquery                       Jquery Class
	 * @property CI_Utf8                                $utf8                         Provides support for UTF-8 environments
	 * @property CI_Security                            $security                     Security Class, xss, csrf, etc...
	 *
	 * @property string                                 $tableName
	 *
	 */
	class General_model extends CI_Model {
		protected $tableName = null;

		public function __construct() {
			if( !$this->tableName )
				$this->tableName = 'msf_'. strtolower( str_replace( '_model', '', get_class( $this ) ) );
		}
		/**
		 * This function is for internal use only
		 *
		 * @param $field String Field to filter in
		 * @param $value String Value to filter for
		 *
		 * @return string field name without the filter part
		 */
		private function setCondition( $field, $value ) {
			if ( preg_match( '/(\_not\_like)$/i', $field ) ) {
				$field = preg_replace( '/(\_not\_like)$/i', '', $field );
				$this->db->not_like( $this->tableName . '.' . $field, $value );
			} else if ( preg_match( '/(\_like)$/i', $field ) ) {
				$field = preg_replace( '/(\_like)$/i', '', $field );
				$this->db->like( $this->tableName . '.' . $field, $value );
			} else if ( preg_match( '/(\_not\_in)$/i', $field ) ) {
				$field = preg_replace( '/(\_not\_in)$/i', '', $field );
				$this->db->where_in( $this->tableName . '.' . $field, $value );
			} else if ( preg_match( '/(\_in)$/i', $field ) ) {
				$field = preg_replace( '/(\_in)$/i', '', $field );
				$this->db->where_not_in( $this->tableName . '.' . $field, $value );
			} else {
				$this->db->where( $this->tableName . '.' . $field, $value );
			}

			return $field;
		}

		private function setOrder( $fields ) {

			$fields = preg_replace( '/^(order\_by\_|by_)/i', '', $fields );

			$parts = explode( '_and_', $fields );
			$matches = array();

			foreach( $parts as $index => $field ) {
				$dir = 'ASC';

				if( preg_match( '/(.*)(\_)(asc|desc)$/i', $field, $matches ) ) {
					$field = $matches[1];
					$dir = ( strtolower( $matches[3] ) == 'desc' ? 'DESC' : 'ASC' );
				}
				$this->db->order_by( $field, $dir );
			}

		}

		private function setConditions( $string, $values ) {

			$conditions = $string;
			$sorters = '';
			$matches = array();

			if( preg_match( '/(.*)(order\_by\_)(.*)/i', $string, $matches ) )    {
				$conditions = trim( $matches[1], '_' );
				$sorters = $matches[3];
			}

			if ( stripos( $conditions, 'by_' ) === 0 )
				$conditions = (string) substr( $conditions, 3 );

			$parts = ( $conditions ? explode( '_and_', $conditions ) : array( ) );

			foreach( $parts as $index => $field ) {
				$field = preg_replace( '/(\_asc|\_desc)$/i', '', $field );
				if( !empty( $values ) )
					$this->setCondition( $field, array_shift( $values ) );
			}

			if( $sorters )
				$this->setOrder( $sorters );

		}

		/**
		 * General magic function for simple purposes like: list(get), count and update
		 *
		 * Examples:
		 * var_dump( $this->model->getAll() );
		 * var_dump( $this->model->get_order_by_name() );
		 * var_dump( $this->model->get_order_by_name_asc() );
		 * var_dump( $this->model->get_order_by_name_desc() );
		 * var_dump( $this->model->get_order_by_description() );
		 * var_dump( $this->model->get_order_by_description_asc() );
		 * var_dump( $this->model->get_order_by_description_desc() );
		 * var_dump( $this->model->get_by_key( 'admi' ) );
		 * var_dump( $this->model->get_by_key_like( 'admi' ) );
		 * var_dump( $this->model->get_by_key_not_LIKE_desc( 'dev' ) );
		 * var_dump( $this->model->get_by_id( 12 ) );
		 * var_dump( $this->model->get_by_key_like( 'update' ) );
		 * var_dump( $this->model->get_one_by_key_like( 'update' ) );
		 * var_dump( $this->model->count( ) );
		 * var_dump( $this->model->count_by_key_not_like( 'update' ) );
		 *
		 * var_dump( $this->model->get_by_id( 17 ) );
		 * var_dump( $this->model->update_by_id( 17, array( 'suspendedon' => null) ) );
		 * var_dump( $this->model->get_by_key( 'admi' ) );
		 *
		 * @param $name         string Function Name
		 * @param $arguments    array Function Arguments
		 *
		 * @return null|int|string|array According to the action required
		 */
		function __call( $name, $arguments ) {
			$action = trim( $name, '_' );

			if ( empty( $arguments ) ) {
				$arguments[0] = '';
			}

			if ( stripos( $action, 'get' ) === 0 ) {
				$action = trim( (string) substr( $action, 3 ), '_' );

				$singleResult = ( stripos( $action, 'one_' ) === 0 );
				if ( $singleResult ) {
					$action = (string) substr( $action, 4 );
					$this->db->limit( 1 );
				}

				$this->setConditions( $action, $arguments );

				$this->onBeforeGet( $action, $arguments );

				if( $singleResult )
					return $this->db->get( $this->tableName )->row_array( );

				return $this->db->get( $this->tableName )->result_array();

			}

			if ( stripos( $action, 'count' ) === 0 ) {
				$action = trim( (string) substr( $action, 5 ), '_' );

				$this->setConditions( $action, $arguments );

				return $this->db->count_all_results( $this->tableName );
			}

			if ( stripos( $action, 'insert_batch' ) === 0 ) {
				$this->db->insert_batch( $this->tableName, array_pop( $arguments ) );
				return $this->db->affected_rows( );
			}
			if ( stripos( $action, 'insert' ) === 0 ) {
				$this->db->insert( $this->tableName, array_pop( $arguments ) );
				return $this->db->insert_id( );
			}

			if ( stripos( $action, 'update' ) === 0 ) {
				$action = trim( (string) substr( $action, 6 ), '_' );

				if ( stripos( $action, 'by_' ) === 0 ) {
					$this->setConditions( $action, $arguments );

					$this->db->update( $this->tableName, array_pop( $arguments ) );
					return $this->db->affected_rows();
				}
				else {
					trigger_error( 'No condition set for update "' . get_class( $this ) . '".', E_USER_ERROR );
				}

				return 0;
			}

			if ( stripos( $action, 'replace' ) === 0 ) {
				$action = trim( (string) substr( $action, 7 ), '_' );

				$this->setConditions( $action, $arguments );

				if ( stripos( $action, 'by_' ) === 0 ) {
					if( $this->db->count_all_results( $this->tableName ) > 0 ) {
						$this->setConditions( $action, $arguments );
						$this->db->update( $this->tableName, array_pop( $arguments ) );
					}
					else {
						$this->setConditions( $action, $arguments );
						$this->db->insert( $this->tableName, array_pop( $arguments ) );
					}
				}
				else
					$this->db->replace( $this->tableName, array_pop( $arguments ) );

				return $this->db->affected_rows( );
			}

			if ( stripos( $action, 'delete' ) === 0 ) {
				$action = trim( (string) substr( $action, 6 ), '_' );

				if ( ( stripos( $action, 'one_' ) === 0 ) ) {
					$action = (string) substr( $action, 4 );
					$this->db->limit( 1 );
				}

				$this->setConditions( $action, $arguments );

				$this->db->delete( $this->tableName );

				return $this->db->affected_rows( );

			}

			trigger_error( 'Function "' . $name . '" not defined for "' . get_class( $this ) . '".', E_USER_ERROR );

			return null;
		}

		/**
		 * Function called before do a get action
		 * @param $arguments
		 */
		public function onBeforeGet( $arguments ) {

		}

		/**
		 * Returns actual table name
		 * @return string
		 */
		public function getTableName() {
			return $this->tableName;
		}

	}