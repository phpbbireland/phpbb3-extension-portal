<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'NO_EDIT'				=> 'Can’t Edit',
	'NO_DELETE'				=> 'Can’t Delete',
	'NO_MOVE_DOWN'			=> 'Can’t move down',
	'NO_MOVE_UP'			=> 'Can’t move up',
	'NO_RESYNC'				=> 'Resynchronise Disabled',

// phpbbportal profile fields
	'ACP_CAT_PORTAL'				=> 'Portal',
	'ACP_CAT_PORTAL_TOOLS'			=> 'Portal Tools',
	'ACP_CAT_CLOUD'					=> 'Cloud Tags',
	'ACP_CAT_STATUS_CONFIG'			=> 'Config Status',
	'ACP_K_WEBPAGES'				=> 'Portal Webpages',

// Common
	'ACP_K_UP'						=> 'Move Up',
	'ACP_K_DOWN'					=> 'Move Down',

// Blocks
	'ACP_K_BLOCKS'					=> 'Blocks',
	'ACP_K_BLOCKS_ADD'				=> 'Add a New Block',
	'ACP_K_BLOCKS_EDIT'				=> 'Edit Block',
	'ACP_K_BLOCKS_DELETE'			=> 'Delete Blocks',
	'ACP_K_BLOCKS_MANAGE'			=> 'Manage/View all Blocks',
	'ACP_K_PAGE_LEFT_BLOCKS'		=> 'Manage Left Blocks',
	'ACP_K_PAGE_CERTRE_BLOCKS'		=> 'Manage Centre Blocks',
	'ACP_K_PAGE_RIGHT_BLOCKS'		=> 'Manage Right Blocks',
	'ACP_K_BLOCKS_REINDEX'			=> 'Reindex Blocks',
	'ACP_K_BLOCKS_RESET'			=> 'Reset all block layouts',
	'ACP_BLOCK_CONFIG'				=> 'Configure block variables',

// Menus
	'ACP_K_MENUS'					=> 'Menus',
	'ACP_K_MENU_ADD'				=> 'Add a New Menu Item',
	'ACP_K_MENU_HEADER'				=> 'Manage Header Menus',
	'ACP_K_MENU_FOOTER'				=> 'Manage Footer Menus',
	'ACP_K_MENU_MAIN'				=> 'Manage Main Menu',
	'ACP_K_MENU_SUB'				=> 'Manage Sub Menus',
	'ACP_K_MENU_LINKS'				=> 'Manage Link Menus',
	'ACP_K_MENU_MANAGE'				=> 'Manage Menus',
	'ACP_K_MENU_EDIT'				=> 'Edit Menu Item',
	'ACP_K_MENU_DELETE'				=> 'Delete a Menu Item',
	'ACP_K_MENU_ICONS'				=> 'Manage Menu Icons',
	'ACP_K_MENU_ALL'				=> 'Manage/View all Menus',
	'ACP_K_MENU_UNALLOCATED'		=> 'View unallocated items',

// Modules
	'ACP_K_MODS_ADD'				=> 'Add a New Module',
	'ACP_K_MODS_BLOCKS'				=> 'Block Modules',
	'ACP_K_MODS_BUGS'				=> 'Bugs Modules',
	'ACP_K_MODS_MODULES'			=> 'Mods Modules',
	'ACP_K_MODS_STYLES'				=> 'Styles Modules',
	'ACP_K_MODS_STATUS'				=> 'Status Modules',
	'ACP_K_MODS_MISC'				=> 'Miscellaneous Modules',
	'ACP_K_MODS_MODS'				=> 'Mod Modules',
	'ACP_K_MODS_ALL'				=> 'All Modules',
	'ACP_K_MODS_EDIT'				=> 'Edit Module',
	'ACP_K_MODS_DELETE'				=> 'Delete Module',
	'ACP_K_CONFIG_WELCOME'			=> 'Manage Welcome Message',
	'ACP_K_CONFIG_STYLES'			=> 'Manage Styles Mods',

