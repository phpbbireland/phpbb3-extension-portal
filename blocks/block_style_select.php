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


// Anonymous users can't select a style...
if ($user->data['user_id'] == ANONYMOUS)
{
	return;
}

global $user_id, $user, $request, $template, $phpbb_root_path, $phpEx, $db, $k_blocks;


//var_dump($user->style['style_path']);
//var_dump($user->data['user_style']);


$current_style = $user->data['user_style'];		// the current style
$new_style = $request->variable('style', 0);			// selected style
$make_permanent = $request->variable('mp', 'false');	// make style permanent

$allow_style_change = ($config['override_user_style']) ? false : true;
$change_db_style = ($allow_style_change && $make_permanent) ? true : false;

if ($make_permanent == 'true' && $new_style != $current_style && $change_db_style)
{
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_style = " . (int) $new_style . "
		WHERE user_id = " . (int) $user_id;
	$db->sql_query($sql);
}

$style_count = 0;
$style_select = '';
$this_page = explode(".", $user->page['page']);

// rebuild forum and topic (viewforum, viewtopic) //
$appends = '';
$fo = $request->variable('f', 0);
$to = $request->variable('t', 0);

if ($fo != 0)
{
	$appends = 'f=' . $fo;
}
if ($to != 0)
{
	$appends .= '&amp;t=' . $to;
}

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_style_select.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		break;
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);

$sql = 'SELECT style_id, style_name
	FROM ' . STYLES_TABLE . '
	WHERE style_active = 1
	ORDER BY LOWER(style_name) ASC';
$result = $db->sql_query($sql, $block_cache_time);

while ($row = $db->sql_fetchrow($result))
{
	$style = $request->variable('style', 0);

	if ($style)
	{
		$url = str_replace('style=' . $style, 'style=' . $row['style_id'], append_sid("{$phpbb_root_path}{$this_page[0]}.$phpEx", $appends));
	}
	else
	{
		$url = append_sid("{$phpbb_root_path}{$this_page[0]}.$phpEx", 'style=' . $row['style_id'] . $appends);
	}
	++$style_count;

	$style_select .= '<option value="' . $url . '"' . ($row['style_id'] == $user->data['user_style'] ? ' selected="selected"' : '') . '>' . strip_tags(sgp_checksize ($row['style_name'], 16)) . '</option>';
}
$db->sql_freeresult($result);

if (strlen($style_select))
{
	$template->assign_var('STYLE_SELECT', $style_select);
}
global $page;

$template->assign_vars(array(
	'STYLE_COUNT'	=> $style_count,
	'S_SHOW_PERM'	=> ($this_page[0] == 'portal') ? true : false,
));
