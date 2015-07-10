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

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_links');

$k_links_forum_id   = $k_config['k_links_forum_id'];
$k_links_to_display = $k_config['k_links_to_display'];

if ($request->is_set_post('submit'))
{
	$k_links_forum_id = $request->variable('k_links_forum_id', '');
	$k_links_to_display = $request->variable('k_links_to_display', 5);

	$sgp_functions_admin->sgp_acp_set_config('k_links_forum_id', $k_links_forum_id);
	$sgp_functions_admin->sgp_acp_set_config('k_links_to_display', $k_links_to_display);
}

$template->assign_vars(array(
	'S_K_LINKS_FORUM_ID'   => $k_links_forum_id,
	'S_K_LINKS_TO_DISPLAY' => $k_links_to_display,
));
