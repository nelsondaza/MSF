<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_google_model extends CI_Model {

	/**
	 * Get account google
	 *
	 * @access public
	 * @param string $account_id
	 * @return object account google
	 */
	function get_by_account_id($account_id)
	{
		return $this->db->get_where('a3m_account_google', array('account_id' => $account_id))->result();
	}

	// --------------------------------------------------------------------

	/**
	 * Get account google
	 *
	 * @access public
	 * @param string $google_id
	 * @return object account google
	 */
	function get_by_google_id($google_id)
	{
		return $this->db->get_where('a3m_account_google', array('google_id' => $google_id))->row();
	}

	// --------------------------------------------------------------------

	/**
	 * Insert account google
	 *
	 * @access public
	 * @param int $account_id
	 * @param int $google_id
	 * @return void
	 */
	function insert($account_id, $google_id)
	{
		$this->load->helper('date');

		if ( ! $this->get_by_google_id($google_id)) // ignore insert
		{
			$this->db->insert('a3m_account_google', array('account_id' => $account_id, 'google_id' => $google_id, 'linkedon' => mdate('%Y-%m-%d %H:%i:%s', now())));
			return TRUE;
		}
		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Delete account google
	 *
	 * @access public
	 * @param int $google_id
	 * @return void
	 */
	function delete($google_id)
	{
		$this->db->delete('a3m_account_google', array('google_id' => $google_id));
	}

}


/* End of file account_google_model.php */
/* Location: ./application/account/models/account_google_model.php */