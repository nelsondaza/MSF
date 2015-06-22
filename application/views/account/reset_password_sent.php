<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?= $this->load->view('common/head', array('title' => lang('forgot_password_page_name'))) ?>
</head>
<body class="clean">
<?= $this->load->view('common/header') ?>
<div class="container">
    <div class="container-login">
        <h3 class="inverted"><?= lang('forgot_password_page_name') ?></h3>
        <div class="title-sep"><?= lang('forgot_password_instructions') ?></div>

        <div class="ui list inverted">
            <a class="item">
                <i class="top aligned right triangle icon"></i>
                <div class="content">
                    <div class="description">Puedes mantener esta página abierta mientras verificas.</div>
                </div>
            </a>
            <a class="item">
                <i class="help icon"></i>
                <div class="content">
                    <div class="description">Si no recibes las instrucciones en un minuto o dos inténtalo nuevamente.</div>
                </div>
            </a>
        </div>

        <?= anchor( 'account/forgot_password', '<i class="mail icon"></i> ' . lang( 'reset_password_resend_the_instructions' ), array( 'class' => 'ui submit button small right floated' ) ); ?>
        <div class="clearfix"></div>
    </div>
</div>
<?php echo $this->load->view('common/footer'); ?>
</body>
</html>
