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
class acp_k_resources
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $cache , $k_cache;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		include($phpbb_root_path . 'includes/sgp_functions.'.$phpEx);

		$user->add_lang('acp/k_resources');
		$this->tpl_name = 'acp_k_resources';
		$this->page_title = 'ACP_K_RESOURCES';

		$form_key = 'acp_k_resources';
		add_form_key($form_key);

		// Set up general vars
		$action = request_var('action', '');
		$action = (isset($_POST['edit'])) ? 'edit' : $action;
		$action = (isset($_POST['add'])) ? 'add' : $action;
		$action = (isset($_POST['save'])) ? 'save' : $action;
		$action = (isset($_POST['switch'])) ? 'switch' : $action;
		$action = (isset($_POST['delete'])) ? 'delete' : $action;

		// ======================================================
		//			[ MAIN PROCESS ]
		// ======================================================

		$add		= request_var('add', '');

		$id_list = (( isset($_POST['id_list']) ) ? request_var('id_list', array(0)) : ( (isset($_GET['id_list'])) ? request_var('id_list', array(0)) : array()));

		switch ($action)
		{
			case 'add':

				$process = true;
				$start = '{';
				$end = '}';

				$new_word = request_var('new_word', '');

				if (isset($k_config[$new_word]))
				{
					$value = (isset($k_config[$new_word])) ? $k_config[$new_word] : '';
					$table = $user->lang['K_CONFIG'];
				}
				else if (isset($config[$new_word]))
				{
					$value = (isset($config[$new_word])) ? $config[$new_word] : '';
					$table = $user->lang['CONFIG'];
				}
				else
				{
					$table = $user->lang['UNKNOWN'];
					$process = false;
					$template->assign_var('L_PROCESS_REPORT', sprintf($user->lang['VAR_NOT_FOUND'], $new_word));
				}

				if ($process)
				{
					$template->assign_var('L_PROCESS_REPORT', sprintf($user->lang['VAR_ADDED'], $new_word));

					$start .= strtoupper($new_word) . $end;

					$sql_array = array(
						'word'	=> $start,
					);

					if (!$db->sql_query('INSERT INTO ' . K_RESOURCES_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_array)))
					{
						trigger_error($user->lang['ERROR_PORTAL_WORDS'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
					}
				}

			break;

			case 'delete':
				for ($i = 0; $i < count($id_list); $i++)
				{
					$sql = 'DELETE FROM '. K_RESOURCES_TABLE ." WHERE id = " . (int)$id_list[$i];
					if (!$result = $db->sql_query($sql))
					{
						trigger_error($user->lang['ERROR_PORTAL_WORDS'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
					}
				}
			break;

			default:
				$template->assign_var('L_PROCESS_REPORT', sprintf($user->lang['PROCESS_REPORT'], '...'));
			break;
		}

		$sql = 'SELECT * FROM ' . K_RESOURCES_TABLE;

		if (!$result = $db->sql_query($sql))
		{
			trigger_error($user->lang['ERROR_PORTAL_WORDS'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
		}

		$a = array('{', '}');
		$b = array('','');

		$value = '';

		while ($row = $db->sql_fetchrow($result))
		{
			$name = strtolower($row['word']);
			$name = str_replace($a, $b, $name);

			if (isset($k_config[$name]))
			{
				$value = (isset($k_config[$name])) ? $k_config[$name] : '';
				$table = $user->lang['K_CONFIG'];
			}
			else if (isset($config[$name]))
			{
				$value = (isset($config[$name])) ? $config[$name] : '';
				$table = $user->lang['CONFIG'];
			}
			else
			{
				$table = $user->lang['NA'];
			}

			$template->assign_block_vars('wordrow', array(
				'ID'    => $row['id'],
				'WORD'  => $row['word'],
				'NAME'  => $name,
				'VALUE' => $value,
				'TABLE' => $table
			));
		}
		$db->sql_freeresult($result);

	}


}

?>
