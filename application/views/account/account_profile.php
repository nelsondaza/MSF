<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang('profile_page_name')) ) ?>
</head>
<body>
<?= $this->load->view('common/header') ?>
<div class="container content">
	<?= $this->load->view('common/menu', array('current' => 'account_profile') ) ?>
	<div class="sub-header"><i class="icon user"></i> <?= lang('profile_page_name') ?></div>
	<div class="section">
<?php
	$errors = array( );
	if( isset( $profile_username_error ) )
		$errors[] = $profile_username_error;
	if( form_error('profile_username') )
		$errors[] = form_error('profile_username');
	if( isset( $profile_picture_error ) )
		$errors[] = $profile_picture_error;
	if( form_error('profile_picture') )
		$errors[] = form_error('profile_picture');

	if ( !empty($errors) ) {
		$this->load->view('common/message', array('type' => 'error','class' => 'warning','content' => $errors) );
	}
	else if (isset($profile_info)) {
		$this->load->view('common/message', array('type' => 'success','class' => 'info','content' => $profile_info) );
	}
	else {
		$this->load->view('common/message', array('class' => 'unlock','content' => lang('profile_instructions')) );
	}
?>
		<div class="ui tertiary segment container-form">
<?php
	echo form_open_multipart(uri_string(), 'class="ui form ' . ( !empty($errors) ? 'error' : '' ) . '"');

	$this->load->view('common/form/input', array(
			'error' => form_error('profile_username') || isset($profile_username_error),
	        'label' => lang('profile_username'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'profile_username',
		        'id' => 'profile_username',
		        'value' => set_value('profile_username') ? set_value('profile_username') : ( isset($account->username) ? $account->username : ''),
		        'maxlength' => '24',
		        'placeholder' => lang('profile_username')
	        )
		)
	);

	$this->load->view('common/form/input', array(
			'error' => form_error('profile_picture') || isset($profile_picture_error),
	        'label' => lang('profile_picture'),
	        'image' => ( isset( $account_details->picture ) && $account_details->picture ? showPhoto( $account_details->picture ) : null ),
	        'header' => lang('profile_custom_upload_picture'),
	        'description' => (
	            isset( $account_details->picture ) && $account_details->picture
		        ? anchor('account/account_profile/index/delete', '<i class="icon trash"></i> '.lang('profile_delete_picture'), 'class="ui mini button negative"')
		        : '<br><small><i class="info icon"></i>' . lang('profile_picture_guidelines') . '></small>'
			),
	        'attributes' => array(
		        'type' => 'item_image',
		        'name' => 'account_picture_upload',
		        'id' => 'account_picture_upload',
		        'value' => set_value('profile_picture') ? set_value('profile_picture') : ( isset($account->username) ? $account->username : ''),
	        )
		)
	);

?>
			<div class="field">
				<?= form_button(array('type' => 'submit', 'class' => 'ui submit primary button small right floated', 'content' => '<i class="archive icon"></i> '.lang('profile_save'))); ?>
			</div>

			<?= form_close() ?>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
