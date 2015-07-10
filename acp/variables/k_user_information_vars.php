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

$user->add_lang_ext('phpbbireland/portal', 'blocks/k_top_downloads');

if ($request->is_set_post('submit'))
{
	$k_max_block_avatar_width  = $request->variable('k_max_block_avatar_width', 90);
	$k_max_block_avatar_height = $request->variable('k_max_block_avatar_height', 90);

	$sgp_functions_admin->sgp_acp_set_config('k_max_block_avatar_width', $k_max_block_avatar_width);
	$sgp_functions_admin->sgp_acp_set_config('k_max_block_avatar_height', $k_max_block_avatar_height);
}
else
{
	$k_max_block_avatar_width   = $k_config['k_max_block_avatar_width'];
	$k_max_block_avatar_height  = $k_config['k_max_block_avatar_height'];
}

$template->assign_vars(array(
	'S_K_MAX_BLOCK_AVATAR_WIDTH'  => $k_max_block_avatar_width,
	'S_K_MAX_BLOCK_AVATAR_HEIGHT' => $k_max_block_avatar_height,
));
