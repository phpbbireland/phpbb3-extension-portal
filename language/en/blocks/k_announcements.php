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
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

// Top downloads mod 11 July 2013
$lang = array_merge($lang, array(
	'ANNOUNCE_SETTINGS'                  => 'Announcement Block Variables',
	'ANNOUNCE_FORUM_ID'                  => 'Announcements Forum ID',
	'ANNOUNCE_FORUM_ID_EXPLAIN'          => 'The ID of the announcement forum.',
	'K_ANNOUNCE_TO_DISPLAY'              => 'Number of announcements to display',
	'K_ANNOUNCE_TO_DISPLAY_EXPLAIN'      => 'The number of announcements to show on portal page.',
	'K_ANNOUNCE_ITEM_MAX_LENGTH'         => 'Length of announcements',
	'K_ANNOUNCE_ITEM_MAX_LENGTH_EXPLAIN' => 'Maximum length of each announcement to display, 0 to show full article.',
	'K_ANNOUNCE_ALLOW'                   => 'Allow Announcements',
	'K_ANNOUNCE_ALLOW_EXPLAIN'           => 'Allow announcements to be displayed on portal.',
));
