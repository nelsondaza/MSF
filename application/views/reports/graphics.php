<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang( $class . '_page_name')) ) ?>
</head>
<body>
<?= $this->load->view('common/header', array('current' => 'graphics' )) ?>
<div class="container content">
	<div class="sub-header"><i class="bar chart icon"></i> <?= lang($class . '_page_name') ?></div>
	<div class="section">
<?php
    $this->load->view( 'common/message', array( 'content' => lang( $class . '_page_description' ) ) );
?>
		<div class="ui small message">
<?php
	echo form_open_multipart(base_url('/reports/graphics'), ' id="graphic-form" class="ui small fluid form" method="post"');


	$this->load->view('common/form/input', array(
			'label' => 'Fecha de Inicio',
			'attributes' => array(
				'type' => 'date',
				'name' => 'start',
				'id' => 'start',
				'value' => $start,
				'placeholder' => "Inicio"
			),
			'cols' => 5,
			'actualCol' => 0
		)
	);

	$this->load->view('common/form/input', array(
			'label' => 'Fecha de Fin',
			'attributes' => array(
				'type' => 'date',
				'name' => 'end',
				'id' => 'end',
				'value' => $end,
				'placeholder' => "Inicio"
			)
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => 'Medición',
			'options' => $measures,
			'selected' => $measure,
			'attributes' => array(
				'type' => 'dropdown',
				'name' => 'measure',
				'id' => 'measure',
				'value' => null,
				'placeholder' => 'Medición'
			)
		)
	);

	$this->load->view('common/form/input', array(
			'error' => false,
			'label' => 'Agrupación',
			'options' => $groupingby,
			'selected' => $groupby,
			'attributes' => array(
				'type' => 'dropdown',
				'name' => 'groupby',
				'id' => 'groupby',
				'value' => null,
				'placeholder' => 'Agrupación'
			)
		)
	);


?>
			<div class="field">
				<br>
				<button id="form-submit" type="submit" class="ui green button small"><i class="download icon"></i>Generar</button>
			</div>
		</div>
		<div class="ui fitted divider"></div>
<?php
	echo form_close();
?>
		</div>
		<div class="ui small message graph-container">
			<h1 class="header">Gráfico<span></span>:</h1>
			<div class="ui secondary attached segment"></div>
		</div>
		<div class="ui small message data-container">
			<h1 class="header">Datos<span></span>:</h1>
			<div class="ui secondary attached segment">
<?php

	if( $table ) {
		$this->load->view('common/table', $table);
	}

?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	$(function () {

		$('#graphic-form').submit(function(){
			$('#form-submit').addClass('disabled').prop('disabled','disabled').text('Generando...');
			$('.graph-container .segment, .data-container .segment').text('Generando...');
		});

<?php
	if( $chart ) {
?>
		// Build chart
		$('.graph-container .segment').highcharts(<?= json_encode($chart)?>);
<?php
	}
?>
	});


</script>
<?= $this->load->view('common/footer') ?>
</body>
</html>
