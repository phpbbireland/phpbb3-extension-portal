<?php
/**
*
* @package phpBB Portal Extension
* @copyright (c) 2013 phpbbireland
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbireland\portal\helpers;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

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
		$k_blocks    = 'k_blocks';
		$k_menus     = 'k_menus';
		$k_pages     = 'k_pages';
		$k_resources = 'k_resources';
		$k_youtube   = 'k_youtube';
		$k_acronyms   = 'k_acronyms';

		$this->tables = array(
			'k_config'	    => $k_config,
			'k_blocks'		=> $k_blocks,
			'k_pages'		=> $k_pages,
			'k_menus'		=> $k_menus,
			'k_resources'	=> $k_resources,
			'k_youtube'		=> $k_youtube,
			'k_acronyms'    => $k_acronyms,

		);
	}

	public function get($table_name)
	{
		global $table_prefix;

		if (isset($this->tables[$table_name]))
		{
			return $table_prefix . $this->tables[$table_name];
		}
		//throw new \phpbbireland\portal\exception('Table [' . $owner . '] not found');
		echo 'Not found [' . $table_prefix . $table_name . ']<br />';
	}
}
