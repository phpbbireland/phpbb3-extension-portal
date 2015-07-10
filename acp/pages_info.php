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

class pages_info
{
	function module()
	{
		return array(
			'filename' => '\phpbbireland\portal\acp\pages_module',
			'title'    => 'ACP_PAGES_TITLE',
			'modes'    => array(
				'add'    => array('title' => 'ACP_K_PAGES_ADD',	   'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_PAGES'), 'display' => false),
				'delete' => array('title' => 'ACP_K_PAGES_DELETE', 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_PAGES'), 'display' => false),
				'land'   => array('title' => 'ACP_K_PAGES_LAND',   'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_PAGES'), 'display' => false),
				'manage' => array('title' => 'ACP_K_PAGES_MANAGE', 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => array('ACP_K_PAGES'))
			),
		);
	}
}
