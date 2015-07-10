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

global $k_config, $phpbb_root_path, $k_blocks;
$queries = $cached_queries = 0;

$phpEx = substr(strrchr(__FILE__, '.'), 1);

$randomimage = '';
$imglist = "";
//$ilist = static array();

$rand_folder = $phpbb_root_path . 'ext/phpbbireland/portal/images/random_images';

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_random_image.html')
	{
		$block_centred = ($blk['position'] == 'C') ? true : false;
		break;
	}
}

@$handle = opendir($rand_folder);

// quick report because people forget to add the image directory //
if (!$handle)
{
	$template->assign_vars(array(
		'RANDOM_ERROR'    => true,
		'MISSING_FOLDER'  => sprintf($user->lang['MISSING_FOLDER'], $rand_folder),
	));
	return;
}

while (($file = readdir($handle)) !== false)
{
	if (eregi("gif", $file) || eregi("jpg", $file) || eregi("png", $file))
	{
		$imglist .= "$file ";
		$ilist[] = $file;
	}
}
closedir($handle);

$imglist = explode(" ", $imglist);
$a = sizeof($imglist)-2;

mt_srand ((double) microtime() * 1000000);

$random = mt_rand(0, $a);
$image = $imglist[$random];

$randomimage .= '<img src="' . $rand_folder . '/' . $image . '" alt="" />';

$template->assign_vars(array(
	'RANDOMIMAGE'         => $randomimage,
	'S_BLOCK_IS_CENTRED'  => ($block_centred) ? true : false,
));
