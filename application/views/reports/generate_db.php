<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang( $class . '_page_name')) ) ?>
</head>
<body>
<?= $this->load->view('common/header', array('current' => 'manage_' . $class )) ?>
<div class="container content">
	<div class="sub-header"><i class="database icon"></i> <?= lang($class . '_page_name') ?></div>
	<div class="section">
<?php
    $this->load->view( 'common/message', array( 'content' => lang( $class . '_page_description' ) ) );
?>
		<div class="ui small message">
<?php
	echo form_open_multipart(base_url('/reports/excel/DB'), ' class="ui small fluid form" method="post"');

	$this->load->view('common/form/input', array(
			'label' => 'Fecha de Inicio',
			'attributes' => array(
				'type' => 'date',
				'name' => 'start',
				'id' => 'start',
				'value' => ( date('Y-m-d', strtotime( "-1 MONTH" ) ) ),
				'placeholder' => "Inicio"
			),
			'cols' => 3,
			'actualCol' => 0
		)
	);

	$this->load->view('common/form/input', array(
			'label' => 'Fecha de Fin',
			'attributes' => array(
				'type' => 'date',
				'name' => 'end',
				'id' => 'end',
				'value' => ( date('Y-m-d' ) ),
				'placeholder' => "Inicio"
			)
		)
	);

?>
			<div class="field">
				<br>
				<button type="submit" class="ui green button small"><i class="download icon"></i>Generar</button>
			</div>
<?php
	echo form_close();
?>
		</div>
	</div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
