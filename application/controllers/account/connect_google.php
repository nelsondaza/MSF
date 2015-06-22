<?php
/*
 * Connect_google Controller
 */
class Connect_google extends CI_Controller {

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// Load the necessary stuff...
		$this->load->config('account/account');
		$this->load->helper(array('language', 'account/ssl', 'url', 'account/google'));
		$this->load->library(array('account/authentication', 'account/authorization', 'account/google_lib'));
		$this->load->model(array('account/account_model', 'account/account_google_model'));
		$this->load->language(array('general', 'account/sign_in', 'account/account_linked', 'account/connect_third_party'));
	}


	function index()
	{
		// Enable SSL?
		maintain_ssl($this->config->item("ssl_enabled"));

		if ( $this->input->get('code') ) {
			$client = $this->google_lib->getClient();
			$plus = new Google_Service_Plus( $client );
			$client->authenticate($this->input->get('code'));
			$person = $plus->people->get('me');

			// Check if user has connect google to a3m
			if ($user = $this->account_google_model->get_by_google_id($person->getId()))
			{
				// Check if user is not signed in on a3m
				if ( !$this->authentication->is_signed_in())
				{
					// Run sign in routine
					$this->authentication->sign_in($user->account_id);
				}

				$user->account_id === $this->session->userdata('account_id') ? $this->session->set_flashdata('linked_error', sprintf(lang('linked_linked_with_this_account'), lang('connect_google'))) : $this->session->set_flashdata('linked_error', sprintf(lang('linked_linked_with_another_account'), lang('connect_google')));
				redirect('account/account_linked');
			}
			// The user has not connect google to a3m
			else
			{
				// Check if user is signed in on a3m
				if ( !$this->authentication->is_signed_in())
				{
					// Store user's google data in session
					$this->session->set_userdata('connect_create', array(
						array(
							'provider' => 'google',
							'provider_id' => $person->getId()
						),
						array(
							'fullname' => $person->getDisplayName(),
							'firstname' => $person->getName()->familyName,
							'lastname' => $person->getName()->givenName,
							'gender' => $person->gender,
							//'dateofbirth' => $this->google_lib->user['birthday'],
							// not a required field, not all users have it set
							'picture' => $person->getImage()->url
							// $this->google_lib->user['bio']
							// $this->google_lib->user['timezone']
							// $this->google_lib->user['locale']
							// $this->google_lib->user['verified']
							// $this->google_lib->user['updated_time']
					)));

					// Create a3m account
					redirect('account/connect_create');
				}
				else
				{
					// Connect google to a3m
					$this->account_google_model->insert($this->session->userdata('account_id'), $person->getId());
					$this->session->set_flashdata('linked_info', sprintf(lang('linked_linked_with_your_account'), lang('connect_google')));
					redirect('account/account_linked');
				}
			}
		}

		// Load google redirect view
		$this->load->view("account/redirect_gplus");
	}

}


/* End of file connect_google.php */
/* Location: ./application/account/controllers/connect_google.php */