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

///var_dump('sgp_portal_blocks.php');

global $phpbb_root_path, $config, $k_config, $phpEx, $db, $user, $request, $template, $auth, $k_groups;

// Grab some portal cached data //
$block_cache_time  = $k_config['k_block_cache_time_default'];
$blocks_width 	   = $this->config['blocks_width'];
$blocks_enabled    = $this->config['blocks_enabled'];
$use_block_cookies = (isset($k_config['use_block_cookies'])) ? $k_config['use_block_cookies'] : 0;

// if block disabled, generate message and return... //
if (!$blocks_enabled)
{
	$template->assign_vars(array(
		'PORTAL_MESSAGE' => $user->lang('BLOCKS_DISABLED'),
	));
}

// set up some vars //
$all = '';
$show_center = $show_left = $show_right = false;
$LB = $CB = $RB = array();
$active_blocks = array();

// if styles use large block images change path to images //
$block_image_path = $phpbb_root_path . 'ext/phpbbireland/portal/images/block_images/block/';
$big_image_path = $phpbb_root_path . 'ext/phpbbireland/portal/images/block_images/large/';
$my_root_path = $phpbb_root_path . 'ext/phpbbireland/portal/';

$this_page = explode(".", $user->page['page']);
$user_id = $user->data['user_id'];


