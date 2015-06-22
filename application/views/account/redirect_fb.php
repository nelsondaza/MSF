<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang('linked_page_name')) ) ?>
</head>
<body class="clean">
<div id="fb-root"></div>
<div class="container">
	<div class="container-login">
<?
	$this->load->view('common/message', array('class' => 'warning sign','content' => lang('linked_page_satement')) );
?>
		<div class="ui tertiary segment container-form">
			<div class="field">
		<?php if (!$this->facebook_lib->user) : ?>
			<fb:login-button></fb:login-button>
		<?php endif; ?>
			</div>
		</div>
		<script>
			window.fbAsyncInit = function() {
				FB.init({
					appId: '<?= $this->facebook_lib->fb->getAppID() ?>',
					cookie: true,
					xfbml: true,
					oauth: true
				});
				FB.Event.subscribe('auth.login', function(response) {
					window.location.reload();
				});
				FB.Event.subscribe('auth.logout', function(response) {
					window.location.reload();
				});
			};
			(function() {
				var e = document.createElement('script'); e.async = true;
				e.src = document.location.protocol +
				'//connect.facebook.net/en_US/all.js';
				document.getElementById('fb-root').appendChild(e);
			}());
		</script>
		<div class="clearfix"></div>
	</div>
</div>
<?= $this->load->view('common/footer'); ?>
</body>
</html>
