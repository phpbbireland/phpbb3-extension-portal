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

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_recent_topics');

$k_recent_topics_to_display     = $k_config['k_recent_topics_to_display'];
$k_recent_topics_per_forum      = $k_config['k_recent_topics_per_forum'];
$k_recent_topics_search_exclude = $k_config['k_recent_topics_search_exclude'];
$k_recent_search_days           = $k_config['k_recent_search_days'];

if ($request->is_set_post('submit'))
{
	$k_recent_topics_to_display      = $request->variable('k_recent_topics_to_display', 10);
	$k_recent_topics_per_forum       = $request->variable('k_recent_topics_per_forum', 5);
	$k_recent_topics_search_exclude  = $request->variable('k_recent_topics_search_exclude', '');
	$k_recent_search_days            = $request->variable('k_recent_search_days', 7);

	$sgp_functions_admin->sgp_acp_set_config('k_recent_topics_to_display', $k_recent_topics_to_display);
	$sgp_functions_admin->sgp_acp_set_config('k_recent_topics_per_forum', $k_recent_topics_per_forum);
	$sgp_functions_admin->sgp_acp_set_config('k_recent_topics_search_exclude', $k_recent_topics_search_exclude);
	$sgp_functions_admin->sgp_acp_set_config('k_recent_search_days', $k_recent_search_days);
}

$template->assign_vars(array(
	'S_K_RECENT_TOPICS_TO_DISPLAY'     => $k_recent_topics_to_display,
	'S_K_RECENT_TOPICS_PER_FORUM'      => $k_recent_topics_per_forum,
	'S_K_RECENT_TOPICS_SEARCH_EXCLUDE' => $k_recent_topics_search_exclude,
	'S_K_RECENT_SEARCH_DAYS'           => $k_recent_search_days
));
