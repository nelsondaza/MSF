<!DOCTYPE html>
<!--[if lt IE 7]>     <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>     <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>     <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang('roles_page_name')) ) ?>
</head>
<body>
<?= $this->load->view('common/header') ?>
<div class="container content">
	<?= $this->load->view('common/menu', array('current' => 'manage_roles') ) ?>
	<div class="sub-header"><i class="student icon"></i> <?= lang('roles_page_name') ?></div>
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
        $this->load->view('common/message', array('content' => lang('roles_page_description')) );
      }

    $table = array( );

    if( $this->authorization->is_permitted('create_roles') )
        $table['options'] = anchor('account/manage_roles/save', '<i class="plus icon"></i> ' . lang('website_create'), 'class="ui purple button mini"');

    $table['headers'] = array(
        '#',
        lang('roles_column_role'),
        lang('roles_column_users'),
        lang('roles_permission')
    );

    $table['rows'] = array( );
    $table['rows_options'] = array( );

    foreach( $roles as $role ) {

        $perms = array();
        foreach( $role['perm_list'] as $itm )
            $perms[] = anchor('account/manage_permissions/save/'.$itm['id'], $itm['key'], 'title="'.$itm['title'].'"');

        $table['rows'][] = array(
            $role['id'],
            $role['name'] . (
                $role['is_disabled']
                ? ' <span class="ui mini red label floated right">' . lang('roles_banned') . '</span>'
                : ''
            ),
            (
                $role['user_count'] > 0
                ? anchor('account/manage_users/filter/role/'.$role['id'], '<i class="icon users"></i> ' . $role['user_count'], 'class="ui teal button mini"')
                : '<span class="ui label warning"><i class="icon users"></i> 0</span>'
            ),
            (
                empty( $perms )
                ? '<span class="ui label warning">Sin Permisos</span>'
                : '<ul class="ui list"><li>' . implode('</li><li>', $perms) . '</li></ul>'
            )
        );

        if( $this->authorization->is_permitted('update_roles') )
            $table['rows_options'][] = anchor('account/manage_roles/save/'.$role['id'], '<i class="edit icon"></i> ' . lang('website_update'), 'class="ui teal button mini"');
    }

    $this->load->view('common/table', $table );
?>
	</div>
	<div class="clearfix"></div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>

