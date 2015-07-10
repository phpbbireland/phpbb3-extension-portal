<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbbireland\portal\helpers;

class tables
{
	/**
	* Array with a map from short table name to full db table name
	* @var array
	*/
	protected $tables;

	/**
	* Constructor
	*
	* @param string $name
	*/

	public function __construct()
	{
		$k_config    = 'k_config';
		$k_vars      = 'k_vars';
		$k_blocks    = 'k_blocks';
		$k_menus     = 'k_menus';
		$k_pages     = 'k_pages';
		$k_resources = 'k_resources';

		$this->tables = array(
			'k_config'	    => $k_config,
			'k_vars'        => $k_vars,
			'k_blocks'		=> $k_blocks,
			'k_pages'		=> $k_pages,
			'k_menus'		=> $k_menus,
			'k_resources'	=> $k_resources,
		);
	}

	public function get($table_name)
	{
		global $table_prefix;

		if (isset($this->tables[$table_name]))
		{
			return $table_prefix . $this->tables[$table_name];
		}
		throw new \phpbbireland\portal\exception('Table [' . $table_name . '] not found');
	}
}
