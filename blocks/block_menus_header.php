<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);

$queries = $cached_queries = 0;

$user->add_lang('portal/kiss_block_variables');

if (!class_exists('sgp_functions'))
{
	include_once($phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_functions' . '.' . $phpEx);
}

$sgp_functions = new \phpbbireland\portal\includes\sgp_functions();

global $db, $user, $_SID, $_EXTRA_URL, $k_groups, $k_blocks;

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_menus.html')
	{
		$block_cache_time	= $blk['block_cache_time'];
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);

$j = 0;
$is_sub_heading = false;
$portal_menus = array();
$my_names = array();

$sql = "SELECT * FROM ". K_MENUS_TABLE . "
	WHERE menu_type = " . HEAD_MENUS . "
	ORDER BY ndx ASC";

if (!$result = $db->sql_query($sql, $block_cache_time))
{
	if (!$result = $db->sql_query($sql))
	{
		trigger_error($user->lang['ERROR_PORTAL_MENUS']);
	}
}

$portal_menus = array();

while ($row = $db->sql_fetchrow($result))
{
	$portal_menus[] = $row;
}
$db->sql_freeresult($result);

if (!function_exists('group_memberships'))
{
	include($phpbb_root_path . 'includes/functions_user.'. $phpEx);
}
$memberships = array();
$memberships = group_memberships(false, $user->data['user_id'], false);


$menu_count = count($portal_menus);

for ($i = 0; $i < $menu_count; $i++)
{
	$u_id = ''; 														// initiate our var user u_id, if we need to pass user id
	$isamp = '';														// initiate our var isamp, if we need to use it

	$menu_view_groups = $portal_menus[$i]['view_groups'];
	$menu_item_view_all = $portal_menus[$i]['view_all'];

	$process_menu_item = false;

	// skip process if everyone can view this menus //
	if ($menu_item_view_all == 0)
	{
		$grps = explode(",", $menu_view_groups);

		if ($memberships)
		{
			foreach ($memberships as $member)
			{
				for ($j = 0; $j < count($grps); $j++)
				{
					if ($grps[$j] == $member['group_id'])
					{
						$process_menu_item = true;
					}
				}
			}
		}
	}
	else
	{
		$process_menu_item = true;
	}

	if ($portal_menus[$i]['append_uid'] == 1)
	{
		$isamp = '&amp';
		$u_id = $user->data['user_id'];
	}
	else
	{
		$u_id = '';
		$isamp = '';
	}

	if ($process_menu_item && $portal_menus[$i]['sub_heading'])
	{
		$j++;
	}


	if ($process_menu_item)
	{
		$name = strtoupper($portal_menus[$i]['name']);														// convert to uppercase //
		$tmp_name = str_replace(' ','_', $name);															// replace spaces with underscore //
		$name = (!empty($user->lang[$tmp_name])) ? $user->lang[$tmp_name] : $portal_menus[$i]['name'];		// get language equivalent //

		if (strstr($portal_menus[$i]['link_to'], 'http'))
		{
			$link = ($portal_menus[$i]['link_to']) ? $portal_menus[$i]['link_to'] : '';
		}
		else
		{
			$link = ($portal_menus[$i]['link_to']) ? append_sid("{$phpbb_root_path}" . $portal_menus[$i]['link_to'] . $u_id) : '';
		}

		$is_sub_heading = ($portal_menus[$i]['sub_heading']) ? true : false;

		switch ($portal_menus[$i]['extern'])
		{
			case 1:
				//$link_option = ' target="_blank"'; // not xhtml
				$link_option = '';
			break;

			case 2:
				$link_option = ' onclick="window.open(this.href); return false;"';
			break;

			default:
				$link_option = '';
			break;
		}

		$template->assign_block_vars('header_menus_row', array(
			'ID'					=> $portal_menus[$i]['m_id'],
			'EXTERN'				=> $link_option,
			'PORTAL_MENU_HEAD_NAME'	=> ($is_sub_heading) ? $name : '',
			'PORTAL_MENU_NAME' 		=> $name,
			'U_PORTAL_MENU_LINK' 	=> ($portal_menus[$i]['sub_heading']) ? '' : $link,
			'PORTAL_MENU_ICON'		=> ($portal_menus[$i]['menu_icon']) ? '<img src="' . $phpbb_root_path . 'images/block_images/menu/' . $portal_menus[$i]['menu_icon'] . '" height="16" width="16" alt="" />' : '<img src="' . $phpbb_root_path . 'images/block_images/menu/spacer.gif" height="15px" width="15px" alt="" />',
			'S_SOFT_HR'				=> $portal_menus[$i]['soft_hr'],
			'S_SUB_HEADING' 		=> ($portal_menus[$i]['sub_heading']) ? true : false,
		));
	}
}

$template->assign_vars(array(
	'DEBUG_QUERIES'		=> (defined('DEBUG_QUERIES')) ? DEBUG_QUERIES : false,
	'S_USER_LOGGED_IN'	=> ($user->data['user_id'] != ANONYMOUS) ? true : false,
	'U_INDEX'			=> append_sid("{$phpbb_root_path}index.$phpEx"),
	'U_PORTAL'			=> append_sid("{$phpbb_root_path}portal.$phpEx"),
	'MENUS_DEBUG'		=> sprintf($user->lang['PORTAL_DEBUG_QUERIES'], ($queries) ? $queries : '0', ($cached_queries) ? $cached_queries : '0', ($total_queries) ? $total_queries : '0'),
));
