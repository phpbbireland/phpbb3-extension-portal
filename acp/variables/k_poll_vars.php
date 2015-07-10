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

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_poll');

if ($request->is_set_post('submit'))
{
	$k_poll_wide    = $request->variable('k_poll_wide', 0);
	$k_poll_post_id = $request->variable('k_poll_post_id', 0, true);
	$k_poll_view    = $request->variable('k_poll_view', 0, true);

	$sgp_functions_admin->sgp_acp_set_config('k_poll_post_id', $k_poll_post_id);
	$sgp_functions_admin->sgp_acp_set_config('k_poll_view', $k_poll_view);
	$sgp_functions_admin->sgp_acp_set_config('k_poll_wide', $k_poll_wide);
}
else
{
	$k_poll_wide    = $k_config['k_poll_wide'];
	$k_poll_post_id = $k_config['k_poll_post_id'];
	$k_poll_view    = $k_config['k_poll_view'];
}

$template->assign_vars(array(
	'S_K_POLL_WIDE'    => $k_poll_wide,
	'S_K_POLL_POST_ID' => $k_poll_post_id,
	'S_K_POLL_VIEW'    => $k_poll_view,
));
