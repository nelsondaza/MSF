<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang('password_page_name')) ) ?>
</head>
<body>
<?= $this->load->view('common/header', array('current' => 'account_password')) ?>
<div class="container content">
	<div class="sub-header"><i class="icon lock"></i> <?= lang('password_page_name') ?></div>
	<div class="section">
<?php
	$errors = array( );
	if( form_error('password_new_password') )
		$errors[] = form_error('password_new_password');
	if( form_error('password_retype_new_password') )
		$errors[] = form_error('password_retype_new_password');

	if ( !empty($errors) ) {
		$this->load->view('common/message', array('type' => 'error','class' => 'warning','content' => $errors) );
	}
    else if( $this->session->flashdata('password_info') ) {
        $this->load->view('common/message', array('type' => 'success','class' => 'info','content' => $this->session->flashdata( 'password_info' )) );
    }
	else {
		$this->load->view('common/message', array('class' => 'warning sign','content' => lang('password_safe_guard_your_account')) );
	}
?>
		<div class="ui tertiary segment container-form">
<?php
	echo form_open(uri_string(), 'class="ui form ' . ( !empty($errors) ? 'error' : '' ) . '"');

	$this->load->view('common/form/input', array(
			'error' => form_error('password_new_password'),
	        'label' => lang('password_new_password'),
	        'attributes' => array(
		        'type' => 'password',
		        'name' => 'password_new_password',
		        'id' => 'password_new_password',
		        'value' => set_value('password_new_password'),
		        'autocomplete' => 'off',
		        'placeholder' => lang('password_new_password')
	        )
		)
	);

	$this->load->view('common/form/input', array(
			'error' => form_error('password_retype_new_password'),
	        'label' => lang('password_retype_new_password'),
	        'attributes' => array(
		        'type' => 'password',
		        'name' => 'password_retype_new_password',
		        'id' => 'password_retype_new_password',
		        'value' => set_value('password_retype_new_password'),
		        'autocomplete' => 'off',
		        'placeholder' => lang('password_retype_new_password')
	        )
		)
	);

?>
			<div class="field">
				<?= form_button(array('type' => 'submit', 'class' => 'ui submit primary button small right floated', 'content' => '<i class="archive icon"></i> '.lang('password_change_my_password'))); ?>
			</div>

			<?= form_close() ?>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
