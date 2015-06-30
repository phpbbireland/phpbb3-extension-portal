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
	'TITLE_MAIN'   => 'General Portal Variable',
	'TITLE_BLOCK'  => 'Portal Block Variable',
	'TITLE_EXPLAIN_MAIN'  => 'Setting for variables used by general portal blocks...',
	'TITLE_EXPLAIN_BLOCK' => '&bull; Blocks can contain variables (normally stored in the K_VARS_TABLE).
	<br />&bull; Each block can have an associated html file (to set the variables), these are located in adm/style/k_block_vars folder.<br />&bull; If you add your own block variables, you need to include the html file to display and edit these variables.',

	'NEWS_SETTINGS'       => 'News Settings',
	'K_NEWS_TYPE'         => 'News Type',
	'K_NEWS_TYPE_EXPLAIN' => 'Local, Global or Both.',
	'LOCAL_ANNOUNCE'      => 'Local Announcement ',
	'GLOBAL_ANNOUNCE'     => 'Global Announcement',
	'LOCAL_NEWS'          => 'Local News',
	'GLOBAL_NEWS'         => 'Global News',
	'BOTH'                => 'Both types',

	'RECENT_TOPICS_SETTINGS'             => 'Recent Topics Settings',
	'K_RECENT_TOPICS_TO_DISPLAY'         => 'Number of recent topics to display if block data is static',
	'K_RECENT_TOPICS_TO_DISPLAY_EXPLAIN' => 'Note: If you allow scrolling, all recent posts will be displayed.',

	'K_RECENT_SEARCH_DAYS'               => 'How many days will we search?',
	'K_RECENT_SEARCH_DAYS_EXPLAIN'       => 'Limit the number of days we search back to reduce database load.',

	'POSTS_TYPES'                        => 'Include all post types',
	'POSTS_TYPES_EXPLAIN'                => 'Yes to display all post types, No to display normal and stickies posts only...',

	'K_NEWS_ITEMS_TO_DISPLAY'            => 'Number of news item to display',
	'K_NEWS_ITEMS_TO_DISPLAY_EXPLAIN'    => 'The number of news items shown on portal page.',

	'K_RECENT_TOPICS_PER_FORUM'          => 'Number of topics per forum',
	'K_RECENT_TOPICS_PER_FORUM_EXPLAIN'  => 'The maximum number of topics returned from each forum.',


	'K_NEWS_ITEM_MAX_LENGTH'             => 'Length of news item',
	'K_NEWS_ITEM_MAX_LENGTH_EXPLAIN'     => 'Maximum length to display for each news item, 0 to show full article.',
	'K_NEWS_ALLOW'                       => 'Allow news to be displayed',
	'K_NEWS_ALLOW_EXPLAIN'               => 'Allow News to be displayed in portal page.',



	'BOT_SETTINGS'                       => 'Bot Settings',
	'K_BOT_DISPLAY_ALLOW'                => 'Allow bot report',
	'K_BOT_DISPLAY_ALLOW_EXPLAIN'        => 'Enable/Disable bot report.',
	'K_BOTS_TO_DISPLAY'                  => 'Number of bots to display',
	'K_BOTS_TO_DISPLAY_EXPLAIN'          => 'You can determine the number of bots to display.',

	'LINKS_SETTINGS'                     => 'Link Block Settings',
	'K_LINKS_TO_DISPLAY'                 => 'Number of links to display in Link Block',
	'K_LINKS_TO_DISPLAY_EXPLAIN'         => '0 (zero) to scroll all links...',
	'K_LINKS_SCROLL_AMOUNT'              => 'Scroll Amount/Speed',
	'K_LINKS_SCROLL_AMOUNT_EXPLAIN'      => 'Set to 1 for slow... 5 for fast...',
	'LINK_TO_US'                         => 'The link image name',
	'LINK_TO_US_EXPLAIN'                 => 'The image must exist in: ./images folder. (size: 88x31px)',
	'K_LINK_FORUM_ID'                    => 'The id of the forum to be used for uploading link images',
	'K_LINK_FORUM_ID_EXPLAIN'            => 'Places a link at the bottom of the Link Block to direct members to a designated links upload forum, should one exist.',
	'K_LINKS_SCROLL_DIRECTION'           => 'Scroll Direction',
	'K_LINKS_SCROLL_DIRECTION_EXPLAIN'   => 'Scroll 0 = Up or 1 = Down',
	'FOOTER_IMAGES'                      => 'Portal Footer Images',
	'K_FOOTER_IMAGES_ALLOW'              => 'Display Portal Footer Images',
	'K_FOOTER_IMAGES_ALLOW_EXPLAIN'      => 'Turn on/off link images in the portal footer...',
	'K_SMILIES_SHOW'                     => 'Show Smilies on Quick Reply',
	'K_SMILIES_SHOW_EXPLAIN'             => 'Some mods may require you don’t display Smilies on Quick Reply',

	'SHOW_BLOCKS_ON_INDEX_PORTAL' => 'Blocks display options.',
	'SHOW_BLOCKS_ON_INDEX_L'      => 'Display the left blocks on the index page',
	'SHOW_BLOCKS_ON_INDEX_R'      => 'Display the right blocks on the index page',
	'SHOW_BLOCKS_ON_PORTAL_L'     => 'Display the left blocks on the portal page',
	'SHOW_BLOCKS_ON_PORTAL_R'     => 'Display the right blocks on the portal page',
	'SHOW_BLOCKS_ON_SEARCH_L'     => 'Display the left blocks on the Search page',
	'SHOW_BLOCKS_ON_SEARCH_R'     => 'Display the right blocks on the Search page',
	'SHOW_BLOCKS_ON_MCP_L'        => 'Display the left blocks on the MCP page',
	'SHOW_BLOCKS_ON_MCP_R'        => 'Display the right blocks on the MCP page',
	'SHOW_BLOCKS_ON_UCP_L'        => 'Display the left blocks on the UCP page',
	'SHOW_BLOCKS_ON_UCP_R'        => 'Display the right blocks on the UCP page',
	'SHOW_BLOCKS_ON_MEM_L'        => 'Display the left blocks on the Members page',
	'SHOW_BLOCKS_ON_MEM_R'        => 'Display the right blocks on the Members page',


	'SHOW_BLOCKS_ON_VT_L' => 'Display the left blocks on the Viewtopic page',
	'SHOW_BLOCKS_ON_VT_R' => 'Display the right blocks on the Viewtopic page',
	'SHOW_BLOCKS_ON_VF_L' => 'Display the left blocks on the Viewforum page',
	'SHOW_BLOCKS_ON_VF_R' => 'Display the right blocks on the Viewforum page',
	'SHOW_BLOCKS_ON_VO_L' => 'Display the left blocks on the Viewonline page',
	'SHOW_BLOCKS_ON_VO_R' => 'Display the right blocks on the Viewonline page',

	//'SHOW_BLOCKS_ON_INDEX_L_EXPLAIN' => 'Enable or disable left blocks on index page.',
	//'SHOW_BLOCKS_ON_INDEX_R_EXPLAIN' => 'Enable or disable right blocks on index page.',

	'BLOCKS_GLOBAL'                     => 'Display blocks options',
	'K_BLOCKS_DISPLAY_GLOBALLY'         => 'Enable blocks for all pages',
	'K_BLOCKS_DISPLAY_GLOBALLY_EXPLAIN' => 'Setting to <strong>No</strong> will disable all blocks on all pages. This will override all other block settings.',

	'K_ANNOUNCE_TYPE'           => 'Announcement type',
	'K_ANNOUNCE_TYPE_EXPLAIN'   => 'Which type of announcements do you want to display?',

	'HEADER_BANNER'             => 'Header banner',
	'FOOTER_BANNER'             => 'Footer Banner',
	'BOTH_BANNERS'              => 'Both Header and Footer',
	'HEADER_IMAGE'              => 'Display a random Header image in Portal',
	'HEADER_IMAGE_SW'           => 'Display a random Header image in all pages (Site Wide)',
	'NO_BANNERS'                => 'No Banner',
	'NO_HEADER'                 => 'No Header Image',
	'RAND_BANNER'               => 'Portal Random Banner',
	'RAND_HEADER'               => 'Portal Random Header Image',
	'ALLOW_RAND_BANNER'         => 'Displays a banner in the site header and/or footer',
	'ALLOW_RAND_BANNER_EXPLAIN' => 'You can add a randomly selected banner image to the header and or footer...<br />Images must be placed in the images/rand_banner folder.<br /><b>Note</b>, for a fixed banner just place one image in folder.',
	'ALLOW_RAND_HEADER'         => 'Displays a random header image at top of portal and index page',
	'ALLOW_RAND_HEADER_EXPLAIN' => 'The images width/height can be set in one of the style css file, the tag being random_header_image.<br />Random header images must be placed in the images/rand_header folder.<br />',

	'BLOCK_COOKIES'             => 'Block Cookies',
	'USE_COOKIES'               => 'Use cookies to store block info',
	'USE_COOKIES_EXPLAIN'       => 'Use cookies to store block location and visibility',
	'PORTAL_LOGOS'              => 'Site Logo',
	'RAND_LOGOS'                => 'Random Site Logo',
	'RAND_LOGOS_EXPLAIN'        => 'Use random logos if they exist. Simply add several images to your extension styles (theme/images/logos directory).',

	'K_TOP_POSTERS_TO_DISPLAY'                   => 'Number of top posters to display',
	'K_TOP_POSTERS_TO_DISPLAY_EXPLAIN'           => 'Set the number of top posters to display in Top Posters Block',
	'NUMBER_OF_TOP_REFERRALS_TO_DISPLAY'         => 'Number of top referrals to display',
	'NUMBER_OF_TOP_REFERRALS_TO_DISPLAY_EXPLAIN' => 'Set the number of top referrals to display in Top Referrals Block',
	'K_TEAMS_DISPLAY_THIS_MANY'                  => 'Number of team members to display',
	'K_TEAMS_DISPLAY_THIS_MANY_EXPLAIN'          => 'You can limit the number per team (0 for no limit).',
	'EXCLUDE'                                    => 'Exclude forums for search',
	'EXCLUDE_EXPLAIN'                            => 'The ID’s of the forums to exclude from search (comma separated).',

	'K_BLOCK_CACHE_TIME_SHORT'         => 'Cache time short',
	'K_BLOCK_CACHE_TIME_SHORT_EXPLAIN' => 'Use where a short duration is preferred (10)',

	'SWITCH_VARS'                 => 'Switch Main/Block variables',
	'NO_VARS_FOUND'               => 'No variables found',

	'K_LINKS_FORUM_ID'            => 'Link forum ID',
	'K_LINKS_FORUM_ID_EXPLAIN'    => 'Dedicated forum for uploading link images (optional)',
	'K_LINKS_TO_DISPLAY'          => 'How many link images to display',
	'K_LINKS_TO_DISPLAY_EXPLAIN'  => 'You can limit the number of images scrolled in the links block',

	// Tooltips
	'ALLOW_TOOLTIPS'                     => 'Allow tooltips',
	'TOOLTIPS'                           => 'Tooltips',
	'K_TOOLTIPS_WHICH'                   => 'Show last/first post in tooltips',
	'FIRST'                              => 'First',
	'LAST'                               => 'Last',
));

