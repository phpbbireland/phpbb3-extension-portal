<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

global $k_config, $k_blocks, $user;

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_top_posters.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		break;
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);

include_once($phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_functions.' . $phpEx);

$k_top_posters_to_display = (!empty($k_config['k_top_posters_to_display'])) ? $k_config['k_top_posters_to_display'] : '5';


/*
$sql = 'SELECT user_id, username, user_posts, user_colour, user_type, group_id, user_avatar, user_avatar_type, user_avatar_width , user_avatar_height, user_website
	FROM ' . USERS_TABLE . '
	WHERE user_type <> ' . USER_IGNORE . '
		AND user_type = ' . USER_NORMAL . '
		AND user_type <> ' . USER_INACTIVE . '
		AND user_posts <> 0
	ORDER BY user_posts DESC';
*/
/*
$sql = "SELECT u.user_id, u.username, u.user_posts, u.user_colour, u.user_type, u.group_id, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, p.user_id, p.pf_phpbb_website, p.pf_phpbb_location
	FROM " . USERS_TABLE . " AS u, " . PROFILE_FIELDS_DATA_TABLE . " AS p
	WHERE u.user_id = p.user_id
		AND u.user_type <> ' . USER_IGNORE . '
		AND u.user_posts <> 0
	ORDER BY u.user_posts DESC";
*/

$sql = 'SELECT user_id, username, user_posts, user_colour, user_type, group_id, user_avatar, user_avatar_type, user_avatar_width , user_avatar_height
	FROM ' . USERS_TABLE . '
	WHERE user_posts <> 0
		AND user_type <> ' . USER_IGNORE . '
		AND user_type <> ' . USER_INACTIVE . '
	ORDER BY user_posts DESC';


$result = $db->sql_query_limit($sql, $k_top_posters_to_display, 0, $block_cache_time);

while ($row = $db->sql_fetchrow($result))
{
	if (!$row['username'])
	{
		continue;
	}

	$template->assign_block_vars('top_posters', array(
		'S_SEARCH_ACTION'	=> append_sid("{$phpbb_root_path}search.$phpEx", 'author_id=' . $row['user_id'] . '&amp;sr=posts'),
		'USERNAME_FULL'		=> get_username_string('full', $row['user_id'], sgp_checksize($row['username'],15), $row['user_colour']),
		'POSTER_POSTS'		=> $row['user_posts'],
		'USER_AVATAR_IMG'	=> get_user_avatar($row['user_avatar'], $row['user_avatar_type'], '16', '16', $user->lang['USER_AVATAR']),
		//'URL'				=> $row['user_website'],
	));
}
$db->sql_freeresult($result);
