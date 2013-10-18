<?php
/**
*
* @package migration
* @copyright (c) 2012 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*
*/

namespace phpbbireland\portal\migrations\v10x;

class release_1_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['portal_mod_version']) && version_compare($this->config['portal_mod_version'], '1.0.0', '>=');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_data()
	{
		return array(

			array('config_add', array('k_portal_enabled', 1)),
			array('config.add', array('k_portal_build', '310-001')),
			array('config.add', array('k_blocks_enabled', 1)),
			array('config.add', array('k_blocks_width', '190')),

			array('module_add', array(
				'acp',
				'',
				'ACP_CAT_PORTAL',
			)),

/*
			array('module_add', array(
				'acp', 'ACP_CAT_PORTAL', 'ACP_CONFIG',
				'acp', 'ACP_CAT_PORTAL', 'ACP_BLOCKS',
				'acp', 'ACP_CAT_PORTAL', 'ACP_MENUS',
				'acp', 'ACP_CAT_PORTAL', 'ACP_VARIABLES',
			)),
*/

			array('module_add', array(
				'acp', 'ACP_CAT_PORTAL', 'ACP_CONFIG',
				'acp', 'ACP_CAT_PORTAL', 'ACP_BLOCKS',
				'acp', 'ACP_CAT_PORTAL', 'ACP_MENUS',
			)),

			array('module.add', array(
				'acp',
				'ACP_CONFIG',
				array(
					'module_basename'	=> '\phpbbireland\portal\acp\config_module',
					'modes'	=> array('config'),
				),
			)),
			array('module.add', array(
				'acp',
				'ACP_BLOCKS',
				array(
					'module_basename'	=> '\phpbbireland\portal\acp\blocks_module',
					'modes'	=> array('add', 'edit', 'delete', 'up', 'down', 'reindex', 'L', 'C', 'R', 'manage', 'reset'),
				),
			)),
			array('module.add', array(
				'acp',
				'ACP_MENUS',
				array(
					'module_basename'	=> '\phpbbireland\portal\acp\menus_module',
					'modes'	=> array('add', 'nav', 'sub', 'link', 'edit', 'delete', 'up', 'down', 'all', 'unalloc'),
				),
			)),

/*
			array('module.add', array(
				'acp',
				'ACP_VARIABLES',
				array(
					'module_basename'	=> '\phpbbireland\portal\acp\variables_module',
					'modes'	=> array('config_variables'),
				),
			)),
*/

/*
			array('module.add', array(
				'acp',
				'ACP_K_CONFIG',
				array(
					'module_basename'	=> '\phpbbireland\portal\acp\config_module',
					'modes'	=> array(
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
				),
			)),

*/
			array('config.add', array('portal_mod_version', '1.0.0')),
		);
	}
}
