<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang( $class . '_page_name')) ) ?>
</head>
<body>
<?= $this->load->view('common/header') ?>
<div class="container content">
	<?= $this->load->view('common/menu', array('current' => 'manage_' . $class ) ) ?>
	<div class="sub-header"><i class="book icon"></i> <?= lang($class . '_page_name') ?></div>
	<div class="section">
<?php
    $this->load->view( 'common/message', array( 'content' => lang( $class . '_page_description' ) ) );

    $table = array( );

    if( $this->authorization->is_permitted('create_reports') )
        $table['options'] = anchor( $scope . '/' . $class . '/save', '<i class="plus icon"></i> ' . lang('website_create'), 'class="ui purple button mini"');

    $table['headers'] = array(
        '#',
        lang('reports_column_name'),
        lang('reports_column_project_name'),
        lang('reports_column_generation'),
        lang('reports_column_creation'),
    );

    $table['rows'] = array( );
    $table['rows_options'] = array( );

    foreach( $reports as $object ) {

        $table['rows'][] = array(
            $object['id'],
            $object['name'] . (
                !$object['active']
                ? ' <span class="ui mini red label floated right">' . lang('reports_inactive') . '</span>'
                : ''
            ),
            $object['project_name'] . ' <small>(' . $object['brand_name'] . '/' . $object['client_name'] . ')</small>',
            ( $object['generation'] ? $object['generation'] : ' - Sin Generar -' ),
            $object['creation']
        );

	    $rowOptions = '';
        if( $this->authorization->is_permitted('update_reports') )
	        $rowOptions[] = anchor( $scope . '/' . $class . '/save/' . $object['id'], '<i class="edit icon"></i> ' . lang('website_update'), 'class="ui teal button mini"');
	    if( $this->authorization->is_permitted('view_reports') )
		    $rowOptions[] = anchor( 'analytics/flow/' . $object['id'], '<i class="wizard icon"></i> ' . lang('generate'), 'class="ui blue button mini"');
	    $table['rows_options'][] = $rowOptions;
    }

    $this->load->view('common/table', $table );

?>
	</div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
