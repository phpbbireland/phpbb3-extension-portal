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

global $user, $ranks, $config, $k_config, $k_blocks, $phpbb_root_path;


$total_queries = $queries = $cached_queries = 0;
$rank_title = $rank_img = $rank_img_src = '';
$mod_root_path = $phpbb_root_path . 'ext/phpbbireland/portal/';
if (!function_exists('get_user_rank'))
{
	include($phpbb_root_path . 'includes/functions_display.' . $this->php_ext);
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

get_user_rank($user->data['user_rank'], (($user->data['user_id'] == ANONYMOUS) ? false : $user->data['user_posts']), $rank_title, $rank_img, $rank_img_src);

// Generate logged in/logged out status
if ($user->data['user_id'] != ANONYMOUS)
{
	$u_login_logout = append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=logout', true, $user->session_id);
	$l_login_logout = sprintf($user->lang['LOGOUT_USER'], $user->data['username']);
}
else
{
	$u_login_logout = append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=login');
	$l_login_logout = $user->lang['LOGIN'];
}

$template->assign_vars(array(
	'AVATAR'          => get_user_avatar($user->data['user_avatar'], $user->data['user_avatar_type'], $user->data['user_avatar_width'], $user->data['user_avatar_height'], 'USER_AVATAR', true),
	'WELCOME_SITE'    => sprintf($user->lang['WELCOME_SITE'], $config['sitename']),
	'USR_RANK_TITLE'  => $rank_title,
	'USR_RANK_IMG'    => $rank_img,
	'MY_ROOT_PATH'    => $mod_root_path,
	'U_LOGIN_LOGOUT'  => $u_login_logout,
	'L_LOGIN_LOGOUT'  => $l_login_logout,
));
