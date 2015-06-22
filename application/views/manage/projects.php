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
	<div class="sub-header"><i class="flag icon"></i> <?= lang($class . '_page_name') ?></div>
	<div class="section">
<?
    $this->load->view( 'common/message', array( 'content' => lang( $class . '_page_description' ) ) );

    $table = array( );

    if( $this->authorization->is_permitted('create_projects') )
        $table['options'] = anchor( $scope . '/' . $class . '/save', '<i class="plus icon"></i> ' . lang('website_create'), 'class="ui purple button mini"');

    $table['headers'] = array(
        '#',
        lang('projects_column_name'),
        lang('projects_column_brand_name'),
        lang('projects_column_description'),
        lang('projects_column_creation'),
    );

    $table['rows'] = array( );
    $table['rows_options'] = array( );

    foreach( $projects as $object ) {

        $table['rows'][] = array(
            $object['id'],
            $object['name'] . (
                !$object['active']
                ? ' <span class="ui mini red label floated right">' . lang('projects_inactive') . '</span>'
                : ''
            ),
            showBrandPhoto( $object['brand_logo'], array( 'class' => 'ui mini avatar image' ) ) . $object['brand_name'] . ' <small>(' . $object['client_name'] . ')</small>',
            $object['description'],
            $object['creation']
        );

        if( $this->authorization->is_permitted('update_projects') )
            $table['rows_options'][] = anchor( $scope . '/' . $class . '/save/' . $object['id'], '<i class="edit icon"></i> ' . lang('website_update'), 'class="ui teal button mini"');
    }

    $this->load->view('common/table', $table );

?>
	</div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
