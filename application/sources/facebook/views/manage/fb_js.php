<script type="text/javascript">
	/**
	 * Configuration object
	**/

	var configDefault = {
		appId: null,        // Facebook App Id
		scope: '',          // Facebook scope required for the app
		onResponse: null,   // Function Called after Facebook LoginStatus response (If it returns a value different from false default action will be avoided)
		onLogout: null,     // Function Called after Facebook Logout
		onLogin: null,      // Function Called after Facebook Login response
		onAccounts: null    // Function Called after Facebook Accounts response
	};


	function DashFBManage( config ) {

		var user = null;
		var accounts = null;
		var self = this;

		this.getUser = function ( ) {
			return user;
		};
		this.getAccounts = function ( ) {
			return accounts;
		};

		this.logout = function ( ) {
			if( FB.getAccessToken() ) {
				FB.logout( function( ) {
					if( config.onLogout )
						config.onLogout( );
				});
			}
			else {
				if( config.onLogout )
					config.onLogout( );
			}
		};

		this.login = function ( ) {
			if( !FB.getAccessToken() ) {
				FB.login(function (response) {
					// this.authResponseChange will handle it
				}, {
					scope: config.scope,
					auth_type: 'rerequest'
				});
			}
		};

		function setResponse( response ) {

			user = null;
			accounts = null;

			if( config.onResponse && config.onResponse( response ) ) {
				if ( response.status === 'connected' )
					loadFBUserInfo( );
				return;
			}

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
			var $button = $('#fb_button_connect');

			if ( response.status === 'connected' ) {
				$('#fb_connect_msg').removeClass('orange red blue green').addClass('blue');
				$('#fb_connect_msg .header').html('<?= lang('fb_insights_connect_connected_header') ?>');
				$button.html('<i class="facebook icon"></i> Facebook Logout</div>').addClass('red');
				$button.unbind('click').click(function(){
					self.logout( );
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
					self.logout( );
				});
			}
			else { // response.status === 'unknown'
				$('#fb_connect_msg').removeClass('orange red blue green').addClass('blue');
				$('#fb_connect_msg .header').html('<?= lang('fb_insights_connect_login_header') ?>');
				$('#fb_connect_msg p').html('<?= lang('fb_insights_connect_login_body') ?>');

				self.logout( );
				$button.html('<i class="facebook icon"></i> Facebook Login</div>').removeClass('red');
				$button.unbind('click').click(function(){
					self.login( );
				});
			}
			$button.removeClass('hidden');
		}

		function loadFBUserInfo( ) {
			FB.api('/me', function (response) {
				if (response && response.id) {
					user = response;
					if( config.onLogin )
						config.onLogin(response);
					loadFBAccounts( );
				}
			});
		}

		function loadFBAccounts( ) {
			accounts = null;
			FB.api('/me/accounts', function( response ) {
				if( response && response.data && response.data ) {

					accounts = response;
					if ( config.onAccounts )
						config.onAccounts(response);

				}
			});
		}

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
				appId: config.appId,
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
			});
		};

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

	}
</script>