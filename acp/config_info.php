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

class config_info
{
	function module()
	{
		return array(
			'filename'	=> '\phpbbireland\portal\acp\config_module',
			'title'		=> 'ACP_PORTAL_TITLE',
			'modes'		=> array(
				'config_portal'	=> array('title' => 'ACP_PORTAL_CONFIG', 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal',	'cat' => array('ACP_CONFIG')),
			),
		);
	}
}
