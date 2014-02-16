<?php
/**
*
* portal_kiss_refresh [English]
*
* @package language (Kiss Portal Engine / Stargate Portal)
* @version $Id$
* @copyright (c) 2005-2013 phpbbireland
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
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//
//- Stargate/Kiss portal engine lang definitions -//

$lang = array_merge($lang, array(
	//SGP Refresh ALL
	'CACHE_PURGED'			=> '<br />&nbsp;&#187;&nbsp;Cache purged!',
	'CACHE_DIR_CLEANED'		=> '<br />&nbsp;&#187;&nbsp;cache directory cleaned up.',
	'DISABSLE_USE'			=> '<br />&nbsp;&#187;&nbsp;Disabled, use refresh in ACP for time being...',
	'DATABASE_TABLE'		=> ' database table!</b>',
	'FAILED_UPDATE'			=> '<br /><b>Failed to update ',
	'NO_INFO_FOUND'			=> '<br /><b>No info found in ',
	'PURGING_CACHE'			=> '<b>Purging cache:</b>',
	'REFRESHED'				=> ' - <b>refreshed</b>',
	'REFRESHING_TEMPLATES'	=> '<b>Refreshing styles templates:</b>',
	'REFRESHING_THEMES'		=> '<b>Refreshing styles themes:</b>',
	'REFRESHING_IMAGESETS'	=> '<b>Refreshing styles imagesets:</b>',
	'SGP_REFRESH_ALL'		=> '<strong>Kiss Refresh All - version: 1.0.1</strong>',
	'SGP_REFRESH_TITLE'		=> 'Refresh All (1.0.1)',
	'SGPRA_EXEPTIONS'		=> '<strong><span class="red">!NOTE:</span><br />SGP Refresh ALL completed with exceptions!</strong> (see above for info)<br />',
	'SGPRA_LOG_IN'			=> '<strong>log in</strong></a> as an <strong class="red">ADMINISTRATOR</strong> and <strong class="green">refresh</strong> this page...<br /><br /><hr />',
	'SGPRA_NO_ADMIN'		=> '<strong class="red">You do not have permission to run SGP Refresh ALL!</strong>',
	'SGPRA_NO_ERRORS'		=> '<br /><strong class="green">SGP Refresh ALL completed without any errors!...</strong><br />',
));

?>