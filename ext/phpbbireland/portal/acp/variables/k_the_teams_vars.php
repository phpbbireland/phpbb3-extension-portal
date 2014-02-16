<?php

namespace phpbbireland\portal\acp;

global $request, $phpEx, $k_config, $template;

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_the_team');

$k_teams                   = $k_config['k_teams'];
$k_teams_display_this_many = $k_config['k_teams_display_this_many'];
$k_teampage_memberships    = $k_config['k_teampage_memberships'];
$k_teams_sort              = $k_config['k_teams_sort'];

if ($request->is_set_post('submit'))
{
	$k_teams                   = request_var('k_teams', '');
	$k_teams_display_this_many = request_var('k_teams_display_this_many', 1);
	$k_teampage_memberships    = request_var('k_teampage_memberships', 0);
	$k_teams_sort              = request_var('k_teams_sort', '');

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
