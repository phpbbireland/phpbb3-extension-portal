<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbbireland\portal\acp;

class blocks_info
{
	function module()
	{
		return array(
			'filename'	=> '\phpbbireland\portal\acp\blocks_module',
			'title'		=> 'ACP_BLOCKS_TITLE',
			'modes'		=> array(
				'add'		=> array('title' => 'ACP_K_BLOCKS_ADD',         'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'edit'		=> array('title' => 'ACP_K_BLOCKS_EDIT',        'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'), 'display' => false),
				'delete'	=> array('title' => 'ACP_K_BLOCKS_DELETE',      'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'), 'display' => false),
				'up'		=> array('title' => 'ACP_K_UP',                 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'), 'display' => false),
				'down'		=> array('title' => 'ACP_K_DOWN',               'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'), 'display' => false),
				'reindex'	=> array('title' => 'ACP_K_BLOCKS_REINDEX',     'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'), 'display' => false),
				'L'			=> array('title' => 'ACP_K_PAGE_LEFT_BLOCKS',   'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'C'			=> array('title' => 'ACP_K_PAGE_CERTRE_BLOCKS', 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'R'			=> array('title' => 'ACP_K_PAGE_RIGHT_BLOCKS',  'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'manage'	=> array('title' => 'ACP_K_BLOCKS_MANAGE',      'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'reset'		=> array('title' => 'ACP_K_BLOCKS_RESET',       'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS')),
				'config'	=> array('title' => 'ACP_BLOCK_CONFIG',         'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_BLOCKS'))
			),
		);
	}
}