// Portal Menu Names + add you menu language variables here! //
$lang = array_merge($lang, array(
	'ACP'              => 'Admin CP',
	'ALBUM'            => 'Album',
	'BOOKMARKS'        => 'Bookmarks',
	'CATEGORIES'       => 'Categories',
	'SGP_Blog'         => 'SGP Integrated Blog',
	'DOWNLOADS'        => 'Downloads',
	'FORUM'            => 'Forum',
	'KB'               => 'Knowledge Base',
	'LINKS'            => 'Links',
	'MEMBERS'          => 'Members',
	'PORTAL'           => 'Portal',
	'RATINGS'          => 'Latest Ratings',
	'RULES'            => 'Site Rules',
	'SITE_NAVIGATOR'   => 'Navigator',
	'STATISTICS'       => 'Statistics',
	'SITE_RULES'       => 'Site Rules',
	'SITE_STATISTICS'  => 'Site Statistics',
	'STAFF'            => 'Staff',
	'STYLES_DEMO'      => 'Styles Demo',
	'STYLE_SELECT'     => 'Style Select',
	'UCP'              => 'User CP',
	'UNRESOLVED/BUGS'  => 'Unresolved/Bugs',
	'UPLOAD'           => 'Upload Images',
	'USER_INFORMATION' => 'User Information',
	'WELCOME'          => 'Welcome',
));

