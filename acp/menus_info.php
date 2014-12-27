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

class menus_info
{
	function module()
	{
		return array(
			'filename'	=> '\phpbbireland\portal\acp\menus_module',
			'title'		=> 'ACP_MENUS_TITLE',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'add'       => array('title' => 'ACP_K_MENU_ADD',         'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_MENUS')),
				'nav'       => array('title' => 'ACP_K_MENU_MAIN',        'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_MENUS')),
				'sub'       => array('title' => 'ACP_K_MENU_SUB',         'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_MENUS')),
				'link'      => array('title' => 'ACP_K_MENU_LINKS',       'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_MENUS')),
				'edit'      => array('title' => 'ACP_K_MENU_EDIT',        'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_MENUS'), 'display' => false),
				'delete'    => array('title' => 'ACP_K_MENU_DELETE',      'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_MENUS'), 'display' => false),
				'up'        => array('title' => 'ACP_K_UP',               'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_MENUS'), 'display' => false),
				'down'      => array('title' => 'ACP_K_DOWN',             'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_MENUS'), 'display' => false),
				'all'       => array('title' => 'ACP_K_MENU_ALL',         'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_MENUS')),
				'unalloc'   => array('title' => 'ACP_K_MENU_UNALLOCATED', 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_MENUS'))
			),
		);
	}
}
