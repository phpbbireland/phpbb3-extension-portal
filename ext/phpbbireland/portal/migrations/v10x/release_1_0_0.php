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

			array('config.add', array('k_portal_enabled', 1)),
			array('config.add', array('k_portal_build', '310-001')),
			array('config.add', array('k_blocks_enabled', 1)),
			array('config.add', array('k_blocks_width', '190')),

			array('module_add', array(
				'acp',
				'',
				'ACP_CAT_PORTAL',
			)),

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

			array('config.add', array('portal_mod_version', '1.0.0')),
		);
	}
}
