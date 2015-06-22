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
	<div class="sub-header"><i class="bullseye icon"></i> <?= lang($class . "_{$action}_page_name") ?></div>
	<div class="section">
<?php
    echo $this->load->view( 'common/breadcrumb', array(
        'path' => array(
            lang( $class . '_page_name' ) => $scope . '/' . $class,
            lang( $class . "_{$action}_page_name" ) => null
        )
    ));

	$errors = array( );

	if( isset( $measures_field_name_error ) )
		$errors[] = $measures_field_name_error;
	if( form_error('measures_field_name') )
		$errors[] = form_error('measures_field_name');
	if( form_error('measures_field_source_measure') )
		$errors[] = form_error('measures_field_source_measure');
	if( form_error('measures_field_description') )
		$errors[] = form_error('measures_field_description');

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
			<a class="active item" data-tab="gral"><?= lang('measures_tab_title') ?></a>
<?php
		if( isset( $measure['source_measure'] ) && $measure['source_measure'] ) {
?>
			<a class="item" data-tab="measure"><?= lang( 'measures_config_tab_title' ) ?></a>
<?php
		}
?>
		</div>
		<div class="ui tertiary active tab segment" data-tab="gral">
<?php

	$this->load->view('common/form/input', array(
			'error' => form_error('measures_field_name') || isset($field_name_error),
	        'label' => lang('measures_field_name'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'measures_field_name',
		        'id' => 'measures_field_name',
		        'value' => set_value('measures_field_name') ? set_value('measures_field_name') : ( isset( $measure['name'] ) ? $measure['name'] : ''),
		        'maxlength' => '80',
		        'placeholder' => lang('measures_field_name')
	        )
		)
	);

    $this->load->view('common/form/input', array(
			'error' => form_error('measures_field_description') || isset($measures_field_description_error),
	        'label' => lang('measures_field_description'),
	        'attributes' => array(
		        'type' => 'textarea',
		        'name' => 'measures_field_description',
		        'id' => 'measures_field_description',
		        'value' => set_value('measures_field_description') ? set_value('measures_field_description') : (isset($measure['description']) ? $measure['description'] : ''),
                'maxlength' => 160,
                'rows'=>'4',
		        'placeholder' => lang('measures_field_description')
	        )
		)
	);

	$options = array();
	foreach( $sources as $source ) {
		$options[$source['id']] = $source['name'];
	}

    $this->load->view('common/form/input', array(
			'error' => form_error('measures_field_id_source') || isset($measures_field_id_source_error),
	        'label' => lang('measures_field_id_source'),
	        'options' => $options,
	        'selected' => ( isset( $measure['id_source'] ) && $measure['id_source'] ? $measure['id_source'] : null ),
	        'attributes' => array(
		        'type' => 'dropdown',
		        'name' => 'measures_field_id_source',
		        'id' => 'measures_field_id_source',
		        'value' => set_value('measures_field_id_source') ? set_value('measures_field_id_source') : (isset($measure['id_source']) ? $measure['id_source'] : ''),
		        'placeholder' => lang('measures_field_id_source')
	        )
		)
	);

    $this->load->view('common/form/input', array(
			'error' => form_error('measures_field_source_measure') || isset($measures_field_source_measure_error),
	        'label' => lang('measures_field_source_measure'),
	        'options' => ( isset( $measure['id_source'] ) && $measure['id_source'] && isset( $measures[$measure['id_source']] ) ? $measures[$measure['id_source']] : null ),
	        'selected' => ( isset( $measure['source_measure'] ) && $measure['source_measure'] ? $measure['source_measure'] : null ),
	        'attributes' => array(
		        'type' => 'dropdown',
		        'name' => 'measures_field_source_measure',
		        'id' => 'measures_field_source_measure',
		        'value' => set_value('measures_field_source_measure') ? set_value('measures_field_source_measure') : (isset($measure['source_measure']) ? $measure['source_measure'] : ''),
		        'placeholder' => lang('measures_field_source_measure')
	        )
		)
	);

?>
			<script type="text/javascript">
				var measures = <?= json_encode( $measures ) ?>;
				$('#measures_field_id_source').change(function(){
					$('#measures_field_source_measure').empty();
					var list = measures[$('#measures_field_id_source').val()];

					for ( var key in list ) {
						if ( list.hasOwnProperty( key ) )
							$('#measures_field_source_measure').append($("<option></option>").attr("value",key).text(list[key]));
					}
				});
				$(function(){
					if( $('#measures_field_source_measure option').length == 0 ) {
						$('#measures_field_id_source').change();
					}
				});
			</script>
		</div>
		<div class="ui tertiary tab segment" data-tab="measure">
<?php
		if( isset( $measure['source_measure'] ) && $measure['source_measure'] )
			$this->load->view( $scope . '/' . $class . '/' . $measure['source_measure'], $_ci_vars );
?>
		</div>
		<div class="field">
<?php
	if( $this->authorization->is_permitted('delete_measures') && $action == 'update' ) {
		if ( !$measure['active'] ) {
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
