<?php
	/**
	 * Created by PhpStorm.
	 * User: nelsondaza
	 * Date: 10/07/15
	 * Time: 10:21 PM
	 */

	$readOnly = ( !!$patient['gender'] && ( !isset( $editable ) || !$editable ) );
	$actual = !$patient['gender'] || ( isset( $editable ) && $editable );
?>
	<div class="<?= ( $actual ? 'active' : '' )?> title">
		<i class="dropdown icon"></i>Primera Visita
<?php
	if( !$patient['gender'] ) {
?>
	<div class="ui tiny teal tag label">Actual</div>
<?php
	}
	else {
?>
		<div class="ui small label"><?= $patient['first_session'] ?></div>
		<div class="ui small label"><?= $patient['code'] . ' (' . $patient['first_name'] . ' ' . $patient['last_name'] . ')' ?></div>
<?php
	}
?>

	</div>
	<div class="<?= ( $actual ? 'active' : '' )?> content">
<?php
	echo form_open_multipart(uri_string() . '/', 'id="first_visit_form" class="ui small fluid form ' . ( !empty($errors) ? 'error' : '' ) . '"');
	echo form_hidden('history_field_id_expert', $patient['id_expert'] );
	echo form_hidden('history_field_id_patient', $patient['id'] );

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_start_date'),
			'attributes' => array(
				'readonly' => 'true',
				'type' => 'date',
				'name' => 'history_field_start_date',
				'id' => 'history_field_start_date',
				'value' => ( $patient['first_session'] ? $patient['first_session'] : '- No iniciada -' ),
				'placeholder' => ( $patient['first_session'] ? $patient['first_session'] : '- No iniciada -' )
			),
			'cols' => 6,
			'actualCol' => 0
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_open_date'),
			'attributes' => array(
				'readonly' => 'true',
				'type' => 'date',
				'name' => 'history_field_open_date',
				'id' => 'history_field_open_date',
				'value' => ( $patient['reopen_date'] ? $patient['reopen_date'] : '- No cerrada -' ),
				'placeholder' => ( $patient['reopen_date'] ? $patient['reopen_date'] : '- No cerrada -' )
			)
		)
	);

	$options = array();
	foreach( $regions as $region ) {
		$options[$region['id']] = $region['name'];
	}

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_id_region'),
			'options' => $options,
			'selected' => $regions[0]['id'],
			'attributes' => array(
				'readonly' => true,
				'type' => 'dropdown',
				'name' => 'history_field_id_region',
				'id' => 'history_field_id_region',
				'value' => $regions[0]['id'],
				'placeholder' => $regions[0]['name']
			)
		)
	);

	$options = array(
		array(
			1 => 'BUENAVENTURA'
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_id_localization'),
			'options' => $options,
			'selected' => 1,
			'attributes' => array(
				'readonly' => true,
				'type' => 'dropdown',
				'name' => 'history_field_id_regionx',
				'id' => 'history_field_id_regionx',
				'value' => 1,
				'placeholder' => 'BUENAVENTURA'
			)
		)
	);

	$options = array();
	$optionName = '';
	foreach( $localizations as $localization ) {
		$options[$localization['id']] = $localization['name'];
		if( isset($patient['id_localization']) && $patient['id_localization'] == $localization['id'] )
			$optionName = $localization['name'];
	}

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_id_origin_place'),
			'options' => $options,
			'selected' => ( isset( $patient['id_localization'] ) && $patient['id_localization'] ? $patient['id_localization'] : null ),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'dropdown',
				'name' => 'history_field_id_localization',
				'id' => 'history_field_id_localization',
				'value' => ( isset( $patient['id_localization'] ) && $patient['id_localization'] ? $patient['id_localization'] : null ),
				'placeholder' => ( $readOnly ? $optionName : lang('history_field_id_localization') )
			)
		)
	);



	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_id_expert'),
			'attributes' => array(
				'readonly' => 'true',
				'type' => 'text',
				'name' => 'history_field_id_expert_name',
				'id' => 'history_field_id_expert_name',
				'value' => $patient['expert'],
				'placeholder' => $patient['expert']
			)
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_code_patient'),
			'attributes' => array(
				'readonly' => 'true',
				'type' => 'text',
				'name' => 'history_field_code_patient',
				'id' => 'history_field_code_patient',
				'value' => $patient['code'],
				'placeholder' => $patient['code']
			),
			'cols' => 7,
			'actualCol' => 0
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_first_name'),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'text',
				'name' => 'history_field_first_name',
				'id' => 'history_field_first_name',
				'value' => $patient['first_name'],
				'placeholder' => $patient['first_name']
			)
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_last_name'),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'text',
				'name' => 'history_field_last_name',
				'id' => 'history_field_last_name',
				'value' => $patient['last_name'],
				'placeholder' => $patient['last_name']
			)
		)
	);

	$options = array(
		'M' => 'Masculino',
		'F' => 'Femenino',
		'I' => 'Indefinido'
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_gender'),
			'options' => $options,
			'selected' => ( isset( $patient['gender'] ) && $patient['gender'] ? $patient['gender'] : null ),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'dropdown',
				'name' => 'history_field_gender',
				'id' => 'history_field_gender',
				'value' => ( isset( $patient['gender'] ) && $patient['gender'] ? $patient['gender'] : null ),
				'placeholder' => ( $readOnly ? ( isset( $patient['gender'] ) && $patient['gender'] ? $options[$patient['gender']] : null ) : lang('history_field_gender') )
			)
		)
	);

	$options = array();
	$optionName = '';
	foreach( $educations as $education ) {
		$options[$education['id']] = $education['name'];
		if( isset($patient['id_education']) && $patient['id_education'] == $education['id'] )
			$optionName = $education['name'];
	}

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_id_education'),
			'options' => $options,
			'selected' => ( isset( $patient['id_education'] ) && $patient['id_education'] ? $patient['id_education'] : null ),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'dropdown',
				'name' => 'history_field_id_education',
				'id' => 'history_field_id_education',
				'value' => ( isset( $patient['id_education'] ) && $patient['id_education'] ? $patient['id_education'] : null ),
				'placeholder' => ( $readOnly ? $optionName : lang('history_field_id_education') )
			)
		)
	);


	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_age'),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'text',
				'name' => 'history_field_age',
				'id' => 'history_field_age',
				'value' => ( isset($patient['age']) ? $patient['age'] : 0),
				'placeholder' => ( $readOnly ? ( isset($patient['age']) ? $patient['age'] : 0) : lang('history_field_age') )
			)
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_age_group'),
			'attributes' => array(
				'readonly' => 'true',
				'type' => 'text',
				'name' => 'history_field_age_group',
				'id' => 'history_field_age_group',
				'value' => ( isset($patient['age_group']) ? $patient['age_group'] : '≤ 5'),
				'placeholder' => ( isset($patient['age_group']) ? $patient['age_group'] : '≤ 5')
			)
		)
	);


	$options = array();
	foreach( $references as $reference ) {
		if( !isset( $options[$reference['category']] ) )
			$options[$reference['category']] = array();
		$options[$reference['category']][] = array(
			'label' => $reference['name'],
			'value' => $reference['id']
		);
	}

	$selected = array();
	foreach( $patient_references as $pr ) {
		$selected[] = $pr['id_reference'];
	}

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_id_reference'),
			'options' => $options['DE'],
			'selected' => $selected,
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'multicheckbox',
				'name' => 'history_field_id_reference',
				'id' => 'history_field_id_reference',
				'value' => null,
				'placeholder' => lang('history_field_id_reference'),
				'cols' => 3,
				'group' => 'Referido',
				'group-end' => true
			),
			'cols' => 0,
			'actualCol' => 0
		)
	);

	/*
	echo "<br>";
	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => 'Diagnóstico',
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'textarea',
				'name' => 'history_field_diagnostic',
				'id' => 'history_field_diagnostic',
				'value' => ( isset( $consult['diagnostic'] ) && $patient['diagnostic'] ? $patient['diagnostic'] : '' ),
				'placeholder' => ( isset( $patient['diagnostic'] ) && $patient['diagnostic'] ? $patient['diagnostic'] : ' - Ninguno -' )
			),
			'cols' => 0,
			'actualCol' => 0
		)
	);
	*/


	if( !$readOnly ) {
?>
		<br>
		<div class="field">
			<?= form_button(array('name' => 'save', 'type' => 'button', 'class' => 'ui submit primary button small', 'content' => '<i class="archive icon"></i> '.lang('history_save'))); ?>
		</div>
<?php
	}
	else {
?>
		<br>
		<div class="field">
			<a href="<?= base_url()?>history/first_visit/<?= $patient['id'] ?>" class="ui orange button small"><i class="edit icon"></i> <?= lang('history_edit') ?></a>
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
	if( false && !$readOnly ) {
?>
			var villages = {};
			function addV( idV, idL, nameL ){
				villages[idV] = (villages[idV] || []);
				villages[idV].push({
					id:idL,
					name:nameL
				});
			}
<?php
		foreach( $localizations as $localization ) {
				echo "addV(" . $localization['id_village'] . "," . $localization['id'] . ",'" . htmlspecialchars( $localization['name'], ENT_QUOTES, 'UTF-8' ) . "');";
		}
?>
			function selectV(idV) {
				$('#history_field_id_localization').empty();
				$.each(villages[idV],function(index,village){
					$('#history_field_id_localization').append(
						$('<option value="' + village.id + '">' + village.name + '</option>'));
				});
				$('#history_field_id_localization').trigger("chosen:updated");
			}

			$('#history_field_id_village').change(function(){
				selectV( $(this).val() );
			}).change();
<?php
	}
?>
		});
	</script>
<?php
