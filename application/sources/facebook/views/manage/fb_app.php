<?php

	if( !isset( $source ) )
		$source = new \sources\DataSource( );
	$source->loadProperties();
?>
<div class="ui stacked segment">
	<h3 class="ui dividing header"> Datos de la aplicación</h3>
	<div class="ui relaxed divided list">
		<div class="item">
			<div class="content">
				<div class="description">
					<b><?= lang('fb_insights_field_app_id') ?>:</b> <?= $source->getProperty('fb_insights_field_app_id') ?>
					<br>
					<b><?= lang('fb_insights_field_app_secret') ?>:</b> <?= $source->getProperty('fb_insights_field_app_secret') ?>
					<br>
					<b><?= lang('fb_insights_field_permissions') ?>:</b> <?= $source->getProperty('fb_insights_field_permissions') ?>
				</div>
			</div>
		</div>
	</div>
	<div class="ui relaxed list">
		<div class="item">
			<div class="content">
				<div id="fb_connect_msg" class="ui small orange message">
					<div class="header"><?= lang('fb_insights_connect_warning_header') ?></div>
					<p><?= lang('fb_insights_connect_warning_body') ?></p>
				</div>
				<div id="fb_button_connect" class="ui facebook button hidden"><i class="facebook icon"></i> Facebook Login</div>
				<div class="list" id="fb_user_data_list"></div>
			</div>
		</div>
	</div>
</div>
