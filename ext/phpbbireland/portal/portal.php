<?php

/**
*
* @package Portal Extension
* @copyright (c) 2013 phpbbireland
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbireland\portal;

class portal
{
	protected $page_title;

	protected $start;

	protected $portal;

	protected $k_config;

	/**
	* Constructor
	* NOTE: The parameters of this method must match in order and type with
	* the dependencies defined in the services.yml file for this service.
	*
	* @param \phpbb\auth				$auth				Auth object
	* @param \phpbb\cache\service		$cache				Cache object
	* @param \phpbb\config				$config				Config object
	* @param \phpbb\db\driver			$db					Database object
	* @param \phpbb\request				$request			Request object
	* @param \phpbb\template			$template			Template object
	* @param \phpbb\user				$user				User object
	* @param \phpbb\content_visibility	$content_visibility	Content visibility object
	* @param \phpbb\controller\helper	$helper				Controller helper object
	* @param string						$root_path			phpBB root path
	* @param string						$php_ext			phpEx
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\cache\service $cache, \phpbb\config\config $config, \phpbb\db\driver\driver $db, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, \phpbb\content_visibility $content_visibility, \phpbb\controller\helper $helper, $root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->db = $db;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->content_visibility = $content_visibility;
		$this->helper = $helper;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;

		$this->page_title = $this->user->lang['PORTAL'];

		$this->cache_setup();

		include($phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_portal_blocks.' . $this->php_ext);
	}

	public function cache_setup()
	{
		global $cache, $k_pages, $k_menus, $k_blocks, $k_resources;

		$this->cache_k_blocks();
		$this->cache_k_pages();
		$this->cache_k_config();
		$this->cache_k_menus();
		$this->cache_k_blocks();
		$this->cache_k_resources();

		$k_pages		= $cache->get('k_pages');
		$k_config		= $cache->get('k_config');
		$k_blocks		= $cache->get('k_blocks');
		$k_menus		= $cache->get('k_menus');
		$k_blocks		= $cache->get('k_blocks');
		$k_resources	= $cache->get('k_resources');
/*
echo '<pre>';print_r($k_config);
echo '<pre>';print_r($k_resources);
*/
	}

	public function get_page_title()
	{
		return $this->page_title;
	}

	public function set_start($start)
	{
		$this->start = (int) $start;

		return $this;
	}


	public function set_portal($portal)
	{
		$this->portal = (int) $portal;

		return $this;
	}
/*
	public function get_page()
	{
		;
	}
*/
	public function base()
	{

		$this->get_page();

		$ranks = $this->cache->obtain_ranks();
		$icons = $this->cache->obtain_icons();
		return;
	}


	/**
	* Generate the pagination for the news list
	*
	* @return	string	$append_route		Additional string that should be appended to the route
	* @return		string		Full URL with append_sid performed on it
	*/
