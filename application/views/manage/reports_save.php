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
<?php
    echo $this->load->view( 'common/breadcrumb', array(
        'path' => array(
            lang( $class . '_page_name' ) => $scope . '/' . $class,
            lang( $class . "_{$action}_page_name" ) => null
        )
    ));

	$errors = array( );

	if( isset( $reports_field_name_error ) )
		$errors[] = $reports_field_name_error;
	if( form_error('reports_field_name') )
		$errors[] = form_error('reports_field_name');

	if ( !empty($errors) ) {
		$this->load->view('common/message', array('type' => 'error','class' => 'warning','content' => $errors) );
	}
	else if (isset($action_info)) {
		$this->load->view('common/message', array('type' => 'success','class' => 'info','content' => $action_info) );
	}
	else {
		$this->load->view('common/message', array('content' => lang($class . "_{$action}_description") ) );
	}

?>
		<div class="ui tertiary segment container-form">
<?php
	echo form_open_multipart(uri_string(), 'class="ui form ' . ( !empty($errors) ? 'error' : '' ) . '"');

      $this->load->view('common/form/input', array(
			'error' => form_error('reports_field_name') || isset($field_name_error),
	        'label' => lang('reports_field_name'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'reports_field_name',
		        'id' => 'reports_field_name',
		        'value' => set_value('reports_field_name') ? set_value('reports_field_name') : ( isset( $report['name'] ) ? $report['name'] : ''),
		        'maxlength' => '80',
		        'placeholder' => lang('reports_field_name')
	        )
		)
	);

      $this->load->view('common/form/input', array(
			'error' => form_error('reports_field_generation') || isset($field_generation_error),
	        'label' => lang('reports_field_generation'),
	        'attributes' => array(
		        'type' => 'text',
		        'readonly' => true,
		        'generation' => 'reports_field_generation',
		        'id' => 'reports_field_generation',
		        'value' => set_value('reports_field_generation') ? set_value('reports_field_generation') : ( isset( $report['generation'] ) ? $report['generation'] : ' - Sin Generar -'),
		        'maxlength' => '10',
		        'placeholder' => lang('reports_field_generation')
	        )
		)
	);


	$options = array();
	foreach( $projects as $project ) {
		$options[$project['id']] = $project['name'] . ' (' . $project['brand_name'] . '/' . $project['client_name'] . ')';
	}

    $this->load->view('common/form/input', array(
			'error' => form_error('reports_field_id_project') || isset($reports_field_id_project_error),
	        'label' => lang('reports_field_id_project'),
	        'options' => $options,
	        'selected' => ( isset( $report['id_project'] ) && $report['id_project'] ? $report['id_project'] : null ),
	        'attributes' => array(
		        'type' => 'dropdown',
		        'name' => 'reports_field_id_project',
		        'id' => 'reports_field_id_project',
		        'value' => set_value('reports_field_id_project') ? set_value('reports_field_id_project') : (isset($report['id_project']) ? $report['id_project'] : ''),
		        'placeholder' => lang('reports_field_id_project')
	        )
		)
	);

?>
			<div class="field">
<?php
	if( $this->authorization->is_permitted('delete_reports') && $action == 'update' ) {
		if ( !$report['active'] ) {
?>
			<?= form_button(array('name' => 'activate', 'value' => lang('activate'), 'type' => 'submit', 'class' => 'ui submit positive button small left floated', 'content' => '<i class="ban icon"></i> ' . lang('activate'))); ?>
<?php
		}
		else {
?>
			<?= form_button(array('name' => 'deactivate', 'value' => lang('deactivate'), 'type' => 'submit', 'class' => 'ui submit negative button small left floated', 'content' => '<i class="ban icon"></i> ' . lang('deactivate'))); ?>
<?php
		}
	}
?>
                <?= form_button(array('name' => 'field_submit', 'value' => lang('save'), 'type' => 'submit', 'class' => 'ui submit primary button small right floated', 'content' => '<i class="archive icon"></i> ' . lang('save'))); ?>
                <?= form_button(array('type' => 'reset', 'class' => 'ui submit secondary button small right floated', 'content' => '<i class="undo icon"></i> ' . lang('discard'))); ?>
			</div>
			<?= form_close() ?>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