// Modules Web Pages
	'ACP_K_WEB_PAGES_ADD'			=> 'Add a New Web Page',
	'ACP_K_WEB_PAGES_ALL'			=> 'View all pages',
	'ACP_K_WEB_PAGES_EDIT'			=> 'Edit Web Page',
	'ACP_K_WEB_PAGES_DELETE'		=> 'Delete this web page',
	'ACP_K_WEB_PAGES_BODY'			=> 'Page Bodies',
	'ACP_K_WEB_PAGES_HEAD'			=> 'Page Headers',
	'ACP_K_WEB_PAGES_FOOT'			=> 'Page Footers',
	'ACP_K_WEB_PAGES_PORTAL'		=> 'Manage Portal pages',

// phpBB Pages
	'ACP_K_PAGES'           => 'Manage phpBB pages',
	'ACP_K_PAGES_ADD'		=> 'Add page',
	'ACP_K_PAGES_DELETE'	=> 'Delete page',

// acronyms
	'ACP_K_ACRONYMS'        => 'Manage Acronyms',

// Modules Variables
	'ACP_K_VARS'				=> 'Portal Variables',
	'ACP_K_CONFIG'				=> 'Main Config',
	'ACP_K_PORTAL_CONFIG'		=> 'Portal Config',
	'ACP_K_MODULES'				=> 'Mini Modules ',
	'ACP_K_VARS_CONFIG'			=> 'Portal Variables',
	'ACP_K_VARS_CONFIG2'		=> 'Block Module Variables',
	'ACP_K_POLL'				=> 'Polls',
	'ACP_K_NEWSFEEDS'			=> 'Newsfeeds',
	'ACP_K_ACRONYMS'			=> 'Acronyms',
	'ACP_K_WEB_PAGES'			=> 'Web Pages',
	'ACP_K_REFERRALS'			=> 'Referrals',
	'ACP_K_VARIABLES'			=> 'Set variables',
	'ACP_K_TOOLS'				=> 'Additional Mods and Tools',
	'ACP_MOD_VERSION_CHECK'		=> 'Mod Version Check',
	'ACP_K_QUOTES'				=> 'Quotes',

// Cloud
	'ACP_K_CLOUD'				=> 'Cloud',
	'ACP_K_CLOUD_EXPLAIN'		=> 'Here you can add, edit and delete tags',
	'ACP_K_CLOUD_TAG'			=> 'Tag',
	'ACP_K_CLOUD_TAG_EXPLAIN'	=> 'Here you can add, edit and delete tags',
	'ACP_K_CLOUD_ADD'			=> 'Add a tag',
	'ACP_K_CLOUD_DELETE'		=> 'Delete tag',
	'ACP_K_CLOUD_EDIT'			=> 'Edit tag',
	'ACP_K_CLOUD_BROWSE'		=> 'Browse tags',
	'ACP_K_QUOTES_MANAGE'		=> 'Quotes',

// youtube
	'ACP_K_YOUTUBE'				=> 'SGP youtubes Mod',
	'ACP_K_YOUTUBE_EXPLAIN'		=> 'Here you can manage your yourtube videos.',
	'ACP_K_YOUTUBE_MANAGE'		=> 'Manage youtube videos',
	'ACP_K_YOUTUBE_ADD'			=> 'Add a youtube video',
	'ACP_K_YOUTUBE_BROWSE'		=> 'Browse youtube videos',

// Quotes
	'ACP_K_QUOTES_ADD'			=> 'Add quote',
	'ACP_K_QUOTES_EDIT'			=> 'Edit quote',
	'ACP_K_QUOTES_CONFIG'		=> 'Configure quotes',
	'QUOTE_ADD'					=> 'Add quote',
	'QUOTE_ADDED'				=> 'Quote added to databse',
	'CONFIG_QUOTES'				=> 'Configure quotes',
	'QUOTE_AUTHOR'				=> 'Author',
	'QUOTES_SETTINGS'			=> 'Quote block settings',
	'LOAD_FROM_FILE'			=> 'Load quotes from the quote file?',
	'FILE_TO_USE'				=> 'The name of the quote file to load',
	'FILE_TO_USE_EXPLAIN'		=> 'The quote files should be located in root/store/ folder',

