<?php
/**
*
* @author Michael O'Toole (michaelo) http://phpbbireland.com
*
* @package acp (Stargate Portal)
* @version $Id: acp_k_youtube.php 305 10 August 2009 16:03:23Z Michealo $
* @copyright (c) 2005-2009 phpbbireland.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package module_install
*/

class acp_k_youtube_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_k_youtube',
			'title'		=> 'ACP_K_YOUTUBE',
			'version'	=> '1.0.0',
			'modes' => array(
				'add'		=> array('title' => 'ACP_K_YOUTUBE_ADD',		'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_YOUTUBES')),
				'delete'	=> array('title' => 'ACP_K_YOUTUBE_DELETE',		'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_YOUTUBES'), 'display' => false),
				'edit'		=> array('title' => 'ACP_K_YOUTUBE_EDIT',		'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_YOUTUBES'), 'display' => false),
				'browse'	=> array('title' => 'ACP_K_YOUTUBE_BROWSE',		'auth' => 'acl_a_k_portal', 'cat' => array('ACP_K_YOUTUBES'))
			),
		);
	}
	function install()
	{
	}
	function uninstall()
	{
	}
}

?>