<?php
/**
*
* @package Portal Extension
* @copyright (c) 2013 phpbbireland
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbireland\portal\acp;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

class pages_info
{
	function module()
	{
		return array(
			'filename' => '\phpbbireland\portal\acp\pages_module',
			'title'    => 'ACP_PAGES_TITLE',
			'version'  => '1.0.0',
			'modes'    => array(
				'add'    => array('title' => 'ACP_K_PAGES_ADD',	   'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_PAGES'), 'display' => false),
				'delete' => array('title' => 'ACP_K_PAGES_DELETE', 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_PAGES'), 'display' => false),
				'land'   => array('title' => 'ACP_K_PAGES_LAND',   'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_PAGES'), 'display' => false),
				'manage' => array('title' => 'ACP_K_PAGES_MANAGE', 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_PAGES'))
			),
		);
	}
}
