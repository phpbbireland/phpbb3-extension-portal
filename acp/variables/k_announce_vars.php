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

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_announcements');

if ($request->is_set_post('submit'))
{
	$k_announce_allow           = $request->variable('k_announce_allow', 1);
	$k_announce_type            = $request->variable('k_announce_type', '');
	$k_announce_item_max_length = $request->variable('k_announce_item_max_length', 0);
	$k_announce_to_display      = $request->variable('k_announce_to_display', 5);

	$sgp_functions_admin->sgp_acp_set_config('k_announce_allow', $k_announce_allow);
	$sgp_functions_admin->sgp_acp_set_config('k_announce_type', $k_announce_type);
	$sgp_functions_admin->sgp_acp_set_config('k_announce_item_max_length', $k_announce_item_max_length);
	$sgp_functions_admin->sgp_acp_set_config('k_announce_to_display', $k_announce_to_display);
}
else
{
	$k_announce_allow           = $k_config['k_announce_allow'];
	$k_announce_item_max_length = $k_config['k_announce_item_max_length'];
	$k_announce_to_display      = $k_config['k_announce_to_display'];
	$k_announce_type            = $k_config['k_announce_type'];
}

$template->assign_vars(array(
	'S_K_ANNOUNCE_ALLOW'           => $k_announce_allow,
	'S_K_ANNOUNCE_ITEM_MAX_LENGTH' => $k_announce_item_max_length,
	'S_K_ANNOUNCE_TO_DISPLAY'      => $k_announce_to_display,
	'S_K_ANNOUNCE_TYPE'            => $k_announce_type,
));
