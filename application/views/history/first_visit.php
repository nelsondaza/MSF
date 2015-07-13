<?php
	/**
	 * Created by PhpStorm.
	 * User: nelsondaza
	 * Date: 10/07/15
	 * Time: 10:21 PM
	 */

	$readOnly = !!$patient['gender'];
?>
	<script type="text/javascript" src="<?= base_url() ?>resources/js/history.js"></script>
	<div class="<?= ( !$patient['gender'] ? 'active' : '' )?> title">
		<i class="dropdown icon"></i>Primera Visita
<?php
	if( !$patient['gender'] ) {
?>
	<div class="ui tiny teal tag label">Actual</div>
<?php
	}
	else {
?>
		<div class="ui small label"><?= $patient['code'] . ' (' . $patient['first_name'] . ' ' . $patient['last_name'] . ')' ?></div>
<?php
	}
?>

	</div>
	<div class="<?= ( !$patient['gender'] ? 'active' : '' )?> content">
<?php
	echo form_open_multipart(uri_string(), 'id="first_visit_form" class="ui small fluid form ' . ( !empty($errors) ? 'error' : '' ) . '"');

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_start_date'),
			'attributes' => array(
				'readonly' => 'true',
				'type' => 'date',
				'name' => 'history_field_start_date',
				'id' => 'history_field_start_date',
				'value' => $patient['first_session'],
				'placeholder' => '- No iniciada -'
			),
			'cols' => 5,
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
				'value' => $patient['reopen_date'],
				'placeholder' => '- No aplica -'
			)
		)
	);

	$options = array();
	$optionName = '';
	foreach( $localizations as $localization ) {
		if( !isset( $options[$localization['region']] ) )
			$options[$localization['region']] = array();
		$options[$localization['region']][$localization['id']] = $localization['name'];
		if( isset($patient['id_localization']) && $patient['id_localization'] == $localization['id'] )
			$optionName = $localization['name'];
	}

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_id_localization'),
			'options' => $options,
			'selected' => ( isset( $patient['id_localization'] ) && $patient['id_localization'] ? $patient['id_localization'] : null ),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'dropdown',
				'name' => 'history_field_id_localization',
				'id' => 'history_field_id_localization',
				'value' => (isset($patient['id_localization']) ? $patient['id_localization'] : ''),
				'placeholder' => ( $readOnly ? $optionName : lang('history_field_id_localization') )
			)
		)
	);

	$options = array();
	$optionName = '';
	foreach( $origin_places as $origin_place ) {
		$options[$origin_place['id']] = $origin_place['name'];
		if( isset($patient['id_origin_place']) && $patient['id_origin_place'] == $origin_place['id'] )
			$optionName = $origin_place['name'];
	}

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_id_origin_place'),
			'options' => $options,
			'selected' => ( isset( $patient['id_origin_place'] ) && $patient['id_origin_place'] ? $patient['id_origin_place'] : null ),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'dropdown',
				'name' => 'history_field_id_origin_place',
				'id' => 'history_field_id_origin_place',
				'value' => ( isset( $patient['id_origin_place'] ) && $patient['id_origin_place'] ? $patient['id_origin_place'] : null ),
				'placeholder' => ( $readOnly ? $optionName : lang('history_field_id_origin_place') )
			)
		)
	);

	$options = array();
	$optionName = '';
	foreach( $experts as $expert ) {
		$options[$expert['id']] = $expert['name'];
		if( isset($patient['id_expert']) && $patient['id_expert'] == $expert['id'] )
			$optionName = $expert['name'];
	}

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_id_expert'),
			'options' => $options,
			'selected' => ( isset( $patient['id_expert'] ) && $patient['id_expert'] ? $patient['id_expert'] : null ),
			'attributes' => array(
				'readonly' => $readOnly,
				'type' => 'dropdown',
				'name' => 'history_field_id_expert',
				'id' => 'history_field_id_expert',
				'value' => ( isset( $patient['id_expert'] ) && $patient['id_expert'] ? $patient['id_expert'] : null ),
				'placeholder' => ( $readOnly ? $optionName : lang('history_field_id_expert') )
			)
		)
	);


	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => lang('history_field_id_patient'),
			'attributes' => array(
				'readonly' => 'true',
				'type' => 'text',
				'name' => 'history_field_id_patient',
				'id' => 'history_field_id_patient',
				'value' => $patient['id'],
				'placeholder' => $patient['code'] . ' (' . $patient['first_name'] . ' ' . $patient['last_name'] . ')'
			),
			'cols' => 5,
			'actualCol' => 0
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
	if( !$readOnly ) {
?>
		<br>
		<div class="field">
			<?= form_button(array('type' => 'submit', 'class' => 'ui submit primary button small', 'content' => '<i class="archive icon"></i> '.lang('history_save'))); ?>
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
		});
	</script>
<?php
