<?php
/**
 * Created by PhpStorm.
 * User: nelson.daza
 * Date: 19/12/2014
 * Time: 10:40 AM
 */

	$this->load->view('common/form/input', array(
			'error' => null,
	        'label' => null,
	        'attributes' => array(
		        'type' => 'hidden',
		        'name' => 'fb_insights_active',
		        'id' => 'fb_insights_active',
		        'value' => $dataSource->getProperty('fb_insights_active'),
		        'placeholder' => null
	        )
		)
	);

	$this->load->view('manage/fb_app', array( 'source' => $dataSource ) );
?>
<script type="text/javascript">
	$(function(){

		window.fbAsyncInit = function () {
			// Executed when the SDK is loaded

			FB = FB || {
				init:function(){},
				getLoginStatus:function(){},
				getAccessToken:function(){},
				logout:function(){},
				login:function(){},
				Event:{
					subscribe:function(){}
				}
			};

			FB.init({
				// App ID
				appId: '<?= $dataSource->getProperty('fb_insights_field_app_id') ?>',
				// Adding a Channel File improves the performance of the javascript SDK, by addressing issues with cross-domain communication in certain browsers.
				channelUrl: 'channel.html',
				// Check the authentication status at the start up of the app
				status: true,
				// Enable cookies to allow the server to access the session
				cookie: true,
				// Parse XFBML
				xfbml: true,
				// FB API Version
				version    : 'v2.2'
			});

			FB.getLoginStatus( function( response ) {
				setResponse( response );
				FB.Event.subscribe('auth.authResponseChange', setResponse );

				//if( '<?= (int)$dataSource->getProperty('fb_insights_active', 0) ?>' == '0' )
				//	doLogout();
			});
		};

		function doLogout( ) {
			if( FB.getAccessToken() ) {
				FB.logout(function(response) {
					// this.authResponseChange will handle it
				});
			}
		}

		function doLogin( ) {
			if( !FB.getAccessToken() ) {
				FB.login(function (response) {
					// this.authResponseChange will handle it
				}, {
					scope: '<?= $dataSource->getProperty('fb_insights_field_permissions') ?>',
					auth_type: 'rerequest'
				});
			}
		}

		function setResponse( response ) {
			/*
			 {
				 status: 'connected',
				 authResponse: {
					 accessToken: '...',
					 expiresIn:'...',
					 signedRequest:'...',
					 userID:'...'
				 }
			 }

			 status specifies the login status of the person using the app. The status can be one of the following:
			 connected. The person is logged into Facebook, and has logged into your app.
			 not_authorized. The person is logged into Facebook, but has not logged into your app.
			 unknown. The person is not logged into Facebook, so you don't know if they've logged into your app.
			 authResponse is included if the status is connected and is made up of the following:
			 accessToken. Contains an access token for the person using the app.
			 expiresIn. Indicates the UNIX time when the token expires and needs to be renewed.
			 signedRequest. A signed parameter that contains information about the person using the app.
			 userID is the ID of the person using the app.
			 */

			$('#fb_user_data_list').empty();
			$('#fb_insights_active').val(0);
			var $button = $('#fb_button_connect');

			if ( response.status === 'connected' ) {
				$('#fb_connect_msg').removeClass('orange red blue green').addClass('blue');
				$('#fb_connect_msg .header').html('<?= lang('fb_insights_connect_connected_header') ?>');
				$('#fb_connect_msg p').html('<?= lang('fb_insights_connect_connected_body') ?>');

				$button.html('<i class="facebook icon"></i> Facebook Logout</div>').addClass('red');
				$button.unbind('click').click(function(){
					doLogout( );
				});

				loadFBUserInfo( );
				// The user is already logged, is possible retrieve his personal info
				// Create a session for the current user.
				// Data inside the response.authResponse object.
			}
			else if ( response.status === 'not_authorized' ) {
				$('#fb_connect_msg').removeClass('orange red blue green').addClass('red');
				$('#fb_connect_msg .header').html('<?= lang('fb_insights_connect_not_authorized_header') ?>');
				$('#fb_connect_msg p').html('<?= lang('fb_insights_connect_not_authorized_body') ?>');

				$button.html('<i class="facebook icon"></i> Facebook Logout</div>').addClass('red');
				$button.unbind('click').click(function(){
					doLogout( );
				});
			}
			else { // response.status === 'unknown'
				$('#fb_connect_msg').removeClass('orange red blue green').addClass('blue');
				$('#fb_connect_msg .header').html('<?= lang('fb_insights_connect_login_header') ?>');
				$('#fb_connect_msg p').html('<?= lang('fb_insights_connect_login_body') ?>');

				doLogout( );
				$button.html('<i class="facebook icon"></i> Facebook Login</div>').removeClass('red');
				$button.unbind('click').click(function(){
					doLogin( );
				});
			}
			$button.removeClass('hidden');
		}

		function loadFBUserInfo( ) {
			FB.api('/me', function( response ) {
				var $list = $('#fb_user_data_list').empty();
				if( response && response.id ) {
					$list.append([
						'<div class="item">',
							'<i class="top aligned right triangle icon"></i>',
							'<div class="content">',
								'<a class="header">User: </a>',
								'<div class="description">',
									'<b>' + ( response.name ? response.name : response.first_name + ' ' + response.last_name ) + '</b> ',
									'(' + response.username + ') ',
									'{' + response.id + '}',
								'</div>',
							'</div>',
						'</div>',
					].join(''));
				}
			});

			FB.api('/me/accounts', function( response ) {
				var $list = $('#fb_user_data_list');
				if( response && response.data && response.data ) {

					var $pages = [];
					$.each( response.data,function( index, elem ){
						$pages.push([
						'<b>' + elem.name + '</b> ',
						'(' + elem.category + ') ',
						'{' + elem.id + '}',
						].join(''));
					});

					$list.append([
						'<div class="item">',
							'<i class="top aligned right triangle icon"></i>',
							'<div class="content">',
								'<a class="header blue">Pages: </a>',
								'<div class="description">',
									$pages.join('<br>'),
								'</div>',
							'</div>',
						'</div>',
					].join(''));

					$('#fb_connect_msg').removeClass('orange red blue green').addClass('green');
					$('#fb_connect_msg .header').html('<?= lang('fb_insights_connect_done_header') ?>');
					$('#fb_connect_msg p').html('<?= lang('fb_insights_connect_done_body') ?>');

					$('#fb_insights_active').val(1);
				}
			});
		}

		(function(d){
			// load the Facebook javascript SDK
			var js,
				id = 'facebook-jssdk',
				ref = d.getElementsByTagName('script')[0];

			if (d.getElementById(id))
				return;

			js = d.createElement('script');
			js.id = id;
			js.async = true;
			js.src = "//connect.facebook.net/en_US/all.js";

			ref.parentNode.insertBefore(js, ref);

		}(document));

	})

</script>