// Resources
	'ACP_K_RESOURCES'			=> 'Portal Resources',

// Country Flag
	'USER_COUNTRY_FLAG'			=> 'User Country Flag',

// BBCODES used in pages
	'BBCODE_A_HELP'				=> 'Inline uploaded attachment: [attachment=]filename.ext[/attachment]',
	'BBCODE_B_HELP'				=> 'Bold text: [b]text[/b]',
	'BBCODE_C_HELP'				=> 'Code display: [code]code[/code]',
	'BBCODE_E_HELP'				=> 'List: Add list element',
	'BBCODE_F_HELP'				=> 'Font size: [size=x-small]small text[/size]',
	'BBCODE_IS_OFF'				=> '%sBBCode%s is <em>OFF</em>',
	'BBCODE_IS_ON'				=> '%sBBCode%s is <em>ON</em>',
	'BBCODE_I_HELP'				=> 'Italic text: [i]text[/i]',
	'BBCODE_L_HELP'				=> 'List: [list]text[/list]',
	'BBCODE_LISTITEM_HELP'		=> 'List item: [*]text[/*]',
	'BBCODE_O_HELP'				=> 'Ordered list: [list=]text[/list]',
	'BBCODE_P_HELP'				=> 'Insert image: [img]http://image_url[/img]',
	'BBCODE_Q_HELP'				=> 'Quote text: [quote]text[/quote]',
	'BBCODE_S_HELP'				=> 'Font color: [color=red]text[/color]  Tip: you can also use color=#FF0000',
	'BBCODE_U_HELP'				=> 'Underline text: [u]text[/u]',
	'BBCODE_W_HELP'				=> 'Insert URL: [url]http://url[/url] or [url=http://url]URL text[/url]',
	'BBCODE_D_HELP'				=> 'Flash: [flash=width,height]http://url[/flash]',

// Stargate aka Kiss Plugins module
	'ACP_CAT_K_BLOCKS_MANAGE'			=> 'Portal Blocks',
	'ACP_CAT_K_MENUS_MANAGE'			=> 'Portal Menus',
	'ACP_CAT_K_MODULES_MANAGE'			=> 'Portal Mini Modules',
	'ACP_CAT_K_TOOLS_MANAGE'			=> 'Portal Tools & other Mods',
	'ACP_CAT_K_WEB_PAGES_MANAGE'		=> 'Portal Web Pages',
	'ACP_CAT_PLUGINS'					=> 'Plugins',
	'ACP_CAT_PLUGINS_EXPLAIN'			=> 'Stargate Portal Plug-ins Mod Manager',
	'ACP_PLUGINS'						=> 'Plugins',
	'ACP_PLUGIN_VARIABLES'				=> 'Configure Defaults',
	'ACP_PLUGIN_CONFIG'					=> 'Configure Plugin Defaults',
	'ACP_PLUGIN_MANAGE'					=> 'Manage Plugin',
	'ACP_PLUGIN_ADD'					=> 'Add New Plugin',
	'ACP_PLUGIN_EDIT'					=> 'Edit Plugin',
	'ACP_PLUGIN_DELETE'					=> 'Delete Plugin',
	'ACP_PLUGIN_UPDATE'					=> 'Update plugin',
	'ACP_PLUGIN_UP'						=> 'Move Up',
	'ACP_PLUGIN_DOWN'					=> 'Move Down',
	'AVAILABLE_FORUM_IMAGE'				=> 'Available Images',
	'AVAILABLE_FORUM_IMAGE_EXPLAIN'		=> 'Display a list of available forum images (images/forum_icons/). Hover over an image to see the path/name...',
	'SHOW_FORUM_IMAGES'					=> 'Show available forum images.',
	'ENABLE_PORTAL'						=> 'Enable Portal',

// phpbbportal profile fields
// Mike
	'MOD_IMAGES'	=> 'The image mod allows the admin to select images for editing where images are stored in the Admins current style.',
	'MOD_ICONS'		=> 'The image mod allows the admin to select icons for editing where icons are stored in the Admins current style.',
	'VARS_FOUND'	=> 'Editing config for',
	'EDITOR_NAME'	=> 'SGP Simple edit',
	'SMILIES'		=> 'Basic Smilies',
	'SHOW_VARS'		=> 'Add this variable',
	'VARIABLES'		=> 'Variables',

