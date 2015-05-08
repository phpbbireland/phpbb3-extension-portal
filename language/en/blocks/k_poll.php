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

// Poll Mods
$lang = array_merge($lang, array(
	'K_POLL_BLOCK_SETTINGS' => 'Poll block variables',

	'K_POLL_OPT'            => 'Option',
	'K_POLL_OPT_EXPLAIN'    => 'This allows tweaking the html code to better suit the display width.',
	'K_POLL_SD'             => 'Simple/Detailed',
	'K_POLL_DETAILED'       => 'Detailed',
	'K_POLL_SIMPLE'         => 'Simple',
	'K_POLL_POST_ID'        => 'Post ID to display Poll from',
	'K_POLL_VIEW'           => 'Poll view (simple or detailed)',
	'K_POLL_VIEW_EXPLAIN'   => 'Use detailed for centre block, simple of left/right blocks.',
	'K_POLL_WIDE_EXPLAIN'   => 'This allows tweaking the html code to better suit the display width.',
	'K_POLL_ACTION'         => 'Action',
	'K_POLL_WIDE'           => 'Display block options',

	'K_POLL_CNTR'           => 'Centre Block',
	'K_POLL_LR'             => 'Left or Right Block',
));
