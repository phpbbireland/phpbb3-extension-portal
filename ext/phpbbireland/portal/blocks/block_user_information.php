<?php
/**
*
* @package Kiss Portal Engine
* @version $Id$
* @author  Michael O'Toole - aka michaelo
* @begin   Saturday, Jan 22, 2005
* @copyright (c) 2005-2013 phpbbireland
* @home    http://www.phpbbireland.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

global $user, $ranks, $config, $k_config, $k_blocks, $phpbb_root_path;

// initialise local variables //
$queries = $cached_queries = 0;
$rank_title = $rank_img = $rank_img_src = '';

include($this->phpbb_root_path . 'includes/functions_display.' . $this->php_ext);

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_user_information.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		break;
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);


get_user_rank($user->data['user_rank'], (($user->data['user_id'] == ANONYMOUS) ? false : $user->data['user_posts']), $rank_title, $rank_img, $rank_img_src);


$template->assign_vars(array(
	'AVATAR'				=> get_user_avatar($user->data['user_avatar'], $user->data['user_avatar_type'], $user->data['user_avatar_width'], $user->data['user_avatar_height'], 'USER_AVATAR', true),

	'WELCOME_SITE'			=> sprintf($user->lang['WELCOME_SITE'], $config['sitename']),
	'USR_RANK_TITLE'		=> $rank_title,
	'USR_RANK_IMG'			=> $rank_img,

	'USER_INFORMATION_DEBUG'	=> sprintf($user->lang['PORTAL_DEBUG_QUERIES'], ($queries) ? $queries : '0', ($cached_queries) ? $cached_queries : '0', ($total_queries) ? $total_queries : '0'),
));

