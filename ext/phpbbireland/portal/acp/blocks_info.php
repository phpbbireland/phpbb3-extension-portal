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

class blocks_info
{
	function module()
	{
		return array(
			'filename'	=> '\phpbbireland\portal\acp\blocks_module',
			'title'		=> 'ACP_BLOCKS_TITLE',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'add'		=> array('title' => 'ACP_K_BLOCKS_ADD',         'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'edit'		=> array('title' => 'ACP_K_BLOCKS_EDIT',        'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'), 'display' => false),
				'delete'	=> array('title' => 'ACP_K_BLOCKS_DELETE',      'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'), 'display' => false),
				'up'		=> array('title' => 'ACP_K_UP',                 'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'), 'display' => false),
				'down'		=> array('title' => 'ACP_K_DOWN',               'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'), 'display' => false),
				'reindex'	=> array('title' => 'ACP_K_BLOCKS_REINDEX',     'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'), 'display' => false),
				'L'			=> array('title' => 'ACP_K_PAGE_LEFT_BLOCKS',   'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'C'			=> array('title' => 'ACP_K_PAGE_CERTRE_BLOCKS', 'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'R'			=> array('title' => 'ACP_K_PAGE_RIGHT_BLOCKS',  'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'manage'	=> array('title' => 'ACP_K_BLOCKS_MANAGE',      'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'reset'		=> array('title' => 'ACP_K_BLOCKS_RESET',       'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'))
			),
		);
	}
}
