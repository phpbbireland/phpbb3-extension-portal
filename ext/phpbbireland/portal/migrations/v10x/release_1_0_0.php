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
				$this->table_prefix . 'k_blocks' => array(
					'COLUMNS' => array(
						'id'               => array('UINT', NULL, 'auto_increment'),
						'ndx'              => array('UINT', '0'),
						'title'            => array('VCHAR:50', ''),
						'position'         => array('CHAR:1', 'L'),
						'type'             => array('CHAR:1', 'H'),
						'active'           => array('BOOL', '1'),
						'html_file_name'   => array('VCHAR', ''),
						'var_file_name'    => array('VCHAR', 'none.gif'),
						'img_file_name'    => array('VCHAR', 'none.gif'),
						'view_all'         => array('BOOL', '1'),
						'view_groups'      => array('VCHAR:100', ''),
						'view_pages'       => array('VCHAR:100', ''),
						'groups'           => array('UINT', '0'),
						'scroll'           => array('BOOL', '0'),
						'block_height'     => array('USINT', '0'),
						'has_vars'         => array('BOOL', '0'),
						'is_static'        => array('BOOL', '0'),
						'minimod_based'    => array('BOOL', '0'),
						'mod_block_id'     => array('UINT', '0'),
						'block_cache_time' => array('UINT', '600'),
					),
					'PRIMARY_KEY'  => 'id',
				),

				$this->table_prefix . 'k_menus' => array(
					'COLUMNS' => array(
						'm_id'         => array('UINT', NULL, 'auto_increment'),
						'ndx'          => array('UINT', '0'),
						'menu_type'    => array('USINT', '0'),
						'name'         => array('VCHAR:50', ''),
						'link_to'      => array('VCHAR', ''),
						'extern'       => array('BOOL', '0'),
						'menu_icon'    => array('VCHAR:30', 'none.gif'),
						'append_sid'   => array('BOOL', '1'),
						'append_uid'   => array('BOOL', '0'),
						'view_all'     => array('BOOL', '1'),
						'view_groups'  => array('VCHAR:100', ''),
						'soft_hr'      => array('BOOL', '0'),
						'sub_heading'  => array('BOOL', '0'),
					),
					'PRIMARY_KEY'  => 'm_id',
				),

				$this->table_prefix . 'k_config' => array(
					'COLUMNS' => array(
						'id'                  => array('USINT', NULL, 'auto_increment'),
						'use_external_files'  => array('BOOL', '0'),
						'update_files'        => array('BOOL', '0'),
						'layout_default'      => array('BOOL', '2'),
						'portal_config'       => array('VCHAR:10', 'Site'),
					),
					'PRIMARY_KEY'  => 'id',
				),

				$this->table_prefix . 'k_vars' => array(
					'COLUMNS' => array(
						'config_name'   => array('VCHAR', ''),
						'config_value'  => array('VCHAR', ''),
						'is_dynamic'    => array('BOOL', '0'),
					),
					'PRIMARY_KEY'  => 'config_name',
					'KEYS'         => array('is_dynamic'	=> array('INDEX', 'is_dynamic'),
					),
				),

				$this->table_prefix . 'k_resources' => array(
					'COLUMNS' => array(
						'id'    => array('UINT', NULL, 'auto_increment'),
						'word'  => array('VCHAR:30', ''),
						'type'  => array('CHAR:1', 'V'),
					),
					'PRIMARY_KEY'  => 'id',
				),

				$this->table_prefix . 'k_pages' => array(
					'COLUMNS' => array(
						'page_id'    => array('UINT', NULL, 'auto_increment'),
						'page_name'  => array('VCHAR_UNI:100', ''),
					),
					'PRIMARY_KEY'	=> 'page_id',
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'users' => array(
					array('user_left_blocks', '2'),
					array('user_center_blocks', '2'),
					array('user_right_blocks', '2'),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '1',
						'title'			=> 'Site Navigator',
						'position'		=> 'L',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_menus_nav.html',
						'var_file_name'	=> '',
						'img_file_name'	=> 'menu.png',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '1,2,3,4,5,6,7,8,9,11,12,13,14',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '0',
						'has_vars'		=> '0',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '600',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '2',
						'title'			=> 'Sub_Menu',
						'position'		=> 'L',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_menus_sub.html',
						'var_file_name'	=> '',
						'img_file_name'	=> 'sub_menu.png',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '1,2,3,4,5,6,7,8,9,11,12,13,14',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '0',
						'has_vars'		=> '0',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '600',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '3',
						'title'			=> 'Links_Menu',
						'position'		=> 'L',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_menus_links.html',
						'var_file_name'	=> '',
						'img_file_name'	=> 'sub_menu.png',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '1,2,3,4,5,6,7,8,9,11,12,13,14',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '0',
						'has_vars'		=> '0',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '600',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '4',
						'title'			=> 'Online Users',
						'position'		=> 'L',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_online_users.html',
						'var_file_name'	=> '',
						'img_file_name'	=> 'online_users.png',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '1,2,3,4,5,6,7,8,9',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '0',
						'has_vars'		=> '0',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '600',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '5',
						'title'			=> 'Last Online',
						'position'		=> 'L',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_last_online.html',
						'var_file_name'	=> 'k_last_online_vars.html',
						'img_file_name'	=> 'last_online.png',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '2,3,4,5,6,7,8,9',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '0',
						'has_vars'		=> '1',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '300',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '8',
						'title'			=> 'Search',
						'position'		=> 'L',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_search.html',
						'var_file_name'	=> '',
						'img_file_name'	=> 'search.png',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '2,3,4,5,6,7,12',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '0',
						'has_vars'		=> '0',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '600',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '10',
						'title'			=> 'Categories',
						'position'		=> 'L',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_forum_categories.html',
						'var_file_name'	=> '',
						'img_file_name'	=> 'categories.png',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '2',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '0',
						'has_vars'		=> '0',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '667',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '2',
						'title'			=> 'Welcome',
						'position'		=> 'C',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_welcome.html',
						'var_file_name'	=> '',
						'img_file_name'	=> 'welcome.png',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '2',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '0',
						'has_vars'		=> '1',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '999',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '3',
						'title'			=> 'Announcements',
						'position'		=> 'C',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_announcements.html',
						'var_file_name'	=> 'k_announce_vars.html',
						'img_file_name'	=> 'announce.png',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '2',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '0',
						'has_vars'		=> '1',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '300',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '4',
						'title'			=> 'Recent Topics',
						'position'		=> 'C',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_recent_topics_wide.html',
						'var_file_name'	=> 'k_recent_topics_vars.html',
						'img_file_name'	=> 'recent_topics.png',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '2',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '200',
						'has_vars'		=> '1',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '20',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '5',
						'title'			=> 'News Report',
						'position'		=> 'C',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_news.html',
						'var_file_name'	=> 'k_news_vars.html',
						'img_file_name'	=> 'news.png',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '2',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '0',
						'has_vars'		=> '1',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '50',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '1',
						'title'			=> 'User Information',
						'position'		=> 'R',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_user_information.html',
						'var_file_name'	=> 'k_user_information_vars.html',
						'img_file_name'	=> 'user.png',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '2,5,8,9',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '0',
						'has_vars'		=> '1',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '600',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '3',
						'title'			=> 'The Team',
						'position'		=> 'R',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_the_teams.html',
						'var_file_name'	=> 'k_the_teams_vars.html',
						'img_file_name'	=> 'team.png',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '2,5,8,9',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '0',
						'has_vars'		=> '1',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '555',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '4',
						'title'			=> 'Top Posters',
						'position'		=> 'R',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_top_posters.html',
						'var_file_name'	=> 'k_top_posters_vars.html',
						'img_file_name'	=> 'rating.png',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '2,5,8,9',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '0',
						'has_vars'		=> '1',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '200',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '6',
						'title'			=> 'Most Active Topics',
						'position'		=> 'R',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_top_topics.html',
						'var_file_name'	=> 'k_top_topics_vars.html',
						'img_file_name'	=> 'most_active_topics.png',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '2',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '0',
						'has_vars'		=> '1',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '100',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '8',
						'title'			=> 'Clock',
						'position'		=> 'R',
						'type'			=> 'H',
						'active'		=> '1',
						'html_file_name'=> 'block_clock.html',
						'var_file_name'	=> '',
						'img_file_name'	=> 'clock.gif',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '2,12,13,14',
						'groups'		=> '0',
						'scroll'		=> '0',
						'block_height'	=> '0',
						'has_vars'		=> '0',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '6009',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks' => array(
					array(
						'ndx'			=> '10',
						'title'			=> 'Links',
						'position'		=> 'R',
						'type'			=> 'H',
						'active'		=> '0',
						'html_file_name'=> 'block_links.html',
						'var_file_name'	=> 'k_links_vars.html',
						'img_file_name'	=> 'www.gif',
						'view_all'		=> '1',
						'view_groups'	=> '0',
						'view_pages'	=> '2,5,8,9,12',
						'groups'		=> '0',
						'scroll'		=> '1',
						'block_height'	=> '0',
						'has_vars'		=> '1',
						'minimod_based'	=> '0',
						'mod_block_id'	=> '0',
						'is_static'		=> '0',
						'block_cache_time'	=> '668',
					),
				),
			),

			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config' => array(
					array(
						'id'					=> '1',
						'use_external_files'	=> '0',
						'update_files'			=> '0',
						'layout_default'		=> '2',
						'portal_config'			=> 'Site',
					),
				),
			),

			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_announce_allow',
						'config_value'	=> '1',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_allow_acronyms',
						'config_value'	=> '1',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_bot_display_allow',
						'config_value'	=> '1',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_footer_images_allow',
						'config_value'	=> '1',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_news_allow',
						'config_value'	=> '1',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_announce_type',
						'config_value'	=> '0',
						'is_dynamic'	=> '1',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_blocks_display_globally',
						'config_value'	=> '1',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_smilies_show',
						'config_value'	=> '1',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_links_scroll_amount',
						'config_value'	=> '0',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_links_scroll_direction',
						'config_value'	=> '0',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_announce_item_max_length',
						'config_value'	=> '400',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_news_item_max_length',
						'config_value'	=> '350',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_last_online_max',
						'config_value'	=> '10',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_news_type',
						'config_value'	=> '0',
						'is_dynamic'	=> '1',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_announce_to_display',
						'config_value'	=> '5',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_links_to_display',
						'config_value'	=> '5',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_links_forum_id',
						'config_value'	=> '',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_news_items_to_display',
						'config_value'	=> '5',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_recent_topics_to_display',
						'config_value'	=> '25',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_recent_topics_per_forum',
						'config_value'	=> '5',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_top_posters_to_display',
						'config_value'	=> '10',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_top_posters_show',
						'config_value'	=> '1',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_recent_search_days',
						'config_value'	=> '7',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_allow_rotating_logos',
						'config_value'	=> '1',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_post_types',
						'config_value'	=> '1',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_top_topics_max',
						'config_value'	=> '5',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_adm_block',
						'config_value'	=> '',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_quick_reply',
						'config_value'	=> '1',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_recent_topics_search_exclude',
						'config_value'	=> '',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_top_topics_days',
						'config_value'	=> '7',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_block_cache_time_default',
						'config_value'	=> '600',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_block_cache_time_short',
						'config_value'	=> '10',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_teams',
						'config_value'	=> '',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_teams_display_this_many',
						'config_value'	=> '2',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_max_block_avatar_width',
						'config_value'	=> '80',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_max_block_avatar_height',
						'config_value'	=> '80',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_teampage_memberships',
						'config_value'	=> '0',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_tooltips_active',
						'config_value'	=> '1',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_tooltips_which',
						'config_value'	=> '1',
						'is_dynamic'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_blocks_config_vars' => array(
					array(
						'config_name'	=> 'k_landing_page',
						'config_value'	=> 'portal',
						'is_dynamic'	=> '0',
					),
				),
			),

			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '1',
						'menu_type'		=> '1',
						'name'			=> 'Main Menu',
						'link_to'		=> '',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'default.png',
						'view_all'		=> '1',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '1',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '2',
						'menu_type'		=> '1',
						'name'			=> 'Portal',
						'link_to'		=> 'portal.php',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'portal.png',
						'view_all'		=> '1',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '3',
						'menu_type'		=> '1',
						'name'			=> 'Forum',
						'link_to'		=> 'index.php',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'home2.png',
						'view_all'		=> '1',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '4',
						'menu_type'		=> '1',
						'name'			=> 'Navigator',
						'link_to'		=> '',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'default.png',
						'view_all'		=> '1',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '1',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '5',
						'menu_type'		=> '99',
						'name'			=> 'Album',
						'link_to'		=> 'inprogress.php',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'gallery.png',
						'view_all'		=> '0',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '6',
						'menu_type'		=> '1',
						'name'			=> 'Bookmarks',
						'link_to'		=> 'ucp.php?i=main&amp;mode=bookmarks',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'bookmark.png',
						'view_all'		=> '0',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '7',
						'menu_type'		=> '99',
						'name'			=> 'Downloads',
						'link_to'		=> 'inprogress.php',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'ff.png',
						'view_all'		=> '0',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '8',
						'menu_type'		=> '99',
						'name'			=> 'Links',
						'link_to'		=> 'inprogress.php',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'link.gif',
						'view_all'		=> '0',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '9',
						'menu_type'		=> '1',
						'name'			=> 'Members',
						'link_to'		=> 'memberlist.php',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'chat.png',
						'view_all'		=> '0',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '10',
						'menu_type'		=> '99',
						'name'			=> 'Ratings',
						'link_to'		=> 'index.php',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'rating.png',
						'view_all'		=> '0',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '11',
						'menu_type'		=> '1',
						'name'			=> 'Rules',
						'link_to'		=> 'basic_rules.php',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'rules.png',
						'view_all'		=> '1',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '12',
						'menu_type'		=> '1',
						'name'			=> 'Staff',
						'link_to'		=> 'memberlist.php?mode=leaders',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'staff.png',
						'view_all'		=> '0',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '13',
						'menu_type'		=> '99',
						'name'			=> 'Statistics',
						'link_to'		=> 'inprogress.php',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'pie.png',
						'view_all'		=> '0',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '14',
						'menu_type'		=> '1',
						'name'			=> 'UCP',
						'link_to'		=> 'ucp.php',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'links.gif',
						'view_all'		=> '0',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '15',
						'menu_type'		=> '99',
						'name'			=> 'Chat',
						'link_to'		=> 'chat/index.php',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'chat.png',
						'view_all'		=> '0',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '16',
						'menu_type'		=> '1',
						'name'			=> 'Admin Menu',
						'link_to'		=> '',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'default.png',
						'view_all'		=> '0',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '1',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '17',
						'menu_type'		=> '1',
						'name'			=> 'ACP',
						'link_to'		=> 'adm/index.php',
						'extern'		=> '0',
						'append_sid'	=> '1',
						'append_uid'	=> '0',
						'menu_icon'		=> 'acp.png',
						'view_all'		=> '0',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '1',
						'menu_type'		=> '2',
						'name'			=> 'Mini Menu',
						'link_to'		=> '',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'default.png',
						'view_all'		=> '1',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '1',
					),
				),
			),

			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '2',
						'menu_type'		=> '2',
						'name'			=> 'Active Posts',
						'link_to'		=> 'search.php?search_id=active_topics',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'links.png',
						'view_all'		=> '1',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '1',
						'menu_type'		=> '5',
						'name'			=> 'Lnks Menu',
						'link_to'		=> '',
						'extern'		=> '0',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'default.png',
						'view_all'		=> '1',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '1',
					),
				),
			),

			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '2',
						'menu_type'		=> '5',
						'name'			=> 'Kiss Portal dev. site',
						'link_to'		=> 'http://www.phpbbireland.com',
						'extern'		=> '1',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'phpireland_globe.gif',
						'view_all'		=> '1',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '3',
						'menu_type'		=> '5',
						'name'			=> 'Stargate Portal',
						'link_to'		=> 'http://www.stargate-portal.com',
						'extern'		=> '1',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'phpireland_globe.gif',
						'view_all'		=> '1',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_menu' => array(
					array(
						'ndx'			=> '4',
						'menu_type'		=> '5',
						'name'			=> 'phpBB3',
						'link_to'		=> 'http://www.phpbb.com',
						'extern'		=> '1',
						'append_sid'	=> '0',
						'append_uid'	=> '0',
						'menu_icon'		=> 'phpireland_globe.gif',
						'view_all'		=> '1',
						'view_groups'	=> '',
						'soft_hr'		=> '0',
						'sub_heading'	=> '0',
					),
				),
			),

			'add_columns' => array(
				$this->table_pefix . 'k_resources' => array(
					array(
						'word'	=> 'phpBB',
						'type'	=> 'R',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_resources' => array(
					array(
						'word'	=> '{PORTAL_VERSION}',
						'type'	=> 'V',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_resources' => array(
					array(
						'word'	=> '{PORTAL_BUILD}',
						'type'	=> 'V',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_resources' => array(
					array(
						'word'	=> '{VERSION}',
						'type'	=> 'V',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_resources' => array(
					array(
						'word'	=> '{SITENAME}',
						'type'	=> 'V',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_pages' => array(
					array(
						'page_name'	=> 'index',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_pages' => array(
					array(
						'page_name'	=> 'portal',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_pages' => array(
					array(
						'page_name'	=> 'viewforum',
					),
				),
			),

			'add_columns' => array(
				$this->table_pefix . 'k_pages' => array(
					array(
						'page_name'	=> 'viewtopic',
					),
				),
			),
			'add_columns' => array(
				$this->table_pefix . 'k_pages' => array(
					array(
						'page_name'	=> 'memberlist',
					),
				),
			),

			'add_columns' => array(
				$this->table_pefix . 'k_pages' => array(
					array(
						'page_name'	=> 'mcp',
					),
				),
			),

			'add_columns' => array(
				$this->table_pefix . 'k_pages' => array(
					array(
						'page_name'	=> 'ucp',
					),
				),
			),

			'add_columns' => array(
				$this->table_pefix . 'k_pages' => array(
					array(
						'page_name'	=> 'search',
					),
				),
			),

			'add_columns' => array(
				$this->table_pefix . 'k_pages' => array(
					array(
						'page_name'	=> 'faq',
					),
				),
			),

			'add_columns' => array(
				$this->table_pefix . 'k_pages' => array(
					array(
						'page_name'	=> 'posting',
					),
				),
			),

		);
	}
}
