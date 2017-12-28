<?php
/**
*
* @package ucp (Kiss Portal Engine)
* @version $Id$
* @copyright (c) 2005-2013 phpbbireland
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace phpbbireland\portal\ucp;

class portal_info
{
	function module()
	{
		return array(
			'filename'	=> '\phpbbireland\portal\ucp\portal_module',
			'title'     => 'UCP_PORTAL_TITLE',
			'modes'     => array(
				'info'     => array('title' => 'UCP_K_BLOCKS_INFO',    'auth' => 'ext_phpbbireland/portal && acl_u_k_portal', 'cat' => array('UCP_K_BLOCKS')),
				'arrange'  => array('title' => 'UCP_K_BLOCKS_ARRANGE', 'auth' => 'ext_phpbbireland/portal && acl_u_k_portal', 'cat' => array('UCP_K_BLOCKS')),
				'edit'     => array('title' => 'UCP_K_BLOCKS_EDIT',    'auth' => 'ext_phpbbireland/portal && acl_u_k_portal', 'cat' => array('UCP_K_BLOCKS')),
				'delete'   => array('title' => 'UCP_K_BLOCKS_DELETE',  'auth' => 'ext_phpbbireland/portal && acl_u_k_portal', 'cat' => array('UCP_K_BLOCKS')),
				'width'    => array('title' => 'UCP_K_BLOCKS_WIDTH',   'auth' => 'ext_phpbbireland/portal && acl_u_k_portal', 'cat' => array('UCP_K_BLOCKS')),
			),
		);
	}

}
