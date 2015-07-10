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
	'K_LINKS_BLOCK_TITLE'        => 'Link Block Variables',
	'K_LINKS_FORUM_ID'           => 'Link forum ID',
	'K_LINKS_FORUM_ID_EXPLAIN'   => 'Dedicated forum for uploading link images (optional)',
	'K_LINKS_TO_DISPLAY'         => 'Number of links to display in Link Block',
	'K_LINKS_TO_DISPLAY_EXPLAIN' => '0 (zero) to scroll all links...',

	'K_LINKS_SCROLL_AMOUNT'              => 'Scroll Amount/Speed',
	'K_LINKS_SCROLL_AMOUNT_EXPLAIN'      => 'Set to 1 for slow... 5 for fast...',
	'LINK_TO_US'                         => 'The link image name',
	'LINK_TO_US_EXPLAIN'                 => 'The image must exist in: ./images folder. (size: 88x31px)',
	'K_LINK_FORUM_ID'                    => 'The id of the forum to be used for uploading link images',
	'K_LINK_FORUM_ID_EXPLAIN'            => 'Places a link at the bottom of the Link Block to direct members to a designated links upload forum, should one exist.',
	'K_LINKS_SCROLL_DIRECTION'           => 'Scroll Direction',
	'K_LINKS_SCROLL_DIRECTION_EXPLAIN'   => 'Scroll 0 = Up or 1 = Down',

	'LINK_IMAGE'	=> 'Image to use',
	'LINK_SITE'		=> 'Link to Site',
));
