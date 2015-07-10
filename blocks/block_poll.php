<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/
return;
/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}


// 2 bug fixes 01 October 2009
$this_page = explode(".", $user->page['page']);
if ($this_page[0] == 'viewtopic' || $this_page[0] == 'ucp')
{
	return;
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);

global $k_config, $b_poll, $config, $phpbb_root_path, $db, $user, $request, $template, $phpEx;

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_poll.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		$wide = ($blk['position'] == 'C') ? true : false;
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);

$queries = $cached_queries = 0;

if (!class_exists('bbcode'))
{
	include($phpbb_root_path . 'includes/bbcode.' . $phpEx);
}

/**
* @ignore
*/

// Initial var setup
$b_forum_id	= $request->variable('f', '');
$b_topic_id	= $request->variable('t', '');

$b_post_id	= $request->variable('p', $k_config['k_poll_post_id']);

if (!$b_post_id)
{
	// we don't have any poll id so skipp //
	return;
}

$b_voted_id	= $request->variable('vote_id', array('' => 0));

$b_start = $request->variable('start', 0);
$b_view = $request->variable('view', '');

$b_sort_days = $request->variable('st', ((!empty($user->data['user_post_show_days'])) ? $user->data['user_post_show_days'] : 0));
$b_sort_key	= $request->variable('sk', ((!empty($user->data['user_post_sortby_type'])) ? $user->data['user_post_sortby_type'] : 't'));
$b_sort_dir	= $request->variable('sd', ((!empty($user->data['user_post_sortby_dir'])) ? $user->data['user_post_sortby_dir'] : 'a'));

$b_update	 = $request->variable('update', false);

$s_can_vote = false;

// Do we have a topic or post id?
//if (!$b_topic_id && !$b_post_id && !$b_forum_id)
if (!$b_post_id)
{
	$k_config['k_poll_topic_id'] = 0;
}

// Find topic id if user requested a newer or older topic
if ($b_view && !$b_post_id)
{
	if (!$b_forum_id)
	{
		$sql = 'SELECT forum_id
			FROM ' . TOPICS_TABLE . "
			WHERE topic_id = $b_topic_id";
		$result = $db->sql_query($sql, $block_cache_time);

		$b_forum_id = (int) $db->sql_fetchfield('forum_id');
		$db->sql_freeresult($result);

		if (!$b_forum_id)
		{
			trigger_error('NO_TOPIC');
		}
	}

	// Check for global announcement correctness?
	if ((!isset($row) || !$row['forum_id']) && !$b_forum_id)
	{
		//trigger_error('NO_TOPIC');
		$k_config['k_poll_topic_id'] = 0;
	}
	else if (isset($row) && $row['forum_id'])
	{
		$b_forum_id = $row['forum_id'];
	}
}

if (!$b_forum_id)
{
	// This rather complex gaggle of code handles querying for topics but
	// also allows for direct linking to a post (and the calculation of which
	// page the post is on and the correct display of viewtopic)
	$sql_array = array(
		'SELECT'	=> 't.*, f.*',

		'FROM'	  => array(
			FORUMS_TABLE	=> 'f',
		)
	);

	if ($user->data['is_registered'])
	{
		$sql_array['SELECT'] .= ', tw.notify_status';
		$sql_array['LEFT_JOIN'] = array();

		$sql_array['LEFT_JOIN'][] = array(
			'FROM'	=> array(TOPICS_WATCH_TABLE => 'tw'),
			'ON'	=> 'tw.user_id = ' . $user->data['user_id'] . ' AND t.topic_id = tw.topic_id'
		);
	}

	if (!$b_post_id)
	{
		$sql_array['WHERE'] = "t.topic_id = $b_topic_id";
	}
	else
	{
		$sql_array['WHERE'] = "p.post_id = $b_post_id AND t.topic_id = p.topic_id" . ((!$auth->acl_get('m_approve', $b_forum_id)) ? ' AND p.post_approved = 1' : '');
		$sql_array['FROM'][POSTS_TABLE] = 'p';
	}

	$sql_array['WHERE'] .= ' AND (f.forum_id = t.forum_id';

	$sql_array['WHERE'] .= ')';
	$sql_array['FROM'][TOPICS_TABLE] = 't';

	// Join to forum table on topic forum_id unless topic forum_id is zero
	// whereupon we join on the forum_id passed as a parameter ... this
	// is done so navigation, forum name, etc. remain consistent with where
	// user clicked to view a global topic
	$sql = $db->sql_build_query('SELECT', $sql_array);
}

