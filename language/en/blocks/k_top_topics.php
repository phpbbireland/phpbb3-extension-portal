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
	'K_BLOCK_TOP_TOPICS_TITLE'  => 'Top Topics Variables',
	'K_TOP_TOPICS_MAX'          => 'Number of topics to display.',
	'K_TOP_TOPICS_MAX_EXPLAIN'  => 'The max number of most active topics to display.',
	'K_TOP_TOPICS_DAYS'         => 'Number of days to look back for top topics.',
	'K_TOP_TOPICS_DAYS_EXPLAIN' => 'The number of past days used for the search.',
));