/*
	public function get_url($append_route = '')
	{
		$base_url = 'portal';

		return $this->helper->url($base_url . $append_route);
	}
*/

	public function cache_k_config()
	{
		global $db, $cache, $table_prefix;

		define('K_VARIABLES_TABLE',	$table_prefix . 'k_variables');

		if (($k_config = $cache->get('k_config')) !== false)
		{
			$sql = 'SELECT config_name, config_value
				FROM ' . K_VARIABLES_TABLE . '
				WHERE is_dynamic = 1';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$k_config[$row['config_name']] = $row['config_value'];
			}
			$db->sql_freeresult($result);
		}
		else
		{
			$k_config = $cached_k_config = array();

			$sql = 'SELECT config_name, config_value, is_dynamic
				FROM ' . K_VARIABLES_TABLE;
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				if (!$row['is_dynamic'])
				{
					$cached_k_config[$row['config_name']] = $row['config_value'];
				}
				else
				$k_config[$row['config_name']] = $row['config_value'];
			}
			$db->sql_freeresult($result);

			$cache->put('k_config', $cached_k_config);
		}
	}

	public function cache_k_pages()
	{
		global $db, $cache, $table_prefix;

		define('K_PAGES_TABLE',	$table_prefix . 'k_pages');

		if (!$k_pages)
		{
			$sql = 'SELECT page_id, page_name
				FROM ' . K_PAGES_TABLE;
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$k_pages[$row['page_id']]['page_id'] = $row['page_id'];
				$k_pages[$row['page_id']]['page_name'] = $row['page_name'];
			}
			$db->sql_freeresult($result);
			$cache->put('k_pages', $k_pages);
		}
	}

	public function cache_k_blocks()
	{
		global $db, $cache, $table_prefix;
		define('K_BLOCKS_TABLE', $table_prefix . 'k_blocks');

		if (!$k_blocks)
		{
			$sql = 'SELECT *
				FROM ' . K_BLOCKS_TABLE . '
				WHERE active = 1 ORDER BY ndx ASC';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				if (!$row['is_static'])
				{
					$k_blocks[$row['id']]['id']				= $row['id'];
					$k_blocks[$row['id']]['ndx']			= $row['ndx'];
					$k_blocks[$row['id']]['title']			= $row['title'];
					$k_blocks[$row['id']]['position']		= $row['position'];
					$k_blocks[$row['id']]['type']			= $row['type'];
					$k_blocks[$row['id']]['view_groups']	= $row['view_groups'];
					$k_blocks[$row['id']]['scroll']			= $row['scroll'];
					$k_blocks[$row['id']]['block_height']	= $row['block_height'];
					$k_blocks[$row['id']]['html_file_name']	= $row['html_file_name'];
					$k_blocks[$row['id']]['html_file_name']	= $row['html_file_name'];
					$k_blocks[$row['id']]['img_file_name']	= $row['img_file_name'];
					$k_blocks[$row['id']]['block_cache_time']	= $row['block_cache_time'];
				}
			}
			$db->sql_freeresult($result);
			$cache->put('k_blocks', $k_blocks);
		}
	}

	public function cache_k_menus()
	{
		global $db, $cache, $table_prefix;
		define('K_MENUS_TABLE', $table_prefix . 'k_menus');

		if (!$k_menus)
		{
			$sql = "SELECT * FROM ". K_MENUS_TABLE . "
				ORDER BY ndx ASC ";
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$k_menus[$row['m_id']]['m_id'] = $row['m_id'];
				$k_menus[$row['m_id']]['ndx'] = $row['ndx'];
				$k_menus[$row['m_id']]['menu_type'] = $row['menu_type'];
				$k_menus[$row['m_id']]['name'] = $row['name'];
				$k_menus[$row['m_id']]['link_to'] = $row['link_to'];
				$k_menus[$row['m_id']]['extern'] = $row['extern'];
				$k_menus[$row['m_id']]['menu_icon'] = $row['menu_icon'];
				$k_menus[$row['m_id']]['append_sid'] = $row['append_sid'];
				$k_menus[$row['m_id']]['append_uid'] = $row['append_uid'];
				$k_menus[$row['m_id']]['view_all'] = $row['view_all'];
				$k_menus[$row['m_id']]['view_groups'] = $row['view_groups'];
				$k_menus[$row['m_id']]['soft_hr'] = $row['soft_hr'];
				$k_menus[$row['m_id']]['sub_heading'] = $row['sub_heading'];
			}
			$db->sql_freeresult($result);
			$cache->put('k_menus', $k_menus);
		}

	}

	public function cache_k_resources()
	{
		global $db, $cache, $table_prefix;

		define('K_RESOURCES_TABLE',	$table_prefix . 'k_resources');

		if (!$k_resources)
		{
			$sql = 'SELECT *
				FROM ' . K_RESOURCES_TABLE  . '
				ORDER BY word ASC';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$k_resources[] = $row['word'];

			}
			$db->sql_freeresult($result);
			$cache->put('k_resources', $k_resources);
		}
	}
}