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

//var_dump('in blocks_build.php');
global $db, $config, $k_config, $request, $template, $SID, $_SID, $user, $auth, $phpEx, $phpbb_root_path;

$block_cache_time = $k_config['k_block_cache_time_default'];
$queries = $cached_queries = $total_queries = 0;


if (empty($user->lang))
{
	$user->add_lang('common');
}
include_once($phpbb_root_path . 'includes/functions.' . $phpEx);

// Process common data as in phpBB header function for pages other than index and portal //
// Needed to fill some block with data... only other option is to code these block
// independently or disable them //


$this_page = explode(".", $user->page['page']);
$this_page_name = $this_page[1];
$this_page_name = str_replace('php/', '', $this_page_name);


//var_dump($this_page_name);


if ($this_page_name == 'portal' || $this_page_name == 'index')
{
	//$page_title = '';
	$display_online_list = true;
	$item_id = 0;
	$item = 'forum';

	// Generate logged in/logged out status
	if ($user->data['user_id'] != ANONYMOUS)
	{
		//$u_login_logout = append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=logout', true, $user->session_id);
		$l_login_logout = sprintf($user->lang['LOGOUT_USER'], $user->data['username']);
	}
	else
	{
		//$u_login_logout = append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=login');
		$l_login_logout = $user->lang['LOGIN'];
	}

	// Last visit date/time
	$s_last_visit = ($user->data['user_id'] != ANONYMOUS) ? $user->format_date($user->data['session_last_visit']) : '';

	// Get users online list ... if required
	$l_online_users = $online_userlist = $l_online_record = $l_online_time = '';

	if ($config['load_online'] && $config['load_online_time'] && $display_online_list)
	{
		//
		// Load online data:
		// For obtaining another session column use $item and $item_id in the function-parameter, whereby the column is session_{$item}_id.
		//
		$item_id = max($item_id, 0);

		$online_users = obtain_users_online($item_id, $item);
		$user_online_strings = obtain_users_online_string($online_users, $item_id, $item);

		$l_online_users = $user_online_strings['l_online_users'];
		$online_userlist = $user_online_strings['online_userlist'];
		$total_online_users = $online_users['total_online'];

		if ($total_online_users > $config['record_online_users'])
		{
			$config->set('record_online_users', $total_online_users, true);
			$config->set('record_online_date', time(), true);
		}

		$l_online_record = sprintf($user->lang['RECORD_ONLINE_USERS'], $config['record_online_users'], $user->format_date($config['record_online_date'], false, true));

		$l_online_time = ($config['load_online_time'] == 1) ? 'VIEW_ONLINE_TIME' : 'VIEW_ONLINE_TIMES';
		$l_online_time = sprintf($user->lang[$l_online_time], $config['load_online_time']);
	}

	$l_privmsgs_text = $l_privmsgs_text_unread = '';
	$s_privmsg_new = false;

	// Obtain number of new private messages if user is logged in
	if (!empty($user->data['is_registered']))
	{
		if ($user->data['user_new_privmsg'])
		{
			$l_message_new = ($user->data['user_new_privmsg'] == 1) ? $user->lang['NEW_PM'] : $user->lang['NEW_PMS'];
			$l_privmsgs_text = sprintf($l_message_new, $user->data['user_new_privmsg']);

			if (!$user->data['user_last_privmsg'] || $user->data['user_last_privmsg'] > $user->data['session_last_visit'])
			{
				$sql = 'UPDATE ' . USERS_TABLE . '
					SET user_last_privmsg = ' . $user->data['session_last_visit'] . '
					WHERE user_id = ' . $user->data['user_id'];
				$db->sql_query($sql);

				$s_privmsg_new = true;
			}
			else
			{
				$s_privmsg_new = false;
			}
		}
		else
		{
			$l_privmsgs_text = $user->lang['NO_NEW_PM'];
			$s_privmsg_new = false;
		}

		$l_privmsgs_text_unread = '';

		if ($user->data['user_unread_privmsg'] && $user->data['user_unread_privmsg'] != $user->data['user_new_privmsg'])
		{
			$l_message_unread = ($user->data['user_unread_privmsg'] == 1) ? $user->lang['UNREAD_PM'] : $user->lang['UNREAD_PMS'];
			$l_privmsgs_text_unread = sprintf($l_message_unread, $user->data['user_unread_privmsg']);
		}
	}

	$forum_id = $request->variable('f', 0);
	$topic_id = $request->variable('t', 0);

	$s_feed_news = false;

	// Get option for news
	if ($config['feed_enable'])
	{
		$sql = 'SELECT forum_id
			FROM ' . FORUMS_TABLE . '
			WHERE ' . $db->sql_bit_and('forum_options', FORUM_OPTION_FEED_NEWS, '<> 0');

		$result = $db->sql_query_limit($sql, 1, 0, $block_cache_time);
		$s_feed_news = (int) $db->sql_fetchfield('forum_id');
		$db->sql_freeresult($result);
	}

	// Determine board url - we may need it later
	$board_url = generate_board_url() . '/';
	$web_path = (defined('PHPBB_USE_BOARD_URL_PATH') && PHPBB_USE_BOARD_URL_PATH) ? $board_url : $phpbb_root_path;

	// Which timezone?
	$tz = ($user->data['user_id'] != ANONYMOUS) ? strval(doubleval($user->data['user_timezone'])) : strval(doubleval($config['board_timezone']));

	// Send a proper content-language to the output
	$user_lang = $user->lang['USER_LANG'];
	if (strpos($user_lang, '-x-') !== false)
	{
		$user_lang = substr($user_lang, 0, strpos($user_lang, '-x-'));
	}
/*
	// The following assigns all _common_ variables that may be used at any point in a template.
	$template->assign_vars(array(
		'SITENAME'						=> $config['sitename'],
		'SITE_DESCRIPTION'				=> $config['site_desc'],
		'STARGATE_VERSION'				=> (isset($config['portal_version'])) ? $config['portal_version'] : '',
		'SCRIPT_NAME'					=> str_replace('.' . $phpEx, '', $user->page['page_name']),
		'LAST_VISIT_DATE'				=> sprintf($user->lang['YOU_LAST_VISIT'], $s_last_visit),
		'LAST_VISIT_YOU'				=> $s_last_visit,
		'CURRENT_TIME'					=> sprintf($user->lang['CURRENT_TIME'], $user->format_date(time(), false, true)),
		'TOTAL_USERS_ONLINE'			=> $l_online_users,
		'LOGGED_IN_USER_LIST'			=> $online_userlist,
		'RECORD_USERS'					=> $l_online_record,
		'PRIVATE_MESSAGE_INFO'			=> $l_privmsgs_text,
		'PRIVATE_MESSAGE_INFO_UNREAD'	=> $l_privmsgs_text_unread,

		'S_USER_NEW_PRIVMSG'			=> $user->data['user_new_privmsg'],
		'S_USER_UNREAD_PRIVMSG'			=> $user->data['user_unread_privmsg'],
		'S_USER_NEW'					=> $user->data['user_new'],

		'SID'				=> $SID,
		'_SID'				=> $_SID,
		'SESSION_ID'		=> $user->session_id,
		'ROOT_PATH'			=> $phpbb_root_path,
		'BOARD_URL'			=> $board_url,

		'L_LOGIN_LOGOUT'	=> $l_login_logout,
		'L_INDEX'			=> $user->lang['FORUM_INDEX'],
		'L_ONLINE_EXPLAIN'	=> $l_online_time,

		'U_PRIVATEMSGS'			=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=pm&amp;folder=inbox'),
		'U_RETURN_INBOX'		=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=pm&amp;folder=inbox'),
		'U_POPUP_PM'			=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=pm&amp;mode=popup'),
		'UA_POPUP_PM'			=> addslashes(append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=pm&amp;mode=popup')),
		'U_MEMBERLIST'			=> append_sid("{$phpbb_root_path}memberlist.$phpEx"),
		'U_VIEWONLINE'			=> ($auth->acl_gets('u_viewprofile', 'a_user', 'a_useradd', 'a_userdel')) ? append_sid("{$phpbb_root_path}viewonline.$phpEx") : '',
		'U_LOGIN_LOGOUT'		=> $u_login_logout,
		'U_INDEX'				=> append_sid("{$phpbb_root_path}index.$phpEx"),
		'U_SEARCH'				=> append_sid("{$phpbb_root_path}search.$phpEx"),
		'U_REGISTER'			=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=register'),
		'U_PROFILE'				=> append_sid("{$phpbb_root_path}ucp.$phpEx"),
		'U_MODCP'				=> append_sid("{$phpbb_root_path}mcp.$phpEx", false, true, $user->session_id),
		'U_FAQ'					=> append_sid("{$phpbb_root_path}faq.$phpEx"),
		'U_SEARCH_SELF'			=> append_sid("{$phpbb_root_path}search.$phpEx", 'search_id=egosearch'),
		'U_SEARCH_NEW'			=> append_sid("{$phpbb_root_path}search.$phpEx", 'search_id=newposts'),
		'U_SEARCH_UNANSWERED'	=> append_sid("{$phpbb_root_path}search.$phpEx", 'search_id=unanswered'),
		'U_SEARCH_UNREAD'		=> append_sid("{$phpbb_root_path}search.$phpEx", 'search_id=unreadposts'),
		'U_SEARCH_ACTIVE_TOPICS'=> append_sid("{$phpbb_root_path}search.$phpEx", 'search_id=active_topics'),
		'U_DELETE_COOKIES'		=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=delete_cookies'),
		'U_TEAM'				=> ($user->data['user_id'] != ANONYMOUS && !$auth->acl_get('u_viewprofile')) ? '' : append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=leaders'),
		'U_TERMS_USE'			=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=terms'),
		'U_PRIVACY'				=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=privacy'),
		'U_RESTORE_PERMISSIONS'	=> ($user->data['user_perm_from'] && $auth->acl_get('a_switchperm')) ? append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=restore_perm') : '',
		'U_FEED'				=> generate_board_url() . "/feed.$phpEx",

		'S_USER_LOGGED_IN'		=> ($user->data['user_id'] != ANONYMOUS) ? true : false,
		'S_AUTOLOGIN_ENABLED'	=> ($config['allow_autologin']) ? true : false,
		'S_BOARD_DISABLED'		=> ($config['board_disable']) ? true : false,
		'S_REGISTERED_USER'		=> (!empty($user->data['is_registered'])) ? true : false,
		'S_IS_BOT'				=> (!empty($user->data['is_bot'])) ? true : false,
		'S_USER_PM_POPUP'		=> $user->optionget('popuppm'),
		'S_USER_LANG'			=> $user_lang,
		'S_USER_BROWSER'		=> (isset($user->data['session_browser'])) ? $user->data['session_browser'] : $user->lang['UNKNOWN_BROWSER'],
		'S_USERNAME'			=> $user->data['username'],
		'S_CONTENT_DIRECTION'	=> $user->lang['DIRECTION'],
		'S_CONTENT_FLOW_BEGIN'	=> ($user->lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
		'S_CONTENT_FLOW_END'	=> ($user->lang['DIRECTION'] == 'ltr') ? 'right' : 'left',
		'S_CONTENT_ENCODING'	=> 'UTF-8',
		'S_TIMEZONE'			=> ($user->data['user_dst'] || ($user->data['user_id'] == ANONYMOUS && $config['board_dst'])) ? sprintf($user->lang['ALL_TIMES'], $user->lang['tz'][$tz], $user->lang['tz']['dst']) : sprintf($user->lang['ALL_TIMES'], $user->lang['tz'][$tz], ''),
		'S_DISPLAY_ONLINE_LIST'	=> ($l_online_time) ? 1 : 0,
		'S_DISPLAY_SEARCH'		=> (!$config['load_search']) ? 0 : (isset($auth) ? ($auth->acl_get('u_search') && $auth->acl_getf_global('f_search')) : 1),
		'S_DISPLAY_PM'			=> ($config['allow_privmsg'] && !empty($user->data['is_registered']) && ($auth->acl_get('u_readpm') || $auth->acl_get('u_sendpm'))) ? true : false,
		'S_DISPLAY_MEMBERLIST'	=> (isset($auth)) ? $auth->acl_get('u_viewprofile') : 0,
		'S_NEW_PM'				=> ($s_privmsg_new) ? 1 : 0,
		'S_REGISTER_ENABLED'	=> ($config['require_activation'] != USER_ACTIVATION_DISABLE) ? true : false,
		'S_FORUM_ID'			=> $forum_id,
		'S_TOPIC_ID'			=> $topic_id,

		'S_LOGIN_ACTION'		=> ((!defined('ADMIN_START')) ? append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=login') : append_sid("index.$phpEx", false, true, $user->session_id)),
		'S_LOGIN_REDIRECT'		=> build_hidden_fields(array('redirect' => str_replace('&amp;', '&', build_url()))),

		'S_ENABLE_FEEDS'			=> ($config['feed_enable']) ? true : false,
		'S_ENABLE_FEEDS_OVERALL'	=> ($config['feed_overall']) ? true : false,
		'S_ENABLE_FEEDS_FORUMS'		=> ($config['feed_overall_forums']) ? true : false,
		'S_ENABLE_FEEDS_TOPICS'		=> ($config['feed_topics_new']) ? true : false,
		'S_ENABLE_FEEDS_TOPICS_ACTIVE'	=> ($config['feed_topics_active']) ? true : false,
		'S_ENABLE_FEEDS_NEWS'		=> ($s_feed_news) ? true : false,

		'T_THEME_PATH'			=> "{$web_path}styles/" . $user->theme['theme_path'] . '/theme',
		'T_TEMPLATE_PATH'		=> "{$web_path}styles/" . $user->theme['template_path'] . '/template',
		'T_SUPER_TEMPLATE_PATH'	=> (isset($user->theme['template_inherit_path']) && $user->theme['template_inherit_path']) ? "{$web_path}styles/" . $user->theme['template_inherit_path'] . '/template' : "{$web_path}styles/" . $user->theme['template_path'] . '/template',

		'T_IMAGES_PATH'			=> "{$web_path}images/",
		'T_SMILIES_PATH'		=> "{$web_path}{$config['smilies_path']}/",
		'T_AVATAR_PATH'			=> "{$web_path}{$config['avatar_path']}/",
		'T_AVATAR_GALLERY_PATH'	=> "{$web_path}{$config['avatar_gallery_path']}/",
		'T_ICONS_PATH'			=> "{$web_path}{$config['icons_path']}/",
		'T_RANKS_PATH'			=> "{$web_path}{$config['ranks_path']}/",
		'T_UPLOAD_PATH'			=> "{$web_path}{$config['upload_path']}/",
		'T_STYLESHEET_LINK'		=> (!$user->theme['theme_storedb']) ? "{$web_path}styles/" . $user->theme['theme_path'] . '/theme/stylesheet.css' : append_sid("{$phpbb_root_path}style.$phpEx", 'id=' . $user->theme['style_id'] . '&amp;lang=' . $user->data['user_lang'], true, $user->session_id),
		'T_STYLESHEET_NAME'		=> $user->theme['theme_name'],

		'T_THEME_NAME'			=> $user->theme['theme_path'],
		'T_TEMPLATE_NAME'		=> $user->theme['template_path'],
		'T_SUPER_TEMPLATE_NAME'	=> (isset($user->theme['template_inherit_path']) && $user->theme['template_inherit_path']) ? $user->theme['template_inherit_path'] : $user->theme['template_path'],

		'T_IMAGES'				=> 'images',
		'T_SMILIES'				=> $config['smilies_path'],
		'T_AVATAR'				=> $config['avatar_path'],
		'T_AVATAR_GALLERY'		=> $config['avatar_gallery_path'],
		'T_ICONS'				=> $config['icons_path'],
		'T_RANKS'				=> $config['ranks_path'],
		'T_UPLOAD'				=> $config['upload_path'],

		'SITE_LOGO_IMG'			=> $user->img('site_logo'),

		'A_COOKIE_SETTINGS'		=> addslashes('; path=' . $config['cookie_path'] . ((!$config['cookie_domain'] || $config['cookie_domain'] == 'localhost' || $config['cookie_domain'] == '127.0.0.1') ? '' : '; domain=' . $config['cookie_domain']) . ((!$config['cookie_secure']) ? '' : '; secure')),
		'AVATAR_SMALL_IMG'		=> get_user_avatar($user->data['user_avatar'], $user->data['user_avatar_type'], '35', '35'),
		'P_USERNAME'			=> (STARGATE) ? $user->data['username'] : '',
		'P_USERNAME_FULL'		=> (STARGATE) ? get_username_string('full', $user->data['user_id'], $user->data['username'], $user->data['user_colour']) : '',
	));
*/

	// The following assigns all _common_ variables that may be used at any point in a template.
	$template->assign_vars(array(
		'SITENAME'                      => $config['sitename'],
		'SITE_DESCRIPTION'              => $config['site_desc'],
		'PAGE_TITLE'                    => $page_title,
		'SCRIPT_NAME'                   => str_replace('.' . $phpEx, '', $user->page['page_name']),
		'LAST_VISIT_DATE'               => sprintf($user->lang['YOU_LAST_VISIT'], $s_last_visit),
		'LAST_VISIT_YOU'				=> $s_last_visit,
		'CURRENT_TIME'					=> sprintf($user->lang['CURRENT_TIME'], $user->format_date(time(), false, true)),
		'TOTAL_USERS_ONLINE'			=> $l_online_users,
		'LOGGED_IN_USER_LIST'			=> $online_userlist,
		'RECORD_USERS'					=> $l_online_record,
		'PRIVATE_MESSAGE_COUNT'			=> (!empty($user->data['user_unread_privmsg'])) ? $user->data['user_unread_privmsg'] : 0,
		'HIDDEN_FIELDS_FOR_JUMPBOX'     => $hidden_fields_for_jumpbox,

		'UNREAD_NOTIFICATIONS_COUNT'	=> ($notifications !== false) ? $notifications['unread_count'] : '',
		'NOTIFICATIONS_COUNT'			=> ($notifications !== false) ? $notifications['unread_count'] : '',
		'U_VIEW_ALL_NOTIFICATIONS'		=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=ucp_notifications'),
		'U_MARK_ALL_NOTIFICATIONS'		=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=ucp_notifications&amp;mode=notification_list&amp;mark=all&amp;token=' . $notification_mark_hash),
		'U_NOTIFICATION_SETTINGS'		=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=ucp_notifications&amp;mode=notification_options'),
		'S_NOTIFICATIONS_DISPLAY'		=> $config['load_notifications'],

		'S_USER_NEW_PRIVMSG'			=> $user->data['user_new_privmsg'],
		'S_USER_UNREAD_PRIVMSG'			=> $user->data['user_unread_privmsg'],
		'S_USER_NEW'					=> $user->data['user_new'],

		'SID'				=> $SID,
		'_SID'				=> $_SID,
		'SESSION_ID'		=> $user->session_id,
		'ROOT_PATH'			=> $web_path,
		'BOARD_URL'			=> $board_url,

		'L_LOGIN_LOGOUT'	=> $l_login_logout,
		'L_INDEX'			=> ($config['board_index_text'] !== '') ? $config['board_index_text'] : $user->lang['FORUM_INDEX'],
		'L_SITE_HOME'		=> ($config['site_home_text'] !== '') ? $config['site_home_text'] : $user->lang['HOME'],
		'L_ONLINE_EXPLAIN'	=> $l_online_time,

		'U_PRIVATEMSGS'			=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=pm&amp;folder=inbox'),
		'U_RETURN_INBOX'		=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=pm&amp;folder=inbox'),
		'U_MEMBERLIST'			=> append_sid("{$phpbb_root_path}memberlist.$phpEx"),
		'U_VIEWONLINE'			=> ($auth->acl_gets('u_viewprofile', 'a_user', 'a_useradd', 'a_userdel')) ? append_sid("{$phpbb_root_path}viewonline.$phpEx") : '',
		'U_LOGIN_LOGOUT'		=> $u_login_logout,
		'U_INDEX'				=> append_sid("{$phpbb_root_path}index.$phpEx"),
		'U_SEARCH'				=> append_sid("{$phpbb_root_path}search.$phpEx"),
		'U_SITE_HOME'			=> $config['site_home_url'],
		'U_REGISTER'			=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=register'),
		'U_PROFILE'				=> append_sid("{$phpbb_root_path}ucp.$phpEx"),
		'U_MODCP'				=> append_sid("{$phpbb_root_path}mcp.$phpEx", false, true, $user->session_id),
		'U_FAQ'					=> append_sid("{$phpbb_root_path}faq.$phpEx"),
		'U_SEARCH_SELF'			=> append_sid("{$phpbb_root_path}search.$phpEx", 'search_id=egosearch'),
		'U_SEARCH_NEW'			=> append_sid("{$phpbb_root_path}search.$phpEx", 'search_id=newposts'),
		'U_SEARCH_UNANSWERED'	=> append_sid("{$phpbb_root_path}search.$phpEx", 'search_id=unanswered'),
		'U_SEARCH_UNREAD'		=> append_sid("{$phpbb_root_path}search.$phpEx", 'search_id=unreadposts'),
		'U_SEARCH_ACTIVE_TOPICS'=> append_sid("{$phpbb_root_path}search.$phpEx", 'search_id=active_topics'),
		'U_DELETE_COOKIES'		=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=delete_cookies'),
		'U_TEAM'				=> ($user->data['user_id'] != ANONYMOUS && !$auth->acl_get('u_viewprofile')) ? '' : append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=leaders'),
		'U_TERMS_USE'			=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=terms'),
		'U_PRIVACY'				=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=privacy'),
		'U_RESTORE_PERMISSIONS'	=> ($user->data['user_perm_from'] && $auth->acl_get('a_switchperm')) ? append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=restore_perm') : '',
		'U_FEED'				=> generate_board_url() . "/feed.$phpEx",

		'S_USER_LOGGED_IN'		=> ($user->data['user_id'] != ANONYMOUS) ? true : false,
		'S_AUTOLOGIN_ENABLED'	=> ($config['allow_autologin']) ? true : false,
		'S_BOARD_DISABLED'		=> ($config['board_disable']) ? true : false,
		'S_REGISTERED_USER'		=> (!empty($user->data['is_registered'])) ? true : false,
		'S_IS_BOT'				=> (!empty($user->data['is_bot'])) ? true : false,
		'S_USER_LANG'			=> $user_lang,
		'S_USER_BROWSER'		=> (isset($user->data['session_browser'])) ? $user->data['session_browser'] : $user->lang['UNKNOWN_BROWSER'],
		'S_USERNAME'			=> $user->data['username'],
		'S_CONTENT_DIRECTION'	=> $user->lang['DIRECTION'],
		'S_CONTENT_FLOW_BEGIN'	=> ($user->lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
		'S_CONTENT_FLOW_END'	=> ($user->lang['DIRECTION'] == 'ltr') ? 'right' : 'left',
		'S_CONTENT_ENCODING'	=> 'UTF-8',
		'S_TIMEZONE'			=> sprintf($user->lang['ALL_TIMES'], $timezone_offset, $timezone_name),
		'S_DISPLAY_ONLINE_LIST'	=> ($l_online_time) ? 1 : 0,
		'S_DISPLAY_SEARCH'		=> (!$config['load_search']) ? 0 : (isset($auth) ? ($auth->acl_get('u_search') && $auth->acl_getf_global('f_search')) : 1),
		'S_DISPLAY_PM'			=> ($config['allow_privmsg'] && !empty($user->data['is_registered']) && ($auth->acl_get('u_readpm') || $auth->acl_get('u_sendpm'))) ? true : false,
		'S_DISPLAY_MEMBERLIST'	=> (isset($auth)) ? $auth->acl_get('u_viewprofile') : 0,
		'S_NEW_PM'				=> ($s_privmsg_new) ? 1 : 0,
		'S_REGISTER_ENABLED'	=> ($config['require_activation'] != USER_ACTIVATION_DISABLE) ? true : false,
		'S_FORUM_ID'			=> $forum_id,
		'S_TOPIC_ID'			=> $topic_id,

		'S_LOGIN_ACTION'		=> ((!defined('ADMIN_START')) ? append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=login') : append_sid("{$phpbb_admin_path}index.$phpEx", false, true, $user->session_id)),
		'S_LOGIN_REDIRECT'		=> build_hidden_fields(array('redirect' => build_url())),

		'S_ENABLE_FEEDS'			=> ($config['feed_enable']) ? true : false,
		'S_ENABLE_FEEDS_OVERALL'	=> ($config['feed_overall']) ? true : false,
		'S_ENABLE_FEEDS_FORUMS'		=> ($config['feed_overall_forums']) ? true : false,
		'S_ENABLE_FEEDS_TOPICS'		=> ($config['feed_topics_new']) ? true : false,
		'S_ENABLE_FEEDS_TOPICS_ACTIVE'	=> ($config['feed_topics_active']) ? true : false,
		'S_ENABLE_FEEDS_NEWS'		=> ($s_feed_news) ? true : false,

		'S_LOAD_UNREADS'			=> ($config['load_unreads_search'] && ($config['load_anon_lastread'] || $user->data['is_registered'])) ? true : false,

		'S_SEARCH_HIDDEN_FIELDS'	=> build_hidden_fields($s_search_hidden_fields),

		'T_ASSETS_VERSION'		=> $config['assets_version'],
		'T_ASSETS_PATH'			=> "{$web_path}assets",
		'T_THEME_PATH'			=> "{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/theme',
		'T_TEMPLATE_PATH'		=> "{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/template',
		'T_SUPER_TEMPLATE_PATH'	=> "{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/template',
		'T_IMAGES_PATH'			=> "{$web_path}images/",
		'T_SMILIES_PATH'		=> "{$web_path}{$config['smilies_path']}/",
		'T_AVATAR_PATH'			=> "{$web_path}{$config['avatar_path']}/",
		'T_AVATAR_GALLERY_PATH'	=> "{$web_path}{$config['avatar_gallery_path']}/",
		'T_ICONS_PATH'			=> "{$web_path}{$config['icons_path']}/",
		'T_RANKS_PATH'			=> "{$web_path}{$config['ranks_path']}/",
		'T_UPLOAD_PATH'			=> "{$web_path}{$config['upload_path']}/",
		'T_STYLESHEET_LINK'		=> "{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/theme/stylesheet.css?assets_version=' . $config['assets_version'],
		'T_STYLESHEET_LANG_LINK'    => "{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/theme/' . $user->lang_name . '/stylesheet.css?assets_version=' . $config['assets_version'],
		'T_JQUERY_LINK'			=> !empty($config['allow_cdn']) && !empty($config['load_jquery_url']) ? $config['load_jquery_url'] : "{$web_path}assets/javascript/jquery.js?assets_version=" . $config['assets_version'],
		'S_ALLOW_CDN'			=> !empty($config['allow_cdn']),

		'T_THEME_NAME'			=> rawurlencode($user->style['style_path']),
		'T_THEME_LANG_NAME'		=> $user->data['user_lang'],
		'T_TEMPLATE_NAME'		=> $user->style['style_path'],
		'T_SUPER_TEMPLATE_NAME'	=> rawurlencode((isset($user->style['style_parent_tree']) && $user->style['style_parent_tree']) ? $user->style['style_parent_tree'] : $user->style['style_path']),
		'T_IMAGES'				=> 'images',
		'T_SMILIES'				=> $config['smilies_path'],
		'T_AVATAR'				=> $config['avatar_path'],
		'T_AVATAR_GALLERY'		=> $config['avatar_gallery_path'],
		'T_ICONS'				=> $config['icons_path'],
		'T_RANKS'				=> $config['ranks_path'],
		'T_UPLOAD'				=> $config['upload_path'],

		'SITE_LOGO_IMG'			=> $user->img('site_logo'),
	));



}
