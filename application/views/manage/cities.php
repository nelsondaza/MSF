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
	<div class="sub-header"><i class="map icon"></i> <?= lang($class . '_page_name') ?></div>
	<div class="section">
<?php
    $this->load->view( 'common/message', array( 'content' => lang( $class . '_page_description' ) ) );

    $table = array( );

    if( $this->authorization->is_permitted('create_' . $class) )
        $table['options'] = anchor( $scope . '/' . $class . '/save', '<i class="plus icon"></i> ' . lang('website_create'), 'class="ui purple button mini"');

    $table['headers'] = array(
        '#',
        lang($class . '_column_name'),
        lang($class . '_column_creation'),
    );

    $table['rows'] = array( );
    $table['rows_options'] = array( );

    foreach( $objects as $object ) {

        $table['rows'][] = array(
            $object['id'],
            $object['name'] . (
                !$object['active']
                ? ' <span class="ui mini red label floated right">' . lang($class . '_inactive') . '</span>'
                : ''
            ),
            $object['creation']
        );

        if( $this->authorization->is_permitted('update_' . $class) )
            $table['rows_options'][] = anchor( $scope . '/' . $class . '/save/' . $object['id'], '<i class="edit icon"></i> ' . lang('website_update'), 'class="ui teal button mini"');
    }

    $this->load->view('common/table', $table );

?>
	</div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
