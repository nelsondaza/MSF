<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


	/**
	 * Class SourcesLoader
	 *
	 * Used as a loader for classes created inside the namespace "sources"
	 *
	 */

	class SourcesLoader {

		/**
		 * Class constructor private to avoid instantiation
		 */
		private function __constructor( ) {

		}

		/**
		 * @param string $className Class name including namespace
		 *
		 * @return bool
		 */
		public static function autoLoad( $className ) {

			if( strpos( $className, "sources\\" ) === 0 ) {
				$file = FCPATH . APPPATH . str_replace( "\\", '/', $className ) . '.php';
				if( file_exists( $file ) )
					require_once( $file );

				if( class_exists( $className ) )
					return true;

				$namespace = explode( "\\", $className );
				$name = array_pop( $namespace );
				$namespace = implode( "\\", $namespace );
				@eval( ( $namespace ? "namespace " . $namespace . ";" : "" ) . " class " . $name . " { };" );
			}

			return false;
		}
	}

	/**
	 * AutoLoad function registration
	 */
	spl_autoload_register( 'SourcesLoader::autoLoad', false, true );