// Errors
	'ERROR_PORTAL_MODULE'			=> 'Error! Could not query portal modules information: ',
	'ERROR_PORTAL_ANNOUNCE'			=> 'Error! Could not query announcements information',
	'ERROR_PORTAL_BLOCKS'			=> 'Error! Could not query <strong> Blocks Table</strong>',
	'ERROR_PORTAL_NEWS'				=> 'Error! Could not query news data',
	'ERROR_PORTAL_RECENT_TOPICS'	=> 'ERROR! Could not query recent topics data',
	'ERROR_PORTAL_FORUMS'			=> 'Error! Could not query forums information',

	'WARNINGIMG_DIR'				=> 'Check to see if you added the image directory!',

// phpBB pages (k_pages)
	'ACP_K_PAGES_MANAGE'	=> 'Manage phpBB pages',
	'ADD_VARS'				=> 'Manage variables',

// phpBB common to sgp //
	'ACTION_CANCELLED'		=> 'Action Cancelled',
	'ADDED'					=> 'Data added...',
	'ADDING_MODULES'		=> 'Adding modules...',
	'BLOCK_ADDED'			=> 'Block added',
	'BLOCK_DELETED'			=> 'Block deleted',
	'DELETED'				=> 'Deleted... ',
	'DELEING'				=> 'Deleting... ',
	'FOUND'					=> '<strong>Found:</strong> ',
	'FUTURE_DEVELOPMENT'	=> 'Planned for future development',
	'MANAGE'				=> 'Manage',
	'MENU_CREATED'			=> 'Menu created...',
	'NONE'					=> 'none',
	'NOT_ASSIGNED'			=> 'Not assigned',
	'PROCESSING'			=> 'Processing...',
	'SAVED'					=> 'Database updated...',
	'TOOLS'					=> 'Tools',
	'TOOLS_1'				=> 'Tools moved to independant Porgram. Contact Site Admin for details',
	'TOOLS_2'				=> 'Tools include: Block Builder, Style and CSS Builder',

// teams block
	'TEAMPAGE_DISP_ALL'     => 'Show users in all groups where they are a member.',
	'TEAMPAGE_DISP_DEFAULT' => 'Show users in their default group only.',
	'TEAMPAGE_MEMBERSHIPS'  => 'Group membership display options',

// donations block
	'ACP_K_DONATIONS'          => 'Donations Mod',
	'ACP_K_DONATIONS_EXPLAIN'  => 'Here you can manage (add/edit/delete) individual entries for the donations block.',
	'ACP_K_DONATIONS_ADD'      => 'Add a donation',
	'ACP_K_DONATIONS_BROWSE'   => 'Browse all donations',
	'DONATIONS_CREATED'        => 'Donation added',

// module install
	'ACP_CONFIG'            => 'Portal ACP',
	'ACP_PORTAL_TITLE'		=> 'Portal',
	'ACP_CONFIG_TITLE'		=> 'Settings',
	'ACP_BLOCKS_TITLE'		=> 'Blocks',
	'ACP_MENUS_TITLE'		=> 'Menus',
	'ACP_PAGES_TITLE'		=> 'Pages',
	'ACP_VARS_TITLE'		=> 'Variables',
	'ACP_PORTAL_CONFIG'		=> 'Configure portal',
	'ACP_VARS_CONFIG'       => 'Manage variables',
	'ACP_RESOURCES_TITLE'	=> 'Resources',
	'BLOCK_LAYOUT_RESET'    => 'All members layouts have been cleared/reset...',

	'K_PORTAL_ENABLED'		=> 'Enable portal',
	'K_PORTAL_BUILD'		=> 'Portal build',
	'K_BLOCKS_ENABLED'		=> 'Enable blocks',
	'K_BLOCKS_WIDTH'		=> 'Block width',
	'PORTAL_CONFIG'			=> 'Portal config',


// modules ucp
	'UCP_PORTAL_TITLE'		=> 'Portal Options',
));
