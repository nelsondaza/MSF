<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang('sign_up_page_name') ) ) ?>
</head>
<body class="clean">
<?= $this->load->view('common/header') ?>
	<div class="container">
		<div class="container-login ">
<?
	if ( !( $this->config->item("sign_up_enabled" ) ) ) {
?>
			<h3 class="inverted"><?= lang( 'sign_up_heading' ); ?></h3>
			<div class="title-sep"></div>
			<p class="ui text right">
				<div class="ui error message">
					<div class="header"><?php echo lang('sign_up_notice'); ?></div>
					<?php echo lang('sign_up_registration_disabled'); ?>
				</div>
				<br/>
				<?= lang('sign_up_already_have_account'); ?> <?= anchor( '', '<i class="sign in icon"></i> ' . lang( 'sign_up_sign_in_now' ), array( 'class' => 'ui submit button small right floated' ) ); ?>
			</p>
<?
	}
	else {
?>
			<div class="span6">
				<?php echo form_open(uri_string(), 'class="form-horizontal"'); ?>
				<h3><?php echo lang('sign_up_heading'); ?></h3>
				<div class="well">

					<div class="control-group <?php echo (form_error('sign_up_username') || isset($sign_up_username_error)) ? 'error' : ''; ?>">
						<label class="control-label" for="sign_up_username"><?php echo lang('sign_up_username'); ?></label>

						<div class="controls">
							<?php echo form_input(array('name' => 'sign_up_username', 'id' => 'sign_up_username', 'value' => set_value('sign_up_username'), 'maxlength' => '24')); ?>
							<?php if (form_error('sign_up_username') || isset($sign_up_username_error)) : ?>
								<span class="help-inline">
									<?php echo form_error('sign_up_username'); ?>
									<?php if (isset($sign_up_username_error)) : ?>
										<span class="field_error"><?php echo $sign_up_username_error; ?></span>
									<?php endif; ?>
									</span>
							<?php endif; ?>
						</div>
					</div>

					<div class="control-group <?php echo (form_error('sign_up_password')) ? 'error' : ''; ?>">
						<label class="control-label" for="sign_up_password"><?php echo lang('sign_up_password'); ?></label>

						<div class="controls">
							<?php echo form_password(array('name' => 'sign_up_password', 'id' => 'sign_up_password', 'value' => set_value('sign_up_password'))); ?>
							<?php if (form_error('sign_up_password')) : ?>
								<span class="help-inline">
									<?php echo form_error('sign_up_password'); ?>
									</span>
							<?php endif; ?>
						</div>
					</div>

					<div class="control-group <?php echo (form_error('sign_up_email') || isset($sign_up_email_error)) ? 'error' : ''; ?>">
						<label class="control-label" for="sign_up_email"><?php echo lang('sign_up_email'); ?></label>

						<div class="controls">
							<?php echo form_input(array('name' => 'sign_up_email', 'id' => 'sign_up_email', 'value' => set_value('sign_up_email'), 'maxlength' => '160')); ?>
							<?php if (form_error('sign_up_email') || isset($sign_up_email_error)) : ?>
								<span class="help-inline">
									<?php echo form_error('sign_up_email'); ?>
									<?php if (isset($sign_up_email_error)) : ?>
										<span class="field_error"><?php echo $sign_up_email_error; ?></span>
									<?php endif; ?>
									</span>
							<?php endif; ?>
						</div>
					</div>

					<?php if (isset($recaptcha)) :
						echo $recaptcha;
						if (isset($sign_up_recaptcha_error)) : ?>
							<span class="field_error"><?php echo $sign_up_recaptcha_error; ?></span>
						<?php endif; ?>
					<?php endif; ?>

					<div>
						<?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-large pull-right', 'content' => '<i class="icon-pencil"></i> '.lang('sign_up_create_my_account'))); ?>
					</div>
					<br/>

					<p><?php echo lang('sign_up_already_have_account'); ?> <?php echo anchor('account/sign_in', lang('sign_up_sign_in_now')); ?></p>
				</div>
				<?php echo form_close(); ?>
			</div>

			<h3><?php echo lang('sign_up_third_party_heading'); ?></h3>
			<div class="ui inverted segment">
<?
		foreach ($this->config->item('third_party_auth_providers') as $provider) {
			$class = strtolower( lang('connect_'.$provider) );
			echo anchor( 'account/connect_' . $provider, '<i class="' . $class . ' icon"></i> ' . lang( 'connect_' . $provider ), array( 'class' => 'circular ui icon mini button ' . $class ) );
		}
?>
			</div>
<?
	}
?>
			<div class="clearfix"></div>
		</div>
	</div>
<? $this->load->view('common/footer'); ?>
</body>
</html>
