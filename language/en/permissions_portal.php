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


/*
$lang['permission_cat']['portal'] = 'Portal';

$lang = array_merge($lang, array(
	'acl_u_k_tools'		=> array('lang' => 'Can use portal tools', 'cat' => 'portal'),
	'acl_a_k_portal'	=> array('lang' => 'Can manage portal settings', 'cat' => 'portal'),
	'acl_a_k_tools'		=> array('lang' => 'Can manage portal tools', 'cat' => 'portal'),
	'acl_a_k_youtube'	=> array('lang' => 'Can manage youtube settings', 'cat' => 'portal'),
));

$lang['permission_cat']['portal'] = 'Portal';
*/

$lang = array_merge($lang, array(
	'ACL_A_PORTAL' => 'Can manage portal settings',
	'ACL_A_TOOLS'  => 'Can manage portal tools',
	'ACL_U_TOOLS'  => 'Can use portal tools',

	'ACL_CAT_PORTAL' => 'Portal',
));

/*
protected $permissions = array(
	'a_portal'		=> array('lang' => 'ACL_A_PORTAL', 'cat' => 'portal'),
));
*/
/*
	$categories = array(
		'portal'		=> 'ACL_CAT_PORTAL',
	);
*/
