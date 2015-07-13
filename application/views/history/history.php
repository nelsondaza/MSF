<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang($class . '_page_name')) ) ?>
</head>
<body class="history">
<?= $this->load->view('common/header', array('current' => 'history' )) ?>
<div class="container content">
	<div class="sub-header"><i class="doctor icon"></i> <?= lang($class . '_page_name') ?></div>
	<div class="section">
<?php
	if( !$patient['gender'] ) {
?>
	<div class="ui orange small floating message">
		<i class="close icon"></i>
		<div class="header">Primera Visita</div>
		<p>Es necesario completar algunos datos del paciente antes de ingresar la primera consulta.</p>
	</div>
<?php
	}
?>
		<div class="ui styled fluid accordion">
		<?= $this->load->view('history/first_visit') ?>
<?php
	$index = 0;
	foreach( $consults as $key => $consult ) {
		echo $this->load->view('history/consultation',
			array('title'   => ( $index == 0 ? '1ra Consulta' : 'Seguimiento ' . $index ),
				  'actual'  => false,
				  'index'   => $index,
				  'consult' => $consult
			)
		);
		$index ++;
	}

	if( $patient['gender'] ) {
		echo $this->load->view('history/consultation', array(
				'title'   => ($index == 0 ? '1ra Consulta' : 'Seguimiento ' . $index),
				'actual'  => TRUE,
				'index'   => $index,
				'consult' => array()
			));
	}
?>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
