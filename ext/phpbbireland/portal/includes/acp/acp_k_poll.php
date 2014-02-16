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
class acp_k_poll
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $cache;
		global $k_config, $SID, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		include($phpbb_root_path . 'includes/sgp_functions.'.$phpEx);

		$message ='';

		$user->add_lang('acp/k_poll');
		$this->tpl_name = 'acp_k_poll';
		$this->page_title = 'ACP_K_POLL';

		$form_key = 'acp_k_poll';
		add_form_key($form_key);

		// Set up general vars
		$action = request_var('action', '');
		$action = (isset($_POST['edit'])) ? 'edit' : $action;
		$action = (isset($_POST['add'])) ? 'add' : $action;
		$action = (isset($_POST['save'])) ? 'save' : $action;
		$poll_id = request_var('id', 0);

		if (!isset($k_config['k_poll_wide']))
		{
			sgp_acp_set_config('k_poll_wide', 0);
		}
		if (!isset($k_config['k_poll_post_id']))
		{
			sgp_acp_set_config('k_poll_post_id', 0);
		}
		if (!isset($k_config['k_poll_view']))
		{
			sgp_acp_set_config('k_poll_view', 0);
		}

		$template->assign_vars(array(
			'K_POLL_POST_ID'	=> $k_config['k_poll_post_id'],
			'K_POLL_VIEW'		=> $k_config['k_poll_view'],
		));


/*
		$sql = 'SELECT config_name, config_value
			FROM ' . K_BLOCKS_CONFIG_VAR_TABLE . '';

		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$k_config[$row['config_name']] = $row['config_value'];
		}
*/

		switch ($action)
		{
			case 'editpoll':
	 			if ($action == 'editpoll')
				{
					$config_poll_post_id = request_var('k_poll_post_id', 0, true);
					$config_poll_view = request_var('k_poll_view', 0, true);
				}

				$template->assign_vars(array(
					'S_EDIT_POLL'			=> true,
					'U_BACK'				=> $this->u_action,
					'U_ACTION_EDIT_COLUMN'	=> $this->u_action . '&amp;action=editpoll',
					'POLL_POST_ID'			=> $k_config['k_poll_post_id'],
					'POLL_VIEW'				=> $k_config['k_poll_view'],
				));
				$cache->destroy('sql', K_BLOCKS_CONFIG_VAR_TABLE);
			break;

			case 'savepoll':
				$config_poll_post_id = request_var('k_poll_post_id', 0, true);
				$config_poll_view = request_var('k_poll_view', 0, true);

 				if ($action == 'savepoll')
				{
					sgp_acp_set_config('k_poll_post_id', $config_poll_post_id);
					sgp_acp_set_config('k_poll_view', $config_poll_view);

					$message = $user->lang['CONFIG_UPDATED'];
				}

				trigger_error($message . adm_back_link($this->u_action));

				$template->assign_vars(array(
					'S_SAVE_POLL'			=> true,
					'U_BACK'				=> $this->u_action,
					'U_ACTION_SAVE_COLUMN'	=> $this->u_action . '&amp;action=savepoll',
					'POLL_POST_ID'			=> $k_config['k_poll_post_id'],
					'POLL_VIEW'				=> $k_config['k_poll_view'],
					));
				$cache->destroy('sql', K_BLOCKS_CONFIG_VAR_TABLE);
			break;

			default:
				$template->assign_block_vars('poll_column', array(
					'S_EDIT_POLL'		=> false,
					'POLL_POST_ID'		=> $k_config['k_poll_post_id'],
					'POLL_VIEW'         => $k_config['k_poll_view'],
					'U_EDIT_POLL'		=> $this->u_action . '&amp;action=editpoll'
				));
			break;
		}
	}
}
?>