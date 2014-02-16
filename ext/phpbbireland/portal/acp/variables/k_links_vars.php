<?php

namespace phpbbireland\portal\acp;

global $request, $phpEx, $k_config, $template;

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_links');

$k_links_forum_id   = $k_config['k_links_forum_id'];
$k_links_to_display = $k_config['k_links_to_display'];

if ($request->is_set_post('submit'))
{
	$k_links_forum_id = request_var('k_links_forum_id', '');
	$k_links_to_display = request_var('k_links_to_display', 5);

	$sgp_functions_admin->sgp_acp_set_config('k_links_forum_id', $k_links_forum_id);
	$sgp_functions_admin->sgp_acp_set_config('k_links_to_display', $k_links_to_display);
}

$template->assign_vars(array(
	'S_K_LINKS_FORUM_ID'   => $k_links_forum_id,
	'S_K_LINKS_TO_DISPLAY' => $k_links_to_display,
));
