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

$lang = array_merge($lang, array(
	'K_RECENT_TOPICS_TITLE'           => 'Recent Topics Variables',
	'K_RECENT_TOPICS_TO_DISPLAY'      => 'Number of topics to display',
	'K_RECENT_TOPICS_PER_FORUM'       => 'Number of topics per forum',
	'K_RECENT_TOPICS_SEARCH_EXCLUDE'  => 'Forums to exclude from search',
	'K_RECENT_SEARCH_DAYS'            => 'Search days: ',

	'NO_RECENT_TOPICS'      => ' No recent topics to display',
	'RECENT_TOPICS'         => 'Recent Topics',
	'RECENT_REPLY'          => 'View latest reply...',
	'BLOCK_RECENT_TOPICS'   => 'Kiss Portal Recent Topics',
));
