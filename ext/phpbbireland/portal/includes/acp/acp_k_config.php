<?php
/**
*
* @package acp Kiss Portal Engine
* @version $Id$
* @copyright (c) 2005-2013 phpbbireland
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package acp
*/
class acp_k_config
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $cache;
		global $config, $SID, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$var = array();
		$current_version = '0.0.0';

		$user->add_lang('acp/k_config');
		$this->tpl_name = 'acp_k_config';
		$this->page_title = 'ACP_K_PORTAL_CONFIG';

		$form_key = 'acp_k_config';
		add_form_key($form_key);

		$action = request_var('action', '');
		$mode	= request_var('mode', '');
		$generate = request_var('generate', '');

		include($phpbb_root_path . 'includes/sgp_functions_admin.'. $phpEx );

		$data = check_version();

		$submit = (isset($_POST['submit'])) ? true : false;

		$forum_id   = request_var('f', 0);
		$forum_data = $errors = array();

		if ($submit && !check_form_key($form_key))
		{
			trigger_error($user->lang['FORM_INVALID']);
		}

		$blocks_width 	= $config['blocks_width'];
		$blocks_enabled = $config['blocks_enabled'];
		$portal_version	= $config['portal_version'];
		$portal_build	= $config['portal_build'];

		if ($data['version'])
		{
			$template->assign_vars(array(
				'MOD_ANNOUNCEMENT'     => $data['announcement'][0],
				'MOD_CURRENT_VERSION'  => $config['portal_version'],
				'MOD_DOWNLOAD'         => $data['download'][0],
				'MOD_LATEST_VERSION'   => $data['version'],
				'MOD_TITLE'            => $data['title'][0],
				'S_UP_TO_DATE'         => ($data['version'] > $config['portal_version']) ? false : true,
			));

		}

		$template->assign_vars(array(
			'S_BLOCKS_WIDTH'    => $blocks_width,
			'S_BLOCKS_ENABLED'  => $blocks_enabled,
			'S_PORTAL_VERSION'  => $portal_version,
			'S_PORTAL_BUILD'    => $portal_build,
			'U_BACK'            => $this->u_action,
			'S_OPT'             => 'configure',
			'S_MOD_DATA'        => ($data['version']) ? true : false,
		));

		if ($submit)
		{
			$mode = 'save';
		}
		else
		{
			$mode = 'reset';
		}

		switch ($mode)
		{
			case 'save':
			{

				$blocks_width    = request_var('blocks_width', '');
				$blocks_enabled  = request_var('blocks_enabled', '');
				$portal_build    = request_var('portal_build', '');

				set_config('blocks_width', $blocks_width);
				set_config('blocks_enabled', $blocks_enabled);
				set_config('portal_build', $portal_build);

				$mode='reset';

				$template->assign_var('S_OPT', 'save');

				$url = $this->u_action . "&amp;i=k_config&amp;action=config";

				meta_refresh(0, $url);
				return;
			}

			case 'default':
			break;
		}
	}
}

?>