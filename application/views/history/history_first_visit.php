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
		<div class="ui small breadcrumb">
			<a href="<?= base_url() ?>" class="section">Inicio</a>
			<i class="right chevron icon divider"></i>
			<a href="<?= base_url() ?>history/<?= $patient['id'] ?>" class="section"><?= $patient['code'] . ' (' . $patient['first_name'] . ' ' . $patient['last_name'] . ')' ?></a>
			<i class="right arrow icon divider"></i>
			<div class="active section">Primera Sesión</div>
		</div>
		<div class="ui styled fluid accordion">
		<?=
			$this->load->view('history/first_visit', array('editable' => true, 'back'=> '<a class="ui small orange button" href="' . base_url() . 'history/' . $patient['id'] . '"><i class="angle double left icon"></i> Regresar a la historia</a>' ) )
		?>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
