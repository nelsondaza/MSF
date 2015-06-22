<?php
/**
 * Created by PhpStorm.
 * User: nelson.daza
 * Date: 19/12/2014
 * Time: 10:40 AM
 */

	$dataSource->loadProperties();

	if( !$dataSource->canActivate( ) ) {
		$this->load->view('common/message', array('type' => 'orange','class' => 'warning','content' => lang('sources_activation_required')) );
	}

	$this->load->view('common/form/input', array(
			'error' => form_error('fb_insights_field_app_id'),
	        'label' => lang('fb_insights_field_app_id'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'fb_insights_field_app_id',
		        'id' => 'fb_insights_field_app_id',
		        'value' => set_value('fb_insights_field_app_id') ? set_value('fb_insights_field_app_id') : $dataSource->getProperty('fb_insights_field_app_id'),
		        'maxlength' => '80',
		        'placeholder' => lang('fb_insights_field_app_id')
	        )
		)
	);

	$this->load->view('common/form/input', array(
			'error' => form_error('fb_insights_field_app_secret'),
	        'label' => lang('fb_insights_field_app_secret'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'fb_insights_field_app_secret',
		        'id' => 'fb_insights_field_app_secret',
		        'value' => set_value('fb_insights_field_app_secret') ? set_value('fb_insights_field_app_secret') : $dataSource->getProperty('fb_insights_field_app_secret'),
		        'maxlength' => '80',
		        'placeholder' => lang('fb_insights_field_app_secret')
	        )
		)
	);

	$this->load->view('common/form/input', array(
			'error' => form_error('fb_insights_field_permissions'),
	        'label' => lang('fb_insights_field_permissions'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'fb_insights_field_permissions',
		        'id' => 'fb_insights_field_permissions',
		        'value' => set_value('fb_insights_field_permissions') ? set_value('fb_insights_field_permissions') : $dataSource->getProperty('fb_insights_field_permissions'),
		        'maxlength' => '80',
		        'placeholder' => lang('fb_insights_field_permissions')
	        )
		)
	);
