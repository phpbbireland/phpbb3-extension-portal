<?php
/**
*
* permissions_kiss [English]
*
* @package language
* @version $Id$
* @copyright (c) 2005-2013 phpbbireland
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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

$lang['permission_cat']['portal'] = 'Portal';

$lang = array_merge($lang, array(
	'acl_u_k_tools'		=> array('lang' => 'Can use portal tools', 'cat' => 'portal'),
	'acl_a_k_portal'	=> array('lang' => 'Can manage portal settings', 'cat' => 'portal'),
	'acl_a_k_tools'		=> array('lang' => 'Can manage portal tools', 'cat' => 'portal'),
	'acl_a_k_youtube'	=> array('lang' => 'Can manage youtube settings', 'cat' => 'portal'),
));

?>