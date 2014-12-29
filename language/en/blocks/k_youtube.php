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
	'AUTOPLAY_OFF'               => 'Autoplay is off...',
	'AUTOPLAY_ON'                => 'Autoplay is on...',
	'ACP_VIDEO_TITLE'            => 'Portal youtube variables.',
	'ACP_K_YOUTUBE_BROWSE'       => 'Browse!',
	'ADD_VIDEO'                  => 'Add a video',
	'ACP_VIDEO_EXPLAIN'          => 'Here you can add, edit and delete videos.',
	'ACP_YOUTUBE'                => 'Youtube Video',
	'COMMENT'                    => 'Comment',
	'CONFIG_VIDEO'               => 'Config video defaults',
	'CONFIRM_OPERATION_VIDEO'    => 'Delete this video?',
	'CONFIRM_OPERATION_YOUTUBE'  => 'Delete: %s',
	'EDIT_VIDEO'                 => 'Editing video',
	'ID'                         => 'ID',
	'SAVED'                      => 'Saved',
	'SWITCHING'                  => 'Switching to k_config',
	'YOUTUBE'                    => 'Youtube',
	'YOUTUBE_PAGE'               => 'Youtube Page',
	'YOUTUBE_LIMIT'              => 'limited to %d videos',
	'YOUTUBE_LINK_LIMIT'         => 'Number of video to display (0 = no limit)',
	'UTUBE_SAMPLE_DATA'          => 'I have included some sample data, just edit to suit...',
	'VIDEO_CATEGORY'             => 'Category',
	'VIDEO_CATEGORY_EXPLAIN'     => 'Select from existing category or add new.',
	'VIDEO_COMMENT'              => 'Comments',
	'VIDEO_CREATED'              => 'Video created...',
	'VIDEO_LINK'                 => 'Video Link',
	'VIDEO_LINK_EXPLAIN'         => 'YouTube video link code.',
	'VIDEO_MOVIE'                => 'YouTube Movie',
	'VIDEO_MOVIE_RXPLAIN'        => 'Each YouTube can have its own movie',
	'VIDEO_RATING'               => 'Rating',
	'VIDEO_RATING_EXPLAIN'       => 'Rate from 1 to 5.',
	'VIDEO_TITLE'                => 'Title',
	'VIDEO_TITLE_EXPLAIN'        => 'The title of the video.',
	'VIDEO_WHO'                  => 'Artist',
	'VIDEO_WHO_EXPLAIN'          => 'Artist or details of the performers.',
));
