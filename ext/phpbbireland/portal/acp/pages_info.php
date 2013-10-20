<?php
/**
*
* @package acp (Kiss Portal Engine)
* @version $Id$
* @copyright (c) 2005-2013 phpbbireland
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
class pages_info
{
	function module()
	{
		return array(
			'filename' => '\phpbbireland\portal\acp\pages_module',
			'title'    => 'ACP_K_PAGES',
			'version'  => '1.0.0',
			'modes'    => array(
				'add'    => array('title' => 'ACP_K_PAGES_ADD',	   'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_PAGES'), 'display' => false),
				'delete' => array('title' => 'ACP_K_PAGES_DELETE', 'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_PAGES'), 'display' => false),
				'land'   => array('title' => 'ACP_K_PAGES_LAND',   'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_PAGES'), 'display' => false),
				'manage' => array('title' => 'ACP_K_PAGES_MANAGE', 'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_PAGES'))
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