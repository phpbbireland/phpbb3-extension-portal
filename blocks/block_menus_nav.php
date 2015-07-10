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

global $phpbb_root_path, $phpEx;

$this->php_ext = $phpEx;
$this->phpbb_root_path = $phpbb_root_path;

include_once($this->phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_functions.' . $this->php_ext);

generate_menus();
