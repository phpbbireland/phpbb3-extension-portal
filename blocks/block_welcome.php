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

global $user, $k_config, $k_blocks, $phpbb_root_path;

$k_welcome_message = (isset($k_config['k_welcome_message']) ? $k_config['k_welcome_message'] : '');

$welcome_image	= $phpbb_root_path . 'ext/phpbbireland/portal/images/welcome.png';

if (file_exists($welcome_image))
{
	$block_link = 'portal.php';
}
else
{
	$welcome_image = '';
	$block_link = '';
}

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_welcome_message.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		break;
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['block_cache_time_default']);

include_once($phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_functions.' . $this->php_ext);

$block_name		= (isset($user->lang['WELCOME']) ? $user->lang['WELCOME'] : '{L_NO_LANG_VALUE}');

// if welcome message has not been set in ACP > BLOCKS > Welcome Blocks, use default //
//$block_details	= (isset($k_welcome_message) && $k_welcome_message != ''  ? $k_welcome_message : $user->lang['WELCOME_MESSAGE']);

$block_details	= $user->lang['WELCOME_MESSAGE'];
$block_details = str_replace('{WM}', $k_welcome_message, $block_details);

$block_details	= process_for_vars($block_details, true);
$block_details	= str_replace("[you]", ('<span style="font-weight:bold; color:#' . $user->data['user_colour'] . ';">' . $user->data['username'] . '</span>'), $block_details);

$this->template->assign_vars( array(
	'W_TITLE'	=> $block_name,
	'W_IMAGE'	=> $welcome_image,
	'U_LINK'	=> $block_link,
	'W_MESSAGE'	=> htmlspecialchars_decode($block_details),
));
