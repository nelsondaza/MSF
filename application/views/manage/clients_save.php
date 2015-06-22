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
	<div class="sub-header"><i class="world icon"></i> <?= lang($class . "_{$action}_page_name") ?></div>
	<div class="section">
<?
    echo $this->load->view( 'common/breadcrumb', array(
        'path' => array(
            lang( $class . '_page_name' ) => $scope . '/' . $class,
            lang( $class . "_{$action}_page_name" ) => null
        )
    ));

	$errors = array( );

	if( isset( $clients_field_name_error ) )
		$errors[] = $clients_field_name_error;
	if( form_error('clients_field_name') )
		$errors[] = form_error('clients_field_name');

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
<?
	echo form_open_multipart(uri_string(), 'class="ui form ' . ( !empty($errors) ? 'error' : '' ) . '"');

      $this->load->view('common/form/input', array(
			'error' => form_error('clients_field_name') || isset($field_name_error),
	        'label' => lang('clients_field_name'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'clients_field_name',
		        'id' => 'clients_field_name',
		        'value' => set_value('clients_field_name') ? set_value('clients_field_name') : ( isset( $client['name'] ) ? $client['name'] : ''),
		        'maxlength' => '80',
		        'placeholder' => lang('clients_field_name')
	        )
		)
	);

    $this->load->view('common/form/input', array(
			'error' => form_error('clients_field_description') || isset($clients_field_description_error),
	        'label' => lang('clients_field_description'),
	        'attributes' => array(
		        'type' => 'textarea',
		        'name' => 'clients_field_description',
		        'id' => 'clients_field_description',
		        'value' => set_value('clients_field_description') ? set_value('clients_field_description') : (isset($client['description']) ? $client['description'] : ''),
                'maxlength' => 160,
                'rows'=>'4',
		        'placeholder' => lang('clients_field_description')
	        )
		)
	);

	$this->load->view('common/form/input', array(
			'error' => form_error('clients_field_logo') || isset($clients_field_logo_error),
	        'label' => lang('clients_field_logo'),
	        'image' => ( isset( $client['logo'] ) && $client['logo'] ? showClientPhoto( $client['logo'] ) : null ),
	        'header' => lang('clients_field_logo_header'),
	        'description' => (
	            isset( $client['logo'] ) && $client['logo']
		        ? anchor('manage/clients/remove_image/' . $client['id'], '<i class="icon trash"></i> '.lang('clients_field_logo_delete'), 'class="ui mini button negative"')
		        : '<br><small><i class="info icon"></i>' . lang('clients_field_logo_guidelines') . '></small>'
			),
	        'attributes' => array(
		        'type' => 'item_image',
		        'name' => 'clients_field_logo',
		        'id' => 'clients_field_logo',
		        'value' => set_value('clients_field_logo') ? set_value('clients_field_logo') : ( isset($client['clients_field_logo']) ? $client['clients_field_logo'] : ''),
	        )
		)
	);

?>
			<div class="field">
<?
	if( $this->authorization->is_permitted('delete_clients') && $action == 'update' ) {
		if ( !$client['active'] ) {
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
	</div>
	<div class="clearfix"></div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
