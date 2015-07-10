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

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_top_topics');

if ($request->is_set_post('submit'))
{
	$k_top_topics_days = $request->variable('k_top_topics_days', 7);
	$k_top_topics_max  = $request->variable('k_top_topics_max', 5);

	$sgp_functions_admin->sgp_acp_set_config('k_top_topics_max', $k_top_topics_max);
	$sgp_functions_admin->sgp_acp_set_config('k_top_topics_days', $k_top_topics_days);
}
else
{
	$k_top_topics_days = $k_config['k_top_topics_days'];
	$k_top_topics_max  = $k_config['k_top_topics_max'];
}

$template->assign_vars(array(
	'S_K_TOP_TOPICS_DAYS' => $k_top_topics_days,
	'S_K_TOP_TOPICS_MAX'  => $k_top_topics_max,
));
