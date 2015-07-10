<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbbireland\portal\acp;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

global $request, $phpEx, $k_config, $template;

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_members_avatars');

if ($request->is_set_post('submit'))
{
	$k_ma_avatar_max_width       = $request->variable('k_ma_avatar_max_width', '90');
	$k_ma_columns                = $request->variable('k_ma_columns', '5');
	$k_ma_display_logged_in_only = $request->variable('k_ma_display_logged_in_only', '0');
	$k_ma_max_avatars            = $request->variable('k_ma_max_avatars', '0');
	$k_ma_rows                   = $request->variable('k_ma_rows', '1');
	$k_ma_user_active            = $request->variable('k_ma_user_active', '0');
	$k_ma_user_has_posted        = $request->variable('k_ma_user_has_posted', '0');

	$sgp_functions_admin->sgp_acp_set_config('k_ma_avatar_max_width', $k_ma_avatar_max_width);
	$sgp_functions_admin->sgp_acp_set_config('k_ma_columns', $k_ma_columns);
	$sgp_functions_admin->sgp_acp_set_config('k_ma_display_logged_in_only', $k_ma_display_logged_in_only);
	$sgp_functions_admin->sgp_acp_set_config('k_ma_max_avatars', $k_ma_max_avatars);
	$sgp_functions_admin->sgp_acp_set_config('k_ma_rows', $k_ma_rows);
	$sgp_functions_admin->sgp_acp_set_config('k_ma_user_active', $k_ma_user_active);
	$sgp_functions_admin->sgp_acp_set_config('k_ma_user_has_posted', $k_ma_user_has_posted);

}
else
{
	$k_ma_avatar_max_width       = $k_config['k_ma_avatar_max_width'];
	$k_ma_columns                = $k_config['k_ma_columns'];
	$k_ma_display_logged_in_only = $k_config['k_ma_display_logged_in_only'];
	$k_ma_max_avatars            = $k_config['k_ma_max_avatars'];
	$k_ma_rows                   = $k_config['k_ma_rows'];
	$k_ma_user_active            = $k_config['k_ma_user_active'];
	$k_ma_user_has_posted        = $k_config['k_ma_user_has_posted'];
}

$template->assign_vars(array(
	'S_K_MA_AVATAR_MAX_WIDTH'       => $k_ma_avatar_max_width,
	'S_K_MA_COLUMNS'                => $k_ma_columns,
	'S_K_MA_DISPLAY_LOGGED_IN_ONLY' => $k_ma_display_logged_in_only,
	'S_K_MA_MAX_AVATARS'            => $k_ma_max_avatars,
	'S_K_MA_ROWS'                   => $k_ma_rows,
	'S_K_MA_USER_ACTIVE'            => $k_ma_user_active,
	'S_K_MA_USER_HAS_POSTED'        => $k_ma_user_has_posted,
));
