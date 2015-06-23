<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?= $this->load->view('common/head', array('title' => lang('website_home') ) ) ?>
</head>
<body>
    <?= $this->load->view('common/header', array('current' => 'home')) ?>
    <div class="container content">
        <div class="sub-header">
            <i class="icon home"></i> <?= lang('website_home') ?>
        </div>
        <div class="section">
            ...
        </div>
    </div>
    <?= $this->load->view('common/footer') ?>
</body>
</html>
