<?php
/**
 * Created by PhpStorm.
 * User: nelson.daza
 * Date: 28/11/2014
 * Time: 03:50 PM
 */

	if( !isset( $path ) ) {
		$path = array( );
		$uri = explode( '/', trim( $_SERVER['REQUEST_URI'], '/' ) );
		$uriPart = array_shift( $uri );
		$last = array_pop( $uri );

		foreach( $uri as $part ) {
			$uriPart .= '/' . $part;
			$path[ ucfirst( preg_replace( '/([^a-z0-9]+)/i', ' ', strtolower( $part ) ) ) ] = $uriPart;
		}
		$path[ucfirst( preg_replace( '/([^a-z0-9]+)/i', ' ', strtolower( $last ) ) )] = null;
	}

?>
<div class="ui bcrumb sticky">
<div class="breadcrumb">
	<div class="ui small breadcrumb">
		<div class="divider"> / </div>
		<a class="section" href="<?= base_url( ) ?>">Home</a>
<?php
	foreach( $path as $key => $value ) {
?>
		<div class="divider"> / </div>
<?php
		if( $value ) {
?>
		<a class="section" href="<?= base_url() . htmlentities( $value ) ?>"><?= htmlentities( $key ) ?></a>
<?php
		}
		else {
?>
		<span class="section active"><?= htmlentities( $key ) ?></span>
<?php
		}
	}
?>
	</div>
<?php
	if( isset( $attach ) )
		echo $attach;
?>
</div>
</div>