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
	'K_TOP_DOWNLOADS_PER_FORUM'          => 'Limit attachemnt count in forums to',
	'K_TOP_DOWNLOADS_SETTINGS'           => 'Top Downloads Block Variables',
	'K_TOP_DOWNLOADS_TO_DISPLAY'         => 'Number of attachments to display',
	'K_TOP_DOWNLOADS_SEARCH_DAYS'        => 'How many days will we search?',
	'K_TOP_DOWNLOADS_SEARCH_EXPLAIN'     => 'Limit the number of days we search back to reduce database load.',
	'K_TOP_DOWNLOADS_TYPES'              => 'Attachments to include',
	'K_TOP_DOWNLOADS_TYPES_EXPLAIN'      => 'Comma separated file extensions to include, example: zip,gif,arc',
));
