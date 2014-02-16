<?php
/**
*
* @package Portal Extension
* @copyright (c) 2013 phpbbireland
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

function obtain_k_config()
{
	global $db, $cache, $k_config, $phpbb_root_path, $phpEx;

	include_once($phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $phpEx);

	if (($k_config = $cache->get('k_config')) === false)
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
		$k_config = $k_cached_config = array();

		$sql = 'SELECT config_name, config_value, is_dynamic
			FROM ' . K_VARIABLES_TABLE;
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			if (!$row['is_dynamic'])
			{
				$k_cached_config[$row['config_name']] = $row['config_value'];
			}
			$k_config[$row['config_name']] = $row['config_value'];
		}
		$db->sql_freeresult($result);

		$cache->put('k_config', $k_cached_config);
	}

	return $k_config;
}


function obtain_k_menus()
{
	global $db, $cache, $k_menus, $phpbb_root_path, $phpEx;

	include_once($phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $phpEx);

	if (($k_menus = $cache->get('k_menus')) === false)
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
			$k_menus[$row['m_id']]['view_forums'] = $row['view_forums'];
			$k_menus[$row['m_id']]['view_groups'] = $row['view_groups'];
			$k_menus[$row['m_id']]['soft_hr'] = $row['soft_hr'];
			$k_menus[$row['m_id']]['sub_heading'] = $row['sub_heading'];
		}
		$db->sql_freeresult($result);

		$cache->put('k_menus', $k_menus);
	}
	return $k_menus;
}

function obtain_block_data()
{
	global $db, $cache, $k_blocks, $phpbb_root_path, $phpEx;

	include_once($phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $phpEx);

	if (($k_blocks = $cache->get('k_blocks')) === false)
	{
		$sql = 'SELECT *
			FROM ' . K_BLOCKS_TABLE . '
			WHERE active = 1 AND is_static = 0 ORDER BY ndx ASC';
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
				$k_blocks[$row['id']]['view_forums']	= $row['view_forums'];
				$k_blocks[$row['id']]['view_groups']	= $row['view_groups'];
				$k_blocks[$row['id']]['scroll']			= $row['scroll'];
				$k_blocks[$row['id']]['block_height']	= $row['block_height'];
				$k_blocks[$row['id']]['html_file_name']	= $row['html_file_name'];
				$k_blocks[$row['id']]['img_file_name']	= $row['img_file_name'];
				$k_blocks[$row['id']]['block_cache_time']	= $row['block_cache_time'];
			}
		}
		$db->sql_freeresult($result);

		$cache->put('k_blocks', $k_blocks);
	}
	return $k_blocks;
}

function obtain_k_pages()
{
	global $db, $cache, $k_pages, $phpbb_root_path, $phpEx;

	include_once($phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $phpEx);

	if (($k_pages = $cache->get('k_pages')) === false)
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
	return $k_pages;
}


//
// get all group names/id's (used to avoid problems with group ID's changing)
//
function obtain_k_groups()
{
	global $db, $cache, $k_groups, $phpbb_root_path, $phpEx;

	include_once($phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $phpEx);

	if (($k_groups = $cache->get('k_groups')) === false)
	{
		// Get us all the groups
		$sql = 'SELECT group_id, group_name
			FROM ' . GROUPS_TABLE . '
			ORDER BY group_id ASC, group_name';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$k_groups[$row['group_id']]['group_id'] = $row['group_id'];
			$k_groups[$row['group_id']]['group_name'] = $row['group_name'];
		}
		$db->sql_freeresult($result);

		$cache->put('k_groups', $k_groups);
	}
	return $k_groups;
}

function obtain_k_resources()
{
	global $db, $cache, $k_resources, $phpbb_root_path, $phpEx;

	include_once($phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $phpEx);

	if (($k_resources = $cache->get('k_resources')) === false)
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
	return $k_resources;
}

