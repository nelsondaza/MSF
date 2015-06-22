<?php

class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->helper(array('language', 'url', 'photo', 'form', 'account/ssl'));
		$this->load->library(array('account/authentication', 'account/authorization'));
		$this->load->model(array('account/account_model','account/account_details_model'));

		$this->load->language(array('general', 'account/account_profile'));

	}

	function index()
	{
		maintain_ssl();

		if ( $this->authentication->is_signed_in() ) {
			$data['account'] = $this->account_model->get_by_id($this->session->userdata('account_id'));
			$data['account_details'] = $this->account_details_model->get_by_account_id($this->session->userdata('account_id'));

			$this->load->view('home', isset($data) ? $data : NULL);
		}
		else {
			redirect('/account/sign_in', 'refresh');
		}
	}

}

/* End of file home.php */
/* Location: ./system/application/controllers/home.php */