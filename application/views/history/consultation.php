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
	if( $actual && ( !isset($consult['creation']) || !$consult['creation'] ) ) {
?>
	<div class="ui tiny teal tag label">Actual</div>
<?php
	}
	else {
?>
		<div class="ui small label"><?= $consult['creation'] ?></div>
<?php
	}
?>

	</div>
	<div class="<?= ( $actual ? 'active' : '' ) ?> content">
<?php
	echo form_open_multipart(uri_string(), 'id="consult_form_' . $index . '" data-index="' . $index . '" class="ui small fluid form consultation"');
	echo form_hidden('history_field_id_patient_' . $index, $patient['id']);
	echo form_hidden('history_field_id_consult_' . $index, ( isset($consult['id']) && $consult['id'] ? $consult['id'] : '' ));

	$options = array();
	foreach( $symptoms as $symptom ) {
		if( !isset( $options[$symptom['category']] ) )
			$options[$symptom['category']] = array();
			$options[$symptom['category']][] = array(
			'name' => 'symptoms[]',
			'label' => $symptom['name'],
			'value' => $symptom['id']
		);
	}

	$selected = array();
	if( isset( $consult['symptoms'] ) && $consult['symptoms'] ) {
		foreach( $consult['symptoms'] as $pr ) {
			$selected[] = $pr['id_symptom'];
		}
	}

	$total = 2;
	$col = 0;
	foreach( $options as $cat => $opts ) {
		$this->load->view('common/form/input', array(
				'error' => form_error('history_field_id_symptom') || isset($history_field_id_symptom_error),
				'label' => $cat,
				'options' => $opts,
				'selected' => $selected,
				'attributes' => array(
					'readonly' => $readOnly,
					'type' => 'multicheckbox',
					'name' => 'history_field_id_symptom[]',
					'id' => 'history_field_id_symptom_' . $index,
					'value' => null,
					'placeholder' => lang('history_field_id_symptom'),
					'group' => ( $col == 0 ? 'Síntomas' : null ),
					'group-end' => ( $col == count( $options ) - 1 ),
				),
				'cols' => $total,
				'actualCol' => $col % 2
			)
		);
		$col++;
	}

	$options = array();
	foreach( $diagnostics as $diagnostic ) {
		$options[] = array(
			'name' => 'diagnostics[]',
			'label' => $diagnostic['name'],
			'value' => $diagnostic['id']
		);
	}

	$selected = array();
	if( isset( $consult['diagnostics'] ) && $consult['diagnostics'] ) {
		foreach( $consult['diagnostics'] as $pr ) {
			$selected[] = $pr['id_diagnostic'];
		}
	}


	$this->load->view('common/form/input', array(
			'error' => form_error('history_field_id_diagnostic') || isset($history_field_id_diagnostic_error),
			'label' => lang('history_field_id_diagnostic'),
			'options' => $options,
			'selected' => $selected,
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'multicheckbox',
				'name' => 'history_field_id_diagnostic',
				'id' => 'history_field_id_diagnostic_' . $index,
				'value' => null,
				'placeholder' => lang('history_field_id_diagnostic'),
				'cols' => 3,
				'group' => 'Diagnóstico',
				'group-end' => true
			),
			'cols' => 0,
			'actualCol' => 0
		)
	);

	$options = array();
	foreach( $risks as $risk ) {
		if( !isset( $options[$risk['category']] ) )
			$options[$risk['category']] = array();
		$options[$risk['category']][] = array(
			'name' => 'risks[]',
			'label' => $risk['name'],
			'value' => $risk['id']
		);
	}
	$selected = array();
	if( isset( $consult['risks'] ) && $consult['risks'] ) {
		foreach( $consult['risks'] as $pr ) {
			$selected[] = $pr['id_risk'];
		}
	}

	$total = 2;
	$col = 0;
	foreach( $options as $cat => $opts ) {
		$this->load->view('common/form/input', array(
				'error' => form_error('history_field_id_risk') || isset($history_field_id_risk_error),
				'label' => $cat,
				'options' => $opts,
				'selected' => $selected,
				'attributes' => array(
					'readonly' => $readOnly,
					'type' => 'multicheckbox',
					'name' => 'history_field_id_risk[]',
					'id' => 'history_field_id_risk_' . $index,
					'value' => null,
					'placeholder' => lang('history_field_id_risk'),
					'group' => ( $col == 0 ? 'Factores de Evento / Riesgo' : null ),
					'group-end' => ( $col == count( $options ) - 1 ),
				),
				'cols' => $total,
				'actualCol' => $col
			)
		);
		$col ++;
	}

	echo "<br>";

	$options = array();
	$optionName = '';
	foreach( $consults_types as $consults_type ) {
		$options[$consults_type['id']] = $consults_type['name'];
		if( isset($consult['id_consults_type']) && $consult['id_consults_type'] == $consults_type['id'] )
			$optionName = $consults_type['name'];
	}

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => '<br>' . lang('history_field_id_consults_type'),
			'options' => $options,
			'selected' => ( isset( $consult['id_consults_type'] ) && $consult['id_consults_type'] ? $consult['id_consults_type'] : null ),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'dropdown',
				'name' => 'history_field_id_consults_type',
				'id' => 'history_field_id_consults_type_' . $index,
				'value' => ( isset( $consult['id_consults_type'] ) && $consult['id_consults_type'] ? $consult['id_consults_type'] : null ),
				'placeholder' => ( $readOnly ? $optionName : lang('history_field_id_consults_type') )
			),
			'cols' => 7,
			'actualCol' => 0
		)
	);

	$options = array();
	$optionName = '';
	foreach( $interventions_types as $interventions_type ) {
		$options[$interventions_type['id']] = $interventions_type['name'];
		if( isset($consult['id_interventions_type']) && $consult['id_interventions_type'] == $interventions_type['id'] )
			$optionName = $interventions_type['name'];
	}

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => '<br>' . lang('history_field_id_interventions_type'),
			'options' => $options,
			'selected' => ( isset( $consult['id_interventions_type'] ) && $consult['id_interventions_type'] ? $consult['id_interventions_type'] : null ),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'dropdown',
				'name' => 'history_field_id_interventions_type',
				'id' => 'history_field_id_interventions_type_' . $index,
				'value' => ( isset( $consult['id_interventions_type'] ) && $consult['id_interventions_type'] ? $consult['id_interventions_type'] : null ),
				'placeholder' => ( $readOnly ? $optionName : lang('history_field_id_interventions_type') )
			)
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => '<br>' . lang('history_field_date'),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'datetime',
				'name' => 'history_field_date',
				'id' => 'history_field_date_' . $index,
				'value' => ( isset( $consult['date'] ) && $consult['date'] ? $consult['date'] : date('Y-m-d H:i:s') ),
				'placeholder' => ( $readOnly ? ( isset( $consult['date'] ) && $consult['date'] ? $consult['date'] : date('Y-m-d H:i:s') ) : lang('history_field_date') )
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
			)
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

	$options = array();
	$optionName = '-';
	foreach( $references as $reference ) {
		if( !isset( $options[$reference['category']] ) ) {
			$options[$reference['category']] = array();
			$options[$reference['category']][''] = ' - Ninguno - ';
		}
		$options[$reference['category']][$reference['id']] = $reference['name'];

		if( isset($consult['id_referenced_to']) && $consult['id_referenced_to'] == $reference['id'] )
			$optionName = $reference['name'];
	}

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => 'Referido Hacia',
			'options' => $options['A'],
			'selected' => ( isset( $consult['id_referenced_to'] ) && $consult['id_referenced_to'] ? $consult['id_referenced_to'] : null ),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'dropdown',
				'name' => 'history_field_id_referenced_to',
				'id' => 'history_field_id_referenced_to_' . $index,
				'value' => ( isset( $consult['id_referenced_to'] ) && $consult['id_referenced_to'] ? $consult['id_referenced_to'] : null ),
				'placeholder' => ( $readOnly ? $optionName : 'Referido Hacia' )
			),
			'cols' => 4,
			'actualCol' => 0
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => 'Fecha de Referido',
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'date',
				'name' => 'history_field_referenced_date',
				'id' => 'history_field_referenced_date_' . $index,
				'value' => ( isset( $consult['referenced_date'] ) && $consult['referenced_date'] ? $consult['referenced_date'] : '' ),
				'placeholder' => ( isset( $consult['referenced_date'] ) && $consult['referenced_date'] ? $consult['referenced_date'] : 'yyyy-mm-dd' )
			)
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => 'Psicotrópicos',
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'checkbox',
				'name' => 'history_field_psychotropics',
				'id' => 'history_field_psychotropics_' . $index,
				'value' => '1',
				'checked' => ( isset( $consult['psychotropics'] ) && $consult['psychotropics'] ),
				'placeholder' => 'Psicotrópicos'
			)
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => 'Fecha de Inicio de Psicotrópicos',
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'date',
				'name' => 'history_field_psychotropics_date',
				'id' => 'history_field_psychotropics_date_' . $index,
				'value' => ( isset( $consult['psychotropics_date'] ) && $consult['psychotropics_date'] ? $consult['psychotropics_date'] : '' ),
				'placeholder' => ( isset( $consult['psychotropics_date'] ) && $consult['psychotropics_date'] ? $consult['psychotropics_date'] : 'yyyy-mm-dd' )
			)
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => 'Comentarios',
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
			<?= form_button(array('type' => 'button', 'class' => 'ui submit primary button small', 'content' => '<i class="archive icon"></i> '.lang('history_save'))); ?>
		</div>
