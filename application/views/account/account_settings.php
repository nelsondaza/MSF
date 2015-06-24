<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang('settings_page_name')) ) ?>
</head>
<body>
<?= $this->load->view('common/header', array('current' => 'account_settings')) ?>
<div class="container content">
	<div class="sub-header"><i class="icon user"></i> <?= lang('settings_page_name') ?></div>
	<div class="section">
<?php
	$errors = array( );
	if( isset( $settings_email_error ) )
		$errors[] = $settings_email_error;
	if( form_error('settings_email') )
		$errors[] = form_error('settings_email');
	if( form_error('settings_fullname') )
		$errors[] = form_error('settings_fullname');
	if( isset( $settings_dob_error ) )
		$errors[] = $settings_dob_error;
	if( form_error('settings_postalcode') )
		$errors[] = form_error('settings_postalcode');
	if( form_error('settings_country') )
		$errors[] = form_error('settings_country');
	if( form_error('settings_language') )
		$errors[] = form_error('settings_language');
	if( form_error('settings_timezone') )
		$errors[] = form_error('settings_timezone');

	if ( !empty($errors) ) {
		$this->load->view('common/message', array('type' => 'error','class' => 'warning','content' => $errors) );
	}
	else if (isset($settings_info)) {
		$this->load->view('common/message', array('type' => 'success','class' => 'info','content' => $settings_info) );
	}
	else {
		$this->load->view('common/message', array('class' => 'lock','content' => sprintf(lang('settings_privacy_statement'), anchor('page/privacy-policy', lang('settings_privacy_policy'))) ) );
	}
?>
		<div class="ui tertiary segment container-form">
