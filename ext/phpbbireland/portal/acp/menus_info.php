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
class menus_info
{
	function module()
	{
		return array(
			'filename'	=> '\phpbbireland\portal\acp\menus_module',
			'title'		=> 'ACP_K_MENUS',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'add'       => array('title' => 'ACP_K_MENU_ADD',         'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_MENUS')),
				'nav'       => array('title' => 'ACP_K_MENU_MAIN',        'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_MENUS')),
				'sub'       => array('title' => 'ACP_K_MENU_SUB',         'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_MENUS')),
				'link'      => array('title' => 'ACP_K_MENU_LINKS',       'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_MENUS')),
				'edit'      => array('title' => 'ACP_K_MENU_EDIT',        'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_MENUS'), 'display' => false),
				'delete'    => array('title' => 'ACP_K_MENU_DELETE',      'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_MENUS'), 'display' => false),
				'up'        => array('title' => 'ACP_K_UP',               'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_MENUS'), 'display' => false),
				'down'      => array('title' => 'ACP_K_DOWN',             'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_MENUS'), 'display' => false),
				'all'       => array('title' => 'ACP_K_MENU_ALL',         'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_MENUS')),
				'unalloc'   => array('title' => 'ACP_K_MENU_UNALLOCATED', 'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_MENUS'))
			),
		);
	}

}

?>