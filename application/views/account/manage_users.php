<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang('users_page_name')) ) ?>
</head>
<body>
<?= $this->load->view('common/header', array('current' => 'manage_users')) ?>
<div class="container content">
	<div class="sub-header"><i class="icon users"></i> <?= lang('users_page_name') ?></div>
	<div class="section">
<?php
	$this->load->view('common/message', array('content' => lang('users_description')) );

	$table = array( );

	if( $this->authorization->is_permitted('create_users') )
		$table['options'] = anchor('account/manage_users/save', '<i class="plus icon"></i> ' . lang('website_create'), 'class="ui purple button mini"');

	$table['headers'] = array(
	    '#',
	    lang('users_username'),
		lang('settings_email'),
		lang('settings_firstname'),
		lang('settings_lastname')
	);

	$table['rows'] = array( );
	$table['rows_options'] = array( );

	foreach( $all_accounts as $acc ) {
		$table['rows'][] = array(
			$acc['id'],
			$acc['username'] . (
				$acc['is_banned']
				? ' <span class="ui mini red label floated right">' . lang('users_banned') . '</span>'
				: (
					$acc['is_admin']
					? ' <span class="ui mini green label floated right">' . lang('users_admin') . '</span>'
					: ''
				)
			),
			$acc['email'],
			$acc['firstname'],
			$acc['lastname']
		);

		if( $this->authorization->is_permitted('update_users') )
			$table['rows_options'][] = anchor('account/manage_users/save/'.$acc['id'], '<i class="edit icon"></i> ' . lang('website_update'), 'class="ui teal button mini"');

	}

	$this->load->view('common/table', $table );

?>
	</div>
	<div class="clearfix"></div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
