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

class resources_info
{
	function module()
	{
		return array(
			'filename' => '\phpbbireland\portal\acp\resources_module',
			'title'    => 'ACP_RESOURCES_TITLE',
			'version'  => '1.0.0',
			'modes'    => array(
				'select' => array('title' => 'ACP_K_RESOURCES', 'auth' => 'ext_phpbbireland/portal && acl_a_k_tools', 'cat' => array('ACP_K_TOOLS')),
			),
		);
	}
}
