<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('IN_PHPBB'))
{
   exit;
}

global $user, $phpbb_root_path;

$this->user = $user;
$this_page = explode(".", $this->user->page['page']);

if ($this_page[0] == 'index')
{
	$this_page_name = $this_page[0];
}
else
{
	$this_page_name = str_replace('php/', '', $this_page[1]);
}


if ($this_page_name != 'portal')
{
	return;
}

display_forums_categories();

/**
* This code is only processed for the portal page in order to allow displaying
* of categories, it is not required for normal phpbb pages as the data is
* available in the data pool (globally)...
*
* The function simply reuses phpbb code but will be updated when block code is rewrittten as classes
* Will use dependency injection later
*/
function display_forums_categories()
{
	// A rewrite for phpBB3 display_forums code drastically reduced to provide categories block with data //

	global $db, $auth, $user, $template;
	global $phpbb_root_path, $phpEx, $config;

	$default_icon = $phpbb_root_path . 'ext/phpbbireland/portal/images/forum_icons/default.png';

	$forum_rows = $subforums = $forum_ids = $active_forum_ary = array(); // $forum_ids_moderator = $forum_moderators = $active_forum_ary = array();
	$parent_id = $visible_forums = 0;
	$sql_from = '';

	$root_data = array('forum_id' => 0);
	$sql_where = '';

	$sql_array = array(
		'SELECT'	=> 'f.*',
		'FROM'		=> array(
			FORUMS_TABLE		=> 'f'
		),
		'LEFT_JOIN'	=> array(),
	);

	$sql = $db->sql_build_query('SELECT', array(
		'SELECT'	=> $sql_array['SELECT'],
		'FROM'		=> $sql_array['FROM'],
		'LEFT_JOIN'	=> $sql_array['LEFT_JOIN'],

		'WHERE'		=> $sql_where,

		'ORDER_BY'	=> 'f.left_id',
	));

	$result = $db->sql_query($sql);

	$branch_root_id = $root_data['forum_id'];
	$cnt = 0;

	// Check for unread global announcements (index page only)
	$ga_unread = false;
	if ($root_data['forum_id'] == 0)
	{
		$unread_ga_list = get_unread_topics($user->data['user_id'], 'AND t.forum_id = 0', '', 1);

		if (!empty($unread_ga_list))
		{
			$ga_unread = true;
		}
	}

	while ($row = $db->sql_fetchrow($result))
	{
		$forum_id = $row['forum_id'];

		// Category with no members
		if ($row['forum_type'] == FORUM_CAT && ($row['left_id'] + 1 == $row['right_id']))
		{
			continue;
		}

		// Skip branch
		if (isset($right_id))
		{
			if ($row['left_id'] < $right_id)
			{
				continue;
			}
			unset($right_id);
		}

		if (!$auth->acl_get('f_list', $forum_id))
		{
			// if the user does not have permissions to list this forum, skip everything until next branch
			$right_id = $row['right_id'];
			continue;
		}

		// Count the difference of real to public topics, so we can display an information to moderators
		//$row['forum_id_unapproved_topics'] = ($auth->acl_get('m_approve', $forum_id) && ($row['forum_topics_real'] != $row['forum_topics'])) ? $forum_id : 0;
		$row['forum_id_unapproved_topics'] = ($auth->acl_get('m_approve', $forum_id) && ($row['forum_topics_approved'] != $row['forum_topics_approved'])) ? $forum_id : 0;
		//$row['forum_topics'] = ($auth->acl_get('m_approve', $forum_id)) ? $row['forum_topics_real'] : $row['forum_topics'];
		$row['forum_topics'] = ($auth->acl_get('m_approve', $forum_id)) ? $row['forum_topics_approved'] : $row['forum_topics_approved'];

		if ($row['parent_id'] == $root_data['forum_id'] || $row['parent_id'] == $branch_root_id)
		{
			// Direct child of current branch
			$parent_id = $forum_id;
			$forum_rows[$forum_id] = $row;

			if ($row['forum_type'] == FORUM_CAT && $row['parent_id'] == $root_data['forum_id'])
			{
				$branch_root_id = $forum_id;
			}
			$forum_rows[$parent_id]['forum_id_last_post'] = $row['forum_id'];
			$forum_rows[$parent_id]['orig_forum_last_post_time'] = $row['forum_last_post_time'];
		}
		else if ($row['forum_type'] != FORUM_CAT)
		{
			$subforums[$parent_id][$forum_id]['display'] = ($row['display_on_index']) ? true : false;
			$subforums[$parent_id][$forum_id]['name'] = $row['forum_name'];
			$subforums[$parent_id][$forum_id]['orig_forum_last_post_time'] = $row['forum_last_post_time'];
			$subforums[$parent_id][$forum_id]['children'] = array();

			if (isset($subforums[$parent_id][$row['parent_id']]) && !$row['display_on_index'])
			{
				$subforums[$parent_id][$row['parent_id']]['children'][] = $forum_id;
			}

			if (!$forum_rows[$parent_id]['forum_id_unapproved_topics'] && $row['forum_id_unapproved_topics'])
			{
				$forum_rows[$parent_id]['forum_id_unapproved_topics'] = $forum_id;
			}

			$forum_rows[$parent_id]['forum_topics'] += $row['forum_topics'];

			// Do not list redirects in LINK Forums as Posts.
			if ($row['forum_type'] != FORUM_LINK)
			{
				$forum_rows[$parent_id]['forum_posts_approved'] += $row['forum_posts_approved'];
			}
		}
	}
	$db->sql_freeresult($result);

	// Used to tell whatever we have to create a dummy category or not.
	$last_catless = true;

	foreach ($forum_rows as $row)
	{
		// Empty category
		if ($row['parent_id'] == $root_data['forum_id'] && $row['forum_type'] == FORUM_CAT)
		{
			$template->assign_block_vars('forumrow', array(
				'S_ID'                  => $cnt,
				'S_IS_CAT'				=> true,
				'FORUM_ID'				=> $row['forum_id'],
				'FORUM_NAME'			=> $row['forum_name'],
				'FORUM_DESC'			=> generate_text_for_display($row['forum_desc'], $row['forum_desc_uid'], $row['forum_desc_bitfield'], $row['forum_desc_options']),
				'FORUM_IMAGE_SRC'		=> ($row['forum_image']) ? $row['forum_image'] : $default_icon,
				'U_VIEWFORUM'			=> append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $row['forum_id']))
			);

			continue;
		}

		$visible_forums++;
		$forum_id = $row['forum_id'];

		$forum_unread = (isset($forum_tracking_info[$forum_id]) && $row['orig_forum_last_post_time'] > $forum_tracking_info[$forum_id]) ? true : false;

		// Mark the first visible forum on index as unread if there's any unread global announcement
		if ($ga_unread && !empty($forum_ids_moderator) && $forum_id == $forum_ids_moderator[0])
		{
			$forum_unread = true;
		}

		$folder_image = $folder_alt = $l_subforums = '';
		$subforums_list = array();

		// Generate list of subforums if we need to
		if (isset($subforums[$forum_id]))
		{
			foreach ($subforums[$forum_id] as $subforum_id => $subforum_row)
			{
				$subforum_unread = (isset($forum_tracking_info[$subforum_id]) && $subforum_row['orig_forum_last_post_time'] > $forum_tracking_info[$subforum_id]) ? true : false;

				if (!$subforum_unread && !empty($subforum_row['children']))
				{
					foreach ($subforum_row['children'] as $child_id)
					{
						if (isset($forum_tracking_info[$child_id]) && $subforums[$forum_id][$child_id]['orig_forum_last_post_time'] > $forum_tracking_info[$child_id])
						{
							// Once we found an unread child forum, we can drop out of this loop
							$subforum_unread = true;
							break;
						}
					}
				}

				if ($subforum_row['display'] && $subforum_row['name'])
				{
					$subforums_list[] = array(
						'link'		=> append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $subforum_id),
						'name'		=> $subforum_row['name'],
						'unread'	=> $subforum_unread,
					);
				}
				else
				{
					unset($subforums[$forum_id][$subforum_id]);
				}

				// If one subforum is unread the forum gets unread too...
				if ($subforum_unread)
				{
					$forum_unread = true;
				}
			}

			$l_subforums = (sizeof($subforums[$forum_id]) == 1) ? $user->lang['SUBFORUM'] . ': ' : $user->lang['SUBFORUMS'] . ': ';
			$folder_image = ($forum_unread) ? 'forum_unread_subforum' : 'forum_read_subforum';
		}
		else
		{
			switch ($row['forum_type'])
			{
				case FORUM_POST:
					$folder_image = ($forum_unread) ? 'forum_unread' : 'forum_read';
				break;

				case FORUM_LINK:
					$folder_image = 'forum_link';
				break;
			}
		}

		// Which folder should we display?
		if ($row['forum_status'] == ITEM_LOCKED)
		{
			$folder_image = ($forum_unread) ? 'forum_unread_locked' : 'forum_read_locked';
			$folder_alt = 'FORUM_LOCKED';
		}
		else
		{
			$folder_alt = ($forum_unread) ? 'UNREAD_POSTS' : 'NO_UNREAD_POSTS';
		}

		// Create last post link information, if appropriate
		if ($row['forum_last_post_id'])
		{
			$last_post_subject = $row['forum_last_post_subject'];
			$last_post_time = $user->format_date($row['forum_last_post_time']);
			$last_post_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $row['forum_id_last_post'] . '&amp;p=' . $row['forum_last_post_id']) . '#p' . $row['forum_last_post_id'];
		}
		else
		{
			$last_post_subject = $last_post_time = $last_post_url = '';
		}

		$l_post_click_count = ($row['forum_type'] == FORUM_LINK) ? 'CLICKS' : 'POSTS';
		$post_click_count = ($row['forum_type'] != FORUM_LINK || $row['forum_flags'] & FORUM_FLAG_LINK_TRACK) ? $row['forum_posts_approved'] : '';

		$s_subforums_list = array();
		foreach ($subforums_list as $subforum)
		{
			$s_subforums_list[] = '<a href="' . $subforum['link'] . '" class="subforum ' . (($subforum['unread']) ? 'unread' : 'read') . '" title="' . (($subforum['unread']) ? $user->lang['UNREAD_POSTS'] : $user->lang['NO_UNREAD_POSTS']) . '">' . $subforum['name'] . '</a>';
		}
		$s_subforums_list = (string) implode(', ', $s_subforums_list);
		$catless = ($row['parent_id'] == $root_data['forum_id']) ? true : false;

		if ($row['forum_type'] != FORUM_LINK)
		{
			$u_viewforum = append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $row['forum_id']);
		}
		else
		{
			// If the forum is a link and we count redirects we need to visit it
			// If the forum is having a password or no read access we do not expose the link, but instead handle it in viewforum
			if (($row['forum_flags'] & FORUM_FLAG_LINK_TRACK) || $row['forum_password'] || !$auth->acl_get('f_read', $forum_id))
			{
				$u_viewforum = append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $row['forum_id']);
			}
			else
			{
				$u_viewforum = $row['forum_link'];
			}
		}

		$template->assign_block_vars('forumrow', array(
			'S_ID'              => $cnt,
			'S_IS_CAT'			=> false,
			'S_NO_CAT'			=> $catless && !$last_catless,
			'S_IS_LINK'			=> ($row['forum_type'] == FORUM_LINK) ? true : false,
			'S_UNREAD_FORUM'	=> $forum_unread,
			'S_AUTH_READ'		=> $auth->acl_get('f_read', $row['forum_id']),
			'S_LOCKED_FORUM'	=> ($row['forum_status'] == ITEM_LOCKED) ? true : false,
			'S_LIST_SUBFORUMS'	=> ($row['display_subforum_list']) ? true : false,
			'S_SUBFORUMS'		=> (sizeof($subforums_list)) ? true : false,

			'FORUM_IMAGE_SRC'		=> ($row['forum_image']) ? $row['forum_image'] : $default_icon,

			'FORUM_ID'				=> $row['forum_id'],
			'FORUM_NAME'			=> $row['forum_name'],
			'FORUM_DESC'			=> generate_text_for_display($row['forum_desc'], $row['forum_desc_uid'], $row['forum_desc_bitfield'], $row['forum_desc_options']),
			'TOPICS'				=> $row['forum_topics'],
			$l_post_click_count		=> $post_click_count,

			'SUBFORUMS'				=> $s_subforums_list,
			'U_VIEWFORUM'			=> $u_viewforum,

		));
		// Assign subforums loop for style authors
		foreach ($subforums_list as $subforum)
		{
			$template->assign_block_vars('forumrow.subforum', array(
				'U_SUBFORUM'	=> $subforum['link'],
				'SUBFORUM_NAME'	=> $subforum['name'],
				'S_UNREAD'		=> $subforum['unread'])
			);
		}

		$last_catless = $catless;
		$cnt++;
		//echo $cnt . ' ';
	}

	/*
		As this code only affects the portal page category block, the option
		to display 'categories only' is somewhat restricted, that is, it won't
		affect the block when viewed on other phpbb pages...

		The switch below is hard-coded atm but can be added to acp later...
	*/

	$template->assign_vars(array(
		'S_DISPLAY_CATS_ONLY' => false,
	));
}

