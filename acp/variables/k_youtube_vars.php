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

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_youtube');

$k_yourtube_auto       = $k_config['k_yourtube_auto'];
$k_yourtube_link       = $k_config['k_yourtube_link'];
$k_yourtube_link_limit = $k_config['k_yourtube_link_limit'];

if ($request->is_set_post('submit'))
{
	$k_yourtube_auto = $request->variable('k_yourtube_auto', 1);
	$k_yourtube_link = $request->variable('k_yourtube_link', '');
	$k_yourtube_link_limit = $request->variable('k_yourtube_link_limit', 5);

	$sgp_functions_admin->sgp_acp_set_config('k_yourtube_auto', $k_yourtube_auto);
	$sgp_functions_admin->sgp_acp_set_config('k_yourtube_link', $k_yourtube_link);
	$sgp_functions_admin->sgp_acp_set_config('k_yourtube_link_limit', $k_yourtube_link_limit);
}

$template->assign_vars(array(
	'S_K_YOURTUBE_AUTO'       => $k_yourtube_auto,
	'S_K_YOURTUBE_LINK'       => $k_yourtube_link,
	'S_K_YOURTUBE_LINK_LIMIT' => $k_yourtube_link_limit
));
