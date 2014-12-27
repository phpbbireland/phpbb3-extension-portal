<?php

namespace phpbbireland\portal\acp;

global $request, $phpEx, $k_config, $template;

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_last_online');

$k_last_online_max = $k_config['k_last_online_max'];

if ($request->is_set_post('submit'))
{
	$k_last_online_max = $request->variable('k_last_online_max', 5);
	$sgp_functions_admin->sgp_acp_set_config('k_last_online_max', $k_last_online_max);
}

$template->assign_vars(array(
	'S_K_LAST_ONLINE_MAX' => $k_last_online_max
));
