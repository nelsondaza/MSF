<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang('linked_page_name')) ) ?>
</head>
<body>
<?= $this->load->view('common/header') ?>
<div class="container content">
	<?= $this->load->view('common/menu', array('current' => 'account_linked') ) ?>
	<div class="sub-header"><i class="icon lock"></i> <?= lang('linked_page_name') ?></div>
	<div class="section">
<?php
	$errors = array( );
	if( $this->session->flashdata('linked_error') )
		$errors[] = $this->session->flashdata('linked_error');

	if ( !empty($errors) ) {
		$this->load->view('common/message', array('type' => 'error','class' => 'warning','content' => $errors) );
	}
	else if( $this->session->flashdata('linked_info') ) {
		$this->load->view('common/message', array('type' => 'success','class' => 'info','content' => $this->session->flashdata('linked_info')) );
	}
	else {
		$this->load->view('common/message', array('class' => 'warning sign','content' => lang('linked_page_satement')) );
	}
?>
            <h3><?php echo lang('linked_currently_linked_accounts'); ?></h3>
<?php
	if( $num_of_linked_accounts == 0 ) {
		$this->load->view( 'common/message', array( 'type' => 'warning', 'class'   => 'warning sign', 'content' => lang( 'linked_no_linked_accounts' ) ) );
	}
	else {
?>

			<?php if ($facebook_links) : ?>
				<?php foreach ($facebook_links as $facebook_link) : ?>
				<p>
					<? if ($num_of_linked_accounts >= 1 && isset($account->password)) { ?>
						<?= form_open(uri_string()) ?>
						<?= form_hidden('facebook_id', $facebook_link->facebook_id) ?>
						<?= anchor('http://facebook.com/' . $facebook_link->facebook_id, '<i class="ui icon facebook"></i> ' . lang('connect_facebook'), array('target' => '_blank', 'title' => lang('connect_facebook'), 'class' => 'circular ui green icon button ' ) ) ?>
						<?= form_button(array('type' => 'submit', 'class' => 'ui mini red icon button', 'content' => '<i class="icon trash"></i>', 'title' => lang('linked_remove') )) ?>
						<?= form_close() ?>
					<? } ?>
				</p>
					<?php endforeach; ?>
				<?php endif; ?>

			<?php if ($twitter_links) : ?>
				<?php foreach ($twitter_links as $twitter_link) : ?>
				<p>
					<? if ($num_of_linked_accounts >= 1 && isset($account->password)) { ?>
						<?= form_open(uri_string()) ?>
						<?= form_hidden('twitter_id', $twitter_link->twitter_id) ?>
						<?= anchor('http://twitter.com/'.$twitter_link->twitter->screen_name, '<i class="ui icon twitter"></i> ' . lang('connect_twitter'), array('target' => '_blank', 'title' => lang('connect_twitter'), 'class' => 'circular ui green icon button ' ) ) ?>
						<?= form_button(array('type' => 'submit', 'class' => 'ui mini red icon button', 'content' => '<i class="icon trash"></i>', 'title' => lang('linked_remove') )) ?>
						<?= form_close() ?>
					<? } ?>
				</p>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php if ($openid_links) : ?>
				<?php foreach ($openid_links as $openid_link) : ?>

                    <div class="row">
                        <div class="span1">
                            <img src="<?php echo RES_DIR?>/img/auth_icons/<?php echo $openid_link->provider; ?>.png" alt="<?php echo lang('connect_'.$openid_link->provider); ?>"
                                 width="40"/>
                        </div>
                        <div class="span7">
                            <strong><?php echo lang('connect_'.$openid_link->provider); ?></strong><br/>
							<?php echo anchor($openid_link->openid, substr($openid_link->openid, 0, 30).(strlen($openid_link->openid) > 30 ? '...' : ''), array('target' => '_blank', 'title' => $openid_link->openid)); ?>
                        </div>
                        <div class="span2">
							<?php if ($num_of_linked_accounts >= 1 && isset($account->password)) : ?>
							<?php echo form_open(uri_string()); ?>
							<?php echo form_fieldset(); ?>
							<?php echo form_hidden('openid', $openid_link->openid); ?>
							<?php echo form_button(array('type' => 'submit', 'class' => 'btn', 'content' => '<i class="icon-trash"></i> '.lang('linked_remove'))); ?>
							<?php echo form_fieldset_close(); ?>
							<?php echo form_close(); ?>
							<?php endif; ?>
                        </div>
                    </div>
					<?php endforeach; ?>
				<?php endif; ?>
<?php
	}
?>

            <h3><?php echo lang('linked_link_with_your_account_from'); ?></h3>
			<div class="ui raised segment">
<?php
				foreach ($this->config->item('third_party_auth_providers') as $provider) {
					$class = strtolower( lang('connect_'.$provider) );
					echo anchor( 'account/connect_' . $provider, '<i class="' . $class . ' icon"></i> ' . lang( 'connect_' . $provider ), array( 'class' => 'circular ui icon mini button ' . $class ) );
				}
?>
            </div>
    </div>
	<div class="clearfix"></div>
</div>
<?= $this->load->view('common/footer') ?>
</body>
</html>
