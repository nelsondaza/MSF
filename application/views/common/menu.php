<?php
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
<div class="ui vertical inverted sidebar menu" id="toc">
	<div class="item">
		<a class="ui logo icon image" href="<?= base_url() ?>">
			<img src="<?= base_url() ?>resources/img/icon.png" alt="MSF">
		</a>
		<a href="<?= base_url() ?>"><b>Médicos Sin Fronteras</b></a>
	</div>
<?php
	echo anchor( base_url(), '<i class="home icon"></i> ' . lang( 'website_home' ), array( 'class' => 'item' . ( $current == 'home' ? ' active' : '' ) ) );
	echo anchor( '#', '<i class="doctor icon"></i> Consulta', array( 'class' => 'consult-action item' . ( $current == 'history' ? ' active' : '' ) ) );

	$active = ( in_array( $current, array( 'account_profile', 'account_settings', 'account_password' ) ) );
?>
		<div class="item <?= ( $active ? 'active' : '' ) ?>">
			<a class="title <?= ( $active ? 'active' : '' ) ?>"><i class="user icon"></i> Mi Cuenta</a>
			<div class="menu content <?= ( $active ? 'active' : '' ) ?>">
				<?= anchor( 'account/account_profile', lang( 'website_profile' ), array( 'class' => 'item' . ( $current == 'account_profile' ? ' active' : '' ) ) ) ?>
				<?= anchor( 'account/account_settings', lang( 'website_account' ), array( 'class' => 'item' . ( $current == 'account_settings' ? ' active' : '' ) ) ) ?>
				<?= anchor( 'account/account_password', lang( 'website_password' ), array( 'class' => 'item' . ( $current == 'account_password' ? ' active' : '' ) ) ) ?>
			</div>
		</div>
