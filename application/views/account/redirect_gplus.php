<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang('linked_page_name')) ) ?>
</head>
<body class="clean">
<div class="container">
	<div class="container-login">
<?
	$this->load->view('common/message', array('class' => 'warning sign','content' => lang('linked_page_satement')) );

	$url = 'https://accounts.google.com/o/oauth2/auth?scope=' .
		rawurlencode( 'openid email') . '&' .
		'state=' . $this->google_lib->getState() . '&' .
		'redirect_uri=' . $this->google_lib->getRedirectURI() . '&'.
		'response_type=code&' .
		'client_id=' . $this->google_lib->getClientID() . '&' .
		'access_type=offline';
?>
		<div class="ui tertiary segment container-form">
			<div class="field">
				<a id="signinButton" href="<?= $url ?>" class="circular ui icon mini button google plus"><i class="google plus icon"></i> Log in</a>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<?= $this->load->view('common/footer'); ?>
</body>
</html>