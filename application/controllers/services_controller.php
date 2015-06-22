<?php

	require_once( APPPATH . "controllers/general_controller.php" );

	class ServicesController extends GeneralController {
		/**
		 * @todo-nelson Add paging to the services response
		 * General error sent to client with at least 3 indexes:
		 * data['data'] => An array/vector (recommended) with all data sent to client
		 *      data['data']['0'] => Object 1 ...
		 * data['paging'] => An object with the description of the paging system (working on it)
		 * data['error'] => An object with the description of the error sent to the client it also needs 3 keys:
		 *      data['error']['code'] => Error's numeric/string representation
		 *      data['error']['type'] => Error's type as a string, like a Scope
		 *      data['error']['msg'] => Error's human description
		 * @var array|null
		 */
		protected $data = null;
		/**
		 * Constructor
		 */
		function __construct() {
			parent::__construct();

			$this->data = array(
				'error' => null,
			    'data' => null,
			    'paging' => null,
				'action' => null
			);
			$this->load->language( array( 'services' ) );
		}

		/**
		 * @param array|null  $restrictions Key => Value Array, where  key is the permission and Value is the Controller
		 *
		 * @return array|null Array with account and account_details data or null if auth fails
		 */
		protected function auth( $restrictions = null ) {

			if ( !$this->authentication->is_signed_in() ) {
				$this->data['error'] = array(
					'code' => 1,
					'type' => 'AuthError',
					'msg' => lang( 'services_auth_error' )
				);
				$this->shapeResponse();
				exit( 0 );
			}

			return parent::auth( null, $restrictions );
		}

		/**
		 * Sent the service response (data) to the client as an verified object adding headers
		 * Currently supported responses: JSON
		 * Currently supported requests: JSON & JSONP
		 * IMPORTANT: It does not ends the PHP process
		 */
		protected final function shapeResponse( ) {

			if( $this->data ) {
				if( !isset( $this->data['error'] ) ) {
					if( !isset( $this->data['data'] ) ) {
						$this->data = array(
							'data' => $this->data
						);
					}
					$this->data['error'] = null;
				}
				else {
					if( !isset( $this->data['data'] ) )
						$this->data['data'] = null;
				}
			}
			else {
				$this->data = array(
					'error' => null,
					'data' => null,
					'paging' => null,
					'action' => null
				);
			}

			if( !isset( $this->data['paging'] ) )
				$this->data['paging'] = null;
			if( isset( $this->data['data']['action'] ) && is_string( $this->data['data']['action'] ) )
				$this->data['action'] = $this->data['data']['action'];

			if ( $this->input->get( 'callback', true ) ) {

				$this->output->set_content_type('text/javascript; charset=utf8');
				$this->output->set_header('Access-Control-Max-Age: 3628800', true);
				$this->output->set_header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE', true);
				$this->output->set_output($this->input->get( 'callback', true ) . '(' . json_encode( $this->data, JSON_UNESCAPED_UNICODE ) . ');');
			} else {
				// normal JSON string
				$this->output->set_content_type('application/json; charset=utf8');
				$this->output->set_output(json_encode( $this->data, JSON_UNESCAPED_UNICODE ));
			}

		}

		/**
		 * General magic function for simple views calling
		 * It is different from de default as the response es turned into an object that will be sent using $this->shapeResponse
		 *
		 * @param $name         string Function Name
		 * @param $arguments    Function's Arguments ( [0] => Data array, [1] => return )
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

				$response = $this->load->view( $view, $arguments[0], true );

				try {
					if( !is_array( $response ) )
						$this->data = @json_decode( $response );
				}
				catch( Exception $e ) {
					$this->data = null;
				}

				$this->shapeResponse();
			}

			trigger_error( 'Function "' . $name . '" not defined for "' . get_class( $this ) . '".', E_USER_ERROR );

			return null;
		}
	}

