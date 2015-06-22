<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang('permissions_page_name')) ) ?>
</head>
<body>
<?= $this->load->view('common/header') ?>
<div class="container content">
	<?= $this->load->view('common/menu', array('current' => 'manage_permissions') ) ?>
	<div class="sub-header"><i class="privacy icon"></i> <?= lang("permissions_{$action}_page_name") ?></div>
	<div class="section">
<?
    echo $this->load->view( 'common/breadcrumb', array(
        'path' => array(
            lang( 'permissions_page_name' ) => 'account/manage_permissions',
            lang( "permissions_{$action}_page_name" ) => null
        )
    ));

	$errors = array( );

	if( isset( $permission_key_error ) )
		$errors[] = $permission_key_error;
	if( form_error('permission_key') )
		$errors[] = form_error('permission_key');
    if( isset( $permission_name_error ) )
        $errors[] = $permission_name_error;
	if( form_error('permission_description') )
		$errors[] = form_error('permission_description');

	if ( !empty($errors) ) {
		$this->load->view('common/message', array('type' => 'error','class' => 'warning','content' => $errors) );
	}
	else {
		$this->load->view('common/message', array('content' => lang("permissions_{$action}_description") ) );
	}
?>
		<div class="ui tertiary segment container-form">
<?
	echo form_open(uri_string(), 'class="ui form ' . ( !empty($errors) ? 'error' : '' ) . '"');

      $this->load->view('common/form/input', array(
			'error' => form_error('permission_key') || isset($permission_key_error),
	        'label' => lang('permissions_key'),
	        'attributes' => array(
		        'type' => 'text',
                'readonly' => $is_system,
		        'name' => 'permission_key',
		        'id' => 'permission_key',
		        'value' => set_value('permission_key') ? set_value('permission_key') : (isset($permission->key) ? $permission->key : ''),
		        'maxlength' => '80',
		        'placeholder' => lang('permissions_key')
	        )
		)
	);

    $this->load->view('common/form/input', array(
			'error' => form_error('permission_description') || isset($permission_name_error),
	        'label' => lang('permissions_description'),
	        'attributes' => array(
		        'type' => 'textarea',
		        'name' => 'permission_description',
		        'id' => 'permission_description',
		        'value' => set_value('permission_description') ? set_value('permission_description') : (isset($permission->description) ? $permission->description : ''),
                'maxlength' => 160,
                'rows'=>'4',
		        'placeholder' => lang('permissions_description')
	        )
		)
	);

	$checkboxes = array( );
	foreach( $roles as $role ) {
		$checkbox = array(
			'name' => "role_permission_{$role->id}",
			'value' => 'apply',
			'label' => $role->name
		);
		if( isset( $role_permissions ) ) {
			foreach( $role_permissions as $rperm ) {
				if( $role->id == $rperm->id ) {
					$checkbox['checked'] = true;
					break;
				}
			}
		}
		$checkboxes[] = $checkbox;
	}

	$this->load->view('common/form/input', array(
			'options' => $checkboxes,
	        'label' => lang('permissions_role'),
	        'attributes' => array(
		        'type' => 'multicheckbox',
	        )
		)
	);

?>
			<div class="field">
<?
	if( $this->authorization->is_permitted('delete_permissions') && $action == 'update' && !$is_system ) {
		if ( isset( $permission->suspendedon ) ) {
?>
			<?= form_button(array('name' => 'manage_permission_unban', 'value' => lang('permissions_unban'), 'type' => 'submit', 'class' => 'ui submit positive button small left floated', 'content' => '<i class="ban icon"></i> ' . lang('permissions_unban'))); ?>
<?
		}
		else {
?>
			<?= form_button(array('name' => 'manage_permission_ban', 'value' => lang('permissions_ban'), 'type' => 'submit', 'class' => 'ui submit negative button small left floated', 'content' => '<i class="ban icon"></i> ' . lang('permissions_ban'))); ?>
<?
		}
	}
?>
                <?= form_button(array('name' => 'manage_permission_submit', 'value' => lang('settings_save'), 'type' => 'submit', 'class' => 'ui submit primary button small right floated', 'content' => '<i class="archive icon"></i> ' . lang('settings_save'))); ?>
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