<?php
	echo form_open_multipart(uri_string(), 'class="ui form ' . ( !empty($errors) ? 'error' : '' ) . '"');

	$this->load->view('common/form/input', array(
			'error' => form_error('settings_email') || isset($settings_email_error),
	        'label' => lang('settings_email'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'settings_email',
		        'id' => 'settings_email',
		        'value' => set_value('settings_email') ? set_value('settings_email') : ( isset($account->email) ? $account->email : ''),
		        'maxlength' => '160',
		        'placeholder' => lang('settings_email')
	        )
		)
	);
	$this->load->view('common/form/input', array(
			'error' => form_error('settings_fullname'),
	        'label' => lang('settings_fullname'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'settings_fullname',
		        'id' => 'settings_fullname',
		        'value' => set_value('settings_fullname') ? set_value('settings_fullname') : ( isset($account_details->fullname) ? $account_details->fullname : ''),
		        'maxlength' => '160',
		        'placeholder' => lang('settings_fullname')
	        )
		)
	);
	$this->load->view('common/form/input', array(
			'error' => form_error('settings_firstname'),
	        'label' => lang('settings_firstname'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'settings_firstname',
		        'id' => 'settings_firstname',
		        'value' => set_value('settings_firstname') ? set_value('settings_firstname') : ( isset($account_details->firstname) ? $account_details->firstname : ''),
		        'maxlength' => '80',
		        'placeholder' => lang('settings_firstname')
	        )
		)
	);
	$this->load->view('common/form/input', array(
			'error' => form_error('settings_lastname'),
	        'label' => lang('settings_lastname'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'settings_lastname',
		        'id' => 'settings_lastname',
		        'value' => set_value('settings_lastname') ? set_value('settings_lastname') : ( isset($account_details->lastname) ? $account_details->lastname : ''),
		        'maxlength' => '80',
		        'placeholder' => lang('settings_lastname')
	        )
		)
	);

    echo '<div class="three fields ' . ( isset( $settings_dob_error ) && $settings_dob_error ? 'error' : '' ) . '">';

    $m = $this->input->post('settings_dob_month') ? $this->input->post('settings_dob_month') : (isset($account_details->dob_month) ? $account_details->dob_month : '');

	$this->load->view('common/form/input', array(
			'error' => isset( $settings_dob_error ) && $settings_dob_error,
	        'label' => lang('settings_dateofbirth'),
	        'attributes' => array(
		        'type' => 'dropdown',
		        'name' => 'settings_dob_month',
		        'id' => 'settings_dob_month',
				'class' => 'chosen-select',
		        'value' => $m,
		        'placeholder' => lang('dateofbirth_month')
	        ),
            'options' => array(
                1 => lang('month_jan'),
                2 => lang('month_feb'),
                3 => lang('month_mar'),
                4 => lang('month_apr'),
                5 => lang('month_may'),
                6 => lang('month_jun'),
                7 => lang('month_jul'),
                8 => lang('month_aug'),
                9 => lang('month_sep'),
                10 => lang('month_oct'),
                11 => lang('month_nov'),
                12 => lang('month_dec')
            ),
            'selected' => $m,
            'divider' => false
		)
	);

    $d = $this->input->post('settings_dob_day') ? $this->input->post('settings_dob_day') : (isset($account_details->dob_day) ? $account_details->dob_day : '');
    $days = array();
    for( $c = 1; $c <= 31; $c ++ )
        $days[$c] = sprintf( "%02d", $c );

    $this->load->view('common/form/input', array(
			'error' => isset( $settings_dob_error ) && $settings_dob_error,
            'label' => ' <br>',
	        'attributes' => array(
		        'type' => 'dropdown',
		        'name' => 'settings_dob_day',
		        'id' => 'settings_dob_day',
				'class' => 'chosen-select',
		        'value' => $d,
		        'placeholder' => lang('dateofbirth_day')
	        ),
            'options' => $days,
            'selected' => $d,
            'divider' => false
		)
	);

    $y = $this->input->post('settings_dob_year') ? $this->input->post('settings_dob_year') : (isset($account_details->dob_year) ? $account_details->dob_year : '');
    $years = array();
    for ( $c = date("Y") - 10; $c > date("Y") - 110; $c --)
        $years[$c] = sprintf( "%04d", $c );

    $this->load->view('common/form/input', array(
			'error' => isset( $settings_dob_error ) && $settings_dob_error,
            'label' => ' <br>',
	        'attributes' => array(
		        'type' => 'dropdown',
		        'name' => 'settings_dob_year',
		        'id' => 'settings_dob_year',
				'class' => 'chosen-select',
		        'value' => $y,
		        'placeholder' => lang('dateofbirth_year')
	        ),
            'options' => $years,
            'selected' => $y,
            'divider' => true
		)
	);

    echo '</div>';

    $s = ($this->input->post('settings_gender') ? $this->input->post('settings_gender') : (isset($account_details->gender) ? $account_details->gender : ''));
    $this->load->view('common/form/input', array(
			'error' => isset( $settings_dob_error ) && $settings_dob_error,
            'label' => lang('settings_gender'),
	        'attributes' => array(
		        'type' => 'dropdown',
		        'name' => 'settings_gender',
		        'id' => 'settings_gender',
				'class' => 'chosen-select',
		        'value' => $s,
		        'placeholder' => lang('settings_gender')
	        ),
            'options' => array(
                'm' => lang('gender_male'),
                'f' => lang('gender_female')
            ),
            'selected' => $s
		)
	);

	$this->load->view('common/form/input', array(
			'error' => form_error('settings_postalcode'),
	        'label' => lang('settings_postalcode'),
	        'attributes' => array(
		        'type' => 'text',
		        'name' => 'settings_postalcode',
		        'id' => 'settings_postalcode',
		        'value' => set_value('settings_postalcode') ? set_value('settings_postalcode') : (isset($account_details->postalcode) ? $account_details->postalcode : ''),
		        'maxlength' => '40',
		        'placeholder' => lang('settings_postalcode')
	        )
		)
	);

    $account_country = ($this->input->post('settings_country') ? $this->input->post('settings_country') : (isset($account_details->country) ? $account_details->country : 'co'));
    $countries_list = array();
    foreach ($countries as $country) {
        $countries_list[$country->alpha2] = $country->country;
    }

    $this->load->view('common/form/input', array(
			'error' => form_error('settings_country'),
            'label' => lang('settings_country'),
	        'attributes' => array(
		        'type' => 'dropdown',
		        'name' => 'settings_country',
		        'id' => 'settings_country',
				'class' => 'chosen-select',
		        'value' => $s,
		        'placeholder' => lang('settings_country')
	        ),
            'options' => $countries_list,
            'selected' => $account_country
		)
	);

    $account_language = ($this->input->post('settings_language') ? $this->input->post('settings_language') : (isset($account_details->language) ? $account_details->language : 'es'));
    $language_list = array();
    foreach ($languages as $language) {
        $language_list[$language->one] = $language->language;
    }

    $this->load->view('common/form/input', array(
			'error' => form_error('settings_language'),
            'label' => lang('settings_language'),
	        'attributes' => array(
		        'type' => 'dropdown',
		        'name' => 'settings_language',
		        'id' => 'settings_language',
				'class' => 'chosen-select',
		        'value' => $s,
		        'placeholder' => lang('settings_language')
	        ),
            'options' => $language_list,
            'selected' => $account_language
		)
	);

    $account_timezone = ($this->input->post('settings_timezone') ? $this->input->post('settings_timezone') : (isset($account_details->timezone) ? $account_details->timezone : 'America/Bogota'));
    $timezone_list = array();
    foreach ($zoneinfos as $zoneinfo) {
        $timezone_list[$zoneinfo->zoneinfo] = $zoneinfo->zoneinfo . ( $zoneinfo->offset ? ' ('.$zoneinfo->offset.')' : '' );
    }

    $this->load->view('common/form/input', array(
			'error' => form_error('settings_timezone'),
            'label' => lang('settings_timezone'),
	        'attributes' => array(
		        'type' => 'dropdown',
		        'name' => 'settings_timezone',
		        'id' => 'settings_timezone',
				'class' => 'chosen-select',
		        'value' => $s,
		        'placeholder' => lang('settings_timezone')
	        ),
            'options' => $timezone_list,
            'selected' => $account_timezone
		)
	);



?>
			<div class="field">
                <?= form_button(array('type' => 'submit', 'class' => 'ui submit primary button small right floated', 'content' => '<i class="archive icon"></i> '.lang('settings_save'))); ?>
                <?= form_button(array('type' => 'reset', 'class' => 'ui submit secondary button small right floated', 'content' => '<i class="undo icon"></i> Cancelar')); ?>
			</div>

			<?= form_close() ?>
		</div>
	</div>
    <div class="clearfix"></div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
