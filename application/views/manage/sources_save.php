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
	<div class="sub-header"><i class="crosshairs icon"></i> <?= lang($class . "_{$action}_page_name") ?></div>
	<div class="section">
<?php
    echo $this->load->view( 'common/breadcrumb', array(
        'path' => array(
            lang( $class . '_page_name' ) => $scope . '/' . $class,
            lang( $class . "_{$action}_page_name" ) => null
        )
    ));


	if( !isset( $dataSource ) || !$dataSource )
		$dataSource = new \sources\DataSource( );

	$errors = array( );

	if( isset( $sources_field_base_class_error ) )
		$errors[] = $sources_field_base_class_error;
	if( form_error('sources_field_base_class') )
		$errors[] = form_error('sources_field_base_class');
	if( isset( $sources_field_name_error ) )
		$errors[] = $sources_field_name_error;
	if( form_error('sources_field_name') )
		$errors[] = form_error('sources_field_name');

	$formRules = $dataSource->getManageFormRules( );
	foreach( $formRules as $rule )  {
		if( form_error($rule['field']) )
			$errors[] = form_error($rule['field']);
	}

	if ( !empty($errors) ) {
		$this->load->view('common/message', array('type' => 'error','class' => 'warning','content' => $errors) );
	}
	else if (isset($action_info)) {
		$this->load->view('common/message', array('type' => 'success','class' => 'info','content' => $action_info) );
	}
	else {
		$this->load->view('common/message', array('content' => lang($class . "_{$action}_description") ) );
	}

	$sourceTabViews = $dataSource->getManageTabViews( );

	echo form_open_multipart(uri_string(), 'class="ui form ' . ( !empty($errors) ? 'error' : '' ) . '"');
?>
		<div class="ui pointing primary inverted menu">
			<a class="active item" data-tab="gral"><?= lang('sources_tab_title') ?></a>
<?php
	foreach( $sourceTabViews as $vName => $vKey )  {
?>
			<a class="item" data-tab="<?= preg_replace( '/([^a-z0-9]+)/i', '', $vKey ) ?>"><?= $vName ?></a>
<?php

	}
?>
		</div>
		<div class="ui tertiary active tab segment" data-tab="gral">
<?php
	$this->load->view('common/form/input', array(
			'error' => form_error('sources_field_name') || isset($sources_field_name_error),
	        'label' => lang('sources_field_name'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'sources_field_name',
		        'id' => 'sources_field_name',
		        'value' => set_value('sources_field_name') ? set_value('sources_field_name') : ( isset( $source['name'] ) ? $source['name'] : ''),
		        'maxlength' => '80',
		        'placeholder' => lang('sources_field_name')
	        )
		)
	);

	$this->load->view('common/form/input', array(
			'error' => form_error('sources_field_base_class') || isset($sources_field_base_class_error),
	        'label' => lang('sources_field_base_class'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'sources_field_base_class',
		        'id' => 'sources_field_base_class',
		        'value' => set_value('sources_field_base_class') ? set_value('sources_field_base_class') : ( isset( $source['base_class'] ) ? $source['base_class'] : ''),
		        'maxlength' => '80',
		        'placeholder' => lang('sources_field_base_class')
	        )
		)
	);

	$this->load->view('common/form/input', array(
			'error' => form_error('sources_field_description') || isset($sources_field_description_error),
	        'label' => lang('sources_field_description'),
	        'attributes' => array(
		        'type' => 'textarea',
		        'name' => 'sources_field_description',
		        'id' => 'sources_field_description',
		        'value' => set_value('sources_field_description') ? set_value('sources_field_description') : (isset($source['description']) ? $source['description'] : ''),
                'maxlength' => 160,
                'rows'=>'4',
		        'placeholder' => lang('sources_field_description')
	        )
		)
	);

	$this->load->view('common/form/input', array(
			'error' => form_error('sources_field_logo') || isset($sources_field_logo_error),
	        'label' => lang('sources_field_logo'),
	        'image' => ( isset( $source['logo'] ) && $source['logo'] ? showSourcePhoto( $source['logo'] ) : null ),
	        'header' => lang('sources_field_logo_header'),
	        'description' => (
	            isset( $source['logo'] ) && $source['logo']
		        ? anchor('manage/sources/remove_image/' . $source['id'], '<i class="icon trash"></i> '.lang('sources_field_logo_delete'), 'class="ui mini button negative"')
		        : '<br><small><i class="info icon"></i>' . lang('sources_field_logo_guidelines') . '></small>'
			),
	        'attributes' => array(
		        'type' => 'item_image',
		        'name' => 'sources_field_logo',
		        'id' => 'sources_field_logo',
		        'value' => set_value('sources_field_logo') ? set_value('sources_field_logo') : ( isset($source['sources_field_logo']) ? $source['sources_field_logo'] : ''),
	        )
		)
	);

?>
		</div>
<?php
	foreach( $sourceTabViews as $vName => $vKey )  {
?>
		<div class="ui tertiary tab segment" data-tab="<?= preg_replace( '/([^a-z0-9]+)/i', '', $vKey ) ?>">
<?php
		$this->load->view( $vKey, $_ci_vars );
?>
		</div>
<?php

	}
?>
		<div class="field">
<?php
	if( $this->authorization->is_permitted('delete_sources') && $action == 'update' ) {
		if ( !$source['active'] ) {
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
	<div class="clearfix"></div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
