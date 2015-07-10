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

/**
* 30 September 2010 Mike
* Avoid reprocessing data if already available... every little helps ;)
**/

global $k_config, $phpbb_root_path, $web_path, $k_blocks, $k_groups;

// initialise variables //

$store = '';
$change = true;
$ext = '.png';
$i = 0;
$poster_image_icon = '';
$group_names = array();

$limit_reached = $team_count = 0;
$team_max_count = $k_config['k_teams_display_this_many'];
$sql_in = explode(",", $k_config['k_teams']);

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_the_team.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		break;
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);

$sort_by = isset($k_config['k_teams_sort']) ? $k_config['k_teams_sort'] : 'g.group_name';

if ($sort_by == '' || $sort_by == 'default')
{
	$sort_by = 'g.group_name';
}

if ($k_config['k_teampage_memberships'] == 0)
{
	$sql_refine = 'and u.group_id = g.group_id';
}
else
{
	$sql_refine = '';
}

$sql = 'SELECT DISTINCT u.user_id, u.group_id, u.username, u.user_colour, u.username_clean, u.user_timezone, g.group_id, g.group_name, g.group_colour, g.group_type, ug.group_id
		FROM ' . USERS_TABLE . ' u, ' . GROUPS_TABLE . ' g, ' . USER_GROUP_TABLE . ' ug
			WHERE ug.group_id = g.group_id and u.user_id = ug.user_id ' . $sql_refine . '
				AND ' . $db->sql_in_set('g.group_id', $sql_in) . '
				ORDER BY ' . $sort_by . ' ASC, u.group_id ASC, u.username_clean ASC';

$result = $db->sql_query($sql, $block_cache_time);

$mod_root_path          = $phpbb_root_path . 'ext/phpbbireland/portal/';
$user_group_image_path  = $mod_root_path . 'styles/'. $user->style['style_path'] . 'style/theme/images/teams/';
$mods_group_image_path  = $mod_root_path . 'images/teams/';


while ($row = $db->sql_fetchrow($result))
{
	$group_name = $row['group_name'];
	$which_row = strtolower($group_name);
	$which_row = str_replace(' ' , '_', $which_row);
	$group_img = strtolower($row['group_name']);
	$group_img = str_replace(' ' , '_', $group_img);

	// Use the code below to check for team images in the user style... //
	// If they don’t exist use default in ./image/teams //

	if (file_exists($user_group_image_path . $group_img . '.png'))
	{
		$g_path = $group_image_path;
		$ext = '.png';
	}
	else if (file_exists($user_group_image_path . $group_img . '.gif'))
	{
		$g_path = $group_image_path;
		$ext = '.gif';
	}
	else
	{
		if (file_exists($mods_group_image_path . $group_img . '.png'))
		{
			$g_path = $mods_group_image_path;
			$ext = '.png';
		}
		else if (file_exists($mods_group_image_path . $group_img . '.gif'))
		{
			$g_path = $mods_group_image_path;
			$ext = '.gif';
		}
		else
		{
			$group_img = 'default';
		}
	}

	// get language vars for group name
	$group_name = ($user->lang(strtoupper('G_'.$group_name))) ? $user->lang(strtoupper('G_'.$group_name)) : $user->lang(strtoupper($group_name));

	if ($group_name[0] == 'G' && $group_name[1] == '_')
	{
		$group_name = ltrim($group_name, 'G_');
	}

	// conver to proper case and remove underscores //
	$group_name = mb_convert_case($group_name, MB_CASE_TITLE, "UTF-8");
	$group_name	= str_replace('_' , ' ', $group_name);

	if ($store != $group_name)
	{
		$change = true;
		$team_count = 0;
	}
	else
	{
		$change = false;
		$team_count++;
	}

	if ($team_count < $team_max_count || $team_max_count == 0)
	{
		$this->template->assign_block_vars('loop', array(
			'FIRST'				=> $i++,
			'S_CHANGE'			=> $change,
			'GROUP_IMG_PATH'	=> $g_path,
			'GROUP_IMG'			=> $group_img . $ext,
			'GROUP_NAME'		=> $group_name,
			'GROUP_COLOR'		=> $row['group_colour'],
			'USER_ID'			=> $row['user_id'],
			'USERNAME_FULL'		=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
		));
	}
	else
	{
		$limit_reached = $limit_reached + 1;
	}

	$store = $group_name;

	$this->template->assign_vars(array(
		'L_TEAM_MAX_COUNT'	=> ($limit_reached) ? sprintf($user->lang['TEAM_MAX_COUNT'], $team_max_count) : '',
	));
}

$db->sql_freeresult($result);