// Portal Block Names + add your block name language variables here! //
$lang = array_merge($lang, array(
	'ACP_SHORT'                => 'Admin CP',
	'ANNOUNCEMENTS'            => 'Announcements',
	'BIRTHDAY'                 => 'Birthday',
	'BLOCK_CACHE_TIME_RECENT'  => 'Recent topics cache time',
	'BLOCK_CACHE_TIME_SHORT'   => 'Block cache time short',
	'BLOCK_CACHE_TIME_LONG'    => 'Block cache time long ',
	'BLOCK_CACHE_TIME_MEDIUM'  => 'Block cache time medium',
	'BLOG'                     => 'SGP Integrated Blog',
	'BOARD_MINI_NAV'           => 'Sub Nav',
	'BOARD_STYLE'              => 'Board Style',
	'BOARD_NAV'                => 'Board Navigation',
	'BOT_TRACKER'              => 'Bot Tracker',
	'BOT_TRACKER_SMALL'        => 'Bot Tracker',
	'BOOKS'                    => 'Books',
	'CALENDAR'                 => 'Calendar',
	'CHAT'                     => 'Chat',
	'CLOCK'                    => 'Clock',
	'DOWNLOADS'                => 'Downloads',
	'FM_RADIO'                 => 'FM Radio',
	'FORUM_CATEGORIES'         => 'Forum categories',
	'GALLERY'                  => 'Gallery',
	'IRC_CHAT'                 => 'IRC Chat',

	//'K_BLOCK_CACHE_TIME_FAST'         => 'Set recent topics cache time.',
	//'K_BLOCK_CACHE_TIME_FAST_EXPLAIN' => 'Default period for recent topics.',

	'K_MAX_BLOCK_AVATAR_WIDTH'        => 'The maxinum width of the avatar',
	'K_MAX_BLOCK_AVATAR_HEIGHT'       => 'The maxinum height of the avatar',
	'K_MAX_BLOCK_AVATAR_EXPLAIN'      => 'Set to 0 to use phpBB config fault value',
	'K_TOP_TOPICS_MAX'                => 'Number of topics to display.',
	'K_TOP_TOPICS_MAX_EXPLAIN'        => 'The max number of most active topics to display.',
	'K_TOP_TOPICS_DAYS'               => 'Number of days to look back for top topics.',
	'K_TOP_TOPICS_DAYS_EXPLAIN'       => 'The number of past days used for the search.',


	'LINKS'                    => 'Links',
	'MAIN_MENU'                => 'Board Navigation',
	'MEMBERS'                  => 'Members',
	'MP3_PLAYER'               => 'Mp3 player',
	'NEWS'                     => 'News',
	'NEWS_REPORT'              => 'Site News Report',
	'NO_CONFIG_FILE_FOUND'     => 'No configuration required, or no file available for this module.',
	'PORTAL'                   => 'Portal',
	'PORTAL_STATUS'            => 'Portal Status',
	'RECENT_TOPICS'            => 'Recent Topics',
	'REQUIRED_DATA_MISSING'    => 'Required data is missing...<br />',
	'SAVING'                   => 'Database updated...',
	'SAVED'                    => 'Database updated...',
	'SELECT_STYLE'             => 'Select a new style',
	'SITE_LINK_TXT'            => 'Link to us',
	'STAFF'                    => 'Staff',
	'STATISTICS'               => 'Statistics',
	'STATS'                    => 'Statistics',
	'STYLE_STATUS'             => 'Styles Status',
	'SUB_MENU'                 => 'Secondary Menu',
	'SWITCHING'                => 'Switching to SGP config',
	'TOP_10_PICS'              => 'Top 10 Rated Pictures',
	'TOP_TOPICS'               => 'Most active topics.',
	'TOPICSPERFORUM'           => 'Number of topics per forum.',
	'TOPICSPERFORUM_EXPLAIN'   => 'Limit the number of topics returned for each forum.',
	'TOP_DOWNLOADS'            => 'Top Downloads',
	'TOP_POSTERS'              => 'Top Posters',
	'TOP_REFERRALS'            => 'Top Referrals',
	'UCP'                      => 'User CP',
	'UNRESOLVED'               => 'Unresolved',
	'USER_INFO'                => 'User Information',
	'YOUR_PROFILE'             => 'User profile',
	'YOUTUBE_LINK'             => 'Actual YouTube link (URL)',
	'YOUTUBE_LINK_EXPLAIN'     => 'Just in case YouTube ever change we best provide an alternate',
	'YOUTUBE_AUTO'				=> 'Autoplay video',
	'YOUTUBE_AUTO_EXPLAIN'		=> 'When a video is selected, it will play automatically.',
	'UNKNOWN_ERROR'            => 'Error not processing saved data<br />',
	'USER_MAX_AVATAR_SETTINGS' => 'Restrict the size of a user avatar in user information block.',
	'USE_BACK_BUTTON'          => 'Please use browser back button',
	'WELCOME_SITE'             => 'Welcome to<br /><strong>%s</strong>',
));

