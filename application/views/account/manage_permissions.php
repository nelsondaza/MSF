<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang('permissions_page_name')) ) ?>
</head>
<body>
<?= $this->load->view('common/header') ?>
<div class="container content">
	<?= $this->load->view('common/menu', array('current' => 'manage_permissions') ) ?>
	<div class="sub-header"><i class="privacy icon"></i> <?= lang('permissions_page_name') ?></div>
	<div class="section">
<?
      $errors = array( );
      if( form_error('password_new_password') )
        $errors[] = form_error('password_new_password');
      if( form_error('password_retype_new_password') )
        $errors[] = form_error('password_retype_new_password');

      if ( !empty($errors) ) {
        $this->load->view('common/message', array('type' => 'error','class' => 'warning','content' => $errors) );
      }
      else if( $this->session->flashdata('password_info') ) {
        $this->load->view('common/message', array('type' => 'success','class' => 'info','content' => $this->session->flashdata( 'password_info' )) );
      }
      else {
        $this->load->view('common/message', array('content' => lang('permissions_page_description')) );
      }

    $table = array( );

    if( $this->authorization->is_permitted('create_users') )
        $table['options'] = anchor('account/manage_permissions/save', '<i class="plus icon"></i> ' . lang('website_create'), 'class="ui purple button mini"');

    $table['headers'] = array(
        '#',
        lang('permissions_column_permission'),
        lang('permissions_description'),
        lang('permissions_column_inroles')
    );

    $table['rows'] = array( );
    $table['rows_options'] = array( );

    foreach( $permissions as $perm ) {
        $perms = array();
        foreach( $perm['role_list'] as $itm )
            $perms[] = anchor('account/manage_roles/save/'.$itm['id'], $itm['name'], 'title="'.$itm['title'].'"');

        $table['rows'][] = array(
            $perm['id'],
            $perm['key'] . (
                $perm['is_disabled']
                ? ' <span class="ui mini red label floated right">' . lang('permissions_banned') . '</span>'
                : ''
            ),
            $perm['description'],
            (
                empty( $perms )
                ? '<span class="ui label warning">Sin Permisos</span>'
                : '<ul class="ui list"><li>' . implode('</li><li>', $perms) . '</li></ul>'
            )
        );

        if( $this->authorization->is_permitted('update_permissions') )
            $table['rows_options'][] = anchor('account/manage_permissions/save/'.$perm['id'], '<i class="edit icon"></i> ' . lang('website_update'), 'class="ui teal button mini"');
    }

    $this->load->view('common/table', $table );

?>
	</div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
