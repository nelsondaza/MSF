<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang('users_page_name')) ) ?>
</head>
<body>
<?= $this->load->view('common/header') ?>
<div class="container content">
	<?= $this->load->view('common/menu', array('current' => 'manage_users') ) ?>
	<div class="sub-header"><i class="icon users"></i> <?= lang("users_{$action}_page_name") ?></div>
	<div class="section">
<?
    echo $this->load->view( 'common/breadcrumb', array(
        'path' => array(
            lang( 'users_page_name' )           => 'account/manage_users',
            lang( "users_{$action}_page_name" ) => null
        )
    ));

	$errors = array( );

	if( isset( $users_username_error ) )
		$errors[] = $users_username_error;
	if( form_error('users_username') )
		$errors[] = form_error('users_username');

	if( isset( $users_email_error ) )
		$errors[] = $users_email_error;
	if( form_error('users_email') )
		$errors[] = form_error('users_email');
	if( form_error('users_fullname') )
		$errors[] = form_error('users_fullname');
	if( form_error('users_firstname') )
		$errors[] = form_error('users_firstname');
	if( form_error('users_lastname') )
		$errors[] = form_error('users_lastname');
	if( form_error('users_new_password') )
		$errors[] = form_error('users_new_password');
	if( form_error('users_retype_new_password') )
		$errors[] = form_error('users_retype_new_password');

	if ( !empty($errors) ) {
		$this->load->view('common/message', array('type' => 'error','class' => 'warning','content' => $errors) );
	}
	else {
		$this->load->view('common/message', array('content' => lang("users_{$action}_description") ) );
	}
?>
		<div class="ui tertiary segment container-form">
<?
	echo form_open(uri_string(), 'class="ui form ' . ( !empty($errors) ? 'error' : '' ) . '"');

	$this->load->view('common/form/input', array(
			'error' => form_error('users_username') || isset($users_username_error),
	        'label' => lang('profile_username'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'users_username',
		        'id' => 'users_username',
		        'value' => set_value('users_username') ? set_value('users_username') : (isset($update_account->username) ? $update_account->username : ''),
		        'maxlength' => '160',
		        'placeholder' => lang('profile_username')
	        )
		)
	);
	$this->load->view('common/form/input', array(
			'error' => form_error('users_email') || isset($users_email_error),
	        'label' => lang('settings_email'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'users_email',
		        'id' => 'users_email',
		        'value' => set_value('users_email') ? set_value('users_email') : (isset($update_account->email) ? $update_account->email : ''),
		        'maxlength' => '160',
		        'placeholder' => lang('settings_email')
	        )
		)
	);
	$this->load->view('common/form/input', array(
			'error' => form_error('users_fullname'),
	        'label' => lang('settings_fullname'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'users_fullname',
		        'id' => 'users_fullname',
		        'value' => set_value('users_fullname') ? set_value('users_fullname') : (isset($update_account_details->fullname) ? $update_account_details->fullname : ''),
		        'maxlength' => '160',
		        'placeholder' => lang('settings_fullname')
	        )
		)
	);
	$this->load->view('common/form/input', array(
			'error' => form_error('users_firstname'),
	        'label' => lang('settings_firstname'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'users_firstname',
		        'id' => 'users_firstname',
		        'value' => set_value('users_firstname') ? set_value('users_firstname') : (isset($update_account_details->firstname) ? $update_account_details->firstname : ''),
		        'maxlength' => '160',
		        'placeholder' => lang('settings_firstname')
	        )
		)
	);
	$this->load->view('common/form/input', array(
			'error' => form_error('users_lastname'),
	        'label' => lang('settings_lastname'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'users_lastname',
		        'id' => 'users_lastname',
		        'value' => set_value('users_lastname') ? set_value('users_lastname') : (isset($update_account_details->lastname) ? $update_account_details->lastname : ''),
		        'maxlength' => '160',
		        'placeholder' => lang('settings_lastname')
	        )
		)
	);
	$this->load->view('common/form/input', array(
			'error' => form_error('users_new_password'),
	        'label' => lang('password_new_password'),
	        'attributes' => array(
		        'type' => 'password',
		        'name' => 'users_new_password',
		        'id' => 'users_new_password',
		        'value' => set_value('users_new_password'),
                'autocomplete' => 'off',
		        'placeholder' => lang('password_new_password')
	        )
		)
	);
	$this->load->view('common/form/input', array(
			'error' => form_error('users_retype_new_password'),
	        'label' => lang('password_retype_new_password'),
	        'attributes' => array(
		        'type' => 'password',
		        'name' => 'users_retype_new_password',
		        'id' => 'users_retype_new_password',
		        'value' => set_value('users_retype_new_password'),
                'autocomplete' => 'off',
		        'placeholder' => lang('password_retype_new_password')
	        )
		)
	);

	$checkboxes = array( );

	foreach( $roles as $role ) {
		$checkbox = array(
			'name' => "account_role_{$role->id}",
			'value' => 'apply',
			'label' => $role->name
		);
		if( isset( $update_account_roles ) ) {
			foreach( $update_account_roles as $acrole ) {
				if( $role->id == $acrole->id ) {
					$checkbox['checked'] = true;
					break;
				}
			}
		}
		$checkboxes[] = $checkbox;
	}

	$this->load->view('common/form/input', array(
			'options' => $checkboxes,
	        'label' => lang('users_roles'),
	        'attributes' => array(
		        'type' => 'multicheckbox',
	        )
		)
	);
?>
			<div class="field">
<?
	if( $this->authorization->is_permitted('ban_users') && $action == 'update' ) {
		if ( isset( $update_account->suspendedon ) ) {
?>
			<?= form_button(array('name' => 'manage_user_unban', 'value' => lang('users_unban'), 'type' => 'submit', 'class' => 'ui submit positive button small left floated', 'content' => '<i class="ban icon"></i> ' . lang('users_unban'))); ?>
<?
		}
		else {
?>
			<?= form_button(array('name' => 'manage_user_ban', 'value' => lang('users_ban'), 'type' => 'submit', 'class' => 'ui submit negative button small left floated', 'content' => '<i class="ban icon"></i> ' . lang('users_ban'))); ?>
<?
		}
	}
?>
                <?= form_button(array('name' => 'manage_user_submit', 'value' => lang('settings_save'), 'type' => 'submit', 'class' => 'ui submit primary button small right floated', 'content' => '<i class="archive icon"></i> ' . lang('settings_save'))); ?>
                <?= form_button(array('type' => 'reset', 'class' => 'ui submit secondary button small right floated', 'content' => '<i class="undo icon"></i> Limpiar')); ?>
			</div>
			<?= form_close() ?>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>