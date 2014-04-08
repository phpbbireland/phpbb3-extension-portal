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
			array('config.add', array('k_blocks_enabled', 1)),
			array('config.add', array('k_portal_version', '1.0.0')),
			array('config.add', array('k_portal_build', '310-001')),
			array('config.add', array('k_blocks_width', '190')),

			array('permission.add', array('a_k_portal')),
			array('permission.add', array('a_k_tools')),
			array('permission.add', array('u_k_tools')),

			array('permission.permission_set', array('ROLE_ADMIN_FULL', 'a_k_portal')),
			array('permission.permission_set', array('ROLE_ADMIN_FULL', 'a_k_tools')),
			array('permission.permission_set', array('REGISTERED', 'u_k_tools', 'group')),

			array('module.add', array(
				'acp',
				'',
				'ACP_PORTAL_TITLE',
			)),

			array('module.add', array(
				'acp',
				'ACP_PORTAL_TITLE',
				'ACP_CONFIG_TITLE',
			)),
			array('module.add', array(
				'acp',
				'ACP_PORTAL_TITLE',
				'ACP_BLOCKS_TITLE',
			)),
			array('module.add', array(
				'acp',
				'ACP_PORTAL_TITLE',
				'ACP_MENUS_TITLE',
			)),

			array('module.add', array(
				'acp',
				'ACP_PORTAL_TITLE',
				'ACP_PAGES_TITLE',
			)),
			array('module.add', array(
				'acp',
				'ACP_PORTAL_TITLE',
				'ACP_RESOURCES_TITLE',
			)),


			array('module.add', array(
				'acp',
				'ACP_CONFIG_TITLE',
				array(
					'module_basename' => '\phpbbireland\portal\acp\config_module',
					'modes'           => array('config_portal'),
				),
			)),

			array('module.add', array(
				'acp',
				'ACP_BLOCKS_TITLE',
				array(
					'module_basename' => '\phpbbireland\portal\acp\blocks_module',
					'modes'           => array('add', 'edit', 'delete', 'up', 'down', 'reindex', 'L', 'C', 'R', 'manage', 'reset'),
				),
			)),
			array('module.add', array(
				'acp',
				'ACP_MENUS_TITLE',
				array(
					'module_basename' => '\phpbbireland\portal\acp\menus_module',
					'modes'           => array('add', 'nav', 'sub', 'link', 'edit', 'delete', 'up', 'down', 'all', 'unalloc'),
				),
			)),

			array('module.add', array(
				'acp',
				'ACP_PAGES_TITLE',
				array(
					'module_basename' => '\phpbbireland\portal\acp\pages_module',
					'modes'           => array('add', 'delete', 'land', 'manage'),
				),
			)),

			array('module.add', array(
				'acp',
				'ACP_RESOURCES_TITLE',
				array(
					'module_basename' => '\phpbbireland\portal\acp\resources_module',
					'modes'           => array('select'),
				),
			)),

			array('config.add', array('portal_mod_version', '1.0.0')),
		);
	}

	public function update_schema()
	{
		return array(

			'add_tables' => array(
				$this->table_prefix . 'k_config' => array(
					'COLUMNS' => array(
						'config_name'   => array('VCHAR', ''),
						'config_value'  => array('VCHAR', ''),
						'is_dynamic'    => array('BOOL', '0'),
					),
					'PRIMARY_KEY' => 'config_name',
					'KEYS'        => array('is_dynamic' => array('INDEX', 'is_dynamic'),
					),
				),
				$this->table_prefix . 'k_blocks' => array(
					'COLUMNS' => array(
						'id'                => array('UINT', NULL, 'auto_increment'),
						'ndx'               => array('UINT', '0'),
						'title'             => array('VCHAR:50', ''),
						'position'          => array('CHAR:1', 'L'),
						'type'              => array('CHAR:1', 'H'),
						'active'            => array('BOOL', '1'),
						'html_file_name'    => array('VCHAR', ''),
						'var_file_name'     => array('VCHAR', 'none.gif'),
						'img_file_name'     => array('VCHAR', 'none.gif'),
						'view_all'          => array('BOOL', '1'),
						'view_groups'       => array('VCHAR:100', ''),
						'view_pages'        => array('VCHAR:100', ''),
						'groups'            => array('UINT', '0'),
						'scroll'            => array('BOOL', '0'),
						'block_height'      => array('USINT', '0'),
						'has_vars'          => array('BOOL', '0'),
						'is_static'         => array('BOOL', '0'),
						'minimod_based'     => array('BOOL', '0'),
						'mod_block_id'      => array('UINT', '0'),
						'block_cache_time'  => array('UINT', '600'),
					),
					'PRIMARY_KEY'  => 'id',
				),
				$this->table_prefix . 'k_menus' => array(
					'COLUMNS' => array(
						'm_id'        => array('UINT', NULL, 'auto_increment'),
						'ndx'         => array('UINT', '0'),
						'menu_type'   => array('USINT', '0'),
						'name'        => array('VCHAR:50', ''),
						'link_to'     => array('VCHAR', ''),
						'extern'      => array('BOOL', '0'),
						'menu_icon'   => array('VCHAR:30', 'none.gif'),
						'append_sid'  => array('BOOL', '1'),
						'append_uid'  => array('BOOL', '0'),
						'view_all'    => array('BOOL', '1'),
						'view_groups' => array('VCHAR:100', ''),
						'soft_hr'     => array('BOOL', '0'),
						'sub_heading' => array('BOOL', '0'),
					),
					'PRIMARY_KEY'  => 'm_id',
				),
				$this->table_prefix . 'k_pages' => array(
					'COLUMNS' => array(
						'page_id'    => array('UINT', NULL, 'auto_increment'),
						'page_name'  => array('VCHAR_UNI:100', ''),
					),
					'PRIMARY_KEY'	=> 'page_id',
				),
				$this->table_prefix . 'k_resources' => array(
					'COLUMNS' => array(
						'id'    => array('UINT', NULL, 'auto_increment'),
						'word'  => array('VCHAR:50', ''),
						'type'  => array('CHAR:1', 'V'),
					),
					'PRIMARY_KEY' => 'id',
				),
			),

			'add_columns'    => array(
				$this->table_prefix . 'users' => array(
					'user_left_blocks'    => array('VCHAR:255', ''),
					'user_center_blocks'  => array('VCHAR:255', ''),
					'user_right_blocks'   => array('VCHAR:255', ''),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'k_config',
				$this->table_prefix . 'k_blocks',
				$this->table_prefix . 'k_menus',
				$this->table_prefix . 'k_pages',
				$this->table_prefix . 'k_resources',
			),
			'drop_columns' => array(
				$this->table_prefix . 'users' => array(
					'user_left_blocks',
					'user_center_blocks',
					'user_right_blocks',
				),
			),
			array('config.remove', array('k_portal_enabled')),
			array('config.remove', array('k_blocks_enabled')),
			array('config.remove', array('k_portal_version')),
			array('config.remove', array('k_portal_build')),
			array('config.remove', array('k_blocks_width')),

			array('permission.remove', array('a_k_portal')),
			array('permission.remove', array('a_k_tools')),
			array('permission.remove', array('u_k_tools')),
		);
	}

	public function seed()
	{
		return array(
			'insert' => array(
				$this->table_prefix . 'k_blocks' => array(
					'ndx'               => '1',
					'title'             => 'Site Navigator',
					'position'          => 'L',
					'type'              => 'H',
					'active'            => '1',
					'html_file_name'    => 'block_menus_nav.html',
					'var_file_name'     => '',
					'img_file_name'     => 'menu.png',
					'view_all'          => '1',
					'view_groups'       => '0',
					'view_pages'        => '1,2,3,4,5,6,7,8,9,11,12,13,14',
					'groups'            => '0',
					'scroll'            => '0',
					'block_height'      => '0',
					'has_vars'          => '0',
					'minimod_based'     => '0',
					'mod_block_id'      => '0',
					'is_static'         => '0',
					'block_cache_time'  => '600',
				),
				$this->table_prefix . 'k_blocks' => array(
					'ndx'               => '2',
					'title'             => 'Sub_Menu',
					'position'          => 'L',
					'type'              => 'H',
					'active'            => '1',
					'html_file_name'    => 'block_menus_sub.html',
					'var_file_name'     => '',
					'img_file_name'     => 'sub_menu.png',
					'view_all'          => '1',
					'view_groups'       => '0',
					'view_pages'        => '1,2,3,4,5,6,7,8,9,11,12,13,14',
					'groups'            => '0',
					'scroll'            => '0',
					'block_height'      => '0',
					'has_vars'          => '0',
					'minimod_based'     => '0',
					'mod_block_id'      => '0',
					'is_static'         => '0',
					'block_cache_time'  => '600',
				),
			),
		);
	}


	/*
	public function seed_database()
	{
		include('\phpbbireland\portal\sql_data.' . $phpEx);

		return array(
			'add_columns' => array(
				array($k_blocks_table, $k_blocks_array),
				array($k_blocks_config_table, $k_blocks_config_array),
				array($k_blocks_config_vars_table, $k_blocks_config_vars_array),
				array($k_menus_table, $k_menus_array),
				array($k_pages_table, $k_pages_array),
				array($k_resources_table, $k_resources_array),
			),
		);


		global $db;
		$db_seed_file = '\phpbbireland\portal\310a3.sql';
		$handle = fopen($db_seed_file, "rb");
		$sql = fread($handle, filesize($db_seed_file));
		fclose($handle);
		var_dump($sql);
		$result = $db->sql_query($sql);
	}
	*/
}
