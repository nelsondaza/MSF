<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
<?= $this->load->view('common/head', array('title' => lang('sign_in_page_name'))) ?>
</head>
<body class="clean">
<?= $this->load->view('common/header') ?>
<div class="container">
	<div class="container-login">
		<?= form_open(uri_string().($this->input->get('continue') ? '/?continue='.urlencode($this->input->get('continue')) : ''), 'class="ui form ' . ( isset( $sign_in_error ) || isset( $sign_in_username_email_error ) || validation_errors() ? 'error' : '' ) . '"'); ?>
		<? if ( isset( $sign_in_error ) || isset( $sign_in_username_email_error ) || validation_errors( ) ) { ?>
			<div class="ui icon error message">
				<i class="attention circle icon"></i>
				<div class="content">
					<div class="header">Error de ingreso</div>
					<p>
<?
	$errors = array( );
	if( isset( $sign_in_error ) )
		$errors[] = $sign_in_error;
	if( isset( $sign_in_username_email_error ) )
		$errors[] = $sign_in_username_email_error;
	if( form_error('sign_in_username_email') )
		$errors[] = form_error('sign_in_username_email');
	if( form_error('sign_in_password') )
		$errors[] = form_error('sign_in_password');
	echo implode('<br>', $errors );
?>
					</p>
				</div>
			</div>
		<? } ?>
		<h3 class="inverted"><?= lang('sign_in_heading'); ?></h3>
		<div class="title-sep">INGRESA TUS DATOS</div>

		<div class="field <?= (form_error('sign_in_username_email') || isset($sign_in_username_email_error) ? 'error' : '' ) ?>">
			<label class="control-label" for="sign_in_username_email"><?= lang('sign_in_username_email'); ?></label>
			<?= form_input(array('name' => 'sign_in_username_email', 'id' => 'sign_in_username_email', 'value' => set_value('sign_in_username_email'), 'maxlength' => '24', 'placeholder' => lang('sign_in_username_email') ) ) ?>
		</div>
		<div class="field <?= form_error('sign_in_password') ? 'error' : ''; ?>">
			<label class="control-label" for="sign_in_password"><?= lang('sign_in_password'); ?></label>
			<?= form_password(array('name' => 'sign_in_password', 'id' => 'sign_in_password', 'value' => set_value('sign_in_password'), 'placeholder' => lang('sign_in_password'))); ?>
		</div>
		<div>
			<small><?= anchor('account/forgot_password', lang('sign_in_forgot_your_password'), array( 'class' => 'right floated' ) ); ?></small>
		</div>
		<div class="inline field hidden">
			<?= form_checkbox(array('name' => 'sign_in_remember', 'id' => 'sign_in_remember', 'value' => 'checked', 'checked' => $this->input->post('sign_in_remember'),)); ?>
			<label class="control-label" for="sign_in_remember"><?= lang('sign_in_remember_me'); ?></label>
		</div>

		<div class="field">
			<?= form_button(array('type' => 'submit', 'class' => 'ui submit button small right floated', 'content' => '<i class="lock icon"></i> '.lang('sign_in_sign_in'))); ?>
		</div>

		<?= form_close(); ?>
		<div class="clearfix"></div>
    </div>
</div>
<?= $this->load->view('common/footer'); ?>
</body>
</html>