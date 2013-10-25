<?php
/**
*
* @package Kiss Portal Engine
* @version $Id$
* @author  Michael O'Toole - aka michaelo
* @begin   Saturday, Jan 22, 2005
* @copyright (c) 2005-2013 phpbbireland
* @home    http://www.phpbbireland.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

$queries = $cached_queries =  $total_queries = $col_count = $total = 0;

global $k_config, $k_blocks;
$count = $page_count = $search_type = 0;
$restrict = '';

$k_ma_columns                = $k_config['k_ma_columns'];
$k_ma_rows                   = $k_config['k_ma_rows'];
$k_ma_avatar_max_width       = $k_config['k_ma_avatar_max_width'];
$k_ma_display_logged_in_only = $k_config['k_ma_display_logged_in_only'];
$k_ma_max_avatars            = $k_config['k_ma_max_avatars'];
$k_ma_user_has_posted        = $k_config['k_ma_user_has_posted'];
$k_ma_user_active            = $k_config['k_ma_user_active'];

if ($k_ma_user_active)
{
	$post_time_days = time() - 86400 * 365;
	$restrict = 'AND user_inactive_time < ' . $post_time_days;
	$search_type = $user->lang['AVA_ACTIVE'];
}

if ($k_ma_display_logged_in_only)
{
	$search_type = $user->lang['AVA_LOGGED_IN'];
}

if (!$k_ma_user_has_posted)
{
	$has_posted = "AND user_posts <> 0";
}
else
{
	$has_posted = "";
}

$page = request_var('page', '');

if (isset($page) && $page == 'avatars')
{
	$per_page = 100;
	$start = 0;
	$ava = true;
}
else
{
	$ava = false;
	$per_page = $k_ma_columns * $k_ma_rows;
}

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_members_avatars.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		$all = ($blk['position'] == 'C') ? true : false;
		break;
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);

include($phpbb_root_path . 'includes/sgp_functions.'. $phpEx );


$sql = 'SELECT user_id, username, user_posts, user_colour, user_type, user_inactive_time, group_id, user_avatar, user_avatar_type, user_avatar_width , user_avatar_height, user_website
	FROM ' . USERS_TABLE . '
	WHERE user_type <> ' . USER_IGNORE . '
		AND user_avatar_type > 0
		' . $restrict . '
	ORDER BY user_posts DESC';

$result = $db->sql_query_limit($sql, $k_ma_max_avatars, 0, $block_cache_time);

$list = obtain_users_online();

while ($row = $db->sql_fetchrow($result))
{

    if (!$row['username'])
    {
        continue;
    }

	// hide hidden users //
	if(in_array($row['user_id'], $list['hidden_users']) and $k_ma_display_logged_in_only)
	{
		continue;
	}

	$total++;

    if (in_array($row['user_id'], $list['online_users']))
    {
		$is_online = true;
    }
	else
	{
		$is_online = false;
	}

	$col_count++;

	if ($col_count == $k_ma_columns)
	{
		$col_count = 0;
	}

	$ua[] = array(
		'col_count'         => $col_count,
		'is_online'         => ($is_online) ? 1 : 0,
		'username_full'     => get_username_string('full', $row['user_id'], sgp_checksize($row['username'],15), $row['user_colour']),
		'member_avatar_img' => ($row['user_avatar'] != '') ? get_user_avatar($row['user_avatar'], $row['user_avatar_type'], $row['user_avatar_width'], $row['user_avatar_height']) : '',
	);
}

if ($per_page <= 0)
{
	$per_page = 1;
}

$pages = floor($total/$per_page);

$start = request_var('start', 0);


for ($j = 0; $j < $pages + 1; $j++)
{
	if ($page)
	{
		$p = append_sid("{$phpbb_root_path}portal.$phpEx?page={$page}&amp;start=") . ($j * $per_page);
	}
	else
	{
		$p = append_sid("{$phpbb_root_path}portal.$phpEx?start=") . ($j * $per_page);
	}

	if ($per_page < $total)
	{
		$template->assign_block_vars('avatars_pages', array(
			'PAGE'    => $j,
			'U_PAGES' => $p,
			'U_PAGE'  => (isset($page) ? append_sid("{$phpbb_root_path}portal.$phpEx?page=avatars&amp;start=") . ($j * $per_page) : ''),
		));
	}
	else
	{
		$template->assign_block_vars('avatars_pages', array(
			'U_PAGE'  => (isset($page) ? append_sid("{$phpbb_root_path}portal.$phpEx?page=avatars&amp;start=") . ($j * $per_page) : ''),
		));
	}
}

$loop = $start + $per_page;

for ($k = $start; $k < $loop; $k++)
{
	if ($k > $total - 1)
	{
		break;
	}

	if ($k_ma_display_logged_in_only)
	{
		if ($ua[$k]['is_online'])
		{
			$template->assign_block_vars('members_avatars', array(
				'COL_COUNT'         => $ua[$k]['col_count'],
				'IS_ONLINE'         => $ua[$k]['is_online'],
				'USERNAME_FULL'     => $ua[$k]['username_full'],
				'MEMBER_AVATAR_IMG' => $ua[$k]['member_avatar_img'],
			));
		}
//		$total = $k + 1;
	}
	else
	{
		$template->assign_block_vars('members_avatars', array(
			'COL_COUNT'         => $ua[$k]['col_count'],
			'IS_ONLINE'         => $ua[$k]['is_online'],
			'USERNAME_FULL'     => $ua[$k]['username_full'],
			'MEMBER_AVATAR_IMG' => $ua[$k]['member_avatar_img'],
		));
	}
}

$template->assign_vars(array(
	'AV_TOTAL'      => $total,
	'MAX_WIDTH'     => $k_ma_avatar_max_width,
	'PAGE_WIDTH'    => $k_ma_columns * $k_ma_avatar_max_width + $k_ma_columns * 14,
	'PAGES'         => ($j > 1) ? true : false,
	'REPORT'        => 'Under construction (in development stage only)',
	'AVA_PAGE'      => $ava,
	'AVATAR_SQL_TYPE' => $user->lang['AVATAR_SQL_TYPE'] . ucfirst($search_type),
));


$db->sql_freeresult($result);
?>