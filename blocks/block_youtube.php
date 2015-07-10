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

global $k_config;

$loop_count = 0;
$emp = '?';
$rating = '';
$video = '';
$last_cat = '';
$k_video_max = ($k_config['k_yourtube_link_limit']) ? $k_config['k_yourtube_link_limit'] : 3;

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_youtube.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		break;
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);

$sql = "SELECT * FROM ". K_YOUTUBE_TABLE . " ORDER BY video_category, video_who ASC";

if (!$result = $db->sql_query($sql))
{
	trigger_error($user->lang['ERROR_PORTAL_MODULE'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . ', line ' . __LINE__);
}

$result = $db->sql_query_limit($sql, $k_video_max, 0, $block_cache_time);

while ($row = $db->sql_fetchrow($result))
{
	$unique = ($row['video_category'] == $last_cat) ? false : true;

	if ($row['video_rating'] ==  '')
	{
		$row['video_rating'] = 0;
	}

	switch ($row['video_rating'])
	{
		case 0: $rating = '';
		break;
		case 1: $rating = '*';
		break;
		case 2: $rating = '**';
		break;
		case 3: $rating = '***';
		break;
		case 4: $rating = '****';
		break;
		case 5: $rating = '*****';
		break;
		default: $rating = '';
		break;
	}

	$usr_name_full = get_user_data($row['video_poster_id'], 'full');

	$template->assign_block_vars('video_loop_row', array(
		'VIDEO_CAT'			=> $row['video_category'],
		'VIDEO_WHO'			=> $row['video_who'],
		'VIDEO_TITLE'		=> $row['video_title'],
		'VIDEO_LINK'		=> $row['video_link'],
		'VIDEO_COMMENT'		=> htmlspecialchars_decode($row['video_comment']),
		'VIDEO_POSTER'		=> ($usr_name_full) ? $usr_name_full : '',
		'VIDEO_RATING'		=> $rating,
		'S_UNIQUE_W'		=> $unique,
		'S_ROW_COUNT'		=> $loop_count,
		'L_YOUTUBE_LIMIT'	=> ($k_video_max > 0) ? sprintf($user->lang['YOUTUBE_LIMIT'], $k_video_max) : '',
	));

	$last_cat = $row['video_category'];
	$loop_count = $loop_count + 1;

	if ($video == $row['video_link'])
	{
		$template->assign_vars(array(
			'L_POSTERS_COMMENT'		=> ($usr_name_full) ? sprintf($user->lang['POSTERS_COMMENT'], $usr_name_full, htmlspecialchars_decode($row['video_comment'])) : '',
			'READY'					=> ($video) ? true : false,
		));
	}

	$template->assign_vars(array(
		'VIDEO_PATH'	=> $k_config['k_yourtube_link'],
		'S_AUTOPLAY'	=> ($k_config['k_yourtube_auto']) ? '&amp;autoplay=1' : '',
		'S_SLIM'		=> true,
		'YOUTUBR_MOD'	=> true,
		'S_STYLE_SPECIFIC_VERSION'	=> true,
	));

	if ($row['video_category'] != $emp)
	{
		$template->assign_block_vars('video_loop_row_cats', array(
			'CATS'	=> $row['video_category'],
		));
	}
	$emp = $row['video_category'];
}
$db->sql_freeresult($result);