$result = $db->sql_query($sql, $block_cache_time);

$topic_data = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

if (!$topic_data)
{
	// If post_id was submitted, we try at least to display the topic as a last resort...
	if ($b_post_id && $b_forum_id && $b_topic_id)
	{
		redirect(append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$b_forum_id&amp;t=$b_topic_id"));
	}

	//trigger_error('NO_TOPIC');
	$k_config['k_poll_topic_id'] = 0;
}

$b_forum_id = (int) $topic_data['forum_id'];
$b_topic_id = (int) $topic_data['topic_id'];

// Setup look and feel
$user->setup('viewtopic', $topic_data['forum_style']);

if (!$topic_data['topic_approved'] && !$auth->acl_get('m_approve', $b_forum_id))
{
	//trigger_error('NO_TOPIC');
	$k_config['k_poll_topic_id'] = 0;
}

// Start auth check
if (!$auth->acl_get('f_read', $b_forum_id))
{
	if ($user->data['user_id'] != ANONYMOUS)
	{
		//trigger_error('SORRY_AUTH_READ');
		$k_config['k_poll_topic_id'] = 0;
	}

	//login_box('', $user->lang['LOGIN_VIEWFORUM']);
	$k_config['k_poll_topic_id'] = 0;
}

// Forum is passworded ... check whether access has been granted to this
// user this session, if not show login box
if ($topic_data['forum_password'])
{
	login_forum_box($topic_data);
}

// General Viewtopic URL for return links
//$b_viewtopic_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$b_forum_id&amp;t=$b_topic_id&amp;start=$b_start&amp;$u_sort_param");
$b_viewtopic_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$b_forum_id&amp;t=$b_topic_id&amp;start=$b_start&");

// Does this topic contain a poll?
if (!empty($topic_data['poll_start']))
{
	$sql = 'SELECT o.*, p.bbcode_bitfield, p.bbcode_uid
		FROM ' . POLL_OPTIONS_TABLE . ' o, ' . POSTS_TABLE . " p
		WHERE o.topic_id = $b_topic_id
			AND p.post_id = {$topic_data['topic_first_post_id']}
			AND p.topic_id = o.topic_id
		ORDER BY o.poll_option_id";
	$result = $db->sql_query($sql, $block_cache_time);

	$b_poll_info = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$b_poll_info[] = $row;
	}
	$db->sql_freeresult($result);

	$cur_voted_id = array();
	if ($user->data['is_registered'])
	{
		$sql = 'SELECT poll_option_id
			FROM ' . POLL_VOTES_TABLE . '
			WHERE topic_id = ' . $b_topic_id . '
			  AND vote_user_id = ' . $user->data['user_id'];
		$result = $db->sql_query($sql, $block_cache_time);

		while ($row = $db->sql_fetchrow($result))
		{
			$cur_voted_id[] = $row['poll_option_id'];
		}
		$db->sql_freeresult($result);
	}
	else
	{
		// Cookie based guest tracking ... I don't like this but hum ho
		// it's oft requested. This relies on "nice" users who don't feel
		// the need to delete cookies to mess with results.
		if (isset($_COOKIE[$config['cookie_name'] . '_poll_' . $b_topic_id]))
		{
			$cur_voted_id = $request->variable($config['cookie_name'] . '_poll_' . $b_topic_id, 0, false, true);
			$cur_voted_id = array_map('intval', $cur_voted_id);
		}
	}

	$s_can_vote = (((!sizeof($cur_voted_id) && $auth->acl_get('f_vote', $b_forum_id)) ||
		($auth->acl_get('f_votechg', $b_forum_id) && $topic_data['poll_vote_change'])) &&
		(($topic_data['poll_length'] != 0 && $topic_data['poll_start'] + $topic_data['poll_length'] > time()) || $topic_data['poll_length'] == 0) &&
		$topic_data['topic_status'] != ITEM_LOCKED &&
		$topic_data['forum_status'] != ITEM_LOCKED) ? true : false;

	//$s_display_results = (!$s_can_vote || ($s_can_vote && sizeof($cur_voted_id)) || $b_view == 'viewpoll') ? true : false;
	$s_display_results = true;

	if ($b_update && $s_can_vote)
	{
		if (!sizeof($b_voted_id) || sizeof($b_voted_id) > $topic_data['poll_max_options'])
		{
			$redirect_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$b_forum_id&amp;t=$b_topic_id");

			meta_refresh(3, $redirect_url);

			$message = (!sizeof($b_voted_id)) ? 'NO_VOTE_OPTION' : 'TOO_MANY_VOTE_OPTIONS';
			$message = $user->lang[$message] . '<br /><br />' . sprintf($user->lang['RETURN_TOPIC'], '<a href="' . $redirect_url . '">', '</a>');
			trigger_error($message);
		}

		foreach ($b_voted_id as $option)
		{
			if (in_array($option, $cur_voted_id))
			{
				continue;
			}

			$sql = 'UPDATE ' . POLL_OPTIONS_TABLE . '
			  SET poll_option_total = poll_option_total + 1
			  WHERE poll_option_id = ' . (int) $option . '
				 AND topic_id = ' . (int) $b_topic_id;
			$db->sql_query($sql);

			if ($user->data['is_registered'])
			{
				$sql_ary = array(
					'topic_id'       => (int) $b_topic_id,
					'poll_option_id' => (int) $option,
					'vote_user_id'   => (int) $user->data['user_id'],
					'vote_user_ip'   => (string) $user->ip,
				);

				$sql = 'INSERT INTO  ' . POLL_VOTES_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
				$db->sql_query($sql);
			}
		}

		foreach ($cur_voted_id as $option)
		{
			if (!in_array($option, $b_voted_id))
			{
				$sql = 'UPDATE ' . POLL_OPTIONS_TABLE . '
					SET poll_option_total = poll_option_total - 1
					WHERE poll_option_id = ' . (int) $option . '
						AND topic_id = ' . (int) $b_topic_id;
				$db->sql_query($sql);

				if ($user->data['is_registered'])
				{
					$sql = 'DELETE FROM ' . POLL_VOTES_TABLE . '
						WHERE topic_id = ' . (int) $b_topic_id . '
							AND poll_option_id = ' . (int) $option . '
							AND vote_user_id = ' . (int) $user->data['user_id'];
					$db->sql_query($sql);
				}
			}
		}

		if ($user->data['user_id'] == ANONYMOUS && !$user->data['is_bot'])
		{
			$user->set_cookie('poll_' . $b_topic_id, implode(',', $b_voted_id), time() + 31536000);
		}

		$sql = 'UPDATE ' . TOPICS_TABLE . '
			SET poll_last_vote = ' . time() . "
			WHERE topic_id = $b_topic_id";
		//, topic_last_post_time = ' . time() . " -- for bumping topics with new votes, ignore for now
		$db->sql_query($sql);

		$redirect_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=$b_forum_id&amp;t=$b_topic_id");

		meta_refresh(5, $redirect_url);
		trigger_error($user->lang['VOTE_SUBMITTED'] . '<br /><br />' . sprintf($user->lang['RETURN_TOPIC'], '<a href="' . $redirect_url . '">', '</a>'));
	}

	$b_poll_total = 0;
	foreach ($b_poll_info as $b_poll_option)
	{
		$b_poll_total += $b_poll_option['poll_option_total'];
	}

	if ($b_poll_info[0]['bbcode_bitfield'])
	{
		$b_poll_bbcode = new bbcode();
	}
	else
	{
		$b_poll_bbcode = false;
	}

	for ($i = 0, $size = sizeof($b_poll_info); $i < $size; $i++)
	{
		$b_poll_info[$i]['poll_option_text'] = censor_text($b_poll_info[$i]['poll_option_text']);
		$b_poll_info[$i]['poll_option_text'] = str_replace("\n", '<br />', $b_poll_info[$i]['poll_option_text']);

		if ($b_poll_bbcode !== false)
		{
			$b_poll_bbcode->bbcode_second_pass($b_poll_info[$i]['poll_option_text'], $b_poll_info[$i]['bbcode_uid'], $b_poll_option['bbcode_bitfield']);
		}

		$b_poll_info[$i]['poll_option_text'] = smiley_text($b_poll_info[$i]['poll_option_text']);
	}

	$topic_data['poll_title'] = censor_text($topic_data['poll_title']);
	$topic_data['poll_title'] = str_replace("\n", '<br />', $topic_data['poll_title']);

	if ($b_poll_bbcode !== false)
	{
		$b_poll_bbcode->bbcode_second_pass($topic_data['poll_title'], $b_poll_info[0]['bbcode_uid'], $b_poll_info[0]['bbcode_bitfield']);
	}
	$topic_data['poll_title'] = smiley_text($topic_data['poll_title']);

	unset($b_poll_bbcode);

	foreach ($b_poll_info as $b_poll_option)
	{
		$option_pct = ($b_poll_total > 0) ? $b_poll_option['poll_option_total'] / $b_poll_total : 0;
		$option_pct_txt = sprintf("%.1d%%", ($option_pct * 100));

		$template->assign_block_vars('b_poll_option', array(
			'POLL_OPTION_ID'		=> $b_poll_option['poll_option_id'],
			'POLL_OPTION_CAPTION'	=> $b_poll_option['poll_option_text'],
			'POLL_OPTION_RESULT'	=> $b_poll_option['poll_option_total'],
			'POLL_OPTION_PERCENT'	=> $option_pct_txt,
			'POLL_OPTION_PCT'		=> round($option_pct * 100),
			'POLL_OPTION_IMG'		=> $user->img('poll_center', $option_pct_txt, round($option_pct * 95)),
			'POLL_OPTION_IMG_C'		=> $user->img('poll_center', $option_pct_txt, round($option_pct * 300)),
			'POLL_OPTION_VOTED'		=> (in_array($b_poll_option['poll_option_id'], $cur_voted_id)) ? true : false,
			)
		);
	}

	$b_poll_end = $topic_data['poll_length'] + $topic_data['poll_start'];

	$template->assign_vars(array(
		'BPOLL_QUESTION'		=> $topic_data['poll_title'],
		'BTOTAL_VOTES'			=> $b_poll_total,
		'BPOLL_LEFT_CAP_IMG'	=> $user->img('poll_left'),
		'BPOLL_RIGHT_CAP_IMG'	=> $user->img('poll_right'),
		'BL_MAX_VOTES'			=> ($topic_data['poll_max_options'] == 1) ? $user->lang['MAX_OPTION_SELECT'] : sprintf($user->lang['MAX_OPTIONS_SELECT'], $topic_data['poll_max_options']),
		'BL_POLL_LENGTH'		=> ($topic_data['poll_length']) ? sprintf($user->lang[($b_poll_end > time()) ? 'POLL_RUN_TILL' : 'POLL_ENDED_AT'], $user->format_date($b_poll_end)) : '',
		'BS_HAS_POLL'			=> true,
		'BS_CAN_VOTE'			=> $s_can_vote,
		'BS_DISPLAY_RESULTS'	=> $s_display_results,
		'BS_IS_MULTI_CHOICE'	=> ($topic_data['poll_max_options'] > 1) ? true : false,
		'BS_POLL_ACTION'		=> $b_viewtopic_url,
		'BU_VIEW_RESULTS'		=> $b_viewtopic_url . '&amp;view=viewpoll',
		'BU_VIEW_RESULTS'		=> $b_viewtopic_url . '',
		)
	);

	unset($b_poll_end, $b_poll_info, $b_voted_id);

	$template->assign_vars(array(
		'S_CENTRE_BLOCK'	=> $wide,
		'S_VIEW'	        => $k_config['k_poll_view'],
	));

	if ($s_can_vote)
	{
		add_form_key('posting');
	}
}

$template->assign_vars(array(
	'POLL_DEBUG'	=> sprintf($user->lang['PORTAL_DEBUG_QUERIES'], ($queries) ? $queries : '0', ($cached_queries) ? $cached_queries : '0', ($total_queries) ? $total_queries : '0'),
));
