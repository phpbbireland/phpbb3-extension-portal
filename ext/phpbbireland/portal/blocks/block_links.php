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

global $k_config, $phpbb_root_path, $k_blocks;
$queries = $cached_queries = 0;

$phpEx = substr(strrchr(__FILE__, '.'), 1);

$show_all_links = false;

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_links.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		break;
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);

// retrieve portal config variables
$k_links_to_display = $k_config['k_links_to_display'];

if ($k_links_to_display > 0 && $k_links_to_display < 6)
{
	$show_all_links = false;
}
else if ($k_links_to_display == 0)
{
	$show_all_links = true;
}

// do we have a dedicated links upload forum? If not don't show the link upload image //
if (isset($k_config['k_links_forum_id']) && $k_config['k_links_forum_id'] != 0)
{
	$links_forum =  append_sid("{$phpbb_root_path}posting.$phpEx", 'mode=post&amp;f=' . (int)$k_config['k_links_forum_id']);
}
else
{
	$links_forum = '';
}

$imglist = array();

mt_srand((double)microtime()*1000002);
$imgs = dir($phpbb_root_path . 'images/links');

while ($file = $imgs->read())
{
	if (strpos($file, ".gif") || strpos($file, ".jpg") || strpos($file, ".png"))
	{
		$imglist[] = $file;
	}
}
closedir($imgs->handle);

$total_images_found = sizeof($imglist);
$links_count = 	$total_images_found;

if ($k_links_to_display > $links_count)	// we do not have enough images! so display what we have //
{
	$k_links_to_display = $links_count;
}

$random = mt_rand(0, $links_count);

if ($random >= ($links_count - $k_links_to_display))
{
	$random = ($links_count - $k_links_to_display);
}

// The number of link images to show (scrolled if scroll set in block)... Could be simplified a little...
if ($show_all_links)
{
	for ($i = 0; $i <= $total_images_found -1; $i++)
	{
		$image = $imglist[$i];

		if (strpos($image, '.gif'))
		{
			$lnk = explode(".gif", $image);
		}
		else if (strpos($image, '.png'))
		{
			$lnk = explode(".png", $image);
		}
		else if (strpos($image, '.jpg'))
		{
			$lnk = explode(".jpg", $image);
		}

		$lnk[0] = str_replace('+','/', $lnk[0]);
		$lnk[0] = str_replace('@','?', $lnk[0]);
		$lnk[0] = str_replace('£','+', $lnk[0]);

		$template->assign_block_vars('portal_links_row', array(
			'LINKS_IMG'	=> $phpbb_root_path . 'images/links/' . $image,
			'U_LINKS'	=> $lnk[0],
		));
	}
}
else
{
	for ($i = 0; $i <= $k_links_to_display-1; $i++)
	{
		$image = $imglist[$i+$random];

		if (strstr($image, 'gif'))
		{
			$lnk = explode(".gif", $image);
		}
		else if (strstr($image, 'png'))
		{
			$lnk = explode(".png", $image);
		}
		else if (strstr($image, 'jpg'))
		{
			$lnk = explode(".jpg", $image);
		}

		$lnk[0] = str_replace('+','/', $lnk[0]);
		$lnk[0] = str_replace('@','?', $lnk[0]);
		$lnk[0] = str_replace('£','+', $lnk[0]);

		$template->assign_block_vars('portal_links_row', array(
			'LINKS_IMG'	=> $phpbb_root_path . 'images/links/' . $image,
			'U_LINKS'	=> $lnk[0],
		));
	}
}

$template->assign_vars(array(
	'SUBMIT_LINK' 		=> $links_forum,
	'LINKS_COUNT'		=> $k_links_to_display,
	'TOTAL_LINKS'		=> $total_images_found,
	'LINKS_DEBUG'		=> sprintf($user->lang['PORTAL_DEBUG_QUERIES'], ($queries) ? $queries : '0', ($cached_queries) ? $cached_queries : '0', ($total_queries) ? $total_queries : '0'),
));

?>