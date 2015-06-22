<?php

	if( !isset( $dataSource ) )
		$dataSource = new \sources\DataSource( );
	$dataSource->loadProperties();

	$projectProperties = new \sources\ProjectProperties( $dataSource->getId( ), $project['id'] );
	$projectProperties->load( );

	if( $projectProperties->get('id_page') ) {
?>
<div class="ui secondary segment" id="actual_data">
	<div class="ui items">
		<div class="item">
			<div class="ui tiny image">
				<img src="https://graph.facebook.com/<?= $projectProperties->get('id_page') ?>/picture?type=square"/>
			</div>
			<div class="content">
				<div class="header"><?= $projectProperties->get('name') ?></div>
				<div class="description">
					<p><?= $projectProperties->get('category') ?></p>
					<a class="ui button mini facebook" href="<?= $projectProperties->get('link') ?>" target="_blank"><i class="icon facebook"></i> Perfil en Facebook</a>
					<a class="ui button mini purple <?= ( !$projectProperties->get('website') ? 'hidden' : '' ) ?>" href="<?= $projectProperties->get('website') ?>" target="_blank"><i class="icon file"></i> Sitio Web</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?
	}
?>
<div class="ui two column grid" id="stepsHolder">
	<div class="column">
		<div class="ui fluid vertical steps">
			<div class="active step">
				<i class="crosshairs icon"></i>
				<div class="content">
					<div class="title">Datos de la fuente</div>
					<div class="description">
						<b><?= lang('fb_insights_field_app_id') ?>:</b> <?= $dataSource->getProperty('fb_insights_field_app_id') ?>
						<br>
						<b><?= lang('fb_insights_field_permissions') ?>:</b> <?= $dataSource->getProperty('fb_insights_field_permissions') ?>
					</div>
				</div>
			</div>
			<div class="disabled step">
				<i class="facebook icon"></i>
				<div class="content">
					<div class="title">Conexión con Facebook</div>
					<div class="description">
						<b>Usuario:</b>
						<br>
						<b>ID:</b>
					</div>
				</div>
			</div>
			<div class="disabled step">
				<i class="file icon"></i>
				<div class="content">
					<div class="title">Selección de página</div>
					<div class="description">
						<b>Página:</b>
						<br>
						<b>ID:</b>
					</div>
				</div>
			</div>
			<div class="disabled step">
				<i class="facebook icon"></i>
				<div class="content">
					<div class="title">Acceso permanente</div>
					<div class="description">
						<br>
						<br>
						<br>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="column">
		<div class="content active">
			<div class="ui small orange message">
				<div class="header">Verficando datos de fuente...</div>
				<p>
					Si el proceso toma demasiado tiempo puede verificar los datos de la fuente: <a href="<?= base_url() ?>manage/sources/save/<?= $dataSource->getId( ) ?>"><?= $source['name'] ?> &raquo;</a>
				</p>
			</div>
		</div>
		<div class="content hidden">
			<div class="ui small blue message">
				<div class="header">Es necesario un usuario con permisos</div>
				<p>
					<div id="fb_button_connect" class="ui facebook button"><i class="facebook icon"></i> Facebook Login</div>
				</p>
			</div>
		</div>
		<div class="content hidden">
			<div class="ui small white message">
				<div class="header">Páginas encontradas</div>
				<p>
					<div class="list" id="fb_pages_list"></div>
				</p>
			</div>
		</div>
		<div class="content hidden">
			<div class="ui icon message inverted orange">
				<i class="notched circle loading icon"></i>
				<div class="content">
					<div class="header">Acceso permanente</div>
					<p>Conectando...</p>
				</div>
			</div>
		</div>
	</div>
</div>
<?
	$this->load->view('manage/fb_js');
