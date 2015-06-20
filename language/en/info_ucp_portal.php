<?php
/**
*
* info_ucp_portal [English]
*
* @package Kiss Portal Engine / Stargate Portal
* @version $Id$
* @copyright (c) 2005-2015 phpbbireland
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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

$lang = array_merge($lang, array(
	'ARRANGE_BLOCKS'		=> '',
	'ARRANGE_NOW'			=> ' &bull; Arrange Blocks &bull; Tools.',
	'ARRANGE_ICO'			=> 'Arrange Icon',
	'ARRANGE_ICON'			=> 'Arrange Blocks, lets you organise blocks from the portal page...<br />You can <strong>move</strong>, <strong>hide</strong> or, <strong>un-hide</strong> them as you wish...<br /><br />Depending on the style, the arrange icon (or text links) are normally located in the header to the left of the phpBB text resize icon...<br /><br />Please note, changes are automatically saved when browse to any othe page... <br /><br />Some arrange icons/text examples: ',

	'B_VERSION'				=> 'build',

	'DEFAULT_BLOCKS'		=> 'Default blocks',
	'DELETE_BLOCKS'			=> 'Check to reset blocks',
	'DELETE_BLOCKS_EXPLAIN'	=> '(to default positions).',
	'DEV_SITE'				=> 'Development site: ',

	'OPTION_WIDTH'			=> 'Wide/Narrow',
	'P_VERSION'				=> 'Installed version',

	'UCP_K_PORTAL'			=> 'Portal',
	'UCP_K_BLOCKS'			=> 'Portal Options',
	'UCP_K_BLOCKS_ARRANGE'	=> 'Arrange blocks',
	'UCP_K_BLOCKS_DELETE'	=> 'Delete layout',
	'UCP_K_BLOCKS_EDIT'		=> 'Edit blocks layout',
	'UCP_K_BLOCKS_INFO'		=> 'Portal information',
	'UCP_K_BLOCKS_WIDTH'	=> 'Set page width',

	'UCP_K_INFO_ARRANGE'	=> 'NOT SET',
	'UCP_K_INFO_DELETE'		=> 'Delete your current layout and use the board defaults.<br /><br />',
	'UCP_K_INFO_EDIT'		=> 'Manually edit your current block layout.<br />Can also be used to add newly installed blocks...<br /><br />',
	'UCP_K_INFO_INFO'		=> 'Some basic portal information here......',
	'UCP_K_INFO_WIDTH'		=> '<br />If your style support it, click on these images below to set the overall page width',

	'UCP_K_LINK'			=> '<a href="%s"> Arrange Blocks</a>',
	'UCP_K_NOT_SAVED'		=> 'Block data could not be saved!',
	'UCP_K_RESET'			=> 'Blocks reset to default positions... refreshing page... <br />',
	'UCP_K_SAVED'			=> 'Your layout has been updated... <br />',

	'LEFT_BLOCKS'			=> 'Left blocks',
	'LEFT_BLOCKS_EXPLAIN'	=> 'comma separated string',
	'CENTER_BLOCKS'			=> 'Centre blocks',
	'CENTER_BLOCKS_EXPLAIN' => 'comma separated string',
	'RIGHT_BLOCKS'			=> 'Right blocks',
	'RIGHT_BLOCKS_EXPLAIN' 	=> 'comma separated string',

	'UCP_PORTAL_TITLE'	=> 'Portal Options',
));
