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

$queries = $cached_queries = 0;

include($phpbb_root_path . 'includes/sgp_functions.'. $phpEx );

global $k_config, $k_blocks;

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_bot_tracker.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		break;
	}
}

$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['block_cache_time_default']);

$k_bots_to_display = $k_config['k_bots_to_display'];
$k_bot_display_allow = $k_config['k_bot_display_allow'];
$after_date = $config['board_startdate'];
$loop_count = 0;

$sql = 'SELECT username, user_colour, user_lastvisit
	FROM ' . USERS_TABLE . '
	WHERE user_type = ' . USER_IGNORE . '
	AND user_lastvisit > ' . (int) $after_date . '
	ORDER BY user_lastvisit DESC';

$result = $db->sql_query_limit($sql, $k_bots_to_display, 0, $block_cache_time);

while ($row = $db->sql_fetchrow($result))
{
	$bot_name = get_username_string('full', '', sgp_checksize($row['username'],23), $row['user_colour']);

	$template->assign_block_vars('bot_tracker', array(
		'BOT_NAME'					=> $bot_name,
		'BOT_TRACKER_VISIT_DATE'	=> $user->format_date($row['user_lastvisit'], 'D. M. d Y, H:i'),
	));
	$loop_count = $loop_count + 1;
}
$db->sql_freeresult($result);

// assign vars
$template->assign_vars(array(
	'NO_DATA'				=> ($loop_count == 0) ? true : false,
	'BOT_TRACKER'			=> sprintf($user->lang['BOT_TRACKER'], $k_bots_to_display),
	'S_BOT_TRACKER_SHOW'	=> ($k_bot_display_allow) ? true : false,
	//'BOT_TRACKER_DEBUG'		=> sprintf($user->lang['PORTAL_DEBUG_QUERIES'], ($queries) ? $queries : '0', ($cached_queries) ? $cached_queries : '0', ($total_queries) ? $total_queries : '0'),
));
