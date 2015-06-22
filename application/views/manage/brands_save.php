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
	<div class="sub-header"><i class="rocket icon"></i> <?= lang($class . "_{$action}_page_name") ?></div>
	<div class="section">
<?php
    echo $this->load->view( 'common/breadcrumb', array(
        'path' => array(
            lang( $class . '_page_name' ) => $scope . '/' . $class,
            lang( $class . "_{$action}_page_name" ) => null
        )
    ));

	$errors = array( );

	if( isset( $brands_field_name_error ) )
		$errors[] = $brands_field_name_error;
	if( form_error('brands_field_name') )
		$errors[] = form_error('brands_field_name');

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
			'error' => form_error('brands_field_name') || isset($field_name_error),
	        'label' => lang('brands_field_name'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'brands_field_name',
		        'id' => 'brands_field_name',
		        'value' => set_value('brands_field_name') ? set_value('brands_field_name') : ( isset( $brand['name'] ) ? $brand['name'] : ''),
		        'maxlength' => '80',
		        'placeholder' => lang('brands_field_name')
	        )
		)
	);

    $this->load->view('common/form/input', array(
			'error' => form_error('brands_field_description') || isset($brands_field_description_error),
	        'label' => lang('brands_field_description'),
	        'attributes' => array(
		        'type' => 'textarea',
		        'name' => 'brands_field_description',
		        'id' => 'brands_field_description',
		        'value' => set_value('brands_field_description') ? set_value('brands_field_description') : (isset($brand['description']) ? $brand['description'] : ''),
                'maxlength' => 160,
                'rows'=>'4',
		        'placeholder' => lang('brands_field_description')
	        )
		)
	);

	$options = array();
	foreach( $clients as $client ) {
		$options[$client['id']] = $client['name'];
	}

    $this->load->view('common/form/input', array(
			'error' => form_error('brands_field_id_client') || isset($brands_field_id_client_error),
	        'label' => lang('brands_field_id_client'),
	        'options' => $options,
	        'selected' => ( isset( $brand['id_client'] ) && $brand['id_client'] ? $brand['id_client'] : null ),
	        'attributes' => array(
		        'type' => 'dropdown',
		        'name' => 'brands_field_id_client',
		        'id' => 'brands_field_id_client',
		        'value' => set_value('brands_field_id_client') ? set_value('brands_field_id_client') : (isset($brand['id_client']) ? $brand['id_client'] : ''),
		        'placeholder' => lang('brands_field_id_client')
	        )
		)
	);

	$this->load->view('common/form/input', array(
			'error' => form_error('brands_field_logo') || isset($brands_field_logo_error),
	        'label' => lang('brands_field_logo'),
	        'image' => ( isset( $brand['logo'] ) && $brand['logo'] ? showBrandPhoto( $brand['logo'] ) : null ),
	        'header' => lang('brands_field_logo_header'),
	        'description' => (
	            isset( $brand['logo'] ) && $brand['logo']
		        ? anchor('manage/brands/remove_image/' . $brand['id'], '<i class="icon trash"></i> '.lang('brands_field_logo_delete'), 'class="ui mini button negative"')
		        : '<br><small><i class="info icon"></i>' . lang('brands_field_logo_guidelines') . '></small>'
			),
	        'attributes' => array(
		        'type' => 'item_image',
		        'name' => 'brands_field_logo',
		        'id' => 'brands_field_logo',
		        'value' => set_value('brands_field_logo') ? set_value('brands_field_logo') : ( isset($brand['brands_field_logo']) ? $brand['brands_field_logo'] : ''),
	        )
		)
	);

?>
			<div class="field">
<?php
	if( $this->authorization->is_permitted('delete_brands') && $action == 'update' ) {
		if ( !$brand['active'] ) {
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