// Block Names
$lang = array_merge($lang, array(
	'ADMIN_OPTIONS'           => 'Admin Options',
	'BABEL_FISH'              => 'Babel Fish',
	'BUG_TRACKER'             => 'Bug Tracker',
	'TRANSLATE_SITE'          => '<strong>Translate site to...</strong>',
	'TRANSLATE_RESET'         => '<strong>Reset to original language</strong>',
	'ANNOUNCEMENTS_AND_NEWS'  => 'Announcements and News',
	'TOP_POSTERS_SETTINGS'    => 'Top Posters block settings',
	'TOP_REFERRALS_SETTINGS'  => 'Top Referrals block settings',
	'THE_TEAM_SETTINGS'       => 'Team Members block settings',
));

// Acronyms
$lang = array_merge($lang, array(
	'ACP_ACRONYMS'           => 'Manage acronyms',
	'ACP_ACRONYMS_EXPLAIN'   => 'Add and manage acronyms in posts... <br /><strong>Note:</strong> Where acronyms are comprised or two or more words, they should not contain existing acronyms in their meaning... <br />For example, in the case of the acronym: phpBB3 which appears in the acronym: Stargate Portal, we replace <strong>phpBB3</strong> with <strong>phpBB version 3</strong> to avoid breaking things... In general acronyms should not contain spaces...',
	'ACRONYM'                => 'Acronym',
	'ACRONYM_EXPLAIN'        => 'From this control panel you can add, edit, and remove acronyms that will be automatically added to posts on your forums.',
	'ACRONYMS'               => 'Acronyms',
	'ADD_ACRONYM'            => 'Add Acronym',
	'ACRONYM_MEANING'        => 'Enter the full meaning',
	'ADD_NEW_WORD'           => 'Add word',
	'ALLOW_ACRONYMS'         => 'Process Local Acronyms (built in) in posts',
	'ALLOW_ACRONYMS_EXPLAIN' => 'Local Acronyms in posts will not be processed if disable here...',
	'CONFIG_ACRONYMS'        => 'Configure',
	'DELETE'                 => 'To delete words, simply remove them',
	'DELETE_CURRENT'         => 'Remove',
	'EDIT_ACRONYM'           => 'Edit acronym',
	'EDIT_ACRONYM_EXPLAIN'   => 'Edit the meaning for the acronym:',
	'RESERVED'               => 'Reserved words.',
	'RESERVED_EXPLAIN'       => 'These words cannot be used as an acronym, they are in the reserved word list...',
	'RESERVED_WORD_LIST'     => 'Manage reserved words',
	'NEW_WORD'               => 'Add new reserved word.',
));

