<?php
	$action = 'create';
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang($class . '_page_name')) ) ?>
</head>
<body>
<?= $this->load->view('common/header', array('current' => 'visits_' . $class )) ?>
<div class="container content">
	<div class="sub-header"><i class="world icon"></i> <?= lang($class . "_{$action}_page_name") ?></div>
	<div class="section">
<?php
	$errors = array( );
	if( isset( $profile_username_error ) )
		$errors[] = $profile_username_error;
	if( form_error('profile_username') )
		$errors[] = form_error('profile_username');
	if( isset( $profile_picture_error ) )
		$errors[] = $profile_picture_error;
	if( form_error('profile_picture') )
		$errors[] = form_error('profile_picture');

	if ( !empty($errors) ) {
		$this->load->view('common/message', array('type' => 'error','class' => 'warning','content' => $errors) );
	}
	else if (isset($profile_info)) {
		$this->load->view('common/message', array('type' => 'success','class' => 'info','content' => $profile_info) );
	}
?>
		<div class="ui tertiary segment container-form">
<?php
	echo form_open_multipart(uri_string(), 'class="ui small fluid form ' . ( !empty($errors) ? 'error' : '' ) . '"');

	$this->load->view('common/form/input', array(
			'error' => form_error('visits_field_start_date') || isset($visits_field_start_date_error),
	        'label' => lang('visits_field_start_date'),
	        'attributes' => array(
				'readonly' => 'true',
		        'type' => 'date',
		        'name' => 'visits_field_start_date',
		        'id' => 'visits_field_start_date',
		        'value' => set_value('visits_field_start_date') ? set_value('visits_field_start_date') : ( isset($visit['start_date']) ? $visit['start_date'] : date('Y-m-d')),
		        'placeholder' => lang('visits_field_start_date')
	        ),
			'cols' => 4,
			'actualCol' => 0
		)
	);

	$this->load->view('common/form/input', array(
			'error' => form_error('visits_field_open_date') || isset($visits_field_open_date_error),
	        'label' => lang('visits_field_open_date'),
	        'attributes' => array(
				'readonly' => 'true',
		        'type' => 'date',
		        'name' => 'visits_field_open_date',
		        'id' => 'visits_field_open_date',
		        'value' => set_value('visits_field_open_date') ? set_value('visits_field_open_date') : ( isset($visit['open_date']) ? $visit['open_date'] : 'N/A'),
		        'placeholder' => lang('visits_field_open_date')
	        )
		)
	);

	$options = array();
	foreach( $regions as $region ) {
		$options[$region['id']] = $region['name'];
	}

	$this->load->view('common/form/input', array(
			'error' => form_error('visits_field_id_region') || isset($visits_field_id_region_error),
			'label' => lang('visits_field_id_region'),
			'options' => $options,
			'selected' => ( isset( $visit['id_region'] ) && $visit['id_region'] ? $visit['id_region'] : null ),
			'attributes' => array(
				'type' => 'dropdown',
				'name' => 'visits_field_id_region',
				'id' => 'visits_field_id_region',
				'value' => set_value('visits_field_id_region') ? set_value('visits_field_id_region') : (isset($visit['id_region']) ? $visit['id_region'] : ''),
				'placeholder' => lang('visits_field_id_region')
			)
		)
	);


	$options = array();
	foreach( $interventions_places as $interventions_place ) {
		$options[$interventions_place['id']] = $interventions_place['name'];
	}

	$this->load->view('common/form/input', array(
			'error' => form_error('visits_field_id_interventions_place') || isset($visits_field_id_interventions_place_error),
			'label' => lang('visits_field_id_interventions_place'),
			'options' => $options,
			'selected' => ( isset( $visit['id_interventions_place'] ) && $visit['id_interventions_place'] ? $visit['id_interventions_place'] : null ),
			'attributes' => array(
				'type' => 'dropdown',
				'name' => 'visits_field_id_interventions_place',
				'id' => 'visits_field_id_interventions_place',
				'value' => set_value('visits_field_id_interventions_place') ? set_value('visits_field_id_interventions_place') : (isset($visit['id_interventions_place']) ? $visit['id_interventions_place'] : ''),
				'placeholder' => lang('visits_field_id_interventions_place')
			)
		)
	);

	$this->load->view('common/form/input', array(
			'error' => form_error('visits_field_id_patient') || isset($visits_field_id_patient_error),
			'label' => lang('visits_field_id_patient'),
			'attributes' => array(
				'readonly' => 'true',
				'type' => 'date',
				'name' => 'visits_field_id_patient',
				'id' => 'visits_field_id_patient',
				'value' => set_value('visits_field_id_patient') ? set_value('visits_field_id_patient') : ( isset($visit['id_patient']) ? $visit['id_patient'] : 'N/A'),
				'placeholder' => lang('visits_field_id_patient')
			)
		)
	);

	$this->load->view('common/form/input', array(
			'error' => form_error('visits_field_id_expert') || isset($visits_field_id_expert_error),
			'label' => lang('visits_field_id_expert'),
			'attributes' => array(
				'readonly' => 'true',
				'type' => 'date',
				'name' => 'visits_field_id_expert',
				'id' => 'visits_field_id_expert',
				'value' => set_value('visits_field_id_expert') ? set_value('visits_field_id_expert') : ( isset($visit['id_expert']) ? $visit['id_expert'] : 'N/A'),
				'placeholder' => lang('visits_field_id_expert')
			)
		)
	);

	$options = array(
		'M' => 'Masculino',
		'F' => 'Femenino',
		'I' => 'Indefinido'
	);

	$this->load->view('common/form/input', array(
			'error' => form_error('visits_field_gender') || isset($visits_field_gender_error),
			'label' => lang('visits_field_gender'),
			'options' => $options,
			'selected' => ( isset( $visit['gender'] ) && $visit['gender'] ? $visit['gender'] : null ),
			'attributes' => array(
				'type' => 'dropdown',
				'name' => 'visits_field_gender',
				'id' => 'visits_field_gender',
				'value' => set_value('visits_field_gender') ? set_value('visits_field_gender') : (isset($visit['gender']) ? $visit['gender'] : ''),
				'placeholder' => lang('visits_field_gender')
			)
		)
	);

	$this->load->view('common/form/input', array(
			'error' => form_error('visits_field_age') || isset($visits_field_age_error),
			'label' => lang('visits_field_age'),
			'attributes' => array(
				'type' => 'text',
				'name' => 'visits_field_age',
				'id' => 'visits_field_age',
				'value' => set_value('visits_field_age') ? set_value('visits_field_age') : ( isset($visit['age']) ? $visit['age'] : 0),
				'placeholder' => lang('visits_field_age')
			)
		)
	);

	$options = array();
	foreach( $educations as $education ) {
		$options[$education['id']] = $education['name'];
	}

	$this->load->view('common/form/input', array(
			'error' => form_error('visits_field_id_education') || isset($visits_field_id_education_error),
			'label' => lang('visits_field_id_education'),
			'options' => $options,
			'selected' => ( isset( $visit['id_education'] ) && $visit['id_education'] ? $visit['id_education'] : null ),
			'attributes' => array(
				'type' => 'dropdown',
				'name' => 'visits_field_id_education',
				'id' => 'visits_field_id_education',
				'value' => set_value('visits_field_id_education') ? set_value('visits_field_id_education') : (isset($visit['id_education']) ? $visit['id_education'] : ''),
				'placeholder' => lang('visits_field_id_education')
			),
			'cols' => 2,
			'actualCol' => 0
		)
	);

	$options = array();
	foreach( $origin_places as $origin_place ) {
		$options[$origin_place['id']] = $origin_place['name'];
	}


	$this->load->view('common/form/input', array(
			'error' => form_error('visits_field_id_origin_place') || isset($visits_field_id_origin_place_error),
			'label' => lang('visits_field_id_origin_place'),
			'options' => $options,
			'selected' => ( isset( $visit['id_origin_place'] ) && $visit['id_origin_place'] ? $visit['id_origin_place'] : null ),
			'attributes' => array(
				'type' => 'dropdown',
				'name' => 'visits_field_id_origin_place',
				'id' => 'visits_field_id_origin_place',
				'value' => set_value('visits_field_id_origin_place') ? set_value('visits_field_id_origin_place') : (isset($visit['id_origin_place']) ? $visit['id_origin_place'] : ''),
				'placeholder' => lang('visits_field_id_origin_place')
			)
		)
	);


	$options = array();
	foreach( $references as $reference ) {
		if( !isset( $options[$reference['category']] ) )
			$options[$reference['category']] = array();
		$options[$reference['category']][] = array(
			'name' => 'references[]',
			'label' => $reference['name'],
			'value' => $reference['id']
		);
	}

	$this->load->view('common/form/input', array(
			'error' => form_error('visits_field_id_reference') || isset($visits_field_id_reference_error),
			'label' => lang('visits_field_id_reference'),
			'options' => $options['DE'],
			'selected' => ( isset( $visit['id_reference'] ) && $visit['id_reference'] ? $visit['id_reference'] : null ),
			'attributes' => array(
				'type' => 'multicheckbox',
				'name' => 'visits_field_id_reference',
				'id' => 'visits_field_id_reference',
				'value' => set_value('visits_field_id_reference') ? set_value('visits_field_id_reference') : (isset($visit['id_reference']) ? $visit['id_reference'] : ''),
				'placeholder' => lang('visits_field_id_reference'),
				'cols' => 3,
				'group' => 'Referido',
				'group-end' => true
			),
			'cols' => 0,
			'actualCol' => 0
		)
	);


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

	$total = 2;
	$col = 0;
	foreach( $options as $cat => $opts ) {
		$this->load->view('common/form/input', array(
				'error' => form_error('visits_field_id_symptom') || isset($visits_field_id_symptom_error),
				'label' => $cat,
				'options' => $opts,
				'selected' => ( isset( $visit['id_symptom'] ) && $visit['id_symptom'] ? $visit['id_symptom'] : null ),
				'attributes' => array(
					'type' => 'multicheckbox',
					'name' => 'visits_field_id_symptom[]',
					'id' => 'visits_field_id_symptom',
					'value' => set_value('visits_field_id_symptom') ? set_value('visits_field_id_symptom') : (isset($visit['id_symptom']) ? $visit['id_symptom'] : ''),
					'placeholder' => lang('visits_field_id_symptom'),
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
		//$options[$diagnostic['id']] = $diagnostic['name'];
		$options[] = array(
			'name' => 'diagnostics[]',
			'label' => $diagnostic['name'],
			'value' => $diagnostic['id']
		);
	}

	$this->load->view('common/form/input', array(
			'error' => form_error('visits_field_id_diagnostic') || isset($visits_field_id_diagnostic_error),
			'label' => lang('visits_field_id_diagnostic'),
			'options' => $options,
			'selected' => ( isset( $visit['id_diagnostic'] ) && $visit['id_diagnostic'] ? $visit['id_diagnostic'] : null ),
			'attributes' => array(
				'type' => 'multicheckbox',
				'name' => 'visits_field_id_diagnostic',
				'id' => 'visits_field_id_diagnostic',
				'value' => set_value('visits_field_id_diagnostic') ? set_value('visits_field_id_diagnostic') : (isset($visit['id_diagnostic']) ? $visit['id_diagnostic'] : ''),
				'placeholder' => lang('visits_field_id_diagnostic'),
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

	$total = 2;
	$col = 0;
	foreach( $options as $cat => $opts ) {
		$this->load->view('common/form/input', array(
				'error' => form_error('visits_field_id_risk') || isset($visits_field_id_risk_error),
				'label' => $cat,
				'options' => $opts,
				'selected' => ( isset( $visit['id_risk'] ) && $visit['id_risk'] ? $visit['id_risk'] : null ),
				'attributes' => array(
					'type' => 'multicheckbox',
					'name' => 'visits_field_id_risk[]',
					'id' => 'visits_field_id_risk',
					'value' => set_value('visits_field_id_risk') ? set_value('visits_field_id_risk') : (isset($visit['id_risk']) ? $visit['id_risk'] : ''),
					'placeholder' => lang('visits_field_id_risk'),
					'group' => ( $col == 0 ? 'Factores de Evento / Riesgo' : null ),
					'group-end' => ( $col == count( $options ) - 1 ),
				),
				'cols' => $total,
				'actualCol' => $col
			)
		);
		$col ++;
	}
?>
			<div class="field">
				<?= form_button(array('type' => 'submit', 'class' => 'ui submit primary button small right floated', 'content' => '<i class="archive icon"></i> '.lang('visits_save'))); ?>
			</div>

			<?= form_close() ?>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
