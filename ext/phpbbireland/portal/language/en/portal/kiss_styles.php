<?php
/**
*
* portal_kiss_styles [English]
*
*
* @package language (Kiss Portal Engine / Stargate Portal)
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

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'IMG_NEWS_READ'					=> 'News',
	'IMG_NEWS_READ_MINE'			=> 'News posted to',
	'IMG_NEWS_READ_LOCKED'			=> 'News locked',
	'IMG_NEWS_READ_LOCKED_MINE'		=> 'News locked posted to',
	'IMG_NEWS_UNREAD'				=> 'News new posts',
	'IMG_NEWS_UNREAD_MINE'			=> 'News posted to new',
	'IMG_NEWS_UNREAD_LOCKED'		=> 'News locked new post',
	'IMG_NEWS_UNREAD_LOCKED_MINE'	=> 'News locked posted to new',
));

?>