<?php

	if ( $this->authorization->is_permitted( array( 'retrieve_users', 'retrieve_roles', 'retrieve_permissions' ) ) ) {

		$active = ( in_array( $current, array( 'manage_users', 'manage_roles', 'manage_permissions' ) ) );

?>
		<div class="item <?= ( $active ? 'active' : '' ) ?>">
			<a class="title <?= ( $active ? 'active' : '' ) ?>"><i class="users icon"></i> Control de Acceso</a>
			<div class="menu content <?= ( $active ? 'active' : '' ) ?>">
<?php

		if ( $this->authorization->is_permitted( 'retrieve_users' ) )
			echo anchor( 'account/manage_users', '<i class="users icon"></i> ' . lang( 'website_manage_users' ), array( 'class' => 'item' . ( $current == 'manage_users' ? ' active' : '' ) ) );

		if ( $this->authorization->is_permitted( 'retrieve_roles' ) )
			echo anchor( 'account/manage_roles', '<i class="student icon"></i> ' . lang( 'website_manage_roles' ), array( 'class' => 'item' . ( $current == 'manage_roles' ? ' active' : '' ) ) );

		if ( $this->authorization->is_permitted( 'retrieve_permissions' ) )
			echo anchor( 'account/manage_permissions', '<i class="privacy icon"></i> ' . lang( 'website_manage_permissions' ), array( 'class' => 'item' . ( $current == 'manage_permissions' ? ' active' : '' ) ) );

?>
			</div>
		</div>
<?php
	}

	if ( $this->authorization->is_permitted( array( 'retrieve_users', 'retrieve_measures' ) ) ) {
		$active = ( in_array( $current, array( 'manage_sources', 'manage_measures' ) ) );

?>
		<div class="item <?= ( $active ? 'active' : '' ) ?>">
			<a class="title <?= ( $active ? 'active' : '' ) ?>"><i class="settings icon"></i> Sistema</a>
			<div class="menu content <?= ( $active ? 'active' : '' ) ?>">
<?php
	/*
		echo anchor( 'manage/regions', '<i class="crosshairs icon"></i> Regiones', array( 'class' => 'item' . ( $current == 'manage_regions' ? ' active' : '' ) ) );
		echo anchor( 'manage/cities', '<i class="crosshairs icon"></i> Ciudades', array( 'class' => 'item' . ( $current == 'manage_cities' ? ' active' : '' ) ) );
		echo anchor( 'manage/villages', '<i class="crosshairs icon"></i> Comunas', array( 'class' => 'item' . ( $current == 'manage_villages' ? ' active' : '' ) ) );
		echo anchor( 'manage/districts', '<i class="crosshairs icon"></i> Barrios', array( 'class' => 'item' . ( $current == 'manage_districts' ? ' active' : '' ) ) );
		echo anchor( 'manage/interventions_places', '<i class="crosshairs icon"></i> Lugares de Interveción', array( 'class' => 'item' . ( $current == 'manage_interventions_places' ? ' active' : '' ) ) );
		echo anchor( 'manage/experts', '<i class="crosshairs icon"></i> Especialistas', array( 'class' => 'item' . ( $current == 'manage_experts' ? ' active' : '' ) ) );
		echo anchor( 'manage/origin_places', '<i class="crosshairs icon"></i> Lugares de Origen', array( 'class' => 'item' . ( $current == 'manage_origin_places' ? ' active' : '' ) ) );
		echo anchor( 'manage/symptoms_categories', '<i class="crosshairs icon"></i> Síntomas - Categorías', array( 'class' => 'item' . ( $current == 'manage_symptoms_categoriies' ? ' active' : '' ) ) );
		echo anchor( 'manage/symptoms', '<i class="crosshairs icon"></i> Síntomas', array( 'class' => 'item' . ( $current == 'manage_symptoms' ? ' active' : '' ) ) );
		echo anchor( 'manage/risks_categories', '<i class="crosshairs icon"></i> Riesgos - Categorías', array( 'class' => 'item' . ( $current == 'manage_risks_categories' ? ' active' : '' ) ) );
		echo anchor( 'manage/risks', '<i class="crosshairs icon"></i> Riesgos', array( 'class' => 'item' . ( $current == 'manage_risks' ? ' active' : '' ) ) );
		echo anchor( 'manage/visits_types', '<i class="crosshairs icon"></i> Tipos de Consulta', array( 'class' => 'item' . ( $current == 'manage_visits_types' ? ' active' : '' ) ) );
		echo anchor( 'manage/references_categories', '<i class="crosshairs icon"></i> Referidos - Categorías', array( 'class' => 'item' . ( $current == 'manage_references_categories' ? ' active' : '' ) ) );
		echo anchor( 'manage/references', '<i class="crosshairs icon"></i> Referidos', array( 'class' => 'item' . ( $current == 'manage_references' ? ' active' : '' ) ) );
		echo anchor( 'manage/closures', '<i class="crosshairs icon"></i> Cierres', array( 'class' => 'item' . ( $current == 'manage_closures' ? ' active' : '' ) ) );
		echo anchor( 'manage/closures_conditions', '<i class="crosshairs icon"></i> Condiciones de Cierre', array( 'class' => 'item' . ( $current == 'closures_conditions' ? ' active' : '' ) ) );
		echo anchor( 'manage/diagnostics', '<i class="crosshairs icon"></i> Diagnósticos Clínicos', array( 'class' => 'item' . ( $current == 'diagnostics' ? ' active' : '' ) ) );
		echo anchor( 'manage/followups_types', '<i class="crosshairs icon"></i> Tipos de consultas de seguimiento', array( 'class' => 'item' . ( $current == 'followups_types' ? ' active' : '' ) ) );
		echo anchor( 'manage/interventions_types', '<i class="crosshairs icon"></i> Tipo de Intervenciones SMPS', array( 'class' => 'item' . ( $current == 'manage_interventions_types' ? ' active' : '' ) ) );
		echo anchor( 'manage/educations', '<i class="crosshairs icon"></i> Educación', array( 'class' => 'item' . ( $current == 'manage_educations' ? ' active' : '' ) ) );
		echo anchor( 'manage/events_categories', '<i class="crosshairs icon"></i> Categoría de Eventos/Acontecimientos', array( 'class' => 'item' . ( $current == 'manage_events_categories' ? ' active' : '' ) ) );
	*/

	echo anchor( 'manage/regions', '<i class="crosshairs icon"></i> Regiones', array( 'class' => 'item' . ( $current == 'manage_regions' ? ' active' : '' ) ) );
	echo anchor( 'manage/cities', '<i class="crosshairs icon"></i> Ciudades', array( 'class' => 'item' . ( $current == 'manage_cities' ? ' active' : '' ) ) );
	echo anchor( 'manage/villages', '<i class="crosshairs icon"></i> Comunas', array( 'class' => 'item' . ( $current == 'manage_villages' ? ' active' : '' ) ) );
	echo anchor( 'manage/districts', '<i class="crosshairs icon"></i> Barrios', array( 'class' => 'item' . ( $current == 'manage_districts' ? ' active' : '' ) ) );
	echo anchor( 'manage/interventions_places', '<i class="crosshairs icon"></i> Lugares de Interveción', array( 'class' => 'item' . ( $current == 'manage_interventions_places' ? ' active' : '' ) ) );
	echo anchor( 'manage/experts', '<i class="crosshairs icon"></i> Especialistas', array( 'class' => 'item' . ( $current == 'manage_experts' ? ' active' : '' ) ) );
	echo anchor( 'manage/origin_places', '<i class="crosshairs icon"></i> Lugares de Origen', array( 'class' => 'item' . ( $current == 'manage_origin_places' ? ' active' : '' ) ) );
	echo anchor( 'manage/symptoms_categories', '<i class="crosshairs icon"></i> Síntomas - Categorías', array( 'class' => 'item' . ( $current == 'manage_symptoms_categoriies' ? ' active' : '' ) ) );
	echo anchor( 'manage/symptoms', '<i class="crosshairs icon"></i> Síntomas', array( 'class' => 'item' . ( $current == 'manage_symptoms' ? ' active' : '' ) ) );
	echo anchor( 'manage/risks_categories', '<i class="crosshairs icon"></i> Riesgos - Categorías', array( 'class' => 'item' . ( $current == 'manage_risks_categories' ? ' active' : '' ) ) );
	echo anchor( 'manage/risks', '<i class="crosshairs icon"></i> Riesgos', array( 'class' => 'item' . ( $current == 'manage_risks' ? ' active' : '' ) ) );
	echo anchor( 'manage/references_categories', '<i class="crosshairs icon"></i> Referidos - Categorías', array( 'class' => 'item' . ( $current == 'manage_references_categories' ? ' active' : '' ) ) );
	echo anchor( 'manage/references', '<i class="crosshairs icon"></i> Referidos', array( 'class' => 'item' . ( $current == 'manage_references' ? ' active' : '' ) ) );
	echo anchor( 'manage/closures', '<i class="crosshairs icon"></i> Cierres', array( 'class' => 'item' . ( $current == 'manage_closures' ? ' active' : '' ) ) );
	echo anchor( 'manage/closures_conditions', '<i class="crosshairs icon"></i> Condiciones de Cierre', array( 'class' => 'item' . ( $current == 'closures_conditions' ? ' active' : '' ) ) );
	echo anchor( 'manage/diagnostics', '<i class="crosshairs icon"></i> Diagnósticos Clínicos', array( 'class' => 'item' . ( $current == 'diagnostics' ? ' active' : '' ) ) );
	echo anchor( 'manage/consults_types', '<i class="crosshairs icon"></i> Tipos de Consultas', array( 'class' => 'item' . ( $current == 'manage_consults_types' ? ' active' : '' ) ) );
	echo anchor( 'manage/followups_types', '<i class="crosshairs icon"></i> Tipos de consultas de seguimiento', array( 'class' => 'item' . ( $current == 'followups_types' ? ' active' : '' ) ) );
	echo anchor( 'manage/interventions_types', '<i class="crosshairs icon"></i> Tipo de Intervenciones SMPS', array( 'class' => 'item' . ( $current == 'manage_interventions_types' ? ' active' : '' ) ) );
	echo anchor( 'manage/educations', '<i class="crosshairs icon"></i> Educación', array( 'class' => 'item' . ( $current == 'manage_educations' ? ' active' : '' ) ) );
	echo anchor( 'manage/events_categories', '<i class="crosshairs icon"></i> Categoría de Eventos/Acontecimientos', array( 'class' => 'item' . ( $current == 'manage_events_categories' ? ' active' : '' ) ) );

?>
			</div>
		</div>
<?php
	}
	echo anchor( base_url(), '<i class="home icon"></i>' . lang( 'website_home' ), array( 'class' => 'item' . ( $current == 'home' ? ' active' : '' ) ) );
?>
</div>
<div class="ui black big launch right attached fixed button">
	<i class="content icon"></i>
	<span class="text">Menú</span>
</div>
