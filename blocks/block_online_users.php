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

global $auth, $user, $config, $template;

$this->config = $config;
$this->template = $template;


$display_online_list = true;
$item_id = 0;
$item = 'forum';


// Get users online list ... if required
$l_online_users = $online_userlist = $l_online_record = $l_online_time = '';

if ($this->config['load_online'] && $this->config['load_online_time'] && $display_online_list)
{
	/**
	* Load online data:
	* For obtaining another session column use $item and $item_id in the function-parameter, whereby the column is session_{$item}_id.
	*/
	$item_id = max($item_id, 0);

	$online_users = obtain_users_online($item_id, $item);
	$user_online_strings = obtain_users_online_string($online_users, $item_id, $item);

	$l_online_users = $user_online_strings['l_online_users'];
	$online_userlist = $user_online_strings['online_userlist'];
	$total_online_users = $online_users['total_online'];

	if ($total_online_users > $this->config['record_online_users'])
	{
		$config->set('record_online_users', $total_online_users, true);
		$config->set('record_online_date', time(), true);
	}

	$l_online_record = $user->lang('RECORD_ONLINE_USERS', (int) $this->config['record_online_users'], $user->format_date($this->config['record_online_date'], false, true));

	$l_online_time = $user->lang('VIEW_ONLINE_TIMES', (int) $this->config['load_online_time']);

	$this->template->assign_vars(array(
		'TOTAL_USERS_ONLINE'	=> $l_online_users,
		'LOGGED_IN_USER_LIST'	=> $online_userlist,
		'RECORD_USERS'			=> $l_online_record,

		'L_ONLINE_EXPLAIN'		=> $l_online_time,

		'U_MEMBERLIST'			=> append_sid("{$phpbb_root_path}memberlist.$phpEx"),
		'U_VIEWONLINE'			=> ($auth->acl_gets('u_viewprofile', 'a_user', 'a_useradd', 'a_userdel')) ? append_sid("{$phpbb_root_path}viewonline.$phpEx") : '',

		'S_DISPLAY_ONLINE_LIST'	=> ($l_online_time) ? 1 : 0,
		'S_DISPLAY_SEARCH'		=> (!$this->config['load_search']) ? 0 : (isset($auth) ? ($auth->acl_get('u_search') && $auth->acl_getf_global('f_search')) : 1),
		'S_DISPLAY_PM'			=> ($this->config['allow_privmsg'] && !empty($user->data['is_registered']) && ($auth->acl_get('u_readpm') || $auth->acl_get('u_sendpm'))) ? true : false,
		'S_DISPLAY_MEMBERLIST'	=> (isset($auth)) ? $auth->acl_get('u_viewprofile') : 0,

	));
}
