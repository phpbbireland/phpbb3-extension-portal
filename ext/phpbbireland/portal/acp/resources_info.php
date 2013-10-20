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

class resources_info
{
	function module()
	{
		return array(
			'filename' => '\phpbbireland\portal\acp\resources_module',
			'title'    => 'ACP_RESOURCES_TITLE',
			'version'  => '1.0.0',
			'modes'    => array(
				'select' => array('title' => 'ACP_K_RESOURCES', 'auth' => 'acl_a_k_tools', 'cat' => array('ACP_K_TOOLS')),
			),
		);
	}
}
