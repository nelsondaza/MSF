<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang($class . '_page_name')) ) ?>
	<script type="text/javascript" src="<?= base_url() ?>resources/js/history.js"></script>
</head>
<body class="resume">
<?= $this->load->view('common/header', array('current' => 'history' )) ?>
<div class="container content">
	<div class="sub-header"><i class="doctor icon"></i> <?= lang($class . '_page_name') ?></div>
	<div class="section">
		<div class="ui segments">
			<div class="ui segment">
				<div class="ui small feed">
					<h4 class="ui header">
						<?= $patient['code'] . ' (' . $patient['first_name'] . ' ' . $patient['last_name'] . ')' ?>
					</h4>
				</div>
			</div>
<?php
	$index = 0;
	foreach( $consults as $key => $consult ) {
?>
			<div class="ui segment">
				<div class="ui small feed">
					<h4 class="ui header">
						<div class="ui small <?= ( $consult['id_closure'] ? 'red' : '' ) ?> label"><?= $consult['creation'] ?></div>
						<?= ( $consult['id_closure'] ? 'Cierre' : ( $index == 0 ? '1ra Consulta' : 'Seguimiento ' . $index ) ) ?>
					</h4>
<?php
	$options = array();
	$optionName = '';
	foreach( $symptoms_categories as $symptoms_category ) {
		$options[$symptoms_category['id']] = $symptoms_category['name'];
		if( isset($consult['id_symptoms_category']) && $consult['id_symptoms_category'] == $symptoms_category['id'] )
			$optionName = $symptoms_category['name'];
	}
	if( $optionName ) {
?>
					<div class="event">
						<div class="content">
							<div class="summary"><?= lang('history_field_id_symptoms_category') ?></div>
							<div class="segment"><?= $optionName ?></div>
						</div>
					</div>
<?php
	}

	$options = array();
	$optionName = '';
	foreach( $risks_categories as $risks_category ) {
		$options[$risks_category['id']] = $risks_category['name'];
		if( isset($consult['id_risks_category']) && $consult['id_risks_category'] == $risks_category['id'] )
			$optionName = $risks_category['name'];
	}
	if( $optionName ) {
?>
					<div class="event">
						<div class="content">
							<div class="summary"><?= lang('history_field_id_risks_category') ?></div>
							<div class="segment"><?= $optionName ?></div>
						</div>
					</div>
<?php
	}

	$optionName = ( isset( $consult['suicide_risk'] ) && $consult['suicide_risk'] ? $consult['suicide_risk'] : '' );
	if( $optionName ) {
?>
					<div class="event">
						<div class="content">
							<div class="summary"><?= 'Riesgo de Suicidio' ?></div>
							<div class="segment"><?= $optionName ?></div>
						</div>
					</div>
<?php
	}

	$optionName = ( isset( $consult['violence_risk'] ) && $consult['violence_risk'] ? $consult['violence_risk'] : '' );
	if( $optionName ) {
?>
					<div class="event">
						<div class="content">
							<div class="summary"><?= 'Riesgo de Suicidio' ?></div>
							<div class="segment"><?= $optionName ?></div>
						</div>
					</div>
<?php
	}

	$optionName = ( isset( $consult['substance_abuse'] ) && $consult['substance_abuse'] ? $consult['substance_abuse'] : '' );
	if( $optionName ) {
?>
					<div class="event">
						<div class="content">
							<div class="summary"><?= 'Abuso de Sustancias' ?></div>
							<div class="segment"><?= $optionName ?></div>
						</div>
					</div>
<?php
	}

	$optionName = ( isset( $consult['serious_medical_conditions'] ) && $consult['serious_medical_conditions'] ? $consult['serious_medical_conditions'] : '' );
	if( $optionName ) {
?>
					<div class="event">
						<div class="content">
							<div class="summary"><?= 'Condiciones médicas graves' ?></div>
							<div class="segment"><?= $optionName ?></div>
						</div>
					</div>
<?php
	}

	$optionName = ( isset( $consult['cognitive_assessment'] ) && $consult['cognitive_assessment'] ? $consult['cognitive_assessment'] : '' );
	if( $optionName ) {
?>
					<div class="event">
						<div class="content">
							<div class="summary"><?= 'Valoración cognitiva' ?></div>
							<div class="segment"><?= $optionName ?></div>
						</div>
					</div>
<?php
	}

	$optionName = ( isset( $consult['psychotropic_medication'] ) && $consult['psychotropic_medication'] ? $consult['psychotropic_medication'] : '' );
	if( $optionName ) {
?>
					<div class="event">
						<div class="content">
							<div class="summary"><?= 'Medicación Psicotrópica' ?></div>
							<div class="segment"><?= $optionName ?></div>
						</div>
					</div>
<?php
	}

	$optionName = ( isset( $consult['Observaciones'] ) && $consult['Observaciones'] ? $consult['Observaciones'] : '' );
	if( $optionName ) {
?>
					<div class="event">
						<div class="content">
							<div class="summary"><?= 'Observaciones' ?></div>
							<div class="segment"><?= $optionName ?></div>
						</div>
					</div>
<?php
	}

?>
				</div>
			</div>
<?php
		$index ++;
	}
?>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
