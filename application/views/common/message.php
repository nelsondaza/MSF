<?php
/**
 * Created by PhpStorm.
 * User: nelson.daza
 * Date: 28/11/2014
 * Time: 03:50 PM
 */

	if( !isset( $content ) )
		$content = '';

	if( !isset( $type ) )
		$type = '';

	if( !isset( $class ) )
		$class = '';

?>
<div class="ui icon <?= $type ?> small message"><?= ( $class ? '<i class="' . $class . ' icon"></i>' : '' ) ?> <div class="content"><?= ( is_array( $content ) ? implode( '<br>', $content ) : $content ) ?></div></div>
