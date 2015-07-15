<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang($class . '_page_name')) ) ?>
	<script type="text/javascript" src="<?= base_url() ?>resources/js/history.js"></script>
</head>
<body class="history">
<?= $this->load->view('common/header', array('current' => 'history' )) ?>
<div class="container content">
	<div class="sub-header"><i class="doctor icon"></i> <?= lang($class . '_page_name') ?></div>
	<div class="section">
<?php
	$found = -1;
	$index = 0;
	foreach( $consults as $key => $value ) {
		if( $consult['id'] == $value['id'] ) {
			$found = $index;
			break;
		}
		$index ++;
	}
?>
		<div class="ui small breadcrumb">
			<a href="<?= base_url() ?>" class="section">Inicio</a>
			<i class="right chevron icon divider"></i>
			<a href="<?= base_url() ?>history/<?= $patient['id'] ?>" class="section"><?= $patient['code'] . ' (' . $patient['first_name'] . ' ' . $patient['last_name'] . ')' ?></a>
			<i class="right arrow icon divider"></i>
			<div class="active section"><?= ($found == 0 ? '1ra Consulta' : 'Seguimiento ' . $found) ?></div>
		</div>
		<div class="ui styled fluid accordion">
<?php
	if( $found >= 0 ) {
		echo $this->load->view('history/consultation', array(
				'title'   => ($found == 0 ? '1ra Consulta' : 'Seguimiento ' . $found),
				'actual'  => true,
				'index'   => $found,
				'consult' => $consults[$found],
				'editable' => true,
				'back'=> '<a class="ui small orange button" href="' . base_url() . 'history/' . $patient['id'] . '"><i class="angle double left icon"></i> Regresar a la historia</a>'
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
