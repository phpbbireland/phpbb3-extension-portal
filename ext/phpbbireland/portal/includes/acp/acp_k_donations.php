<?php
/**
*
* @package acp Stargate Portal
* @version $Id: acp_k_donations.php 305 2009-01-01 16:03:23Z Michealo $
* @copyright (c) 2007 Michael O'Toole aka michaelo
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

class acp_k_donations
{

	var $u_action;
	//var $action;

	function main($donations_id, $mode)
	{
		global $db, $user, $auth, $template, $cache;
		global $config, $SID, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		include($phpbb_root_path . 'includes/sgp_functions.' . $phpEx);

		$user->add_lang('acp/k_donations');
		$this->tpl_name = 'acp_k_donations';
		$this->page_amount = 'ACP_DONATIONS';

		$form_key = 'acp_k_donations';
		add_form_key($form_key);

		$mode = request_var('mode', '');
		$donations_id = request_var('donations_id', '');
		$action = request_var('config', '');
		$submit = (isset($_POST['submit'])) ? true : false;

		$action = (isset($_POST['add_donations'])) ? 'add' : ((isset($_POST['save'])) ? 'save' : ((isset($_POST['config'])) ? 'config' : $action));

		switch ($action)
		{
			case 'config':
				$template->assign_var('MESSAGE', $user->lang['SWITCHING']);

				meta_refresh (1, "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_vars&amp;mode=config&amp;switch=k_donations_vars.html");
			break;

			case 'add':
				$mode = '';
				meta_refresh (0, "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_donations&amp;mode=add");
			break;

			default:
			break;
		}


		if ($submit && !check_form_key($form_key))
		{
			$submit = false;
			$mode = '';
			trigger_error('Error! ' . $user->lang['FORM_INVALID'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . ', line ' . __LINE__);
		}

		$template->assign_vars(array(
			'U_BACK'    => "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_donations",
			'U_ADD'     => "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_donations&amp;mode=add",
			'U_EDIT'    => "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_donations&amp;mode=edit" . '&amp;donations_id=' . $donations_id,
			'U_DELETE'  => "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_donations&amp;mode=delete" . '&amp;donations_id=' . $donations_id,
			'U_BROWSE'  => "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_donations&amp;mode=browse",
			'S_OPT'     => 'browse',
		));

		switch ($mode)
		{
			case 'edit':

				if ($submit)
				{
					$donations_id        = request_var('donations_id', 0);
					$donations_date      = utf8_normalize_nfc(request_var('donations_date', '', true));
					$donations_name      = utf8_normalize_nfc(request_var('donations_name', '', true));
					$donations_amount    = utf8_normalize_nfc(request_var('donations_amount', '', true));
					$donations_r_tot     = utf8_normalize_nfc(request_var('donations_r_tot', ''));

					$year = substr($donations_date, -4);

					$sql_ary = array(
						//'donations_id'	=> $donations_id,
						'donations_date'    => $donations_date,
						'donations_name'    => $donations_name,
						'donations_amount'  => $donations_amount,
						'donations_r_tot'   => $donations_r_tot,
						'donations_year'    => $year,
					);

					$sql = 'UPDATE ' . K_DONATIONS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . " WHERE donations_id = " . (int)$donations_id;

					if (!$result = $db->sql_query($sql))
					{
						trigger_error($user->lang['ERROR_PORTAL_VIDEO'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . ', line ' . __LINE__);
					}

					$template->assign_vars(array(
						'MESSAGE' => $user->lang['SAVED'] . '</font><br />',
						'S_OPT'   => 'saving',
					));

					meta_refresh(0, "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_donations&amp;mode=browse");
				}

				get_donations_item($donations_id);

				$template->assign_var('S_OPTION', 'edit');
			break;

			case 'delete':

				//get the amount of the video to make delete clearer to the user...
				$donations_name = get_donations_item($donations_id);

				if (confirm_box(true))
				{
					$sql = 'DELETE FROM ' . K_DONATIONS_TABLE . '
						WHERE donations_id = ' . (int)$donations_id;

					if (!$result = $db->sql_query($sql))
					{
						trigger_error($user->lang['ERROR_PORTAL_VIDEO'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . ', line ' . __LINE__);
					}

					$template->assign_vars(array(
						'MESSAGE' =>  $user->lang['DELETING'] . $donations_id . '<br />',
						'S_OPT'   => 'delete', //not a language var
					));

					$cache->destroy('sql', K_DONATIONS_TABLE);

					meta_refresh(1, "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_donations&amp;mode=browse");
					break;
				}
				else
				{
					confirm_box(false, sprintf($user->lang['CONFIRM_OPERATION_DONATIONS'], $donations_name), build_hidden_fields(array(
						'id'     => $donations_id,
						'mode'   => $mode,
						'action' => 'delete'))
					);
				}

				$template->assign_var('MESSAGE', $user->lang['ACTION_CANCELLED']);
				meta_refresh(1, "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_donations&amp;mode=browse");
			break;

			case 'add':

				if ($submit)
				{
					$donations_date      = utf8_normalize_nfc(request_var('donations_date', '', true));
					$donations_name      = utf8_normalize_nfc(request_var('donations_name', '', true));
					$donations_amount    = utf8_normalize_nfc(request_var('donations_amount', '', true));
					$donations_r_tot     = utf8_normalize_nfc(request_var('donations_r_tot', '', true));

					$year = substr($donations_date, -4);

	               $sql_array = array(
						'donations_date'       => $donations_date,
						'donations_name'       => $donations_name,
						'donations_amount'     => $donations_amount,
						'donations_r_tot'      => $donations_r_tot,
   						'donations_year'       => $year,
                    );

		           $db->sql_query('INSERT INTO ' . K_DONATIONS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_array));

					meta_refresh(0, "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_donations&amp;mode=browse");

					$template->assign_var('L_MENU_REPORT', $user->lang['DONATIONS_CREATED']);

					$cache->destroy('sql', K_DONATIONS_TABLE);
					break;
				}
				else
				{
					get_donations_item(0);
					$template->assign_vars(array(
						'S_OPTION' => 'add',
						'MESSAGE' =>  '',
					));
					$mode = 'add';
				}
			break;

			case 'config':
			break;

			case 'browse':
				get_donations_data();
			break;

			case 'default':
			break;
		}

		$template->assign_var('U_ACTION', $this->u_action);
	}
}


function get_donations_data()
{
	global $db, $template;//, $s_hidden_fields;

	$sql = 'SELECT *
		FROM ' . K_DONATIONS_TABLE . '
		WHERE donations_id <> 0
		ORDER by donations_id ASC';

	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('donations', array(
			'DONATIONS_ID'         => $row['donations_id'],
			'DONATIONS_DATE'       => $row['donations_date'],
			'DONATIONS_NAME'       => $row['donations_name'],
			'DONATIONS_AMOUNT'     => $row['donations_amount'],
			'DONATIONS_R_TOT'      => $row['donations_r_tot'],
			'DONATIONS_YEAR'       => $row['donations_year'],
		));
	}
	$db->sql_freeresult($result);
}


// Assigns data, also returns the video name of delete option where donations_id == 0//
function get_donations_item($donations_id)
{
	global $db, $template, $user;//, $s_hidden_fields;
	$copy = false;
	$today = date("d-M-Y");

	if ($donations_id == 0) // used for copying a tag //
	{
		$copy = true;
		$sql = 'SELECT *
			FROM ' . K_DONATIONS_TABLE . '
			WHERE donations_id = 0';
	}
	else
	{
		$sql = 'SELECT *
			FROM ' . K_DONATIONS_TABLE . '
			WHERE donations_id = ' . (int)$donations_id;
	}

	$result = $db->sql_query($sql);

	if ($result = $db->sql_query($sql))
	{
		$row = $db->sql_fetchrow($result);
	}

	$template->assign_vars(array(
		'DONATIONS_ID'         => ($donations_id == 0) ? '' : $row['donations_id'],
		'DONATIONS_DATE'       => ($row['donations_date']) ? $row['donations_date'] : $today,
		'DONATIONS_NAME'       => $row['donations_name'],
		'DONATIONS_AMOUNT'     => $row['donations_amount'],
		'DONATIONS_R_TOT'      => $row['donations_r_tot'],
		'DONATIONS_YEAR'       => $row['donations_year'],
	));

	$db->sql_freeresult($result);


	if ($donations_id != 0)
	{
		return($row['donations_id']);
	}
	else
	{
		return('');
	}

}
?>