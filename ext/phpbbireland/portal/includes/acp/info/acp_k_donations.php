<?php
/**
*
* @author Michael O'Toole (michaelo) http://phpbbireland.com
*
* @package acp (Stargate Portal)
* @version $Id: acp_k_donations.php 305 10 August 2009 16:03:23Z Michealo $
* @copyright (c) 2005-2009 phpbbireland.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package module_install
*/

class acp_k_donations_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_k_donations',
			'title'		=> 'ACP_K_DONATIONS',
			'version'	=> '1.0.0',
			'modes' => array(
				'add'		=> array('title' => 'ACP_K_DONATIONS_ADD',     'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_DONATIONSS')),
				'delete'	=> array('title' => 'ACP_K_DONATIONS_DELETE',  'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_DONATIONSS'), 'display' => false),
				'edit'		=> array('title' => 'ACP_K_DONATIONS_EDIT',    'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_DONATIONSS'), 'display' => false),
				'browse'	=> array('title' => 'ACP_K_DONATIONS_BROWSE',  'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_DONATIONSS'))
			),
		);
	}
	function install()
	{
	}
	function uninstall()
	{
	}
}

?>