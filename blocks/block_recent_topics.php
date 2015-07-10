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

// URL PARAMETERS
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

global $user, $forum_id, $phpbb_root_path, $phpEx, $SID, $config , $template, $portal_config, $userdata, $config, $db, $phpEx;

// set up variables used //
$display_this_many = $k_config['k_recent_topics_to_display'];
$forum_count = $row_count = 0;
$except_forum_id = '';
$forum_data = array();
$recent_topic_row = array();

// get all forums //
$sql = "SELECT * FROM ". FORUMS_TABLE . " ORDER BY forum_id";
if (!$result = $db->sql_query($sql))
{
	trigger_error('Error! Could not query forums information: ' . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . ', line ' . __LINE__);
}

while( $row = $db->sql_fetchrow($result) )
{
	$forum_data[] = $row;
	$forum_count++;
}
$db->sql_freeresult($result);

for ($i = 1; $i < $forum_count; $i++)
{
	if (!$auth->acl_gets('f_list', 'f_read', $forum_data[$i]['forum_id']))
	{
		$except_forum_id .= "'" . $forum_data[$i]['forum_id'] . "'";
		$except_forum_id .= ",";
	}
}

$except_forum_id = rtrim($except_forum_id,",");

if ($except_forum_id == '')
{
	$except_forum_id = '0';
}

$sql = "SELECT t.topic_id, t.topic_title, t.topic_last_post_id, t.forum_id, p.post_id, p.poster_id, p.post_time, u.user_id, u.username, u.user_colour
	FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p, " . USERS_TABLE . " AS u
	WHERE t.forum_id NOT IN (" . $except_forum_id . ")
		AND t.topic_status <> 2
		AND p.post_id = t.topic_last_post_id
		AND p.poster_id = u.user_id
			ORDER BY p.post_id DESC
				LIMIT " . $display_this_many;

if (!$result = $db->sql_query($sql, 300))
{
	trigger_error($user->lang['ERROR_PORTAL_FORUMS'] . ' 109');
}

while ($row = $db->sql_fetchrow($result))
{
	$recent_topic_row[] = $row;

	if ($row['forum_id'] > 0)
	{
		// Get forum name for this postid
		$sql2 = "SELECT forum_name
			FROM " . FORUMS_TABLE . "
			WHERE forum_id = " . $row['forum_id'] . "
			LIMIT " . 1;
		if (!$my_result = $db->sql_query($sql2, 300))
		{
			trigger_error($user->lang['ERROR_PORTAL_FORUMS'] . '125');
		}
		$my_row = $db->sql_fetchrow($my_result);
		$recent_topic_row['forum_name'][$row_count] = $my_row['forum_name'];
		$row_count ++;
	}
}

$db->sql_freeresult($result);
$db->sql_freeresult($my_result);

$sql = "SELECT scroll, position
	FROM " . K_BLOCKS_TABLE . "
		WHERE id = '19'";

if( $result = $db->sql_query($sql, 300) )
{
	$rowx = $db->sql_fetchrow($result);
	$scroll = $rowx['scroll'];
	$display_center = $rowx['position'];
}
else
{
	trigger_error($user->lang['ERROR_PORTAL_FORUMS'] . '148');
}

//echo $scroll;
//echo $display_center;

$db->sql_freeresult($result);

($scroll) ? $style_row = 'scroll' : $style_row = 'static';

// change topics to display count if there are less topics that set in ACP
if ($display_this_many > $row_count)
{
	$display_this_many = $row_count;
}

if ($scroll)
{
	$display_this_many = $row_count;

	if ($row_count <= 6)
	{
		$style_row = 'static';
		$template->assign_var('PROCESS', false);
	}
	else
	{
		$template->assign_var('PROCESS', true);
	}
}

for ($i = 0; $i < $display_this_many; $i++)
{
	if ($recent_topic_row[$i]['user_id'] != -1)
	{
		if ($display_center != 'C')
		{
			//$my_title_long = $recent_topic_row[$i]['topic_title'];
			$recent_topic_row[$i]['topic_title'] = sgp_checksize ($recent_topic_row[$i]['topic_title'],20); // Michaelo's function to stop page from stretching due to long names in form select options... Names are truncated//
		}

		$view_topic_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . (($recent_topic_row[$i]['forum_id']) ? $recent_topic_row[$i]['forum_id'] : $forum_id) );

		// add spaces for nice scrolling
		//$my_title = smilies_pass($recent_topic_row[$i]['topic_title']);
		$my_title = $recent_topic_row[$i]['topic_title'];

		$length = strlen($my_title);

		// padd if too long
		if ($length > 25)
		{
			sgp_checksize ($my_title, 25);
		}

		// do same for forum name
		//$my_forum = smilies_pass($recent_topic_row['forum_name'][$i]);
		$my_forum = $recent_topic_row['forum_name'][$i];

		$length = strlen($my_forum);

		// padd if too long
		if ($length > 25)
		{
			sgp_checksize ($my_forum, 25);
		}

		$template->assign_block_vars($style_row . '_recent_topic_row', array(
			'U_FORUM'		=> append_sid("viewforum.$phpEx?" . POST_FORUM_URL . '=' . $recent_topic_row[$i]['forum_id']),
			'U_LAST_POST'	=> $view_topic_url . '&amp;p=' . $recent_topic_row[$i]['topic_last_post_id'] . '#p' . $recent_topic_row[$i]['topic_last_post_id'],
			'U_TITLE'		=> append_sid("viewtopic.$phpEx?" . POST_POST_URL  . '=' . $recent_topic_row[$i]['post_id']),
			'S_FORUM'		=> $my_forum,
			'S_POSTER'		=> get_username_string('full', $recent_topic_row[$i]['user_id'], $recent_topic_row[$i]['username'], $recent_topic_row[$i]['user_colour']),
			'S_POSTTIME'	=> $user->format_date($recent_topic_row[$i]['post_time']),
			'S_ROW_COUNT'	=> $i,
			'S_TITLE'		=> $my_title,
			//'S_TITLE_LONG'	=> $my_title_long,
			'ORI_LAST_POST_IMG'	=> $user->img('icon_topic_newest', 'VIEW_LATEST_POST'),
			)
		);
	}
}

$template->assign_vars(array(
	'S_RECENT_TOPICS_COUNT_ASKED'		=> $display_this_many,
	'S_RECENT_TOPICS_COUNT_RETURNED'	=> $row_count,
	'S_ALIGN_IT'						=> 'center',
	'S_DISPLAY_CENTRE'					=> $display_center,
	'S_COUNT'							=> $display_this_many,
	//'RT1_PORTAL_DEBUG'					=> sprintf($user->lang['PORTAL_DEBUG_QUERIES'], ($queries) ? $queries : '0', ($cached_queries) ? $cached_queries : '0'),
));
