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
class resources_info
{
	function module()
	{
		return array(
			'filename' => '\phpbbireland\portal\acp\resources_module',
			'title'    => 'ACP_K_RESOURCES',
			'version'  => '1.0.0',
			'modes'    => array(
				'select' => array('title' => 'ACP_K_RESOURCES', 'auth' => 'acl_a_k_tools', 'cat' => array('ACP_K_TOOLS')),
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