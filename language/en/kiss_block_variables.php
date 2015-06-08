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
// Portal Menu Names + add you menu language variables here! //

$lang = array_merge($lang, array(
	'SGP_BLOG'         => 'SGP Integrated Blog',
	'LINKS_MENU'       => 'Links Menu',
	'RATINGS_LATEST'   => 'Latest Ratings',
	'REFRESH_ALL'      => 'Refresh All',
	'SITE_NAVIGATOR'   => 'Navigator',
	'SITE_RULES'       => 'Site Rules',
	'SITE_STATISTICS'  => 'Site Statistics',
	'STYLES_DEMO'      => 'Styles Demo',
	'STYLE_SELECT'     => 'Style Select',
	'UNRESOLVED/BUGS'  => 'Unresolved/Bugs',
	'UPLOAD_IMAGES'    => 'Upload Images',
	'USER_INFORMATION' => 'User Information',

));

// Portal Block Names + add your block name language variables here! //
$lang = array_merge($lang, array(
	'ADMIN_CP'          => 'Admin CP',
	'BOARD_MINI_NAV'    => 'Sub Nav',
	'BOARD_STYLE'       => 'Board Style',
	'BOARD_NAV'         => 'Board Navigation',
	'BOT_TRACKER'       => 'Bot Tracker',
	'BOT_TRACKER_SMALL' => 'Bot Tracker',
	'CLOUD9_LINKS'      => 'Cloud9 Links',
	'CLOUD9_SEARCHES'   => 'Cloud9 Searches',
	'FM_RADIO'          => 'FM Radio',
	'FORUM_CATEGORIES'  => 'Forum categories',
	'MAIN_MENU'         => 'Board Navigation',
	'MP3_PLAYER'        => 'Mp3 player',
	'NEWS_REPORT'       => 'Site News Report',
	'PORTAL_STATUS'     => 'Portal Status',
	'RECENT_TOPICS'     => 'Recent Topics',
	'SELECT_STYLE'      => 'Select a new style',
	'SITE_LINK_TXT'     => 'Link to us',
	'STATS'             => 'Statistics',
	'STYLE_STATUS'      => 'Styles Status',
	'SUB_MENU'          => 'Sub Menu',
	'TOP_10_PICS'       => 'Top 10 Rated Pictures',
	'TOP_DOWNLOADS'     => 'Top Downloads',
	'TOP_POSTERS'       => 'Top Posters',
	'TOP_TOPICS_DAYS'   => 'in the last %d days',
	'WELCOME_SITE'      => 'Welcome to<br /><strong>%s</strong>',
	'YOUR_PROFILE'      => 'User profile',

));

// Block Names
$lang = array_merge($lang, array(
	'ADMIN_OPTIONS'           => 'Admin Options',
	'BUG_TRACKER'             => 'Bug Tracker',
	'TRANSLATE_SITE'          => '<strong>Translate site to...</strong>',
	'TRANSLATE_RESET'         => '<strong>Reset to original language</strong>',
	'ANNOUNCEMENTS_AND_NEWS'  => 'News and Announcements',
));

// Acronyms
$lang = array_merge($lang, array(
	'ALLOW_ACRONYMS'         => 'Process Local Acronyms (built in) in posts',
	'ALLOW_ACRONYMS_EXPLAIN' => 'Allow local acronyms in posts',
));

// IRC Channel(s)
$lang = array_merge($lang, array(
	'IRC_POPUP'    => 'Popup IRC Channel',
	'SIGNED_OFF'   => 'Signed off',
	'NO_JAVA_SUP'  => 'No java support',
	'NO_JAVA_VER'  => 'Sorry, but you need a Java 1.4.x enabled browser to use PJIRC',
));

// Age ranges
$lang = array_merge($lang, array(
	'AGE_RANGE'        => 'Age range',
	'AVERAGE_AGE'      => 'Average age',
	'TOTAL_AGE'        => 'Total age',
	'TOTAL_AGE_COUNTS' => 'Total age counts',
));

// RSS Newsfeeds
$lang = array_merge($lang, array(
	'NO_CURL'               => 'curl not installed. Use fopen instead (change in ACP)',
	'NO_FOPEN'              => 'fopen not installed. Use curl instead (change in ACP)',
	'RSS_CACHE_ERROR'       => 'Sorry, no RSS items found in the cache file.',
	'RSS_DISABLED'          => 'Newsfeeds are currently disabled',
	'RSS_FEED_ERROR'        => 'Or something wrong with RSS feed.',
	'RSS_LIST_ERROR'        => 'Could not get RSS list.',
	'RSS_ERROR'             => 'RSS Error - Check feed link (above) to confirm.',
	'LOG_RSS_CACHE_CLEANED' => 'RSS cache cleared',
));

// HTTP Referrals
$lang = array_merge($lang, array(
	'TOT_REF' => 'Total Referrals',
));

// Mini Mods
$lang = array_merge($lang, array(
	'CHECK_VERSION'  => 'Check for updates',
));
