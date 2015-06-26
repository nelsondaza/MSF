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

	if( !isset( $icon ) )
		$icon = '';

?>
<div class="ui icon <?= $type ?> small message"><?= ( $icon ? '<i class="' . $icon . ' icon"></i>' : '' ) ?> <div class="content"><?= ( is_array( $content ) ? implode( '<br>', $content ) : $content ) ?></div></div>
