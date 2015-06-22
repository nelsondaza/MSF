<?php
/**
 * Created by PhpStorm.
 * User: nelson.daza
 * Date: 18/12/2014
 * Time: 11:19 AM
 */

namespace sources;

require_once( APPPATH . "models/manage/sources_properties_model.php" );

class Properties {
	protected $model;
	protected $idDataSource;
	protected $properties;

	public function __construct( $idDataSource = null ) {
		$this->idDataSource = $idDataSource;
		$this->model = new \Sources_properties_model();
		$this->properties = array( );
	}

	public function load( ) {
		if( $this->idDataSource ) {
			$props = $this->model->get_by_id_source( $this->idDataSource );
			$this->properties = array( );
			foreach( $props as $prop )  {
				$this->set( $prop['name'], $prop['value'] );
			}
		}
	}

	public function save( ) {
		if( $this->idDataSource ) {
			$props = $this->model->get_by_id_source( $this->idDataSource );
			foreach( $props as $prop ) {
				if( !isset( $this->properties[$prop['name']] ) )
					$this->model->delete_by_id_source_and_name( $this->idDataSource, $prop['name'] );
			}

			foreach( $this->properties as $key => $value )  {
				$this->model->replace_by_id_source_and_name( $this->idDataSource, $key, array(
					'id_source' => $this->idDataSource,
					'name' => $key,
					'value' => $value
				));
			}
		}
	}

	public function erase( ) {
		if( $this->idDataSource )
			$this->model->delete_by_id_source( $this->idDataSource );
	}

	public function get( $key, $defaultValue = null ) {
		if( isset( $this->properties[$key] ) )
			$defaultValue = $this->properties[$key];

		return $defaultValue;
	}

	public function getAll( ) {
		return array_merge( array( ), ( is_array( $this->properties ) ? $this->properties : array( ) ) );
	}

	public function set( $key, $value = null ) {
		$array = ( is_array( $key ) ? $key : array( $key => $value ) );
		foreach( $array as $key => $value )   {
			if( $value === null )
				$this->remove( $key );
			else
				$this->properties[$key] = $value;
		}
	}

	public function remove( $key ) {
		$value = $this->get( $key );
		unset( $this->properties[$key] );
		return $value;
	}

	public function removeAll( ) {
		$this->properties = array( );
	}

}