<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (!defined('POST_TOPIC_URL'))
{
	define('POST_TOPIC_URL' , 't');
}
if (!defined('POST_CAT_URL'))
{
	define('POST_CAT_URL', 'c');
}
if (!defined('POST_FORUM_URL'))
{
	define('POST_FORUM_URL', 'f');
}
if (!defined('POST_USERS_URL'))
{
	define('POST_USERS_URL', 'u');
}
if (!defined('POST_POST_URL'))
{
	define('POST_POST_URL', 'p');
}
if (!defined('POST_GROUPS_URL'))
{
	define('POST_GROUPS_URL', 'g');
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);

$auth->acl($user->data);

$queries = $cached_queries = 0;

global $user, $forum_id, $phpbb_root_path, $phpEx, $SID, $config, $template, $k_config, $k_blocks, $db, $web_path;

$total_downloads = 0;

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_top_downloads.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		$position = $blk['position'];
		break;
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);

// set up variables used //
$forum_count = $row_count = 0;
$valid_forum_ids = array();

$display_this_many = $k_config['k_top_downloads_to_display'];
$except_forum_id = $k_config['k_top_downloads_search_exclude'];
$k_top_downloads_search_days = $k_config['k_top_downloads_search_days'];
$k_top_downloads_per_forum = $k_config['k_top_downloads_per_forum'];
$k_top_downloads_types = $k_config['k_top_downloads_types'];

static $last_forum = 0;

$colour = 'black';

$forum_data = array();


/* New code to allow skipping of archived(old) forums...
   You can modify the years to skip by editing the '-2' in the code below...
*/

$skip_archived_forums = false; // manually edit for true / false

if ($skip_archived_forums)
{
	$skip_to_year = strtotime('2012-01-01 -1 year');

	$sql = "SELECT * FROM ". FORUMS_TABLE . "
		WHERE forum_last_post_time > " . $skip_to_year . "
		ORDER BY forum_id";

	if (!$result = $db->sql_query($sql, 600))
	{
		trigger_error($user->lang['ERROR_PORTAL_FORUMS'] . '102');
	}
}
else
{
$block_cache_time = 0;
	$sql = "SELECT * FROM ". FORUMS_TABLE . " ORDER BY forum_id";
	if (!$result = $db->sql_query($sql, $block_cache_time))
	{
		trigger_error($user->lang['ERROR_PORTAL_FORUMS'] . ' 111');
	}
}


/* don't show these (set in ACP) */
$except_forum_ids = explode(",", $except_forum_id);
$types = explode(",", $k_top_downloads_types);

while ($row = $db->sql_fetchrow($result))
{
	if (!in_array($row['forum_id'], $except_forum_ids))
	{
		$forum_data[] = $row;
		$forum_count++;
	}
}
$db->sql_freeresult($result);

for ($i = 0; $i < $forum_count; $i++)
{
	if ($auth->acl_gets('f_list', 'f_read', $forum_data[$i]['forum_id']))
	{
		$valid_forum_ids[] = (int) $forum_data[$i]['forum_id'];
	}
}

// do we at least one valid forum for this user, if not, don't continue //
if (count($valid_forum_ids) < 1)
{
	return;
}

$where_sql = $db->sql_in_set('t.forum_id', $valid_forum_ids);


if ($k_top_downloads_search_days > 0)
{
	$post_time_days = time() - 86400 * $k_top_downloads_search_days;
	$days = "AND (t.topic_last_post_time >= " . $post_time_days . "	OR p.post_edit_time >= " . $post_time_days . ')';
}
else
{
	$days = "";
}

// New code //
$sql_array = array(
	'SELECT'		=> 'distinct p.post_id, t.topic_id, t.topic_time, t.topic_title, t.forum_id, t.topic_last_post_time, t.topic_last_post_id, t.topic_last_poster_id, t.topic_last_poster_name, t.topic_last_poster_colour, t.topic_type, t.topic_attachment, f.forum_name, p.post_edit_time, p.post_subject, p.post_text, p.post_time, p.bbcode_bitfield, p.bbcode_uid, f.forum_desc, u.user_avatar,  u.user_avatar_width, u.user_avatar_height, u.user_avatar_type, a.topic_id, a.download_count, a.extension, a.is_orphan',

	'FROM'			=> array(FORUMS_TABLE => 'f'),

	'LEFT_JOIN'		=> array(
		array(
			'FROM'	=> array(TOPICS_TABLE => 't'),
			'ON'	=> "f.forum_id = t.forum_id",
		),
		array(
			'FROM'	=> array(POSTS_TABLE => 'p'),
			'ON'	=> "t.topic_id = p.topic_id",
		),
		array(
			'FROM'	=> array(USERS_TABLE => 'u'),
			'ON'	=> "t.topic_last_poster_id = u.user_id",
		),
		array(
			'FROM'	=> array(ATTACHMENTS_TABLE => 'a'),
			'ON'	=> "a.topic_id = t.topic_id",
		),
	),

	'WHERE'	=> $where_sql . '
		AND ' . $db->sql_in_set('a.extension', $types) . '
		AND t.topic_attachment = 1
		AND p.post_id = t.topic_first_post_id
		' . $days . '
			ORDER BY a.download_count DESC'
);

$sql = $db->sql_build_query('SELECT', $sql_array);

//echo '<br />Top Downoads<br />' . $sql . '<br />';

$result = $db->sql_query_limit($sql, $display_this_many, 0, $block_cache_time);

$row = $db->sql_fetchrowset($result);

