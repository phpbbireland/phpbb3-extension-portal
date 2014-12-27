<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbbireland\portal\acp;

class portal_constants {

	global $table_prefix;

	define('WELCOME_MESSAGE', 1);
	define('UN_ALLOC_MENUS', 0);
	define('NAV_MENUS', 1);
	define('SUB_MENUS', 2);
	define('HEAD_MENUS', 3);
	define('FOOT_MENUS', 4);
	define('LINKS_MENUS', 5);
	define('ALL_MENUS', 90);
	define('UNALLOC_MENUS', 99);
	define('OPEN_IN_TAB', 1);
	define('OPEN_IN_WINDOW', 2);

	define('K_CONFIG_TABLE',		$table_prefix . 'k_config');
	define('K_BLOCKS_TABLE',		$table_prefix . 'k_blocks');
	define('K_MENUS_TABLE',			$table_prefix . 'k_menus');
	define('K_PAGES_TABLE',			$table_prefix . 'k_pages');
	define('K_RESOURCES_TABLE',		$table_prefix . 'k_resources');
	define('K_YOUTUBE_TABLE',		$table_prefix . 'k_youtube');
}