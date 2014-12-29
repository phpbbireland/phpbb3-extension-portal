<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* Modified for 3.1 Dec 2014, now using database table...
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

global $k_config, $phpbb_root_path, $k_blocks, $template;
$queries = $cached_queries = 0;

$phpEx = substr(strrchr(__FILE__, '.'), 1);

$show_all_links = false;


if ($blk['html_file_name'] == 'block_links.html')
{
	$block_cache_time = $blk['block_cache_time'];
	break;
}

$sql = "SELECT *
	FROM " . K_LINK_IMAGES_TABLE . "
	WHERE open_in_tab = 1
		ORDER BY RAND()";

$result = $db->sql_query($sql, $block_cache_time);


while ($row = $db->sql_fetchrow($result))
{
	//var_dump($row);

	$template->assign_block_vars('portal_links_row', array(
		'LINKS_IMG'	=> $phpbb_root_path . 'ext/otoole/portal/images/links/' . $row['image'],
		'U_LINKS'	=> $row['url'],
	));

}
