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
class acp_k_blocks_info
{
	function module()
	{
		return array(
			'filename'  => 'acp_k_blocks',
			'title'     => 'ACP_K_BLOCKS',
			'version'   => '1.0.22',
			'modes'     => array(
				'add'      => array('title' => 'ACP_K_BLOCKS_ADD',         'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'edit'     => array('title' => 'ACP_K_BLOCKS_EDIT',        'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'), 'display' => false),
				'delete'   => array('title' => 'ACP_K_BLOCKS_DELETE',      'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'), 'display' => false),
				'up'       => array('title' => 'ACP_K_UP',                 'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'), 'display' => false),
				'down'     => array('title' => 'ACP_K_DOWN',               'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'), 'display' => false),
				'reindex'  => array('title' => 'ACP_K_BLOCKS_REINDEX',     'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'), 'display' => false),
				'L'        => array('title' => 'ACP_K_PAGE_LEFT_BLOCKS',   'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'C'        => array('title' => 'ACP_K_PAGE_CERTRE_BLOCKS', 'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'R'        => array('title' => 'ACP_K_PAGE_RIGHT_BLOCKS',  'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'manage'   => array('title' => 'ACP_K_BLOCKS_MANAGE',      'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'reset'    => array('title' => 'ACP_K_BLOCKS_RESET',       'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'))
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