// IRC Channel
$lang = array_merge($lang, array(
	'IRC_CHANNEL'              => 'IRC Channel',
	'IRC_CHANNEL_NAME'         => 'Name of your IRC channel',
	'IRC_CHANNEL_EXPLAIN'      => 'The name of the IRC channel you want to use on your board.',
	'OPT_IRC_CHANNELS'         => 'Optional IRC Channels',
	'OPT_IRC_CHANNELS_EXPLAIN' => 'Here you can add optional IRC channels. starting with # in the channel name and separated with a comma (,) but NOT spaces. For example: #channel1,#Channel2,#channel3',
));

// Age Ranges
$lang = array_merge($lang, array(
	'AGE_RANGES'           => 'Age Ranges',
	'AGE_INTERVAL'         => 'Age interval',
	'AGE_INTERVAL_EXPLAIN' => 'The interval to use in the age groups.',
	'AGE_START'            => 'Age start',
	'AGE_START_EXPLAIN'    => 'The age to start the first group with.',
	'AGE_LIMIT'            => 'Age upper limit',
	'AGE_LIMIT_EXPLAIN'    => 'The upper age limit to show. NOTE: If you want to show up to for example 100: put in 101 here. (Last group end value + 1)',
));

// Cloud
$lang = array_merge($lang, array(
	'ACP_CLOUD'            => 'Portal cloud tags default settings. (Cloud 9)',
	'ACP_CLOUD_EXPLAIN'    => 'Here you can add, edit and delete tags. <strong>Note:</strong> Using a font size greater than 16pt is not recommended, also it might be difficult to see light coloured fonts...',

	'ADD_CLOUD'            => 'Add a cloud',
	'EDIT_CLOUD'           => 'Edit a cloud',
	'DELETE_CLOUD'         => 'Delete a cloud',
	'CONFIG_CLOUD'         => 'Config cloud defaults',

	'CLOUD_ID'             => 'ID',
	'CLOUD_ID_LONG'        => 'The tag ID (do not edit)',
	'CLOUD_IS_ACTIVE'      => 'A',
	'CLOUD_IS_ACTIVE_LONG' => 'Set tag as active',
	'CLOUD_LONG'           => 'Tags/Cats',
	'CLOUD_LINK'           => 'Link',
	'CLOUD_LINK_LONG'      => 'Link to use for the tag',
	'CLOUD_COLOUR'         => 'Colour',
	'CLOUD_COLOUR_LONG'    => 'Tag colour (HEX color value)',
	'CLOUD_HCOLOUR'        => 'H Colour',
	'CLOUD_HCOLOUR_LONG'   => 'Tag Highlight colour (HEX color value)',
	'CLOUD_CLASS'          => 'Class',
	'CLOUD_CLASS_LONG'     => 'Unknown Class',
	'CLOUD_REL'            => 'R',
	'CLOUD_REL_LONG'       => 'Unknown',
	'CLOUD_FONT_SIZE'      => 'Font size',
	'CLOUD_FONT_SIZE_LONG' => 'The font size to use (MAX ~ 16pt)',
	'CLOUD_TEXT'           => 'Tag Text',
	'CLOUD_TEXT_LONG'      => 'The tag text to display<br />Actual size and colour',


	'CLOUD_MAX_TAGS'          => 'Max tags',
	'CLOUD_MAX_TAGS_EXPLAIN'  => 'Set Max tags so you don’t clutter the block.',
	'CLOUD_MOVIE'             => 'Cloud Movie',
	'CLOUD_MOVIE_EXPLAIN'     => 'Each cloud can have its own movie.',
	'CLOUD_WIDTH'             => 'Cloud width',
	'CLOUD_WIDTH_EXPLAIN'     => 'The width of the Cloud block.',
	'CLOUD_HEIGHT'            => 'Cloud height',
	'CLOUD_HEIGHT_EXPLAIN'    => 'The height of the Cloud block.',
	'CLOUD_TCOLOUR'           => 'Tag colour',
	'CLOUD_TCOLOUR_EXPLAIN'   => 'Tag main colour.',
	'CLOUD_TCOLOUR2'          => 'Secondary tag colour',
	'CLOUD_TCOLOUR2_EXPLAIN'  => 'Tag color for less important tags.',
	'CLOUD_HICOLOUR'          => 'Highlight colour',
	'CLOUD_HICOLOUR_EXPLAIN'  => 'The highlight colour for the tag.',
	'CLOUD_BG_COLOUR'         => 'Tag cloud background colour',
	'CLOUD_BG_COLOUR_EXPLAIN' => 'Tag cloud background colour *transparent (see WMODE).',
	'CLOUD_SPEED'             => 'Speed, how fast tags rotate',
	'CLOUD_SPEED_EXPLAIN'     => 'Percentage, higher means faster.',
	'CLOUD_MODE'              => 'Tag/Category mode',
	'CLOUD_MODE_EXPLAIN'      => '"tags" or "cats" or "both"',
	'CLOUD_WMODE'             => 'WMode (Display Mode)',
	'CLOUD_WMODE_EXPLAIN'     => 'Background transparency.',
	'CLOUD_DISTR'             => 'Tag distributions',
	'CLOUD_DISTR_EXPLAIN'     => 'Check for even tag distributions along sphere.',

	'TEAMSPEAK_SETTINGS'      => 'Teanspeak Config',
	'TEAMSPEAK_PASS'          => 'Password',
	'TEAMSPEAK_CONNECT'       => 'Connection',

	'CLOUD_SEARCH'               => 'Cloud Search Block',
	'CLOUD_SEARCH_ALLOW'         => 'Show Cloud Search Block',
	'CLOUD_SEARCH_CACHE'         => 'Cache time for this block',
	'CLOUD_SEARCH_CACHE_EXPLAIN' => ' (cache time in seconds).',
));

