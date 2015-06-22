<?
/**
 * Created by PhpStorm.
 * User: nelson.daza
 * Date: 27/11/2014
 * Time: 10:30 AM
 */

	if ( !$this->authentication->is_signed_in( ) )
		return;

	if( !isset( $current ) )
		$current = '';

?>
<div class="main-menu">
	<div class="sub-header">
		<img src="<?= base_url() ?>resources/img/stats.jpg" alt="" height="25"> <?= lang( 'website_title' ) ?>
	</div>
	<br>
	<div class="ui vertical text menu accordion inverted dash sticky">
<?
	echo anchor( '', '<i class="home icon"></i>' . lang( 'website_home' ), array( 'class' => 'item' . ( $current == 'home' ? ' active' : '' ) ) );

	$active = ( in_array( $current, array( 'account_profile', 'account_settings', 'account_password' ) ) );
?>
		<div class="item <?= ( $active ? 'active' : '' ) ?>">
			<a class="title <?= ( $active ? 'active' : '' ) ?>"><i class="user icon"></i><i class="dropdown icon"></i> Mi Cuenta</a>
			<div class="menu content <?= ( $active ? 'active' : '' ) ?>">
				<?= anchor( 'account/account_profile', lang( 'website_profile' ), array( 'class' => 'item' . ( $current == 'account_profile' ? ' active' : '' ) ) ) ?>
				<?= anchor( 'account/account_settings', lang( 'website_account' ), array( 'class' => 'item' . ( $current == 'account_settings' ? ' active' : '' ) ) ) ?>
				<?= anchor( 'account/account_password', lang( 'website_password' ), array( 'class' => 'item' . ( $current == 'account_password' ? ' active' : '' ) ) ) ?>
			</div>
		</div>
<?
	/*
	<?= anchor( 'account/account_linked', '<i class="user icon"></i> ' . lang( 'website_linked' ), array( 'class' => 'item' ) ) ?>
	<i class="user icon"></i>
	<div class="header item">Administración</div>
	*/
	if ( $this->authorization->is_permitted( array( 'retrieve_users', 'retrieve_roles', 'retrieve_permissions' ) ) ) {

		$active = ( in_array( $current, array( 'manage_users', 'manage_roles', 'manage_permissions' ) ) );

?>
		<div class="item <?= ( $active ? 'active' : '' ) ?>">
			<a class="title <?= ( $active ? 'active' : '' ) ?>"><i class="users icon"></i><i class="dropdown icon"></i> Control de Acceso</a>
			<div class="menu content <?= ( $active ? 'active' : '' ) ?>">
<?

		if ( $this->authorization->is_permitted( 'retrieve_users' ) )
			echo anchor( 'account/manage_users', '<i class="users icon"></i> ' . lang( 'website_manage_users' ), array( 'class' => 'item' . ( $current == 'manage_users' ? ' active' : '' ) ) );

		if ( $this->authorization->is_permitted( 'retrieve_roles' ) )
			echo anchor( 'account/manage_roles', '<i class="student icon"></i> ' . lang( 'website_manage_roles' ), array( 'class' => 'item' . ( $current == 'manage_roles' ? ' active' : '' ) ) );

		if ( $this->authorization->is_permitted( 'retrieve_permissions' ) )
			echo anchor( 'account/manage_permissions', '<i class="privacy icon"></i> ' . lang( 'website_manage_permissions' ), array( 'class' => 'item' . ( $current == 'manage_permissions' ? ' active' : '' ) ) );

?>
			</div>
		</div>
<?
	}


	if ( $this->authorization->is_permitted( array( 'retrieve_clients', 'retrieve_brands', 'retrieve_projects', 'retrieve_reports' ) ) ) {

		$active = ( in_array( $current, array( 'manage_clients', 'manage_brands', 'manage_projects', 'manage_reports' ) ) );

?>
		<div class="item <?= ( $active ? 'active' : '' ) ?>">
			<a class="title <?= ( $active ? 'active' : '' ) ?>"><i class="cloud icon"></i><i class="dropdown icon"></i> Cuentas</a>
			<div class="menu content <?= ( $active ? 'active' : '' ) ?>">
<?
		if ( $this->authorization->is_permitted( 'retrieve_clients' ) )
			echo anchor( 'manage/clients', '<i class="world icon"></i> Clientes', array( 'class' => 'item' . ( $current == 'manage_clients' ? ' active' : '' ) ) );

		if ( $this->authorization->is_permitted( 'retrieve_brands' ) )
			echo anchor( 'manage/brands', '<i class="rocket icon"></i> Marcas', array( 'class' => 'item' . ( $current == 'manage_brands' ? ' active' : '' ) ) );

		if ( $this->authorization->is_permitted( 'retrieve_projects' ) )
			echo anchor( 'manage/projects', '<i class="flag icon"></i> Proyectos', array( 'class' => 'item' . ( $current == 'manage_projects' ? ' active' : '' ) ) );

		if ( $this->authorization->is_permitted( 'retrieve_reports' ) )
			echo anchor( 'manage/reports', '<i class="book icon"></i> Reportes', array( 'class' => 'item' . ( $current == 'manage_reports' ? ' active' : '' ) ) );

?>
			</div>
		</div>
<?
	}

	if ( $this->authorization->is_permitted( array( 'retrieve_sources', 'retrieve_measures' ) ) ) {
		$active = ( in_array( $current, array( 'manage_sources', 'manage_measures' ) ) );

?>
		<div class="item <?= ( $active ? 'active' : '' ) ?>">
			<a class="title <?= ( $active ? 'active' : '' ) ?>"><i class="settings icon"></i><i class="dropdown icon"></i> Sistema</a>
			<div class="menu content <?= ( $active ? 'active' : '' ) ?>">
<?
		if ( $this->authorization->is_permitted( 'retrieve_sources' ) )
			echo anchor( 'manage/sources', '<i class="crosshairs icon"></i> Fuentes', array( 'class' => 'item' . ( $current == 'manage_sources' ? ' active' : '' ) ) );

		if ( $this->authorization->is_permitted( 'retrieve_measures' ) )
			echo anchor( 'manage/measures', '<i class="bullseye icon"></i> Mediciones', array( 'class' => 'item' . ( $current == 'manage_measures' ? ' active' : '' ) ) );
/*
		if ( $this->authorization->is_permitted( 'retrieve_projects' ) )
			echo anchor( 'manage/projects', '<i class="flag icon"></i> Proyectos', array( 'class' => 'item' . ( $current == 'manage_projects' ? ' active' : '' ) ) );

		if ( $this->authorization->is_permitted( 'retrieve_reports' ) )
			echo anchor( 'manage/reports', '<i class="book icon"></i> Reportes', array( 'class' => 'item' . ( $current == 'manage_reports' ? ' active' : '' ) ) );
*/
?>
			</div>
		</div>
<?
	}
?>
	</div>
</div>
