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
	echo form_open_multipart(uri_string(), 'class="ui form ' . ( !empty($errors) ? 'error' : '' ) . '"');

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
	        )
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
		'F' => 'Femenino'
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
			)
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
