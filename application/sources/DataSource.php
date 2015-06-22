<?php
/**
 * Created by PhpStorm.
 * User: nelson.daza
 * Date: 16/12/2014
 * Time: 04:00 PM
 */

namespace sources;


class DataSource {
	private $id = null;
	private $path = null;
	private $name = null;
	private $properties = null;

	/**
	 *
	 */
	public function __construct( ) {

		$this->id = null;
		$this->properties = new Properties( );

		$path = explode( "\\", get_class( $this ) );
		$this->name = strtolower( array_pop( $path ) );
		$this->path = APPPATH . implode('/', $path ) . '/';

		$CI =& get_instance();
		$CI->load->add_package_path( $this->path );
	}

	/**
	 * Sets the DB id for this source
	 * @param int $id
	 */
	public function setId( $id ) {
		$this->id = $id;
		$this->properties = new Properties( $id );
	}

	/**
	 * Returns the DB id of this source
	 * @return null
	 */
	public function getId( ) {
		return $this->id;
	}

	/**
	 * Returns the value related to the key parameter
	 * @param string $key
	 *
	 * @return null|string
	 */
	public function getProperty( $key ) {
		return $this->properties->get( $key );
	}

	/**
	 * Sets/Replaces the value for the key parameter
	 * @param $key
	 * @param $value
	 */
	public function setProperty( $key, $value ) {
		$this->properties->set( $key, $value );
	}

	/**
	 * Source initialization setting the DB id of the source, and loading the language (if exists)
	 * @param null $id DB id of the source
	 */
	public function init( $id = null ) {

		$CI =& get_instance();
		if( file_exists( $this->path . '/language/' . $CI->config->item('language') . '/' . $this->name . '_lang.php' ) )
			$CI->lang->load( $this->name );

		if( $id )
			$this->setId( $id );
	}

	/**
	 * Load all properties from DB
	 */
	public function loadProperties( ) {
		$this->properties->load( );
	}

	/**
	 * Send all properties to DB
	 */
	public function saveProperties( ) {
		$this->properties->save();
	}

	/**
	 * Returns the list of view for this source
	 * @return array (key,value) => (title,view) array of the views
	 */
	public function getManageTabViews( ) {
		return array( );
	}

	/**
	 * Returns the form rules for general validation, see CI Rules
	 * @return array
	 */
	public function getManageFormRules( ) {
		return array();
	}

	/**
	 * Persistence of data
	 * @return array Errors found
	 */
	public function save( ) {

		$CI =& get_instance();

		$this->loadProperties( );
		$rules = $this->getManageFormRules( );

		foreach( $rules as $rule )  {
			$this->setProperty( $rule['field'], ( $CI->input->post( $rule['field'], true ) ? $CI->input->post( $rule['field'], true ) : null ) );
		}

		$this->saveProperties();

		return array();
	}

	/**
	 * Returns the name of view for this source when adding it to a project
	 * @return string
	 */
	public function getManageProjectView( ) {
		return '';
	}


	/**
	 * Whether or not the system can activate this source, this method have to be overridden
	 * @return bool
	 */
	public function canActivate( ) {
		return false;
	}

	/**
	 * Returns the list of measures allowed for this source
	 * @return array A list of keys, each represents a measure
	 */
	public function getMeasures( ) {
		return array( );
	}

	/**
	 * Testing function for AJAX services
	 * @param $type string
	 * @param $action string
	 *
	 * @return mixed response of the called service. JSON or array to convert to JSON
	 */
	public function service_test( $type = null, $action = null ) {

		$response = array( );

		try {
			$method = 'service_test_' . $type . '_' . $action;
			if( method_exists( $this, $method ) ) {
				$response = $this->$method( $action );
			}
			else {
				$method = 'service_test_' . $type;
				if( method_exists( $this, $method ) ) {
					$response = $this->$method( );
				}
				else {
					$response['error'] = array(
						'code' => 'DS001',
						'type' => 'ServiceError',
						'msg' => sprintf( lang( 'services_action_not_found' ), $type . '::' . $action )
					);
				}
			}
		}
		catch( \Exception $ex ) {
			$response['error'] = array(
				'code' => $ex->getCode(),
				'type' => 'ServiceError',
				'msg' => $ex->getMessage()
			);
		}

		return $response;
	}

	final static public function checkClass( $className ) {
		try {
			$className = "sources\\" . $className;
			if( class_exists ( $className ) ) {
				$tmp = new $className();

				if( is_a( $tmp, "sources\\DataSource" ) )
					return true;
			}
		}
		catch( \Exception $e ) {
			;
		}
		return false;
	}
	final static public function create( $className ) {
		try {
			$className = "sources\\" . $className;
			if( class_exists ( $className ) ) {
				$tmp = new $className();

				if( is_a( $tmp, "sources\\DataSource" ) )
					return $tmp;
			}
		}
		catch( \Exception $e ) {
			;
		}
		return new self();
	}
}