$db->sql_freeresult($result);

$row_count = count($row);

// display_this_many do we have them?
if ($row_count < $display_this_many)
{
	$display_this_many = $row_count;
}

for ($i = 0; $i < $display_this_many; $i++)
{
	$unique = ($row[$i]['forum_id'] == $last_forum) ? false : true;

	if ($i >= $k_top_downloads_per_forum && $row[$i]['forum_id'] == $row[$i - $k_top_downloads_per_forum]['forum_id'])
	{
		continue;
	}

	$my_title = $row[$i]['topic_title'];

	if (strlen($my_title) > 25)
	{
		sgp_checksize ($my_title, 25);
	}

	$forum_name = $row[$i]['forum_name'];

	if (strlen($forum_name) > 25)
	{
		$forum_name = sgp_checksize ($forum_name, 25);
	}

	$view_topic_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $row[$i]['forum_id']);

	if ($row[$i]['post_edit_time'] > $row[$i]['topic_last_post_time'])
	{
		$this_post_time = '*<span style="font-style:italic">' . $user->format_date($row[$i]['post_edit_time']) . '</span>';
	}
	else
	{
		$this_post_time = $user->format_date($row[$i]['topic_last_post_time']);
	}

	$total_downloads = $total_downloads + $row[$i]['download_count'];

	switch($row[$i]['extension'])
	{
		case '7z':
		case 'ace':
		case 'bz2':
		case 'gtar':
		case 'gz':
		case 'rar':
		case 'tar':
		case 'tgz':
		case 'torrent':
		case 'zip':
			$next_img = '<img style="vertical-align:middle" src="' . $phpbb_root_path . 'images/upload_icons/rar.gif" height="15" width="15" alt="" />';
			$colour = 'red';
		break;
		case 'gif':
		case 'jpeg':
		case 'jpg':
		case 'png':
		case 'tga':
		case 'tif':
		case 'tiff':
			$next_img = '<img style="vertical-align:middle" src="' . $phpbb_root_path . 'images/upload_icons/gif.gif" height="15" width="15" alt="" />';
			$colour = 'green';
		break;
		default:
			$next_img = '<img style="vertical-align:middle" src="' . $phpbb_root_path . 'images/upload_icons/rar.gif" height="15" width="15" alt="" />';
			$colour = 'black';
		break;
	}

	$avatar_data = array(
		'avatar' => $row['user_avatar'],
		'avatar_width' => $row['user_avatar_width'],
		'avatar_height' => $row['user_avatar_height'],
		'avatar_type' => $row['user_avatar_type'],
	);

	// resize image to 15x15 //
	$ava = phpbb_get_avatar($avatar_data, $user->lang['USER_AVATAR'], false);

	$ava = str_replace('width="' . $row['user_avatar_height'] . '"', 'width="16"', $ava);
	$ava = str_replace('height="' . $row['user_avatar_width'] . '"', 'height="16"', $ava);

	$template->assign_block_vars('top_downloads_row', array(
		'NUM'               => $i + 1,
		'ATT_COUNT'         => $row[$i]['download_count'],
		'AVATAR_SMALL_IMG'	=> $ava,
		'FORUM_W'			=> $forum_name,
		'LAST_POST_IMG_W'	=> $user->img('icon_topic_newest', 'VIEW_LATEST_POST'),
		'LAST_POST_IMG_W'	=> $next_img,
		'POSTER_FULL_W'		=> get_username_string('full', $row[$i]['topic_last_poster_id'], $row[$i]['topic_last_poster_name'], $row[$i]['topic_last_poster_colour']),
		'POSTTIME_W'		=> $this_post_time,
//		'REPLIES'			=> $row[$i]['topic_replies'],
		'U_FORUM_W'			=> append_sid("{$phpbb_root_path}viewforum.$phpEx?" . POST_FORUM_URL . '=' . $row[$i]['forum_id']),
		'U_TITLE_W'			=> $view_topic_url . '&amp;p=' . $row[$i]['topic_last_post_id'] . '#p' . $row[$i]['topic_last_post_id'],
		'S_ROW_COUNT'		=> $i,
		'S_UNIQUE'			=> $unique,
		'S_CTYPE'			=> $colour,
		'TITLE_W'			=> censor_text($my_title),
		'TOOLTIP_W'			=> bbcode_strip($row[$i]['post_text']),
		'TOOLTIP2_W'		=> bbcode_strip($row[$i]['forum_desc']),
	));

	$last_forum = $row[$i]['forum_id'];
}

if ($i > 1)
{
	$post_or_posts = strtolower($user->lang['TOPICS']);
}
else
{
	$post_or_posts = strtolower($user->lang['TOPIC']);
}

$template->assign_vars(array(
	'S_CENTRED'         => ($position == 'C') ? true : false,
	'S_COUNT'           => ($i > 0) ? true : false,
	'T_DOWNLOADS'       => $total_downloads,
	'SEARCH_TYPE'		=> $k_top_downloads_types,
	'SEARCH_LIMIT'		=> $user->lang['T_LIMITS'] . $k_top_downloads_per_forum . $user->lang['K_TOP_DL_PER_FORUM'] . $display_this_many . ' ' . $post_or_posts,
	'TOP_DOWNLOADS_DEBUG'	=> sprintf($user->lang['PORTAL_DEBUG_QUERIES'], ($queries) ? $queries : '0', ($cached_queries) ? $cached_queries : '0', ($total_queries) ? $total_queries : '0'),
));
