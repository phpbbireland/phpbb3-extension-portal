<?php
/**
*
* @package Kiss Portal Engine
* @version $Id$
* @author  Michael O'Toole - aka michaelo
* @begin   Saturday, Jan 22, 2005
* @copyright (c) 2005-2013 phpbbireland
* @home    http://www.phpbbireland.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

global $phpbb_root_path, $config, $k_config, $phpEx, $SID, $userm, $table_prefix;

include_once($phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_functions.' . $phpEx);

generate_menus();
