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
class acp_k_config_info
{
	function module()
	{
		return array(
			'filename' => 'acp_k_config',
			'title'    => 'ACP_K_PORTAL_CONFIG',
			'version'  => '1.0.22',
			'modes'    => array(
				'config' => array('title' => 'ACP_K_PORTAL_CONFIG', 'auth' => 'acl_a_k_portal',	'cat' => array('ACP_K_CONFIG')),
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
