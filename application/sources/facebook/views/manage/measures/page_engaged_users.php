<?
	$this->load->view('manage/fb_app', array( 'source' => $source ) );
	$this->load->view('manage/fb_js');
?>
<script type="text/javascript">
	var FBTest = new DashFBManage({
		appId: '<?= $source->getProperty('fb_insights_field_app_id') ?>',
		scope: '<?= $source->getProperty('fb_insights_field_permissions') ?>',
		onResponse: null,
		onLogout: null,
		onLogin: function( response ) {
			var $list = $('#fb_user_data_list');
			$list.append([
				'<div class="item">',
				'<div class="content">',
				'<div class="description">',
				'<b>' + ( response.name ? response.name : response.first_name + ' ' + response.last_name ) + '</b> ',
				'(' + response.username + ') ',
				'{' + response.id + '}',
				'</div>',
				'</div>',
				'</div>',
			].join(''));
		},
		onAccounts: function( response ) {
			var $list = $('#fb_user_data_list');
			var $pages = [];

			$.each( response.data,function( index, elem ){
				$pages.push([
					'<div class="inline field"><div class="ui toggle checkbox"><input type="radio" name="page_id" value="' + elem.id + '"><label>',
					'<b>' + elem.name + '</b> ',
					'(' + elem.category + ') ',
					'{' + elem.id + '}',
					'</label></div></div>'
				].join(''));
			});

			$list.append([
				'<div class="item">',
				'<div class="content">',
				'<div class="header blue">Pages: </div>',
				'<p>',
				$pages.join(''),
				'</p>',
				'</div>',
				'</div>',
			].join(''));

			$pages = [];
			$.each( ['day', 'week', 'days_28'],function( index, elem ){
				$pages.push([
					'<div class="inline field"><div class="ui toggle checkbox"><input type="radio" name="period" value="' + elem + '"><label>',
					'<b>' + elem + '</b>',
					'</label></div></div>'
				].join(''));
			});

			$list.append([
				'<div class="item">',
				'<div class="content">',
					'<div class="header blue">Period: </div>',
					'<div class="fields">',
					$pages.join(''),
					'</div>',
				'</div>',
				'</div>',
			].join(''));

			$('#fb_connect_msg').removeClass('orange red blue green').addClass('hidden');
			$('#fb_connect_msg .header').html('<?= lang('fb_insights_connect_done_header') ?>');
			$('#fb_connect_msg p').html('<?= lang('fb_insights_connect_done_body') ?>');

			$list.find('.ui.checkbox').checkbox();

			var FBObj = null;
			$list.find('input[name="period"]').change(function(){
				$list.find('.checked input[name="page_id"]').change();
			});
			$list.find('input[name="page_id"]').change(function(){
				if( $(this).is(":checked") ) {
					var $fbRequest = $('#fbRequest');
					if( $fbRequest.length > 0 )
						$fbRequest.remove();

					$fbRequest = $('<div id="fbRequest" class="ui icon message inverted orange"><i class="notched circle loading icon"></i><div class="content"><div class="header">Just one second</div><p>Trying to connect...</p></div></div>');
					$list.append( $fbRequest );

					FBObj = null;
					for( var c = 0; c < response.data.length; c ++ ) {
						if( response.data[c].id == $(this).val( ) )
							FBObj = response.data[c];
					}

					if( FBObj ) {
						var data = {
							'page': {
								'id': FBObj.id,
								'access_token': FBObj.access_token
							},
							'period': $list.find('input[name="period"]:checked').val()
						};

						$.getJSON('<?= base_url() ?>services/source/<?= $source->getId( ) ?>/test/measure/<?= $measure['source_measure'] ?>', data, function ( response ) {
							if( FBObj.id != data.page.id )
								return;

							if( response.error && response.error.code ) {
								$fbRequest.removeClass('orange').addClass('red').html('<i class="warning circle icon"></i><div class="content"><div class="header">ERROR ' + response.error.type + ':</div><p>' + response.error.msg + '</p></div>' );
							}
							else {
								$fbRequest.removeClass('orange').addClass('blue').html('<i class="facebook icon"></i><div class="content"><div class="header">USERS ENGAGED: ' + ( response.data[0].engaged + '' ).replace(/\d(?=(\d{3})+$)/g, '$&.') + '</div><p>Today\'s total at 0 hours.</p></div>' );
							}
						});
					}
				}
			});
		}

	});

</script>