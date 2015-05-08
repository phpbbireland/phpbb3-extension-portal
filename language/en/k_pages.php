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
	'ACP_PAGES'					=> 'Pages',
	'ACP_K_PAGES'				=> 'phpBB pages',
	'ACP_K_PAGES_LAND'			=> 'Set landing page',
	'ACP_K_PAGES_MANAGE'		=> 'Manage phpBB pages',
	'ACP_K_RESOURCES'			=> 'Portal Resources',
	'ACP_K_RESOURCES'			=> 'Manage portal resources',
	'ADD_PAGE'					=> 'Add page',
	'ADDING_PAGES'				=> 'Page added... ',
	'CONFIG_PAGES'				=> 'Config pages',
	'DELETE_FROM_LIST'			=> 'Remove this page from list?',
	'ERROR_PORTAL_PAGES'		=> 'Error! deleting this page from database list',
	'FOLDER_ADDED'              => 'Mod directories list updated...',
	'ID'						=> 'ID',
	'LAND'                      => 'Set the default page to load on logout.<br />
	Please note, when you login,<br />
	you will be returned to the page you logged in from.<br /><br />
	phpBB 3.1 will the above behaviour',

	'LANDING_PAGE'				=> 'Landing page',
	'LANDING_PAGE_EXPLAIN'		=> 'Return to this page after successful login.',
	'LANDING_PAGE_SET'			=> 'Landing page set',
	'LINE'						=> ', line ',
	'MANAGE_PAGES'				=> 'Manage pages',
	'MOD_FOLDERS'               => 'Search addition folders',
	'MOD_FOLDERS_EXPLAIN'       => 'Example: gallery, facebook, other (Submit to add to dropdown)',
	'NO_FILES_FOUND'			=> 'The dropdown is unavailable as there are no files to add...',
	'NO_MOD_FOLDER'             => 'The folder you are trying to add could not be found: root/',

	'PAGE_INFO'					=> '&bull; PAGE: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>List current pages where blocks can be displayed.<br />',
	'PAGE_INFO2'				=> '&bull; REMOVE: &nbsp;&nbsp; Pressing the delete icon, will delete the associated page from the list.<br />',
	'PAGE_INFO3'				=> '&bull; LANDING PAGE: Not implemented for phpBB 3.1 atm.<br /><br />',
	'PAGE_NEW_FILENAME'			=> 'Add this file (page) to the list',
	'PAGE_NEW_FILENAME_EXPLAIN'	=> 'Select file (page) from the dropdown and hit Submit...',
	'REMOVING_PAGES'			=> 'Page removed... ',
	'SWITCHING'					=> 'Switching to k_pages',
	'TRAILING_COMMA'            => 'Removed tailing comma from Mod folder list...',
	'TITLE_PAGES'               => 'phpBB pages',
	'TITLE_EXPLAIN_PAGES'		=> '<br />Blocks can be displayed on valid pages including phpBB, Extension and Web pages...',
));
