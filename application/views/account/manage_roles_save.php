<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang('users_page_name')) ) ?>
</head>
<body>
<?= $this->load->view('common/header', array('current' => 'manage_roles')) ?>
<div class="container content">
	<div class="sub-header"><i class="student icon"></i> <?= lang("roles_{$action}_page_name") ?></div>
	<div class="section">
<?php
    echo $this->load->view( 'common/breadcrumb', array(
        'path' => array(
            lang( 'roles_page_name' ) => 'account/manage_roles',
            lang( "roles_{$action}_page_name" ) => null
        )
    ));

	$errors = array( );

	if( isset( $role_name_error ) )
		$errors[] = $role_name_error;
	if( form_error('role_name') )
		$errors[] = form_error('role_name');
	if( form_error('role_description') )
		$errors[] = form_error('role_description');

	if ( !empty($errors) ) {
		$this->load->view('common/message', array('type' => 'error','class' => 'warning','content' => $errors) );
	}
	else {
		$this->load->view('common/message', array('content' => lang("roles_{$action}_description") ) );
	}
?>
		<div class="ui tertiary segment container-form">
<?php
	echo form_open(uri_string(), 'class="ui form ' . ( !empty($errors) ? 'error' : '' ) . '"');

      $this->load->view('common/form/input', array(
			'error' => form_error('role_name') || isset($role_name_error),
	        'label' => lang('roles_name'),
	        'attributes' => array(
		        'type' => 'text',
                'readonly' => $is_system,
		        'name' => 'role_name',
		        'id' => 'role_name',
		        'value' => set_value('role_name') ? set_value('role_name') : (isset($role->name) ? $role->name : ''),
		        'maxlength' => '80',
		        'placeholder' => lang('roles_name')
	        )
		)
	);

    $this->load->view('common/form/input', array(
			'error' => form_error('role_name') || isset($role_name_error),
	        'label' => lang('roles_description'),
	        'attributes' => array(
		        'type' => 'textarea',
		        'name' => 'role_description',
		        'id' => 'role_description',
		        'value' => set_value('role_description') ? set_value('role_description') : (isset($role->description) ? $role->description : ''),
                'maxlength' => 160,
                'rows'=>'4',
		        'placeholder' => lang('roles_description')
	        )
		)
	);

	$checkboxes = array( );
	foreach( $permissions as $perm ) {
		$checkbox = array(
			'name' => "role_permission_{$perm->id}",
			'value' => 'apply',
			'label' => $perm->key
		);
		if( isset( $role_permissions ) ) {
			foreach( $role_permissions as $rperm ) {
				if( $perm->id == $rperm->id ) {
					$checkbox['checked'] = true;
					break;
				}
			}
		}
		$checkboxes[] = $checkbox;
	}

	$this->load->view('common/form/input', array(
			'options' => $checkboxes,
	        'label' => lang('roles_permission'),
	        'attributes' => array(
		        'type' => 'multicheckbox',
	        )
		)
	);

?>
			<div class="field">
<?php
	if( $this->authorization->is_permitted('delete_roles') && $action == 'update' && !$is_system ) {
		if ( isset( $role->suspendedon ) ) {
?>
			<?= form_button(array('name' => 'manage_role_unban', 'value' => lang('roles_unban'), 'type' => 'submit', 'class' => 'ui submit positive button small left floated', 'content' => '<i class="ban icon"></i> ' . lang('roles_unban'))); ?>
<?php
		}
		else {
?>
			<?= form_button(array('name' => 'manage_role_ban', 'value' => lang('roles_ban'), 'type' => 'submit', 'class' => 'ui submit negative button small left floated', 'content' => '<i class="ban icon"></i> ' . lang('roles_ban'))); ?>
<?php
		}
	}
?>
                <?= form_button(array('name' => 'manage_role_submit', 'value' => lang('settings_save'), 'type' => 'submit', 'class' => 'ui submit primary button small right floated', 'content' => '<i class="archive icon"></i> ' . lang('settings_save'))); ?>
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
