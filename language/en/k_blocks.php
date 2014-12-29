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
	'ACP_BLOCKS'                  => 'Blocks',
	'ACP_BLOCK_TOOLS'             => 'Blocks Tools',
	'BLOCK_ACTIVE'                => 'Block is Active',
	'BLOCK_ADDED'                 => ' Block Added!',
	'BLOCK_CACHE_TIME'            => 'Set the block cache time.',
	'BLOCK_CACHE_TIME_EXPLAIN'    => 'Default cache time for blocks (600).',
	'BLOCK_CACHE_TIME_HEAD'       => 'Block Cache Time',
	'BLOCK_DELETE'                => 'Del.',
	'BLOCK_DELETED'               => ' Block deleted',
	'BLOCK_DISABLED_BIG'          => 'Block is Disabled',
	'BLOCK_EDITED'                => ' Block Edited!',
	'BLOCK_FNAME_EXPLAIN'         => '(styles/portal_common/template/blocks)',
	'BLOCK_FNAME_H'               => 'Filename (.html)',
	'BLOCK_FNAME_H_BIG'           => 'Block Filename.html',
	'BLOCK_FNAME_I'               => 'Icon',
	'BLOCK_FNAME_I_EXPLAIN'       => 'Please note, image filenames cannot contain spaces.',
	'BLOCK_FNAME_I_BIG'           => 'Block Mini image',
	'BLOCK_FNAME_IS_BIG'          => 'Mini block images found',
	'BLOCK_FNAME_IS_BIG2'         => '(images/block_images/block)',
	'BLOCK_FNAME_P'               => 'Filename (.php)',
	'BLOCK_FNAME_P_BIG'           => 'Block Filename.php',
	'BLOCK_G_COUNT'               => 'Generic store for blocks',
	'BLOCK_G_COUNT_EXPLAIN'       => 'The number of Announcements, News Items or Recent Topics to display if scrolling is disabled in their associated blocks.',
	'BLOCK_INDEX'                 => '(Index / Sort Order)',
	'BLOCK_LINE'                  => ', line ',
	'BLOCK_MOVE_ERROR'            => 'Blocks require reindexing...<br /><br />The block ndx values are out of sequence, you can go back and correct these manually or use <br /><br /><strong>Manage All Blocks</strong> option on the left and click on [Re-index blocks] button...<br /><br />Once you have corrected the ndx values you can proceed to move blocks.<br />',
	'BLOCK_NDX'                   => 'NDX',
	'BLOCK_POSITION_BIG'          => 'Block Position',
	'BLOCK_SCROLL'                => 'S',
	'BLOCK_TITLE'                 => 'Block Title',
	'BLOCK_TYPE'                  => 'T',
	'BLOCK_TYPE_BIG'              => 'File Type',
	'BLOCK_UPDATED'               => ' Block Updated',
	'BLOCK_VIEW_ALL'              => 'Optionally',
	'BLOCK_VIEW_ALL_EXPLAIN'      => 'Ignore these setting and set this Block visibility to <strong>all</strong> groups.',
	'BLOCK_VIEW_BY'               => 'Groups',
	'BLOCK_VIEW_BY_EXPLAIN'       => 'Select a group to add to the current list.<br />Selecting <b>(None)</b> will empty the list.',
	'BLOCK_VIEW_GROUPS'           => 'Block Group Visibility',
	'BLOCK_VIEW_GROUPS_EXPLAIN'   => 'Enter group ID(s) manually (comma separated) or use the dropdown below to automatically add to the list.',
	'BLOCK_SCROLL_BIG'            => 'Allow Scrolling',
	'BLOCK_SCROLL_BIG_EXPLAIN'    => 'Yes, block (data) will scroll, No, block is static.',
	'BLOCK_UPDATING'              => 'Block positions updated...<br />',
	'BLOCK_VAR_FILE'              => 'Select the block config file',
	'BLOCK_VAR_FILE_EXPLAIN'      => '(vars will be displayed below, location d in adm/style/k_block_vars folder).',

	'BLOCKS_ADD_HEADER'           => 'Add a new block',
	'BLOCKS_AUTO_REINDEXED'       => 'The block index has been corrected...',
	'BLOCKS_HEADER_ADMIN'         => 'Block Management',
	'BLOCKS_REINDEX'              => 'Re-index blocks',
	'BLOCKS_REINDEXED'            => 'All blocks have been Re-Indexed',
	'BLOCKS_TITLE'                   => 'Block Administration/Management',
	'BLOCKS_TITLE_EXPLAIN'           => '&bull; Block titles will be replaced with user language variables, if none exits, the values show below will be used.<br />&bull; The last edited block is highlighted (bold).',
	'BLOCKS_TITLE_EXPLAIN_EXPAND'    => '&bull; The NDX indicates position relative to other blocks in the same column.<br />&bull; Block html files are located in: styles/_portal_common/template/blocks folder.',

	'CONFIRM_OPERATION_BLOCKS'            => 'Do you wish to delete this block?',
	'CONFIRM_OPERATION_BLOCKS_REINDEX'    => 'Do you wish to re-index the blocks?',
	'DELETE_THIS_BLOCK'                   => 'Delete this block',
	'DO_NOT_EDIT'                         => ' (Do not edit this value)',
	'EDIT_BLOCK'                          => 'Edit block',
	'HAS_VARS'                            => 'Block contains configurable data',
	'HAS_VARS_EXPLAIN'                    => '(config info is stored in the database).',

	'MANAGE_PAGES'                   => 'Manage pages',
	'MINIMOD_BASED'                  => 'Is this block based on a SGP minimod?',
	'MINIMOD_BASED_EXPLAIN'          => 'Select Yes, if Block is based on a portal minmod? (adjusted elsewhere)',
	'MINIMOD_OPTIONS'                => 'Which minimod is associated with this block?',
	'MINIMOD_OPTIONS_EXPLAIN'        => 'Ignore if block is not based on a minimod.',
	'MINIMOD_DETAILS_SHOW'           => 'This block is based on a minimod, this is a link to it!',
	'MINIMOD_DETAILS_NO_EDIT'        => 'Block is not a minimod',
	'MISSING_FILE_OR_FOLDER'         => '%s is missing',
	'MUST_SELECT_VALID_BLOCK_DATA'   => 'Invalid block ID',

	'NO_VAR_FILE'                    => 'The %s block variable file was not found, please add the missing file...<br />Please use browser back button...',
	'PAGE_ARRAY'                     => 'Array of page',
	'PAGE_ARRAY_EXPLAIN'             => 'List of all block where block is visible',
	'PORTAL_BLOCKS_ENABLED'          => 'Portal blocks enabled',

	'SET_VARIABLES_IN_MINI-MODULES'  => 'Set variables in Mini-Modules',

	'UNKNOWN_ERROR'                  => 'Error not processing saved data<br />',
	'VARS_HAS_EDIT'                  => 'Set block variables',
	'VARS_NO_EDIT'                   => 'Block has no variables',

	'VIEW_PAGE'                 => 'Add page from available pages:',
	'VIEW_PAGE2'                => 'Available pages:',
	'VIEW_PAGES'                => 'Page ID where the block will be displayed',
	'VIEW_PAGE_EXPLAIN'         => 'Select from this list (reusable) to add, selecting <strong>None</strong> will empty the list.',
	'VIEW_PAGE_EXPLAIN2'        => 'Select the pages where this block will be visible.<br /><br /><strong>Notes:</strong><br />Blocks will only be visible on pages that support blocks.<br />We do not process blocks if the information they contain is already process by the page they are displayed on.',
	'VIEW_PAGES_EXPLAIN'        => 'The list will be updated automatically.',

	'PAGE_CENTRE'                    => 'Page Centre',
	'PAGE_LEFT'                      => 'Page Left',
	'PAGE_RIGHT'                     => 'Page right',
	'RIGHT_OF_CENTRE'                => 'Right (centre 2x)',
	'LEFT_OF_CENTRE'                 => 'Left (centre 2x)',

));

// Message Settings
$lang = array_merge($lang, array(
	'ACP_MESSAGE_SETTINGS_EXPLAIN'    => 'Here you can set all default settings for private messaging',
));

// common single words
$lang = array_merge($lang, array(
	'ACTIVE'      => 'Active',
	'ALL_GROUPS'  => 'All Groups',
	'BBCODE'      => 'BBcode',
	'DISABLED'    => 'Disabled',
	'DOWN'        => 'Down',
	'EDIT'        => 'Edit',
	'HTML'        => 'HTML',
	'ID'          => 'ID',
	'MOVE'        => 'Move',
	'MOVE_DOWN'   => 'Move Down',
	'MOVE_UP'     => 'Move Up',
	'NONE'        => 'None',
	'POSITION'    => 'Position',
	'PROCESS'     => 'process',
	'SAVING'      => 'Changes saved...',
	'SAVED'       => 'Data saved...',
	'VIEW_BY'     => 'View By',
	'UP'          => 'Up',
));
