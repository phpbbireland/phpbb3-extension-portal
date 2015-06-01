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
// phpbbportal profile fields
$lang = array_merge($lang, array(
	'ACP_MENUS'                => 'Menus',
	'ADD_ICON'                 => 'Add new icon',
	'ALL_GROUPS'               => 'All Groups',
	'ALL_MENUS'                => 'Show All Menu Items',
	'CONFIRM_OPERATION_MENUS'  => 'Do you wish to delete this Menu?',
	'CREATE'                   => 'Create Menu',
	'DELETED'                  => ' has been deleted<br />',
	'DO_NOT_EDIT'              => 'Note: Do not edit this value',
	'FOOT'                     => 'Footer',
	'FOOT_MENU'                => 'Footer Menu',
	'HEAD'                     => 'Header',
	'HEADER_MENU'              => 'Header Menu',
	'ICON'                     => 'Icon',
	'LINE'                     => ', line ',
	'LINKS'                    => 'Links',
	'LINKS_MENU'               => 'Links Menu',
	'MANAGE_MENU_ICONS'        => 'Manage Menu Icons: ',
	'MENU'                     => 'Menu',
	'MENUID'                   => 'ID',
	'MENU_ID'                  => 'ID',
	'MENU_ID_BIG'              => 'Menu ID (Auto)',
	'MENU_ADD'                 => 'Add',
	'MENU_ADD_NEW'             => 'Add a New Menu Option',
	'MENU_ACTIVE'              => 'A',
	'MENU_ACTIVE_BIG'          => 'MENU is Active',
	'MENU_ADDED'               => 'Menu added!',
	'MENU_APPEND_SID_BIG'      => 'Append SID to link.',
	'MENU_APPEND_UID_BIG'      => 'Append UID to link.',
	'MENU_APPEND_SID'          => 'SID',
	'MENU_APPEND_UID'          => 'UID',
	'MENU_APPEND_SID_EXPLAIN'  => 'The SID, may be required by code/blocks.',
	'MENU_APPEND_UID_EXPLAIN'  => 'The user ID may be required by code/blocks.',
	'MENU_DOWN'                => 'Down',
	'MENU_DISABLED'            => 'Dis',
	'MENU_DISABLED_BIG'        => 'MENU is Disabled',
	'MENU_EDIT'                => 'Edit',
	'MENU_EDITED'              => 'Menu edited!',
	'MENU_DELETE'              => 'Del.',
	'MENU_EXTERN'              => 'Link Options',
	'MENU_EXTERN_EXPLAIN'      => 'Open this link in browser tab:',
	'MENU_EXTERN_NORMAL'       => 'Normal',
	'MENU_EXTERN_SHORT'        => 'Extrn',
	'MENU_EXTERN_TAB'          => 'Tab',
	'MENU_EXTERN_WINDOW'       => 'Window',
	'MENU_ICON'                => 'Menu Icon',
	'MENU_ICONS'               => 'Available menu icon images',
	'MENU_ICON_ALT_TEXT'       => 'Image alt text',
	'MENU_ICONS_HEADER'        => 'Manage Menu Icons ',
	'MENU_ICON_THIS'           => ' current icon = ',
	'MENU_ICON_BIG'            => 'Menu item image to use',
	'MENU_ICON_COUNT'          => 'Available Icons ',
	'MENU_ICON_EXPLAIN'        => 'Icon to use with this menu item. ',
	'MENU_ICON_IMG'            => 'available icon images',
	'MENU_ICONS_L'             => '(images/block_images/menu)',
	'MENU_ITEM_NAME'           => '* Menu item name/title',
	'MENU_ITEM_NAME_EXPLAIN'   => 'Will be replaced by user language variable if it exist in block_menus_vars.php, else the value shown here will be used.',
	'MENU_ITEM_NAME_SHORT'     => 'Name',
	'MENU_LINK'                => 'Menu link',
	'MENU_LINK_BIG'            => '* Link to file',
	'MENU_LINK_EXPLAIN'        => 'Link associated with this menu item.',
	'MENU_MOVE_ERROR'          => 'The ndx’s values are not consecutive, they have been corrected...<br />Use the back arrow and refresh page, you can then move the item... ',
	'MENU_NAME_1'              => 'Header Menu',
	'MENU_NAME_2'              => 'Navigation Menu',
	'MENU_NAME_3'              => 'Sub Menu',
	'MENU_NDX'                 => 'NDX',
	'MENU_NDX_BIG'             => 'Menu Index (for sort order)',

	'MUST_SELECT_VALID_MENU_DATA'  => 'Invalid menu ID.',

	'MENU_TYPE'                 => '* Type',
	'MENU_TYPE_BIG'             => 'File Type',
	'MENU_TYPE_EXPLAIN'         => 'The position of the menu (you must select one option).',
	'MENU_TYPE_FOOTER'          => 'Footer Menu',
	'MENU_TYPE_HEADER'          => 'Header Menu',
	'MENU_TYPE_LINKS'           => 'Links Menu',
	'MENU_TYPE_NAV'             => 'Nav Menu',
	'MENU_TYPE_UNALLOC'         => 'Unassigned',
	'MENU_TYPE_SUB'             => 'Sub Menu',
	'MENU_UP'                   => 'Up',
	'MENU_UPDATED'              => 'Menu updated',
	'MENU_VIEW_ALL'             => 'Optionally',
	'MENU_VIEW_ALL_EXPLAIN'     => 'Ignore these setting and set this menu/menu item visibility to <strong>all</strong> groups.',
	'MENU_VIEW_BY'              => 'Groups',
	'MENU_VIEW_BY_SHORT'        => 'View By',
	'MENU_VIEW_BY_EXPLAIN'      => 'Select a group to add to the current list.<br />Selecting <b>(None)</b> will empty the list.',
	'MENU_VIEWED_BY'            => 'Which groups can view this menu item?',
	'MENU_VIEWED_BY_EXPLAINED'  => 'Admin can see all menu items...',
	'MENU_VIEW_GROUPS'          => 'Menu Group Visibility',
	'MENU_VIEW_GROUPS_EXPLAIN'  => 'Enter group ID(s) manually (comma separated) or use the dropdown below to automatically add to the list.',
	'MENUS_ADD_HEADER'          => 'Add a menu item:',
	'MENUS_ALL_ITEMS'           => 'All Menus and Items',
	'MENUS_HEADER_ADMIN'        => 'Menu Management',
	'MENUS_EDIT'                => 'Edit Menu.',
	'MISC'                      => 'Manage Unallocated Items',
	'MISSING_DATA'              => '<br />You are missing one or more required fields... Please use browser back arrow and add required fields',
	'NAV'                       => 'Nav',
	'NAV_MENU'                  => 'Navigation Menu',
	'OPEN_SAME'                 => 'No',
	'OPEN_TAB'                  => 'In Tab',
	'OPEN_WIN'                  => 'Win',
	'OTHER_MENUS'               => 'Other Menus',
	'PROCESS'                   => 'process',
	'PORTAL_ERROR_MENU'         => 'Could not query portal menus information',
	'UNDER_CONSTRUCTION'        => '<strong>Under construction... <br />This section of the portal ACP is for future development (links are disabled)...</strong><br /><br />Here you can see all available block images, later you will be able to add, delete and update images...',
	'UNKNOW_MENU'               => 'Unknown Menu/Option',
	'UNALLOC'                   => 'Unallocated',
	'SOFT_HR'                   => 'Add a soft return (image or spacer) between this and the next menu item',
	'SOFT_HR_EXPLAIN'           => 'You can use this to group menu items...',
	'SOURCE_DIRECTORY'          => 'Source Directory = images/block_images/ (menu | block)',
	'SORT_ORDER_UPDATING'       => 'Sort order updating... please wait!<br />',
	'SUB'                       => 'Sub',
	'SUB_HEADING'               => 'Menu Sub Heading',
	'SUB_HEADING_EXPLAIN'       => 'This is a Menu Sub Heading... no links allowed.',
	'SUB_MENU'                  => 'Sub Menu',

	'TITLE'                 => 'Stargate (aka Kiss) Menu Manager (Administration)',
	'TITLE_EXPLAIN'         => '&bull; The <strong>*</strong> indicated the fields cannot be empty<br />&bull; Menu names will be replaced with user language variables, if none exits, the values show below will be used.',
	'TITLE_EXPLAIN_EXPAND'  => '&bull; Fields marked with * cannot be empty.<br />Legend: NDX = Index or sort order, Icon = Menu Icon (each menu item can have a different icon), Alt = Alternative text, Link = Link to whatever... <br />Menu icons are located in images/block_images/menu directory.',
	'TITLE_ICON_EXPLAIN'    => 'Manage Icons',
));

// Message Settings
$lang = array_merge($lang, array(
	'ACP_MESSAGE_SETTINGS_EXPLAIN'  => 'Here you can set all default settings for private messaging',
));
