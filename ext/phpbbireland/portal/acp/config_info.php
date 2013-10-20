<?php

/**
*
* @package Portal Extension
* @copyright (c) 2013 phpbbireland
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbireland\portal\acp;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

class config_info
{
	function module()
	{
		return array(
			'filename'	=> '\phpbbireland\portal\acp\config_module',
			'title'		=> 'ACP_PORTAL_TITLE',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'config_portal'	=> array('title' => 'ACP_PORTAL_CONFIG', 'auth' => 'acl_a_k_portal',	'cat' => array('ACP_CONFIG')),
			),
		);
	}
}
