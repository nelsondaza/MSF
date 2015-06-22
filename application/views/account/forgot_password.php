<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang('forgot_password_page_name'))) ?>
</head>
<body class="clean">
<?= $this->load->view('common/header') ?>
<div class="container">
	<div class="container-login">
		<?= form_open(uri_string(), 'class="ui inverted form' . ( form_error('forgot_password_username_email') || isset($forgot_password_username_email_error) ? ' error' : '' ) . '"') ?>

		<?php if (form_error('forgot_password_username_email') || isset($forgot_password_username_email_error)) { ?>
			<div class="ui icon error message">
				<i class="attention circle icon"></i>
				<div class="content">
					<div class="header">Error de cambio de contraseña</div>
					<p><?= isset( $forgot_password_username_email_error ) ? $forgot_password_username_email_error : form_error('forgot_password_username_email') ?></p>
				</div>
			</div>
		<?php } ?>

		<h3 class="inverted"><?= lang('forgot_password_page_name') ?></h3>
		<div class="title-sep"><?= lang('forgot_password_instructions') ?></div>

		<div class="field <?= (form_error('forgot_password_username_email') || isset($forgot_password_username_email_error)) ? 'error' : ''; ?>">
			<label class="control-label" for="forgot_password_username_email"><?= lang('forgot_password_username_email'); ?></label>
			<?php
				$value = set_value('forgot_password_username_email') ? set_value('forgot_password_username_email') : (isset($account) ? $account->username : '');
				$value = str_replace(array('\'', '"'), ' ', $value);
			?>
			<?= form_input(array( 'name' => 'forgot_password_username_email', 'id' => 'forgot_password_username_email', 'value' => $value, 'maxlength' => '80', 'placeholder' => lang('forgot_password_username_email') ) ) ?>
		</div>

		<div class="field">
			<?= form_button(array('type' => 'submit', 'class' => 'ui submit button small right floated', 'content' => '<i class="mail icon"></i> '.lang('forgot_password_send_instructions'))); ?>
		</div>

		<?php echo form_close(); ?>
		<div class="clearfix"></div>
    </div>
</div>
<?= $this->load->view('common/footer'); ?>
</body>
</html>