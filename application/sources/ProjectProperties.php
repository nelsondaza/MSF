<?php
/**
 * Created by PhpStorm.
 * User: nelson.daza
 * Date: 18/12/2014
 * Time: 11:19 AM
 */

namespace sources;

require_once( APPPATH . "models/manage/projects_sources_properties_model.php" );

class ProjectProperties extends Properties {
	protected $idProject;

	public function __construct( $idDataSource = null, $idProject = null ) {
		$this->idDataSource = $idDataSource;
		$this->idProject = $idProject;
		$this->model = new \Projects_sources_properties_model();
		$this->properties = array( );
	}

	public function load( ) {
		if( $this->idDataSource && $this->idProject ) {
			$props = $this->model->get_by_id_source_and_id_project( $this->idDataSource, $this->idProject );
			$this->properties = array( );
			foreach( $props as $prop )  {
				$this->set( $prop['name'], $prop['value'] );
			}
		}
	}

	public function save( ) {
		if( $this->idDataSource ) {
			$props = $this->model->get_by_id_source_and_id_project( $this->idDataSource, $this->idProject );
			foreach( $props as $prop ) {
				if( !isset( $this->properties[$prop['name']] ) )
					$this->model->delete_by_id_source_and_id_project_and_name( $this->idDataSource, $this->idProject, $prop['name'] );
			}

			foreach( $this->properties as $key => $value )  {
				$this->model->replace_by_id_source_and_id_project_and_name( $this->idDataSource, $this->idProject, $key, array(
					'id_source' => $this->idDataSource,
					'id_project' => $this->idProject,
					'name' => $key,
					'value' => $value
				));
			}
		}
	}

	public function erase( ) {
		if( $this->idDataSource && $this->idProject )
			$this->model->delete_by_id_source_and_id_project( $this->idDataSource, $this->idProject );
	}

}