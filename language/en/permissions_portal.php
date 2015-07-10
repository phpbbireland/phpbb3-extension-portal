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
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ACL_A_PORTAL'	=> 'Can manage portal settings',
	'ACL_U_PORTAL'	=> 'Can view portal',
	'ACL_U_K_PORTAL' => 'Can use portal',
	'ACL_CAT_PORTAL' => 'Portal',
));
