<?php
	/**
	 * Created by PhpStorm.
	 * User: nelsondaza
	 * Date: 10/07/15
	 * Time: 10:21 PM
	 */

	$readOnly = ( isset($consult['creation']) && $consult['creation'] && ( !isset( $editable ) || !$editable ) );

?>
	<div class="<?= ( $actual ? 'active' : '' ) ?> title">
		<i class="dropdown icon"></i><?= $title ?>
<?php
	if( $actual || ( !isset($consult['creation']) || !$consult['creation'] ) ) {
?>
	<div class="ui tiny red tag label">Cerrar caso</div>
<?php
	}
	else {
?>
		<div class="ui small red label"><?= $consult['creation'] ?></div>
<?php
	}
?>

	</div>
	<div class="<?= ( $actual ? 'active' : '' ) ?> content">
<?php
	echo form_open_multipart(uri_string(), 'id="consult_form_' . $index . '" data-index="' . $index . '" class="ui small fluid form closure"');
	echo form_hidden('history_field_id_patient_' . $index, $patient['id']);
	echo form_hidden('history_field_id_consult_' . $index, ( isset($consult['id']) && $consult['id'] ? $consult['id'] : '' ));

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => 'Fecha de Cierre',
			'attributes' => array(
				'readonly' => 'true',
				'type' => 'date',
				'name' => 'history_field_creation',
				'id' => 'history_field_creation',
				'value' => ( isset($consult['creation']) && $consult['creation'] ? $consult['creation'] : date('Y-m-d H:i:s') ),
				'placeholder' => ( isset($consult['creation']) && $consult['creation'] ? $consult['creation'] : date('Y-m-d H:i:s') )
			),
			'cols' => 4,
			'actualCol' => 0
		)
	);

	$options = array();
	$optionName = '';
	foreach( $closures as $closure ) {
		$options[$closure['id']] = $closure['name'];
		if( isset($consult['id_closure']) && $consult['id_closure'] == $closure['id'] )
			$optionName = $closure['name'];
	}

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_id_closure'),
			'options' => $options,
			'selected' => ( isset( $consult['id_closure'] ) && $consult['id_closure'] ? $consult['id_closure'] : null ),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'dropdown',
				'name' => 'history_field_id_closure',
				'id' => 'history_field_id_closure_' . $index,
				'value' => ( isset( $consult['id_closure'] ) && $consult['id_closure'] ? $consult['id_closure'] : null ),
				'placeholder' => ( $readOnly ? $optionName : lang('history_field_id_closure') )
			)
		)
	);

	$options = array();
	$optionName = '';
	foreach( $closures_conditions as $closure_condition ) {
		$options[$closure_condition['id']] = $closure_condition['name'];
		if( isset($consult['id_closure_condition']) && $consult['id_closure_condition'] == $closure_condition['id'] )
			$optionName = $closure_condition['name'];
	}

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_id_closure_condition'),
			'options' => $options,
			'selected' => ( isset( $consult['id_closure_condition'] ) && $consult['id_closure_condition'] ? $consult['id_closure_condition'] : null ),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'dropdown',
				'name' => 'history_field_id_closure_condition',
				'id' => 'history_field_id_closure_condition_' . $index,
				'value' => ( isset( $consult['id_closure_condition'] ) && $consult['id_closure_condition'] ? $consult['id_closure_condition'] : null ),
				'placeholder' => ( $readOnly ? $optionName : lang('history_field_id_closure_condition') )
			)
		)
	);

	$options = array();
	$optionName = '';
	foreach( $consults_types as $consults_type ) {
		$options[$consults_type['id']] = $consults_type['name'];
		if( isset($consult['id_consults_type']) && $consult['id_consults_type'] == $consults_type['id'] )
			$optionName = $consults_type['name'];
	}

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_id_consults_type'),
			'options' => $options,
			'selected' => ( isset( $consult['id_consults_type'] ) && $consult['id_consults_type'] ? $consult['id_consults_type'] : null ),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'dropdown',
				'name' => 'history_field_id_consults_type',
				'id' => 'history_field_id_consults_type_' . $index,
				'value' => ( isset( $consult['id_consults_type'] ) && $consult['id_consults_type'] ? $consult['id_consults_type'] : null ),
				'placeholder' => ( $readOnly ? $optionName : lang('history_field_id_consults_type') )
			)
		)
	);

	$options = array();
	$optionName = '';
	for( $c = 1; $c <= 10; $c ++ ) {
		$options[$c] = $c;
		if( isset($consult['symptoms_severity']) && $consult['symptoms_severity'] == $c )
			$optionName = $c;
	}

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_symptoms_severity') . ' (Ratio)',
			'options' => $options,
			'selected' => ( isset( $consult['symptoms_severity'] ) && $consult['symptoms_severity'] ? $consult['symptoms_severity'] : null ),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'dropdown',
				'name' => 'history_field_symptoms_severity',
				'id' => 'history_field_symptoms_severity_' . $index,
				'value' => ( isset( $consult['symptoms_severity'] ) && $consult['symptoms_severity'] ? $consult['symptoms_severity'] : null ),
				'placeholder' => ( $readOnly ? $optionName : lang('history_field_symptoms_severity') )
			),
			'cols' => 6,
			'actualCol' => 0
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => '<br>(Diferencia)',
			'attributes' => array(
				'readonly' => 'true',
				'type' => 'text',
				'name' => 'history_field_symptoms_severity_dif_' . $index,
				'id' => 'history_field_symptoms_severity_dif_' . $index,
				'value' => 0,
				'placeholder' => ''
			)
		)
	);

	$options = array();
	$optionName = '';
	for( $c = 1; $c <= 10; $c ++ ) {
		$options[$c] = $c;
		if( isset($consult['operation_reduction']) && $consult['operation_reduction'] == $c )
			$optionName = $c;
	}

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_operation_reduction') . ' (Ratio)',
			'options' => $options,
			'selected' => ( isset( $consult['operation_reduction'] ) && $consult['operation_reduction'] ? $consult['operation_reduction'] : null ),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'dropdown',
				'name' => 'history_field_operation_reduction',
				'id' => 'history_field_operation_reduction_' . $index,
				'value' => ( isset( $consult['operation_reduction'] ) && $consult['operation_reduction'] ? $consult['operation_reduction'] : null ),
				'placeholder' => ( $readOnly ? $optionName : lang('history_field_operation_reduction') )
			)
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => '<br>(Diferencia)',
			'attributes' => array(
				'readonly' => 'true',
				'type' => 'text',
				'name' => 'history_field_operation_reduction_dif_' . $index,
				'id' => 'history_field_operation_reduction_dif_' . $index,
				'value' => 0,
				'placeholder' => ''
			)
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => '<br>Total Sesiones',
			'attributes' => array(
				'readonly' => 'true',
				'type' => 'text',
				'name' => 'history_field_total_sessions',
				'id' => 'history_field_total_sessions',
				'value' => ( isset($consult['total_sessions']) && $consult['total_sessions'] ? $consult['total_sessions'] : count( $consults ) ),
				'placeholder' => ( isset($consult['total_sessions']) && $consult['total_sessions'] ? $consult['total_sessions'] : count( $consults ) )
			)
		)
	);

	$time = (int)( ( time( ) - strtotime( $patient['first_session'] ) ) / ( 60 * 60 * 24 ) );

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => '<br>Duración (días)',
			'attributes' => array(
				'readonly' => 'true',
				'type' => 'text',
				'name' => 'history_field_duration',
				'id' => 'history_field_duration',
				'value' => ( isset($consult['duration']) && $consult['duration'] ? $consult['duration'] : $time ),
				'placeholder' => ( isset($consult['duration']) && $consult['duration'] ? $consult['duration'] : $time )
			)
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => 'Observaciones',
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'textarea',
				'name' => 'history_field_comments',
				'id' => 'history_field_comments_' . $index,
				'value' => ( isset( $consult['comments'] ) && $consult['comments'] ? $consult['comments'] : '' ),
				'placeholder' => ( isset( $consult['comments'] ) && $consult['comments'] ? $consult['comments'] : ' - Ninguno -' )
			),
			'cols' => 0,
			'actualCol' => 0
		)
	);



	if( !$readOnly ) {
?>
		<br>
		<div class="field">
			<?= form_button(array('type' => 'button', 'class' => 'ui submit red button small', 'content' => '<i class="archive icon"></i> '.lang('history_close'))); ?>
		</div>
<?php
	}
	else {
?>
		<br>
		<div class="field">
			<a href="<?= base_url()?>history/closure/<?= $consult['id'] ?>" class="ui orange button small"><i class="edit icon"></i> <?= lang('history_edit') ?></a>
		</div>
<?php
	}
	if( isset( $back ) && $back ) {
?>
		<br>
		<div class="field">
			<?= $back ?>
		</div>
<?php
	}
?>

		<?= form_close() ?>
	</div>
	<script type="text/javascript">
		$(function(){

<?php
	if( count( $consults ) > 0 && $index > 0 ) {
?>
			$('#history_field_operation_reduction_<?= $index ?>').change(function(){
				var value = parseInt( $(this).val() );
				$('#history_field_operation_reduction_dif_<?= $index ?>').parent().children('.read-only').text(
					<?= $consults[0]['operation_reduction'] ?> - value
				);
			}).change();
			$('#history_field_symptoms_severity_<?= $index ?>').change(function(){
				var value = parseInt( $(this).val() );
				$('#history_field_symptoms_severity_dif_<?= $index ?>').parent().children('.read-only').text(
					<?= $consults[0]['symptoms_severity'] ?> - value
				);
			}).change();
<?php
	}
?>
		});
	</script>
<?php
