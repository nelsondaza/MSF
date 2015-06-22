<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?= $this->load->view('common/head', array('title' => lang('sign_out_page_name') ) ) ?>
</head>
<body class="clean">
<?= $this->load->view('common/header') ?>
<div class="container">
    <div class="container-login inverted ">
        <h3 class="inverted"><?= lang('sign_out_heading'); ?></h3>
        <div class="title-sep"><?php echo lang('sign_out_successful'); ?></div>
        <p>
            <?php echo anchor('', '<i class="sign in icon"></i> ' . lang('sign_out_go_to_home'), array('class' => 'ui submit button small right floated')); ?>
        </p>
        <div class="clearfix"></div>
    </div>
</div>
<?php echo $this->load->view('common/footer'); ?>
</body>
</html>