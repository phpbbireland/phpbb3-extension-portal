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

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_the_team');

$k_teams                   = $k_config['k_teams'];
$k_teams_display_this_many = $k_config['k_teams_display_this_many'];
$k_teampage_memberships    = $k_config['k_teampage_memberships'];
$k_teams_sort              = $k_config['k_teams_sort'];

if ($request->is_set_post('submit'))
{
	$k_teams                   = $request->variable('k_teams', '');
	$k_teams_display_this_many = $request->variable('k_teams_display_this_many', 1);
	$k_teampage_memberships    = $request->variable('k_teampage_memberships', 0);
	$k_teams_sort              = $request->variable('k_teams_sort', '');

	$sgp_functions_admin->sgp_acp_set_config('k_teams', $k_teams);
	$sgp_functions_admin->sgp_acp_set_config('k_teams_display_this_many', $k_teams_display_this_many);
	$sgp_functions_admin->sgp_acp_set_config('k_teampage_memberships', $k_teampage_memberships);
	$sgp_functions_admin->sgp_acp_set_config('k_teams_sort', $k_teams_sort);
}

$template->assign_vars(array(
	'S_K_TEAMS'                   => $k_teams,
	'S_K_TEAMS_DISPLAY_THIS_MANY' => $k_teams_display_this_many,
	'S_K_TEAMPAGE_MEMBERSHIPS'    => $k_teampage_memberships,
	'S_K_TEAMS_SORT'              => $k_teams_sort,
));