?>
<script type="text/javascript">
	$(function(){

		var $stepsHolder = $('#stepsHolder');
		var actualStep = 0;
		var data = {};

		function setStep( step ) {
			if( step != actualStep ) {

				var $step = $stepsHolder.find( '.column>.steps>.step:eq(' + step + ')');
				$step.prevAll('.step').removeClass('active disabled').addClass('completed');
				$step.nextAll('.step').removeClass('active competed').addClass('disabled');
				$step.removeClass('completed disabled').addClass('active');

				var $content = $stepsHolder.find( '.column>.content:eq(' + step + ')');
				$content.siblings('.content').removeClass('active').addClass('hidden');
				$content.removeClass('hidden').addClass('active');

				actualStep = step;
			}
		}

		var FBTest = new DashFBManage({
			appId: '<?= $dataSource->getProperty('fb_insights_field_app_id') ?>',
			scope: '<?= $dataSource->getProperty('fb_insights_field_permissions') ?>',
			onResponse: function ( response ) {

				data = {
					dataSource: <?= $dataSource->getId( ) ?>,
					project: <?= $project['id'] ?>
				};

				// The app config is right, then the step 1 is done
				setStep( 1 );
				if( response.status == 'connected' )
					data.authResponse = response.authResponse
				else
					this.onLogout();

				return true;
			},
			onLogout: function( ) {
				setStep( 1 );

				var $button = $('#fb_button_connect');
				$button.html('<i class="facebook icon"></i> Facebook Login</div>').removeClass('red').addClass('blue');
				$button.unbind('click').click(function(){
					FBTest.login( );
				});

				$stepsHolder.find( '.column>.steps>.step:eq(1) .description').html([
					'<b>Usuario:</b>',
					'<br>',
					'<b>ID:</b>'
				].join(''));

			},
			onLogin: function( response ) {

				setStep( 2 );

				var $button = $('#fb_button_connect');
				$button.html('<i class="facebook icon"></i> Facebook Logout</div>').removeClass('blue').addClass('red');
				$button.unbind('click').click(function(){
					FBTest.logout( );
				});

				data.user = response;
				data.user.name = ( response.name ? response.name : response.first_name + ' ' + response.last_name );

				$stepsHolder.find( '.column>.steps>.step:eq(1) .description').html([
					'<b>Usuario:</b> ' + data.user.name,
					'<br>',
					'<b>ID:</b> ' + data.user.id + ' <i class="close icon red button"></i>'
				].join(''));
				$stepsHolder.find( '.column>.steps>.step:eq(1) .description .button').click(function(){
					FBTest.logout( );
				});
			},
			onAccounts: function( response ) {

				var $list = $('#fb_pages_list').empty();
				var $pages = [];

				$.each( response.data,function( index, elem ){
					$pages.push([
						'<div class="inline field"><div class="ui toggle checkbox"><input type="radio" name="page_id" value="' + elem.id + '"><label>',
						'<b>' + elem.name + '</b> ',
						'(' + elem.category + ') ',
						'</label></div></div>'
					].join(''));
				});

				$list.append( $pages.join('') );
				$list.find('.ui.checkbox').checkbox();

				$list.find('input[name="page_id"]').change(function(){
					if( $(this).is(":checked") ) {

						var index = $list.find('input[name="page_id"]').index( $(this) );
						data.page = response.data[index];

						$stepsHolder.find( '.column>.steps>.step:eq(2) .description').html([
							'<b>Página:</b> ' + data.page.name + ' {' + data.page.category + '}',
							'<br>',
							'<b>ID:</b> ' + data.page.id + ' <i class="close icon red button"></i>'
						].join(''));

						$stepsHolder.find( '.column>.steps>.step:eq(2) .description .button').click(function(){
							setStep( 2 );
							$stepsHolder.find( '.column>.steps>.step:eq(2) .description').html([
								'<b>Página:</b>',
								'<br>',
								'<b>ID:</b>'
							].join(''));
						});

						$stepsHolder.find( '.column>.content:eq(3)').html('<div class="ui icon message inverted orange"><i class="notched circle loading icon"></i><div class="content"><div class="header">Permisos a largo plazo</div><p>Conectando...</p></div></div>');

						setStep( 3 );

						$.getJSON('<?= base_url() ?>services/source/<?= $dataSource->getId( ) ?>/access/extend/', data, function ( response ) {
							var page = response.data[0];

							if( page.id != data.page.id )
								return;

							if( response.error && response.error.code ) {
								$stepsHolder.find( '.column>.content:eq(3)').html('<div class="ui icon message inverted red"><i class="warning circle icon"></i><div class="content"><div class="header">ERROR ' + response.error.type + ':</div><p>' + response.error.msg + '</p></div></div>');
							}
							else {
								$stepsHolder.find( '.column>.content:eq(3)').html('<div class="ui icon small message"><div class="content"><div class="header">' + ( page.cover ? '<img src="' + page.cover + '" style="width:100%" /><br/><br/>' : '' ) + page.name + '</div><p>' + page.about + '</p></div></div>');
								$stepsHolder.find( '.column>.content:eq(3)').append('<div class="ui icon small positive message"><i class="checkmark icon"></i><div class="content"><div class="header">Acceso almacenado.</div><p>Los datos de uso de Facebook para este proyecto se han actualizado.</p></div></div>');

								var $step = $stepsHolder.find( '.column>.steps>.step');
								$step.removeClass('active disabled').addClass('completed');

								$('#actual_data').find('img:first').attr('src', 'https://graph.facebook.com/' + page.id + '/picture?type=square');
								$('#actual_data').find('.header:first').html( page.name );
								$('#actual_data').find('.description>p:first').html( page.category );

								$('#actual_data').find('a.button.facebook:first').toggleClass( 'hidden', !page.link ).attr('href', page.link);
								$('#actual_data').find('a.button.purple:first').toggleClass( 'hidden', !page.website ).attr('href', page.website);

							}
						});
					}
				});
			}
		});
	});
</script>