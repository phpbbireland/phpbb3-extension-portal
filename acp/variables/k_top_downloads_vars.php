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

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_top_downloads');

if ($request->is_set_post('submit'))
{
	$k_top_downloads_to_display     = $request->variable('k_top_downloads_to_display', 5);
	$k_top_downloads_search_exclude = $request->variable('k_top_downloads_search_exclude', '');
	$k_top_downloads_search_days    = $request->variable('k_top_downloads_search_days', 28);
	$k_top_downloads_per_forum      = $request->variable('k_top_downloads_per_forum', 5);
	$k_top_downloads_types          = $request->variable('k_top_downloads_types', 'gif,png,jpg,zip,arc');

	$sgp_functions_admin->sgp_acp_set_config('k_top_downloads_to_display', $k_top_downloads_to_display);
	$sgp_functions_admin->sgp_acp_set_config('k_top_downloads_search_exclude', $k_top_downloads_search_exclude);
	$sgp_functions_admin->sgp_acp_set_config('k_top_downloads_search_days', $k_top_downloads_search_days);
	$sgp_functions_admin->sgp_acp_set_config('k_top_downloads_per_forum', $k_top_downloads_per_forum);
	$sgp_functions_admin->sgp_acp_set_config('k_top_downloads_types', $k_top_downloads_types);
}
else
{
	$k_top_downloads_to_display      = $k_config['k_top_downloads_to_display'];
	$k_top_downloads_search_exclude  = $k_config['k_top_downloads_search_exclude'];
	$k_top_downloads_search_days     = $k_config['k_top_downloads_search_days'];
	$k_top_downloads_per_forum       = $k_config['k_top_downloads_per_forum'];
	$k_top_downloads_types           = $k_config['k_top_downloads_types'];
}

$template->assign_vars(array(
	'S_K_TOP_DOWNLOADS_TO_DISPLAY'     => $k_top_downloads_to_display,
	'S_K_TOP_DOWNLOADS_SEARCH_EXCLUDE' => $k_top_downloads_search_exclude,
	'S_K_TOP_DOWNLOADS_SEARCH_DAYS'    => $k_top_downloads_search_days,
	'S_K_TOP_DOWNLOADS_PER_FORUM'      => $k_top_downloads_per_forum,
	'S_K_TOP_DOWNLOADS_TYPES'          => $k_top_downloads_types
));
