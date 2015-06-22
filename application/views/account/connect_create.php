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
	$errors = array( );
	if( isset( $connect_create_error ) )
		$errors[] = $connect_create_error;
	if( isset( $connect_create_username_error ) )
		$errors[] = $connect_create_username_error;
	if( form_error('connect_create_username') )
		$errors[] = form_error('connect_create_username');
	if( isset( $connect_create_email_error ) )
		$errors[] = $connect_create_email_error;
	if( form_error('connect_create_email') )
		$errors[] = form_error('connect_create_email');

	if ( !empty($errors) ) {
		$this->load->view('common/message', array('type' => 'error','class' => 'warning','content' => $errors) );
	}
	else if( $this->session->flashdata('linked_info') ) {
		$this->load->view('common/message', array('type' => 'success','class' => 'info','content' => $this->session->flashdata('linked_info')) );
	}
	else {
		$this->load->view('common/message', array('class' => 'info sign','content' => lang('connect_create_heading')) );
	}
?>
			<div class="ui tertiary segment container-form">
<?
	echo form_open_multipart(uri_string(), 'class="ui form ' . ( !empty($errors) ? 'error' : '' ) . '"');

	$this->load->view('common/form/input', array(
			'error' => !empty($errors),
	        'label' => lang('connect_create_username'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'connect_create_username',
		        'id' => 'connect_create_username',
		        'value' => set_value('connect_create_username') ? set_value('connect_create_username') : (isset($connect_create[0]['username']) ? $connect_create[0]['username'] : ''),
		        'maxlength' => '16',
		        'placeholder' => lang('connect_create_username')
	        )
		)
	);

	$this->load->view('common/form/input', array(
			'error' => !empty($errors),
	        'label' => lang('connect_create_email'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'connect_create_email',
		        'id' => 'connect_create_email',
		        'value' => set_value('connect_create_email') ? set_value('connect_create_email') : (isset($connect_create[0]['email']) ? $connect_create[0]['email'] : ''),
		        'maxlength' => '160',
		        'placeholder' => lang('connect_create_email')
	        )
		)
	);

?>
			<div class="field">
				<?= form_button(array('type' => 'submit', 'class' => 'ui submit primary button small right floated', 'content' => '<i class="user icon"></i> '.lang('connect_create_button'))); ?>
			</div>

			<?= form_close() ?>
		</div>
		<div class="clearfix"></div>
    </div>
</div>
<? $this->load->view('common/footer'); ?>
</body>
</html>
