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

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_acronym');

$k_poll_wide = $k_config['k_poll_wide'];

if ($request->is_set_post('submit'))
{
	$k_poll_wide = $request->variable('k_poll_wide', 0);
	$sgp_functions_admin->sgp_acp_set_config('k_poll_wide', $k_poll_wide);
}

$template->assign_var('S_K_POLL_WIDE', $k_poll_wide);
