<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang('linked_page_name'))) ?>
</head>
<body class="clean">
<?= $this->load->view('common/header') ?>
<div class="container">
	<div class="container-login">
<?
	$errors = array( );
	if (isset($connect_openid_error))
		$errors[] = $connect_openid_error;
	if ($this->session->flashdata('connect_openid_error'))
		$errors[] = $this->session->flashdata('connect_openid_error');

	if ( !empty($errors) ) {
		$this->load->view('common/message', array('type' => 'error','class' => 'warning','content' => $errors) );
	}
	else if (isset($profile_info)) {
		$this->load->view('common/message', array('type' => 'success','class' => 'info','content' => $profile_info) );
	}
	else {
		$this->load->view('common/message', array('class' => 'unlock','content' => sprintf(lang('connect_with_x'), lang('connect_openid'))) );
	}
?>
		<div class="ui tertiary segment container-form">
<?
	echo form_open_multipart(uri_string(), 'class="ui form ' . ( !empty($errors) ? 'error' : '' ) . '"');

	$this->load->view('common/form/input', array(
			'error' => $this->session->flashdata('connect_openid_error') || isset($connect_openid_error),
	        'label' => sprintf(lang('connect_enter_your'), lang('connect_openid_url')) . ' <small>(' .anchor($this->config->item('openid_what_is_url'), lang('connect_start_what_is_openid'), array('target' => '_blank')) . ')</small>',
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'connect_openid_url',
		        'id' => 'connect_openid_url',
		        'value' => set_value('connect_openid_url'),
		        'placeholder' => sprintf(lang('connect_enter_your'), lang('connect_openid_url'))
	        )
		)
	);

?>
			<div class="field">
				<?= form_button(array('type' => 'submit', 'class' => 'ui submit primary button small right floated', 'content' => '<i class="unlock icon"></i> '.lang('connect_proceed'))); ?>
			</div>

		<?= form_close() ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<?= $this->load->view('common/footer'); ?>
</body>
</html>
