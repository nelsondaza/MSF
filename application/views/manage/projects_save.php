<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang($class . '_page_name')) ) ?>
</head>
<body>
<?= $this->load->view('common/header') ?>
<div class="container content">
	<?= $this->load->view('common/menu', array('current' => 'manage_' . $class) ) ?>
	<div class="sub-header"><i class="flag icon"></i> <?= lang($class . "_{$action}_page_name") ?></div>
	<div class="section">
<?
    echo $this->load->view( 'common/breadcrumb', array(
        'path' => array(
            lang( $class . '_page_name' ) => $scope . '/' . $class,
            lang( $class . "_{$action}_page_name" ) => null
        )
    ));

	$errors = array( );

	if( isset( $projects_field_name_error ) )
		$errors[] = $projects_field_name_error;
	if( form_error('projects_field_name') )
		$errors[] = form_error('projects_field_name');

	if ( !empty($errors) ) {
		$this->load->view('common/message', array('type' => 'error','class' => 'warning','content' => $errors) );
	}
	else if (isset($action_info)) {
		$this->load->view('common/message', array('type' => 'success','class' => 'info','content' => $action_info) );
	}
	else {
		$this->load->view('common/message', array('content' => lang($class . "_{$action}_description") ) );
	}

		echo form_open_multipart(uri_string(), 'class="ui form horizontal ' . ( !empty($errors) ? 'error' : '' ) . '"');
?>
		<div class="ui pointing primary inverted menu">
			<a class="active item" data-tab="gral"><?= lang('projects_tab_title') ?></a>
<?
		foreach( $sources as $source )  {
			if( isset( $source['dataSource'] ) ) {
?>
				<a class="item" data-tab="<?= 'source_' . $source['id'] ?>"><?= $source['name'] ?></a>
<?
			}
		}
?>
		</div>
		<div class="ui tertiary active tab segment" data-tab="gral">
<?

    $this->load->view('common/form/input', array(
			'error' => form_error('projects_field_name') || isset($field_name_error),
	        'label' => lang('projects_field_name'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'projects_field_name',
		        'id' => 'projects_field_name',
		        'value' => set_value('projects_field_name') ? set_value('projects_field_name') : ( isset( $project['name'] ) ? $project['name'] : ''),
		        'maxlength' => '80',
		        'placeholder' => lang('projects_field_name')
	        )
		)
	);

    $this->load->view('common/form/input', array(
			'error' => form_error('projects_field_description') || isset($projects_field_description_error),
	        'label' => lang('projects_field_description'),
	        'attributes' => array(
		        'type' => 'textarea',
		        'name' => 'projects_field_description',
		        'id' => 'projects_field_description',
		        'value' => set_value('projects_field_description') ? set_value('projects_field_description') : (isset($project['description']) ? $project['description'] : ''),
                'maxlength' => 160,
                'rows'=>'4',
		        'placeholder' => lang('projects_field_description')
	        )
		)
	);

	$options = array();
	foreach( $brands as $brand ) {
		$options[$brand['id']] = $brand['name'] . ' (' . $brand['client_name'] . ')';
	}

    $this->load->view('common/form/input', array(
			'error' => form_error('projects_field_id_brand') || isset($projects_field_id_brand_error),
	        'label' => lang('projects_field_id_brand'),
	        'options' => $options,
	        'selected' => ( isset( $project['id_brand'] ) && $project['id_brand'] ? $project['id_brand'] : null ),
	        'attributes' => array(
		        'type' => 'dropdown',
		        'name' => 'projects_field_id_brand',
		        'id' => 'projects_field_id_brand',
		        'value' => set_value('projects_field_id_brand') ? set_value('projects_field_id_brand') : (isset($project['id_brand']) ? $project['id_brand'] : ''),
		        'placeholder' => lang('projects_field_id_brand')
	        )
		)
	);

	$checkboxes = array( );
	foreach( $sources as $source ) {
		$checkbox = array(
			'name' => "projects_field_sources[]",
			'value' => $source['id'],
			'label' => $source['name']
		);
		if( isset( $project_sources[$source['id']] ) )
			$checkbox['checked'] = true;

		$checkboxes[] = $checkbox;
	}

	$this->load->view('common/form/input', array(
			'options' => $checkboxes,
	        'label' => lang('projects_field_sources'),
	        'attributes' => array(
		        'type' => 'multicheckbox',
	        )
		)
	);


?>
		</div>
<?
		foreach( $sources as $source )  {
			if( isset( $source['dataSource'] ) ) {
?>
				<div class="ui tertiary tab segment" data-tab="<?= 'source_' . $source['id'] ?>">
<?
				$this->load->view( $source['dataSource']->getManageProjectView( ), array(
					'source' => $source,
					'dataSource' => $source['dataSource'],
					'project' => $project,
					'account' => $account,
					'account_details' => $account_details,
				) );
?>
				</div>
<?
			}
	}
?>
		<div class="field">
<?
	if( $this->authorization->is_permitted('delete_projects') && $action == 'update' ) {
		if ( !$project['active'] ) {
?>
			<?= form_button(array('name' => 'activate', 'value' => lang('activate'), 'type' => 'submit', 'class' => 'ui submit positive button small left floated', 'content' => '<i class="ban icon"></i> ' . lang('activate'))); ?>
<?
		}
		else {
?>
			<?= form_button(array('name' => 'deactivate', 'value' => lang('deactivate'), 'type' => 'submit', 'class' => 'ui submit negative button small left floated', 'content' => '<i class="ban icon"></i> ' . lang('deactivate'))); ?>
<?
		}
	}
?>
            <?= form_button(array('name' => 'field_submit', 'value' => lang('save'), 'type' => 'submit', 'class' => 'ui submit primary button small right floated', 'content' => '<i class="archive icon"></i> ' . lang('save'))); ?>
            <?= form_button(array('type' => 'reset', 'class' => 'ui submit secondary button small right floated', 'content' => '<i class="undo icon"></i> ' . lang('discard'))); ?>
		</div>
		<?= form_close() ?>
	</div>
	<div class="clearfix"></div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
