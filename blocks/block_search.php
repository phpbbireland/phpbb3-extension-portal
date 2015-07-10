<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('IN_PHPBB'))
{
   exit;
}

global $php_root_path, $user, $request, $config, $k_config, $k_blocks, $phpbb_root_path;
global $portal_config, $board_config;

//$phpEx = substr(strrchr(__FILE__, '.'), 1);

$this->user = $user;
$this->config = $config;

$this->user->add_lang_ext('phpbbireland/portal', 'kiss_search');

$submit = $request->variable('submit', false);
$keywords = $request->variable('keywords', '', true);

$allow_search = true;

$queries = $cached_queries = 0;

if (!$auth->acl_get('u_search') || !$auth->acl_getf_global('f_search') || !$this->config['load_search'])
{
	$allow_search = false;

	if ($this->user->data['user_id'] == ANONYMOUS)
	{
		return;
	}
}

$this->template->assign_vars(array(
	'S_SEARCH'			=> $allow_search,
	'L_SEARCH_ADV' 		=> $this->user->lang['SEARCH_ADV'],
	'L_SEARCH_OPTION' 	=> (!empty($portal_config['search_option_text'])) ? $portal_config['search_option_text'] : $board_config['sitename'],
	'SITE_NAME'         => $this->config['sitename'],
	'S_USER_LOGGED_IN'	=> ($this->user->data['user_id'] != ANONYMOUS) ? true : false,
	'U_INDEX'			=> append_sid("{$phpbb_root_path}index.$phpEx"),
	'U_PORTAL'			=> append_sid("{$phpbb_root_path}portal.$phpEx"),
	'U_SEARCH'			=> append_sid("{$phpbb_root_path}search.$phpEx", 'keywords=' . urlencode($keywords)),
	'U_SEARCH_BOOKMARKS'=> ($this->user->data['user_id'] != ANONYMOUS) ? append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=main&mode=bookmarks') : '',
));