<?php
	}
	else {
?>
		<br>
		<div class="field">
			<a href="<?= base_url()?>history/consult/<?= $consult['id'] ?>" class="ui orange button small"><i class="edit icon"></i> <?= lang('history_edit') ?></a>
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
			$('#history_field_age').keyup(function() {
				var text = $(this).val().replace(/([^0-9]+)/g,'');
				if( text != $(this).val( ) )
					$(this).val(parseInt( text, 10 ));

				text = parseInt( text, 10 );
				$('#history_field_age_group').val(
					( text <= 5 ? '≤ 5' : ( text >= 19 ? '≥ 19' : '6-18' ) )
				).parent().children('.read-only').text(
					( text <= 5 ? '≤ 5' : ( text >= 19 ? '≥ 19' : '6-18' ) )
				);
			}).keyup();

<?php
	if( count( $consults ) > 0 && $index > 0 ) {
?>
			$('#history_field_operation_reduction_<?= $index ?>').change(function(){
				var value = parseInt( $(this).val() );
				$('#history_field_operation_reduction_dif_<?= $index ?>').parent().children('.read-only').text(
					<?= $consults[$index - 1]['operation_reduction'] ?> - value
				);
			}).change();
			$('#history_field_symptoms_severity_<?= $index ?>').change(function(){
				var value = parseInt( $(this).val() );
				$('#history_field_symptoms_severity_dif_<?= $index ?>').parent().children('.read-only').text(
					<?= $consults[$index - 1]['symptoms_severity'] ?> - value
				);
			}).change();
<?php
	}
?>
		});
	</script>
<?php
