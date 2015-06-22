<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Shows users photo
 * @param string $picture Description
 * @param Array $param Array with parameters to be included in the generated img tag (height, width, id, check, nocache, title, class)
 * @return string
 */
function showPhoto( $picture = NULL, $param = NULL, $path = null, $defaultPath = null ) {

	if( !is_array( $param ) )
		$param = array();

	if( !$path )
		$path = base_url() . RES_DIR . '/user/profile/';

	if( !$defaultPath )
		$defaultPath = base_url() . RES_DIR . '/img/user.png';

	// usable parameters for photo display
	$check = (isset($param['check'])) ? $param['check'] : FALSE;
	$nocache = (isset($param['nocache'])) ? $param['nocache'] : FALSE; // TRUE = disable caching, add time string to image url

	unset( $param['check'], $param['nocache'] );

	$height = ( isset( $param['height'] ) ? (int)$param['height'] : 100 );
	$width = ( isset( $param['width'] ) ? (int)$param['width'] : 100 );
	$param['alt'] = ( isset( $param['alt'] ) ? trim( $param['alt'] ) : '' );

	if (isset($picture) && strlen(trim($picture)) > 0) {
		$remote = stristr($picture, 'http'); // do a check here to see if image is from twitter / facebook / remote URL

		if ( !$remote) {
			if ($nocache) {
				$picture = $picture .( strpos( $path, '?') === false ? '?' : '&amp;' ) . 't='.md5(time());
			} // only if $nocache is TRUE
			$path .= $picture; //.		-- disabled time attachment, no need to break cache
		}
		else {
			$path = $picture;
			// request proper cropped size from facebook for this photo
			if (stripos($path, 'graph.facebook.com')) {
				$path .= ( strpos( $path, '?') === false ? '?' : '&amp;' ) . 'width=' . $width . '&amp;height=' . $height; // this appends size requirements to facebook image
			}

			// request bigger photo from twitter
			if (stripos($path, 'twimg.com')) {
				if ( $height > 75 ) {
					$path = str_replace('_normal', '_bigger', $path); // this forces _bigger 73x73 sized image (over default 48x48), no custom crop offered
				}
				if ( $height < 25 ) {
					$path = str_replace('_normal', '_mini', $path); // this forces _mini 24x24 sized image (over default 48x48), no custom crop offered
				}
			}
		}

		if ($check && ! $remote) {
			if ( ! fileExists($path)) {
				$title = "Imagen no encontrada! ";
				$path = $defaultPath;
			}
		}
	}
	else {
		$path = $defaultPath;
	}

	$param['src'] = $path;

	$output = "<img ";
	foreach( $param as $key => $value ) {
		$output .= $key.'="'.$value.'" ';
	}
	$output .= "/>";

	return $output;

}

/**
 * Shows client's photo
 * @param string $picture Description
 * @param Array $param Array with parameters to be included in the generated img tag (height, width, id, check, nocache, title, class)
 * @return string
 */
function showClientPhoto( $picture = NULL, $param = NULL ) {
	return showPhoto( $picture, $param, base_url() . RES_DIR . '/client/profile/' );
}

/**
 * Shows brands's photo
 * @param string $picture Description
 * @param Array $param Array with parameters to be included in the generated img tag (height, width, id, check, nocache, title, class)
 * @return string
 */
function showBrandPhoto( $picture = NULL, $param = NULL ) {
	return showPhoto( $picture, $param, base_url() . RES_DIR . '/brand/profile/' );
}

/**
 * Shows source's photo
 * @param string $picture Description
 * @param Array $param Array with parameters to be included in the generated img tag (height, width, id, check, nocache, title, class)
 * @return string
 */
function showSourcePhoto( $picture = NULL, $param = NULL ) {
	return showPhoto( $picture, $param, base_url() . RES_DIR . '/source/profile/' );
}


/* Used for checking if a particular file exists (locally). Use sparingly as it is time consuming!
 *
 */
function fileExists($path)
{
	if (@fopen($path, "r") == TRUE)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}
