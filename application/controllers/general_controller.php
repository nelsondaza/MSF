<?php
	/**
	 * Created by PhpStorm.
	 * User: nelson.daza
	 * Date: 09/12/2014
	 * Time: 11:55 AM
	 */

	/**
	 * @property CI_DB_active_record            $db                           This is the platform-independent base Active Record implementation class.
	 * @property CI_DB_forge                    $dbforge                      Database Utility Class
	 * @property CI_Benchmark                   $benchmark                    This class enables you to mark points and calculate the time difference between them.<br />  Memory consumption can also be displayed.
	 * @property CI_Calendar                    $calendar                     This class enables the creation of calendars
	 * @property CI_Cart                        $cart                         Shopping Cart Class
	 * @property CI_Config                      $config                       This class contains functions that enable config files to be managed
	 * @property CI_Controller                  $controller                   This class object is the super class that every library in.<br />CodeIgniter will be assigned to.
	 * @property CI_Email                       $email                        Permits email to be sent using Mail, Sendmail, or SMTP.
	 * @property CI_Encrypt                     $encrypt                      Provides two-way keyed encoding using XOR Hashing and Mcrypt
	 * @property CI_Exceptions                  $exceptions                   Exceptions Class
	 * @property CI_Form_validation             $form_validation              Form Validation Class
	 * @property CI_Ftp                         $ftp                          FTP Class
	 * @property CI_Hooks                       $hooks                        Provides a mechanism to extend the base system without hacking.
	 * @property CI_Image_lib                   $image_lib                    Image Manipulation class
	 * @property CI_Input                       $input                        Pre-processes global input data for security
	 * @property CI_Lang                        $lang                         Language Class
	 * @property CI_Loader                      $load                         Loads views and files
	 * @property CI_Log                         $log                          Logging Class
	 * property CI_Model                       $model                        CodeIgniter Model Class
	 * @property CI_Output                      $output                       Responsible for sending final output to browser
	 * @property CI_Pagination                  $pagination                   Pagination Class
	 * @property CI_Parser                      $parser                       Parses pseudo-variables contained in the specified template view,<br />replacing them with the data in the second param
	 * @property CI_Profiler                    $profiler                     This class enables you to display benchmark, query, and other data<br />in order to help with debugging and optimization.
	 * @property CI_Router                      $router                       Parses URIs and determines routing
	 * @property CI_Session                     $session                      Session Class
	 * @property CI_Sha1                        $sha1                         Provides 160 bit hashing using The Secure Hash Algorithm
	 * @property CI_Table                       $table                        HTML table generation<br />Lets you create tables manually or from database result objects, or arrays.
	 * @property CI_Trackback                   $trackback                    Trackback Sending/Receiving Class
	 * @property CI_Typography                  $typography                   Typography Class
	 * @property CI_Unit_test                   $unit_test                    Simple testing class
	 * @property CI_Upload                      $upload                       File Uploading Class
	 * @property CI_URI                         $uri                          Parses URIs and determines routing
	 * @property CI_User_agent                  $agent                        Identifies the platform, browser, robot, or mobile devise of the browsing agent
	 * @property CI_Xmlrpc                      $xmlrpc                       XML-RPC request handler class
	 * @property CI_Xmlrpcs                     $xmlrpcs                      XML-RPC server class
	 * @property CI_Zip                         $zip                          Zip Compression Class
	 * @property CI_Javascript                  $javascript                   Javascript Class
	 * @property CI_Jquery                      $jquery                       Jquery Class
	 * @property CI_Utf8                        $utf8                         Provides support for UTF-8 environments
	 * @property CI_Security                    $security                     Security Class, xss, csrf, etc...
	 *
	 * @property Authentication                 $authentication
	 * @property Authorization                  $authorization
	 *
	 * @property Account_model                  $account_model
	 * @property Account_details_model          $account_details_model
	 *
	 * @property General_model                  $model
	 *
	 */
	class GeneralController extends CI_Controller {
		protected $scope = null;
		protected $model = null;
		/**
		 * Constructor
		 */
		function __construct() {
			parent::__construct();

			$this->load->config( 'account/account' );

			$this->load->helper( array(
				                     'date',
				                     'language',
				                     'account/ssl',
				                     'url',
				                     'photo',
				                     'form',
				                     'sources'
			                     ) );

			$this->load->library( array(
				                      'account/authentication',
				                      'account/authorization',
				                      'form_validation'
			                      ) );

			$this->load->model( array(
				                    'account/account_model',
				                    'account/account_details_model'
			                    ) );
			$this->load->language( array( 'general', 'calendar' ) );

			if( $this->scope !== null ) {
				$scope = ( $this->scope ? $this->scope . '/' : '' ) . strtolower( get_class( $this ) );
				$scopeUC = ( $this->scope ? $this->scope . '/' : '' ) . get_class( $this );

				if( file_exists( APPPATH . 'config/' . $scope . '.php' ) )
					$this->load->config( $scope );
				if( file_exists( APPPATH . 'language/' . $this->config->item('language') . '/' . $scope . '_lang.php' ) )
					$this->load->language( $scope );
				if( file_exists( APPPATH . 'helpers/' . $scope . '_helper.php' ) )
					$this->load->helper( $scope );
				if( file_exists( APPPATH . 'libraries/' . $scope . '.php' ) )
					$this->load->library( $scope );
				else if( file_exists( APPPATH . 'libraries/' . $scopeUC . '.php' ) )
					$this->load->library( $scopeUC );
				if( file_exists( APPPATH . 'models/' . $scope . '_model.php' ) ) {
					$this->load->model( $scope . '_model' );
					$model = substr($scope, strrpos($scope, '/') + 1) . '_model';
					$this->model = $this->$model;
				}
			}

			$this->form_validation->set_error_delimiters('', '');
		}

		/**
		 * @param string|null $signin       Controller to redirect after user signin if it needs to
		 * @param array|null  $restrictions Key => Value Array, where  key is the permission and Value is the Controller
		 *
		 * @return array|null Array with account and account_details data or null if auth fails
		 */
		protected function auth( $signin = null, $restrictions = null ) {

			if ( ! is_array( $restrictions ) ) {
				$restrictions = array();
			}

			// Enable SSL?
			maintain_ssl( $this->config->item( "ssl_enabled" ) );

			// Redirect unauthenticated users to signin page
			if ( ! $this->authentication->is_signed_in() ) {
				redirect( 'account/sign_in/?continue=' . urlencode( base_url() . trim( ( $signin ? $signin : '' ) ) ) );

				return null;
			}

			foreach ( $restrictions as $permi => $redirect ) {
				// Redirect unauthorized users to a page
				if ( ! $this->authorization->is_permitted( $permi ) ) {
					redirect( $redirect );

					return null;
				}
			}

			// Retrieve sign in user
			return array(
				'account'         => $this->account_model->get_by_id( $this->session->userdata( 'account_id' ) ),
				'account_details' => $this->account_details_model->get_by_account_id( $this->session->userdata( 'account_id' ) )
			);
		}

		/**
		 * General magic function for simple views calling
		 *
		 * @param $name         string Function Name
		 * @param $arguments    Function Arguments ( [0] => Data array, [1] => return )
		 *
		 * @return null|string According to the action required
		 */
		function __call( $name, $arguments ) {
			$action = trim( $name, '_' );

			if ( empty( $arguments ) )
				$arguments[] = array( );
			if ( count( $arguments ) < 2 )
				$arguments[] = false;

			if ( stripos( $action, 'view' ) === 0 ) {
				$action = strtolower( trim( (string) substr( $action, 4 ), '_' ) );

				$arguments[0]['class'] = strtolower( get_class( $this ) );
				$arguments[0]['scope'] = (string)$this->scope;

				$view = (string)$this->scope . '/' . strtolower( get_class( $this ) );
				if( $action )
					$view .= '_' . strtolower( $action );

				return $this->load->view( $view, $arguments[0], $arguments[1] );
			}

			trigger_error( 'Function "' . $name . '" not defined for "' . get_class( $this ) . '".', E_USER_ERROR );

			return null;
		}
	}

