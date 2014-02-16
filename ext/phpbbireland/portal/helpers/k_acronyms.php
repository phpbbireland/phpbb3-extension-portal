<?php
/**
*
* @package phpBB Portal Extension
* @copyright (c) 2013 phpbbireland
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbireland\core\helpers;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

class k_acronym
{
	const OWNER_PUBLIC		= 0;
	const TYPE_ACRONYM		= 0;
	const STATUS_OPEN		= 0;
	const STATUS_LOCKED		= 1;

	public function get_owner($owner)
	{
		switch ($owner)
		{
			case 'public':
				return self::OWNER_PUBLIC;
		}
		throw new \phpbbireland\core\exception('k_acronym [' . $owner . '] not found');
	}

	public function get_type($type)
	{
		switch ($type)
		{
			case 'k_config':
				return self::TYPE_CFG;
		}
		throw new \phpbbireland\core\exception('k_acronym type [' . $type . '] not found');
	}

	public function get_status($status)
	{
		switch ($status)
		{
			case 'open':
			case 'unlocked':
				return self::STATUS_OPEN;

			case 'locked':
				return self::STATUS_LOCKED;
		}
		throw new \phpbbireland\core\exception('k_acronym status [' . $status . '] not found');
	}
}