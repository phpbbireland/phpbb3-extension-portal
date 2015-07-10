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

class sgp_functions_admin
{
	public function sgp_acp_set_config($config_name, $config_value)
	{
		global $db, $cache, $table_prefix, $k_config;

		$sql = 'UPDATE ' . K_VARS_TABLE . "
			SET config_value = '" . $db->sql_escape($config_value) . "'
			WHERE config_name = '" . $db->sql_escape($config_name) . "'";
		$result = $db->sql_query($sql);

		if (!$db->sql_affectedrows() && !isset($k_config[$config_name]))
		{
			$sql = 'INSERT INTO ' . K_VARS_TABLE . ' ' . $db->sql_build_array('INSERT', array(
				'config_name'   => $config_name,
				'config_value'  => $config_value));
			$db->sql_query($sql);
		}

		$k_config[$config_name] = $config_value;

		$cache->destroy('k_config');
		$cache->destroy('config');

	}

	public function get_reserved_words()
	{
		global $reserved_words, $db, $template, $table_prefix;

		$reserved_words = array();

		$sql = 'SELECT *
			FROM ' . K_RESOURCES_TABLE . "
			WHERE type = 'R' ";

		$result = $db->sql_query($sql, 300);

		while ($row = $db->sql_fetchrow($result))
		{
			$reserved_words[] = $row['word'];

			$template->assign_block_vars('reserved_words', array(
				'RESERVED_WORDS' => $row['word'],
			));

		}
		$db->sql_freeresult($result);

		return($reserved_words);
	}

	public function get_all_groups()
	{
		global $db, $template, $user, $table_prefix;

		// Get us all the groups
		$sql = 'SELECT group_id, group_name
			FROM ' . GROUPS_TABLE . '
			ORDER BY group_id ASC, group_name';
		$result = $db->sql_query($sql);

		// backward compatability, set up group zero //
		$template->assign_block_vars('groups', array(
			'GROUP_NAME' => $user->lang['NONE'],
			'GROUP_ID'   => 0,
		));

		while ($row = $db->sql_fetchrow($result))
		{
			$group_id = $row['group_id'];
			$group_name = $row['group_name'];

			$group_name = ($user->lang(strtoupper('G_'.$group_name))) ? $user->lang(strtoupper('G_'.$group_name)) : $user->lang(strtoupper($group_name));

			$template->assign_block_vars('groups', array(
				'GROUP_NAME' => $group_name,
				'GROUP_ID'   => $group_id,
			));
		}
		$db->sql_freeresult($result);
	}

	public function get_teams_sort()
	{
		global $db, $template, $user;

		// Get all the groups
		$sql = 'SELECT group_id, group_name
			FROM ' . GROUPS_TABLE . '
			ORDER BY group_id ASC, group_name';
		$result = $db->sql_query($sql);

		$team_sort_array = array('default', 'g.group_id', 'g.group_name', 'g.group_type', 'u.username');

		foreach ($team_sort_array as $item)
		{
			$template->assign_block_vars('teams_sort', array(
				'SORT_OPTION' => $item
			));
		}
		$db->sql_freeresult($result);
	}

	public function phpbb_preg_quote($str, $delimiter)
	{
		$text = preg_quote($str);
		$text = str_replace($delimiter, '\\' . $delimiter, $text);

		return $text;
	}

	public function check_version()
	{
		global $db, $template;

		$url = 'phpbbireland.com';
		$sub = 'kiss2/updates';
		$file = 'kiss.xml';

		$errstr = '';
		$errno = 0;

		$data = array();

		$data_read = get_remote_file($url, '/' . $sub, $file, $errstr, $errno);

		$mod = @simplexml_load_string(str_replace('&', '&amp;', $data_read));

		if (isset($mod->version_check))
		{
			$row = $mod->version_check;

			$version = $row->version->major[0] . '.' . $row->version->minor[0] . '.' . $row->version->revision[0];

			$data = array(
				'title'			=> $row->title[0],
				'description'	=> $row->description[0],
				'download'		=> $row->download,
				'announcement'	=> $row->announcement,
				'version'       => $version,
			);
			return($data);
		}
		return(null);
	}

	public function get_link_images()
	{
		global $db, $template, $k_config, $phpbb_root_path;

		$i = 0;

		$sql = "SELECT *
			FROM " . K_LINK_IMAGES_TABLE . "
			WHERE active = 1";

		$result = $db->sql_query($sql, 10);//$block_cache_time);


		while ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('link_images_row', array(
				'LINK_IMAGE'	=> $phpbb_root_path . 'ext/phpbbireland/portal/images/links/' . $row['image'],
				'LINK_URL'		=> $row['url'],
				'LINK_LINK'		=> $row['link'],
				'TLINK'		=> $i++,
			));

		}
	}

}
