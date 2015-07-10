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

global $k_config, $k_blocks, $template, $user;

$this->template = $template;
$this->user = $user;

$total_queries = $queries = $cached_queries = 0;
$rank_title = $rank_img = $rank_img_src = '';

if (!function_exists('get_user_rank'))
{
	include($this->phpbb_root_path . 'includes/functions_display.' . $this->php_ext);
}

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_user_information.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		break;
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);

get_user_rank($this->user->data['user_rank'], (($this->user->data['user_id'] == ANONYMOUS) ? false : $this->user->data['user_posts']), $rank_title, $rank_img, $rank_img_src);

// Generate logged in/logged out status
if ($this->user->data['user_id'] != ANONYMOUS)
{
	$u_login_logout = append_sid("{$this->phpbb_root_path}ucp.$phpEx", 'mode=logout', true, $this->user->session_id);
	$l_login_logout = sprintf($this->user->lang['LOGOUT_USER'], $this->user->data['username']);
}
else
{
	$u_login_logout = append_sid("{$this->phpbb_root_path}ucp.$phpEx", 'mode=login');
	$l_login_logout = $this->user->lang['LOGIN'];
}

$avatar_data = array(
	'avatar' => $this->user->data['user_avatar'],
	'avatar_width' => $this->user->data['user_avatar_width'],
	'avatar_height' => $this->user->data['user_avatar_height'],
	'avatar_type' => $this->user->data['user_avatar_type'],
);

$this->template->assign_vars(array(
	//'AVATAR'          => get_user_avatar($this->user->data['user_avatar'], $this->user->data['user_avatar_type'], $this->user->data['user_avatar_width'], $this->user->data['user_avatar_height'], 'USER_AVATAR', true),
	'AVATAR'          => phpbb_get_avatar($avatar_data, $user->lang['USER_AVATAR'], false),
	'WELCOME_SITE'    => sprintf($this->user->lang['WELCOME_SITE'], $this->config['sitename']),
	'USR_RANK_TITLE'  => $rank_title,
	'USR_RANK_IMG'    => $rank_img,
	'U_LOGIN_LOGOUT'  => $u_login_logout,
	'L_LOGIN_LOGOUT'  => $l_login_logout,
	'S_LOGIN_ACTION'  => append_sid("{$this->phpbb_root_path}ucp.$phpEx", 'mode=login'),
));