// Mini Mod vars
$lang = array_merge($lang, array(
	'MINI_MOD_DEVELOPMENT'         => 'Mini Modules Development, display options',
	'MINI_MOD_STYLE_COUNT'         => 'The number of styles to include in this block',
	'MINI_MOD_STYLE_COUNT_EXPLAIN' => '',
	'MINI_MOD_BLOCK_COUNT'         => 'The number of blocks to include in this block',
	'MINI_MOD_BLOCK_COUNT_EXPLAIN' => '',
	'MINI_MOD_MOD_COUNT'           => 'The number of mods to display in this block',
	'MINI_MOD_MOD_COUNT_EXPLAIN'   => '',
));

// SGP Quick Reply vars 11 February 2010
$lang = array_merge($lang, array(
	'SGP_QR_SETTINGS'  => 'SGP Quick Reply Settings',
	'SGP_QR'           => 'Use SGP quick reply',
	'SGP_QR_EXPLAIN'   => 'Replace the default quick reply with the portal version.',
));

// Random avatars
$lang = array_merge($lang, array(
	'RANDOM_AVATARS'       => 'Random avatar mod',
	'ALLOW_AVATAR'         => 'Allow random avatars',
	'ALLOW_AVATAR_EXPLAIN' => 'Use a random avatar if user has no avatar and avatars are allowed.',
));

