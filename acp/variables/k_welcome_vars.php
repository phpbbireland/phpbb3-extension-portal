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

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_welcome');

$k_welcome_message = $k_config['k_welcome_message'];

if ($request->is_set_post('submit'))
{
	$k_welcome_message = $request->variable('k_welcome_message', '');
	$sgp_functions_admin->sgp_acp_set_config('k_welcome_message', $k_welcome_message);
}

$template->assign_var('S_K_WELCOME_MESSAGE', $k_welcome_message);
