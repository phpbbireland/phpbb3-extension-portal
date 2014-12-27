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

// SGP Teams Block 15 March 2011
$lang = array_merge($lang, array(

	'K_BLOCK_TEAMS'              => 'Select teams to display in block',
	'K_BLOCK_TEAMS_EXPLAIN'      => 'This dropdown is reusable, each selection will update the list.<br />Selecting <strong>none</strong> will reset the list.',
	'K_BLOCK_TEAMS_SORT'         => 'Sort By',
	'K_BLOCK_TEAMS_SORT_EXPLAIN' => 'Determines the team sort order.',

	'K_TEAM_BLOCK_TITLE'    => 'Team Block Varaibles',
	'K_TEAMS'               => 'These teams will be displayed in the block',
	'K_TEAMS_EXPLAIN'       => 'Enter team id’s in comma separated list.',

	'TEAM'                  => 'Team',
	'THE_TEAM'              => 'The Team',
	'TEAM_MAX_COUNT'        => '(limiting: %s per team)',
	'THE_TEAM_SETTINGS_2'         => 'Limit to members',
	'TEAMPAGE_DISP_ALL'     => 'Show users in all groups where they are a member.',
	'TEAMPAGE_DISP_DEFAULT' => 'Show users in their default group only.',
	'TEAMPAGE_MEMBERSHIPS'  => 'Group membership display options',

	'NO_TEAMS'              => 'No teams selected!<br />Can be added in<br /> ACP > PORTAL > BLOCKS (team block variables)',
));