// SGP Teams Block 15 March 2011
$lang = array_merge($lang, array(
	'K_BLOCK_TEAMS'               => 'Select teams to display in block',
	'K_BLOCK_TEAMS_EXPLAIN'       => 'This dropdown is reusable, each selection will update the list.<br />Selecting <strong>none</strong> will reset the list.',
	'K_BLOCK_TEAMS_SORT'          => 'Sort By',
	'K_BLOCK_TEAMS_SORT_EXPLAIN'  => 'Determines the team sort order.',
	'K_TEAMS'                     => 'These teams will be displayed',
	'K_TEAMS_EXPLAIN'             => '(ID of teams in comma separated list).',
	'THE_TEAM_SETTINGS_2'         => 'Limit to members',
));



// Member Avatar mod
$lang = array_merge($lang, array(
	'MEMBERS_AVATARS_SETTINGS'       => 'Manage members avatars variables',
	'K_MA_MAX_AVATARS'               => 'The number of avatars to process',
	'K_MA_MAX_AVATARS_EXPLAIN'       => 'Set this to 0 (zero) to process all avatars',
	'K_MA_COLUMNS'                   => 'Number of avatars to display per column',
	'K_MA_ROWS'                      => 'Number of rows to display per page',
	'K_MA_SHOW_LOGGED_IN_ONLY'       => 'Display avatars for logged in users only',
	'K_MA_DISPLAY_PER_PAGE'          => 'Number of avatars to display (per page)',
	'K_MA_DISPLAY_PER_PAGE_EXPLAIN'  => 'Must be greater than 0...',
	'K_MA_AVATAR_MAX_WIDTH'          => 'Limit avatar width to (px)',
	'K_MA_USER_ACTIVE'               => 'Display only active members',
	'K_MA_USER_HAS_POSTED'           => 'Include members with no posts',
));