include_once($phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_functions.' . $phpEx);

// Grab data for this user //
$sql = "SELECT group_id, user_type, user_style, user_avatar, user_avatar_type, username, user_left_blocks, user_center_blocks, user_right_blocks
	FROM " . USERS_TABLE . "
	WHERE user_id = $user_id";

if ($result = $db->sql_query($sql))
{
	$row = $db->sql_fetchrow($result);

	$user_avatar = $row['user_avatar'];
	$user_style = $row['user_style'];
	$usertype = $row['user_type'];
	$groupid = $row['group_id'];

	$left = $row['user_left_blocks'];
	$LB = explode(',', $left);
	$center = $row['user_center_blocks'];
	$CB = explode(',', $center);
	$right = $row['user_right_blocks'];
	$RB = explode(',', $right);

	$LCR = array_merge((array) $LB, (array) $CB, (array) $RB);
	$left .= ',';
	$center .= ',';

	$all .= $left .= $center .= $right;
}
else
{
	trigger_error($user->lang['ERROR_USER_TABLE']);
}

// Process block positions for members only //
if ($row['group_id'] != ANONYMOUS)
{
	if (isset($_COOKIE[$config['cookie_name'] . '_sgp_left']) || isset($_COOKIE[$config['cookie_name'] . '_sgp_center']) || isset($_COOKIE[$config['cookie_name'] . '_sgp_right']) && $use_block_cookies)
	{
		$left = $request->variable($config['cookie_name'] . '_sgp_left', '', false, true);
		$left = str_replace("left[]=", "", $left);
		$left = str_replace("&amp;", ',', $left);
		$LBA = explode(',', $left);

		$center = $request->variable($config['cookie_name'] . '_sgp_center', '', false, true);
		$center = str_replace("center[]=", "", $center);
		$center = str_replace("&amp;", ',', $center);
		$CBA = explode(',', $center);

		$right = $request->variable($config['cookie_name'] . '_sgp_right', '', false, true);
		$right = str_replace("right[]=", "", $right);
		$right = str_replace("&amp;", ',', $right);
		$RBA = explode(',', $right);

		// store cookie data (block positions in user table)
		if (!empty($left))
		{
			$sql = 'UPDATE ' . USERS_TABLE . '
				SET user_left_blocks = ' . "'" . $db->sql_escape($left) . "'" . ', user_center_blocks = ' . "'" . $db->sql_escape($center) . "'" . ', user_right_blocks = ' . "'" . $db->sql_escape($right) . "'" . '
				WHERE user_id = ' . (int) $user->data['user_id'];
			$db->sql_query($sql);
			// set switch clear cookies now that we have them stored (we use javascript)//
			$template->assign_vars(array(
				'S_CLEAR_CACHE' => true
			));
		}
	}

	if (empty($row['user_left_blocks']))
	{
		$sql = "SELECT *
			FROM " . K_BLOCKS_TABLE . "
			WHERE active = 1
				AND (view_pages <> '0')
				ORDER BY ndx ASC";
	}
	else
	{
		$sql = "SELECT *
			FROM " . K_BLOCKS_TABLE . "
			WHERE active = 1
				AND (view_pages <> '0')
				AND " . $db->sql_in_set('id', $LCR) . "
			ORDER BY find_in_set(id,'" . $all . "')";
	}
}
else
{
	$sql = "SELECT *
		FROM " . K_BLOCKS_TABLE . "
		WHERE active = 1
				AND (view_pages <> '0')
				ORDER BY ndx ASC";
}

$result = $db->sql_query($sql, $block_cache_time);

while ($row = $db->sql_fetchrow($result))
{
	$active_blocks[] = $row;
	$arr[$row['id']] = explode(','  , $row['view_pages']);
}

// process phpbb common data //
//include($phpbb_root_path . 'ext/phpbbireland/portal/blocks/block_build.' . $phpEx);




$this_page_name = $this_page[1];
$this_page_name = str_replace('php/', '', $this_page_name);
$page_id = get_page_id($this_page_name);

if ($page_id == 0)
{
	$page_id = $this_page[0];
	$page_id = get_page_id($this_page[0]);
}

//var_dump($page_id);

foreach ($active_blocks as $active_block)
{
	$filename = substr($active_block['html_file_name'], 0, strpos($active_block['html_file_name'], '.'));
	if (file_exists($phpbb_root_path . 'ext/phpbbireland/portal/blocks/' . $filename . '.' . $phpEx))
	{
		if (in_array($page_id, $arr[$active_block['id']]))
		{
			include($phpbb_root_path . 'ext/phpbbireland/portal/blocks/' . $filename . '.' . $phpEx);
			//var_dump($filename);
		}
	}

}
$db->sql_freeresult($result);

if (!function_exists('group_memberships'))
{
	include($phpbb_root_path . 'includes/functions_user.'. $phpEx);
}
$memberships = array();
$memberships = group_memberships(false, $user->data['user_id'], false);

// Main processing of block data here //

if ($active_blocks)
{
	//var_dump($active_blocks);

	$L = $R = $C = 0;
	foreach ($active_blocks as $row)
	{
		$block_position = $row['position'];

		// override default position with user designated position //
		if (in_array($row['id'], $LB))
		{
			$block_position		= 'L';
		} else if (in_array($row['id'], $CB))
		{
			$block_position		= 'C';
		} else if (in_array($row['id'], $RB))
		{
			$block_position		= 'R';
		}

		$block_id           = $row['id'];
		$block_ndx          = $row['ndx'];
		$block_title        = $row['title'];
		$block_active       = $row['active'];
		$block_type         = $row['type'];
		$block_view_groups  = $row['view_groups'];
		$block_view_all     = $row['view_all'];
		$block_scroll       = $row['scroll'];
		$block_height       = $row['block_height'];
		$html_file_name     = $row['html_file_name'];
		$img_file_name      = $row['img_file_name'];
		$view_pages         = $row['view_pages'];

		$arr = explode(',', $view_pages);
		$grps = explode(",", $block_view_groups);

		$process_block = false;
		$block_title = get_menu_lang_name($row['title']);

		// process blocks for different groups //
		if ($memberships)
		{
			foreach ($memberships as $member)
			{
				// First we check to see if the view_all over-ride is set (saves having to enter all groups) //
				if ($block_view_all)
				{
					$process_block = true;
				}
				else
				{
					for ($j = 0; $j < $jcount = count($grps); $j++) // now we loop for all group the user is in //
					{
						if ($grps[$j] == $member['group_id'])
						{
							$process_block = true;
						}
					}
				}
			}

		}
		$this_page_name = $this_page[1];
		$this_page_name = str_replace('php/', '', $this_page_name);
		$page_id = get_page_id($this_page_name);

		if ($page_id == 0)
		{
			$page_id = $this_page[0];
			$page_id = get_page_id($this_page[0]);
		}

		if ($process_block && in_array($page_id, $arr))
		{
			//var_dump($html_file_name);
			switch ($block_position)
			{
				case 'L':
						$left_block_ary[$L]    = $html_file_name;
						$left_block_id[$L]     = $block_id;
						$left_block_ndx[$L]    = $block_ndx;
						$left_block_title[$L]  = $block_title;
						$left_block_img[$L]    = $img_file_name;
						$left_block_scroll[$L] = $block_scroll;
						$left_block_height[$L] = $block_height;
						$L++;
						$show_left = true;//show_blocks($this_page_name, $block_position);
				break;
				case 'C':
						$center_block_ary[$C]    = $html_file_name;
						$center_block_id[$C]     = $block_id;
						$center_block_ndx[$C]    = $block_ndx;
						$center_block_title[$C]  = $block_title;
						$center_block_img[$C]    = $img_file_name;
						$center_block_scroll[$C] = $block_scroll;
						$center_block_height[$C] = $block_height;
						$C++;
						$show_center = true;//show_blocks($this_page_name, $block_position);
				break;
				case 'R':
						$right_block_ary[$R]    = $html_file_name;
						$right_block_id[$R]     = $block_id;
						$right_block_ndx[$R]    = $block_ndx;
						$right_block_title[$R]  = $block_title;
						$right_block_img[$R]    = $img_file_name;
						$right_block_scroll[$R] = $block_scroll;
						$right_block_height[$R] = $block_height;
						$R++;
						$show_right = true;//show_blocks($this_page_name, $block_position);
				break;
				default:
			}
		}
	}
}


if (isset($left_block_ary) && $show_left)
{
	foreach ($left_block_ary as $block => $value)
	{
		$template->assign_block_vars('left_block_files', array(
			'LEFT_BLOCKS'           => portal_block_template($value),
			'LEFT_BLOCK_ID'         => 'L_' .$left_block_id[$block],
			'LEFT_BLOCK_TITLE'      => $left_block_title[$block],
			'LEFT_BLOCK_SCROLL'     => $left_block_scroll[$block],
			'LEFT_BLOCK_HEIGHT'     => $left_block_height[$block],
			'LEFT_BLOCK_IMG'        => ($left_block_img[$block]) ? $block_image_path . $left_block_img[$block] : $block_image_path . 'none.gif',
			'LEFT_BLOCK_IMG_2'      => (file_exists($big_image_path . $left_block_img[$block])) ? $big_image_path  . $left_block_img[$block] : $big_image_path . 'none.png',
			'S_CONTENT_FLOW_BEGIN'  => ($user->lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
			'S_CONTENT_FLOW_END'    => ($user->lang['DIRECTION'] == 'ltr') ? 'right' : 'left',
		));
	}
}

if (isset($right_block_ary) && $show_right)
{
	foreach ($right_block_ary as $block => $value)
	{
		$template->assign_block_vars('right_block_files', array(
			'RIGHT_BLOCKS'          => portal_block_template($value),
			'RIGHT_BLOCK_ID'        => 'R_' .$right_block_id[$block],
			'RIGHT_BLOCK_TITLE'     => $right_block_title[$block],
			'RIGHT_BLOCK_SCROLL'    => $right_block_scroll[$block],
			'RIGHT_BLOCK_HEIGHT'    => $right_block_height[$block],
			'RIGHT_BLOCK_IMG'       => ($right_block_img[$block]) ? $block_image_path . $right_block_img[$block] : $block_image_path . 'none.gif',
			'RIGHT_BLOCK_IMG_2'     => (file_exists($big_image_path . $right_block_img[$block])) ? $big_image_path  . $right_block_img[$block] : $big_image_path . 'none.png',
			'S_CONTENT_FLOW_BEGIN'  => ($user->lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
			'S_CONTENT_FLOW_END'    => ($user->lang['DIRECTION'] == 'ltr') ? 'right' : 'left',
		));
	}
}

if (isset($center_block_ary) && $show_center)
{
	foreach ($center_block_ary as $block => $value)
	{
		// As it is not always possible to display all data as intended in a narrow block (left or right blocks) we automatically load a wide layout if it exists //
		// We check the default template folder and the SGP common folder templates //

///// 3.1
		//$my_file_wide = "{$style_path_ext}" . $user->theme['template_path'] . '/template/blocks/' . $value;
		//$my_file_wide = str_replace('.html', '_wide.html', $my_file_wide);
		$my_file_wide = $style_path_ext = '';

		if (file_exists($my_file_wide))
		{
			$value = str_replace('.html', '_wide.html', $value);
		}
		else
		{
			$my_file_wide = "{$style_path_ext}common/template/blocks/" . $value;
			$my_file_wide = str_replace('.html', '_wide.html', $my_file_wide);
			if (file_exists($my_file_wide))
			{
				$value = str_replace('.html', '_wide.html', $value);
			}
		}

		$template->assign_block_vars('center_block_files', array(
			'CENTER_BLOCKS'        => portal_block_template($value),
			'CENTER_BLOCK_ID'      => 'C_' .$center_block_id[$block],
			'CENTER_BLOCK_TITLE'   => $center_block_title[$block],
			'CENTER_BLOCK_SCROLL'  => $center_block_scroll[$block],
			'CENTER_BLOCK_HEIGHT'  => $center_block_height[$block],
			'CENTER_BLOCK_IMG'     => ($center_block_img[$block]) ? $block_image_path . $center_block_img[$block] : $block_image_path . 'none.gif',
			'CENTER_BLOCK_IMG_2'   => (file_exists($big_image_path . $center_block_img[$block])) ? $big_image_path  . $center_block_img[$block] : $big_image_path . 'none.png',
			'S_CONTENT_FLOW_BEGIN' => ($user->lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
			'S_CONTENT_FLOW_END'   => ($user->lang['DIRECTION'] == 'ltr') ? 'right' : 'left',
		));
	}
}


$avatar_data = array(
	'avatar' => $row['user_avatar'],
	'avatar_width' => $row['user_avatar_width'],
	'avatar_height' => $row['user_avatar_height'],
	'avatar_type' => $row['user_avatar_type'],
);
$ava = phpbb_get_avatar($avatar_data, $user->lang['USER_AVATAR'], false);

//var_dump($phpbb_root_path . 'ext/phpbbireland/portal/style/' . rawurlencode($user->style['style_path']) . '/theme/images/');
$template->assign_vars(array(
	'T_THEME_PATH'            => $phpbb_root_path . 'ext/phpbbireland/portal/style/' . rawurlencode($user->style['style_path']) . '/theme/images/',
	'AVATAR'				  => $ava,
	'BLOCK_WIDTH'			  => $blocks_width . 'px',

	'PORTAL_ACTIVE'			  => $config['portal_enabled'],
	'PORTAL_BUILD'			  => $config['portal_build'],
	'PORTAL_VERSION'		  => $config['portal_version'],

	'READ_ARTICLE_IMG'		  => $user->img('btn_read_article', 'READ_ARTICLE'),
	'POST_COMMENTS_IMG'		  => $user->img('btn_post_comments', 'POST_COMMENTS'),
	'VIEW_COMMENTS_IMG'		  => $user->img('btn_view_comments', 'VIEW_COMMENTS'),

	'SITE_NAME'				  => $config['sitename'],
	'S_USER_LOGGED_IN'		  => ($user->data['user_id'] != ANONYMOUS) ? true : false,

	'S_SHOW_LEFT_BLOCKS'	  => $show_left,
	'S_SHOW_RIGHT_BLOCKS'	  => $show_right,

	'S_BLOCKS_ENABLED'        => $blocks_enabled,
	'S_K_FOOTER_IMAGES_ALLOW' => ($k_config['k_footer_images_allow']) ? true : false,
	'S_CONTENT_FLOW_BEGIN'    => ($user->lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
	'S_CONTENT_FLOW_END'      => ($user->lang['DIRECTION'] == 'ltr') ? 'right' : 'left',

	'USER_NAME'               => $user->data['username'],
	'USERNAME_FULL'           => get_username_string('full', $user->data['user_id'], $user->data['username'], $user->data['user_colour']),
	'U_INDEX'                 => append_sid("{$phpbb_root_path}index.$phpEx"),
	///'U_PORTAL'                => append_sid("{$phpbb_root_path}portal.$phpEx"),
	//'U_PORTAL'                => append_sid("{$phpbb_root_path}portal"),
	//'U_PORTAL_ARRANGE'        => append_sid("{$phpbb_root_path}portal.$phpEx", "arrange=1"),
	'U_STAFF'                 => append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=leaders'),
	'U_SEARCH_BOOKMARKS'      => append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=main&mode=bookmarks'),
));


/***
// process common data here //
if ($this_page[0] == 'viewtopic')
{
	global $phpEx, $phpbb_root_path;
	global $config, $user, $template, $k_quick_posting_mode, $forum_id, $post_data, $topic_id, $topic_data, $k_config;

	include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);

	if (!function_exists('get_user_avatar'))
	{
		include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
	}

	if (!isset($smilies_status))
	{
		generate_smilies('inline', $forum_id);
	}

	// HTML, BBCode, Smilies, Images and Flash status amended version
	$bbcode_status    = ($config['allow_bbcode'] && $auth->acl_get('f_bbcode', $forum_id)) ? true : false;
	$smilies_status   = ($bbcode_status && $config['allow_smilies'] && $auth->acl_get('f_smilies', $forum_id)) ? true : false;
	$img_status       = ($bbcode_status && $auth->acl_get('f_img', $forum_id)) ? true : false;
	$url_status       = ($config['allow_post_links']) ? true : false;
	$quote_status     = ($auth->acl_get('f_reply', $forum_id)) ? true : false;
	$subscribe_topic  = ($config['allow_topic_notify'] && $user->data['is_registered'] && $user->data['user_notify']) ? true : false;
	$flash_status     = ($bbcode_status && $auth->acl_get('f_flash', $forum_id)) ? true : false;
	$enable_sig       = ($config['allow_sig'] && $user->optionget('attachsig')) ? true: false;

	add_form_key('posting');

	$template->assign_vars(array(
		'STARGATE'            => true,
		'MESSAGE'             => '',
		'L_QUICK_TITLE'       => $user->lang['K_QUICK_REPLY'],
		'S_QUICK_TITLE'       => 'Re: ' . $topic_data['topic_title'],
		'S_SMILIES_ALLOWED'   => $smilies_status,
		'S_LINKS_ALLOWED'     => $url_status,
		'S_SIG_ALLOWED'       => ($auth->acl_get('f_sigs', $forum_id) && $config['allow_sig'] && $user->data['is_registered']) ? true : false,
		'S_SIGNATURE_CHECKED' => ($enable_sig) ? ' checked="checked"' : '',
		'S_SUBSCRIBE'         => $subscribe_topic,
		'S_BBCODE_QUOTE'      => $quote_status,
		'S_BBCODE_IMG'        => $img_status,
		'S_BBCODE_FLASH'      => $flash_status,
		'U_MORE_SMILIES'      => append_sid("{$phpbb_root_path}posting.$phpEx", 'mode=smilies&amp;f=' . $forum_id),
		'S_USER_LOGGED_IN'    => ($user->data['user_id'] != ANONYMOUS) ? true : false,
		'S_K_SHOW_SMILES'     => $k_config['k_smilies_show'],
		'QUOTE_IMG'           => $user->img('icon_post_quote', 'REPLY_WITH_QUOTE'),
	));
}
***/
