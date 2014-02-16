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

/*
* Note: S_OPTIONS hold options, these are not anguage variables.
*/

class acp_k_menus
{
	var $u_action = '';

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $cache;
		global $config, $SID, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		include($phpbb_root_path . 'includes/sgp_functions_admin.'.$phpEx);

		$store = '';

		$user->add_lang('acp/k_menus');
		$this->tpl_name = 'acp_k_menus';
		$this->page_title = 'ACP_MENUS';

		$form_key = 'acp_k_menus';
		add_form_key($form_key);

		//$action	= request_var('action', '');
		$submit = (isset($_POST['submit'])) ? true : false;

		if ($submit && !check_form_key($form_key))
		{
			$submit = false;
			$mode = '';
			trigger_error($user->lang['FORM_INVALID'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
		}

		$menuitem	= request_var('menuitem', '');

		$template->assign_vars(array(
			'U_BACK'	=> append_sid("{$phpbb_admin_path}index.$phpEx", "i=k_menus&amp;mode=nav"),
		));

		$mode     = request_var('mode', '');
		$menu     = request_var('menu', 0);
		$menuitem = request_var('menuitem', '');
		$menutype = request_var('menutype', '');

		$u_action = append_sid("{$phpbb_admin_path}index.$phpEx" , "i=$id&amp;mode=$mode");

		if ($mode == '')
		{
			$mode = 'manage';
		}

		if ($submit)
		{
			$store = $mode;
		}

		switch ($mode)
		{
			//case 'head':     get_menu(HEAD_MENUS);    $template->assign_var('S_OPTIONS', 'head'); break;
			//case 'foot':     get_menu(FOOT_MENUS);    $template->assign_var('S_OPTIONS', 'foot'); break;

			case 'nav':      get_menu(NAV_MENUS);     $template->assign_var('S_OPTIONS', 'nav');  break;
			case 'sub':      get_menu(SUB_MENUS);     $template->assign_var('S_OPTIONS', 'sub');  break;
			case 'link':     get_menu(LINKS_MENUS);   $template->assign_var('S_OPTIONS', 'link'); break;
			case 'all':      get_menu(ALL_MENUS);     $template->assign_var('S_OPTIONS', 'all');  break;
			case 'unalloc':  get_menu(UNALLOC_MENUS); $template->assign_var('S_OPTIONS', 'unalloc'); break;

			case 'edit':
			{
				if ($submit)
				{
					$m_id           = request_var('m_id', 0);
					$ndx            = request_var('ndx', 0);
					$menu_type      = request_var('menu_type', '');
					$menu_icon      = request_var('menu_icon', '');
					$name           = utf8_normalize_nfc(request_var('name', '', true));
					$link_to        = request_var('link_to', '');
					$append_sid     = request_var('append_sid', 0);
					$append_uid     = request_var('append_uid', 0);
					$extern         = request_var('extern', 0);
					$soft_hr        = request_var('soft_hr', 0);
					$sub_heading    = request_var('sub_heading', 0);
					$view           = request_var('view', 1);
					$view_all       = request_var('view_all', 1);
					$view_groups    = request_var('view_groups', '');

					if ($view_all)
					{
						$view_groups = get_all_groups();
						if ($view_groups == '')
						{
							$view_groups = 0;
						}
					}

					if (strstr($menu_icon, '..'))
					{
						$menu_icon = 'default.png';
					}

					$sql_ary = array(
						'menu_type'    => $menu_type,
						'ndx'          => $ndx,
						'menu_icon'    => $menu_icon,
						'name'         => $name,
						'link_to'      => $link_to,
						'append_sid'   => $append_sid,
						'append_uid'   => $append_uid,
						'extern'       => $extern,
						'soft_hr'      => $soft_hr,
						'sub_heading'  => $sub_heading,
						'view_all'     => $view_all,
						'view_groups'  => $view_groups,
					);

					$sql = 'UPDATE ' . K_MENUS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . " WHERE m_id = " . (int)$m_id;

					if (!$result = $db->sql_query($sql))
					{
						trigger_error($user->lang['ERROR_PORTAL_MENUS'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
					}


					$cache->destroy('sql', K_MENUS_TABLE);

					switch ($menu_type)
					{
						case 1: $mode = 'nav';
						break;
						case 2: $mode = 'sub';
						break;
						case 3: $mode= 'head';
						break;
						case 3: $mode= 'foot';
						break;
						case 5: $mode= 'link';
						break;

						default: $mode = $mode;
						break;
					}

					$template->assign_vars(array(
						'L_MENU_REPORT' => $user->lang['SAVED'] . '<br />',
						'S_OPTIONS' => 'save',
					));

					meta_refresh (1, append_sid("{$phpbb_admin_path}index.$phpEx", 'i=k_menus&amp;mode=' . $mode));
					break;
				}

				// get all groups and fill array //
				parse_all_groups();

				if ($submit == 1)
				{
					get_menu_item($m_id);
				}
				else
				{
					get_menu_item($menu);
				}

				$template->assign_var('S_OPTIONS', 'edit');
				get_menu_icons();

				break;
			}

			case 'delete':
			{
				if (!$menu)
				{
					trigger_error($user->lang['MUST_SELECT_VALID_MENU_DATA'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				if (confirm_box(true))
				{
					$sql = 'SELECT name, m_id
						FROM ' . K_MENUS_TABLE . '
						WHERE m_id = ' . (int)$menu;

					$result = $db->sql_query($sql);

					$name = (string) $db->sql_fetchfield('name');
					$id = (int) $db->sql_fetchfield('m_id');
					$db->sql_freeresult($result);
					$name .= $user->lang['MENU'];

					$sql = 'DELETE FROM ' . K_MENUS_TABLE . "
						WHERE m_id = " . (int)$menu;

					$db->sql_query($sql);


					$template->assign_var('L_MENU_REPORT', $name . $user->lang['DELETED'] . '<br />');
					$cache->destroy('sql', K_MENUS_TABLE);

					meta_refresh (1, append_sid("{$phpbb_admin_path}index.$phpEx", 'i=k_menus&amp;mode=all'));
					break;
				}
				else
				{
					confirm_box (false, $user->lang['CONFIRM_OPERATION_MENUS'], build_hidden_fields(array(
						'i'       => $id,
						'mode'    => $mode,
						'action'  => 'delete',
					)));
				}

				$template->assign_var('L_MENU_REPORT', $user->lang['ACTION_CANCELLED']);

				meta_refresh (1, append_sid("{$phpbb_admin_path}index.$phpEx", 'i=k_menus&amp;mode=all'));

				break;
			}

			case 'up':
			case 'down':
			{
				$current_ndx = $current_id = $first_id = $last_id = $first_ndx = $last_ndx = $prev_ndx = $next_ndx = $col_count = $current_count = $error = 0;

				$id_array = array();
				$ndx_array = array();

				// get current menu id //
				$sql = "SELECT m_id, ndx, menu_type
					FROM " . K_MENUS_TABLE . "
					WHERE m_id = " . (int)$menu;

				$result = $db->sql_query_limit($sql, 1);
				$row = $db->sql_fetchrow($result);
				$type = $row['menu_type'];
				$db->sql_freeresult($result);

				$sql = "SELECT m_id, ndx, menu_type
					FROM " . K_MENUS_TABLE . "
					WHERE menu_type = '" . $db->sql_escape($type) . "'" . "
					ORDER BY ndx";
				$result = $db->sql_query($sql);

				if ($result = $db->sql_query($sql))
				{
					while ($row = $db->sql_fetchrow($result))
					{
						$id_array[] = $row['m_id'];
						$ndx_array[] = $row['ndx'];

						if ($menu == $row['m_id'])
						{
							$current_ndx = $row['ndx'];
							$current_id = $row['m_id'];
							$current_count = $col_count;
						}
						$col_count++;
					}
				}
				$db->sql_freeresult($result);

				$first_ndx = $ndx_array[0];
				$first_id = $id_array[0];
				$last_ndx = $ndx_array[$col_count-1];
				$last_id = $id_array[$col_count-1];

				if ($current_count - 1 > 0)
				{
					$prev_ndx = $ndx_array[$current_count - 1];
					$prev_id =  $id_array[$current_count - 1];
				}
				else
				{
					/* can't happenas we don't allow moving up
					   if ndx is first (move icon is disabled)
					   we could move to last... roll around
				   */

					//$prev_ndx = $ndx_array[$col_count-1];
					//$prev_id =  $id_array[$col_count-1];
				}

				if ($current_count + 1 < $col_count)
				{
					$next_ndx = $ndx_array[$current_count + 1];
					$next_id =  $id_array[$current_count + 1];
				}
				else
				{
					/* can happen as we don't allow moving down
					   if ndx is last (move icon is disabled)
					   we could move to first... roll around
					*/

					//$next_ndx = $ndx_array[0];
					//$next_id =  $id_array[0];
				}

				if ($mode == 'up')
				{
					// sql is not duplicated
					$sql = "UPDATE " . K_MENUS_TABLE . " SET ndx = " . (int)$prev_ndx . " WHERE m_id = " . (int)$current_id;
					if (!$result = $db->sql_query($sql))
					{
						$error = true;
					}
					$sql = "UPDATE " . K_MENUS_TABLE . " SET ndx = " . (int)$current_ndx . " WHERE m_id = " . (int)$prev_id;
					if (!$result = $db->sql_query($sql))
					{
						$error = true;
					}

				}
				if ($mode == 'down')
				{
					// sql is not duplicated
					$sql = "UPDATE " . K_MENUS_TABLE . " SET ndx = " . (int)$next_ndx . " WHERE m_id = " . (int)$current_id;
					if (!$result = $db->sql_query($sql))
					{
						$error = true;
					}
					$sql = "UPDATE " . K_MENUS_TABLE . " SET ndx = " . (int)$current_ndx . " WHERE m_id = " . (int)$next_id;
					if (!$result = $db->sql_query($sql))
					{
						$error = true;
					}
				}

				if ($error)
				{
					trigger_error($user->lang['MENU_MOVE_ERROR'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
				}

				$template->assign_vars(array(
					'L_MENU_REPORT' => $user->lang['SORT_ORDER_UPDATING'],
					'S_OPTIONS' => 'updn',
				));

				$cache->destroy('sql', K_MENUS_TABLE);

				switch ($type)
				{
					/*
					case HEAD_MENUS: $current_menu_type = 'head';
					break;
					case FOOT_MENUS: $current_menu_type = 'foot';
					break;
					*/
					case NAV_MENUS:	$current_menu_type = 'nav';
					break;
					case SUB_MENUS:	$current_menu_type = 'sub';
					break;
					case LINKS_MENUS: $current_menu_type = 'link';
					break;
					default: $current_menu_type = 'nav';
					break;
				}

				meta_refresh (1, append_sid("{$phpbb_admin_path}index.$phpEx", 'i=k_menus&amp;mode=' . $current_menu_type));

				break;
			}

			case 'add':
			{
				if ($submit)
				{
					//$m_id     = request_var('m_id', '');
					//$ndx      = request_var('ndx', '');
					$menu_type     = request_var('menu_type', '');
					$menu_icon     = request_var('menu_icon', '');
					$name          = utf8_normalize_nfc(request_var('name', '', true));
					$link_to       = request_var('link_to', '');
					$append_sid    = request_var('append_sid', 0);
					$append_uid    = request_var('append_uid', 0);
					$extern        = request_var('extern', 0);
					$soft_hr       = request_var('soft_hr', 0);
					$sub_heading   = request_var('sub_heading', 0);
					$view_all      = request_var('view_all', 1);
					$view_groups   = request_var('view_groups', '');

					if ($menu_type == NULL || $name == NULL)
					{
						// catch all we check menu_type, $name, view)
						$template->assign_vars(array(
							'L_MENU_REPORT' => $user->lang['MISSING_DATA'],
							'S_OPTIONS' => 'updn',
						));
						return;
					}

					if (strstr($menu_icon, '..') && !$sub_heading)
					{
						$menu_icon = 'default.png';
					}

					$ndx = get_next_ndx($menu_type);

					if ($view_all)
					{
						$view = 1;
						$view_groups = '';
					}

					$sql_array = array(
						'menu_type'    => $menu_type,
						'ndx'          => $ndx,
						'menu_icon'    => $menu_icon,
						'name'         => $name,
						'link_to'      => $link_to,
						'append_sid'   => $append_sid,
						'append_uid'   => $append_uid,
						'extern'       => $extern,
						'soft_hr'      => $soft_hr,
						'sub_heading'  => $sub_heading,
						'view_all'     => $view_all,
						'view_groups'  => $view_groups,
					);

					$db->sql_query('INSERT INTO ' . K_MENUS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_array));

					$cache->destroy('sql', K_MENUS_TABLE);

					//fix for the different menus...
					meta_refresh (1, append_sid("{$phpbb_admin_path}index.$phpEx", 'i=k_menus&amp;mode=' . $store));

					$template->assign_var('L_MENU_REPORT', $user->lang['MENU_CREATED']);

					break;//return;
				}
				else
				{
					// get all groups and fill array //
					parse_all_groups();
					get_menu_icons();

					$template->assign_var('S_OPTIONS', 'add');

					$template->assign_vars(array(
						'S_MENU_ICON' => 'acp.png',
						'S_OPTIONS'   => 'add',
						'S_MENU_TYPE' => $menutype,
					));

					break;
				}
			}
			case 'icons':
			{
				$dirslist='';

				$i = get_menu_icons();
				$template->assign_vars(array(
					'S_OPTIONS'          => 'icons',
					'S_HIDE'             => 'HIDE',
					'L_ICONS_REPORT'     => '',
					'S_MENU_ICON_COUNT'  => $i,
					'S_MENU_ICONS_LIST'  => $dirslist,
					)
				);
				break;
			}

			case 'manage':
				$template->assign_var('L_MENU_REPORT', $user->lang['FUTURE_DEVELOPMENT'] . '<br />');
				$template->assign_var('S_OPTIONS', 'manage');

			break;

			case 'sync':
				$template->assign_vars('L_MENU_REPORT', $user->lang['NOT_ASSIGNED'] . '<br />');
				$template->assign_var('S_OPTIONS', 'sync');
			break;

			case 'tools':
				$template->assign_var('S_OPTIONS', 'tools');
			break;

			case 'default':
			break;

		}

		$template->assign_var('U_ACTION', $u_action);
	}
}

function get_menu($this_one)
{
	global $db, $phpbb_root_path, $phpEx, $template;
	global $phpbb_admin_path, $phpEx;

	if ($this_one > UN_ALLOC_MENUS && $this_one < ALL_MENUS) // standard menus defined as 1 to 5 //
	{
		$sql = 'SELECT * FROM ' . K_MENUS_TABLE . ' WHERE menu_type = ' . (int)$this_one . ' ORDER BY ndx ASC';
	}
	else if ($this_one == ALL_MENUS)
	{
		$sql = 'SELECT * FROM ' . K_MENUS_TABLE . ' ORDER BY menu_type, ndx ASC';
	}
	else if ($this_one == UN_ALLOC_MENUS)
	{
		$sql = 'SELECT * FROM ' . K_MENUS_TABLE . ' WHERE menu_type = ' . (int)$this_one . ' ORDER BY ndx, menu_type ASC';
	}
	else
	{
		$sql = 'SELECT * FROM ' . K_MENUS_TABLE . ' WHERE menu_type=' . (int)$this_one;
	}

	if ($result = $db->sql_query($sql))
	{
		while ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('mdata', array(
				'S_MENUID'           => $row['m_id'],
				'S_MENU_NDX'         => $row['ndx'],
				'S_MENU_TYPE'        => $row['menu_type'],
				'S_MENU_ICON'        => $row['menu_icon'],
				'S_MENU_ITEM_NAME'   => $row['name'],
				'S_MENU_LINK'        => $row['link_to'],
				'S_MENU_APPEND_SID'  => $row['append_sid'],
				'S_VIEW_ALL'         => $row['view_all'],
				'S_VIEW_GROUPS'      => $row['view_groups'],
				'S_MENU_APPEND_UID'  => $row['append_uid'],
				'S_MENU_EXTERN'      => $row['extern'],
				'S_SOFT_HR'          => $row['soft_hr'],
				'S_SUB_HEADING'      => $row['sub_heading'],

				'U_EDIT'    => append_sid("{$phpbb_admin_path}index.$phpEx", "i=k_menus&amp;mode=edit&amp;menu=" . $row['m_id']),
				'U_UP'      => append_sid("{$phpbb_admin_path}index.$phpEx", "i=k_menus&amp;mode=up&amp;menu=" . $row['m_id']),
				'U_DOWN'    => append_sid("{$phpbb_admin_path}index.$phpEx", "i=k_menus&amp;mode=down&amp;menu=" . $row['m_id']),
				'U_DELETE'  => append_sid("{$phpbb_admin_path}index.$phpEx", "i=k_menus&amp;mode=delete&amp;menu=" . $row['m_id']),
			));
		}
		$db->sql_freeresult($result);
	}
	else
	{
		trigger_error($user_lang['COULD_NOT_RETRIEVE_BLOCK'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
	}
}

function get_menu_item($item)
{
	global $db, $template;

	$m_id = $item;

	$sql = 'SELECT *
		FROM ' . K_MENUS_TABLE . '
		WHERE m_id=' . (int)$item;

	if ($result = $db->sql_query($sql))
	{
		$row = $db->sql_fetchrow($result);
	}

	$template->assign_vars(array(
		'S_MENUID'          => $row['m_id'],
		'S_MENU_NDX'        => $row['ndx'],
		'S_MENU_TYPE'       => $row['menu_type'],
		'S_MENU_ICON'       => $row['menu_icon'],
		'S_MENU_ITEM_NAME'  => $row['name'],
		'S_MENU_LINK'       => $row['link_to'],
		'S_VIEW_ALL'        => $row['view_all'],
		'S_VIEW_GROUPS'     => $row['view_groups'],
		'S_MENU_APPEND_SID' => $row['append_sid'],
		'S_MENU_APPEND_UID' => $row['append_uid'],
		'S_MENU_EXTERN'     => $row['extern'],
		'S_SOFT_HR'         => $row['soft_hr'],
		'S_SUB_HEADING'     => $row['sub_heading'],
	));
	$db->sql_freeresult($result);
}


function get_menu_icons()
{
	global $phpbb_root_path, $phpEx, $template, $dirslist, $user;

	$dirslist = ' ';

	$dirs = dir($phpbb_root_path. 'images/block_images/menu');

	while ($file = $dirs->read())
	{
		//if (stripos($file, "enu") || stripos($file, ".gif") || stripos($file, ".png") && stripos($file ,"ogo_"))
		if (stripos($file, ".gif") || stripos($file, ".png"))
		{
			$dirslist .= "$file ";
		}
	}

	closedir($dirs->handle);

	$dirslist = explode(" ", $dirslist);
	sort($dirslist);

	for ($i=0; $i < sizeof($dirslist); $i++)
	{
		if ($dirslist[$i] != '')
		{
			$template->assign_block_vars('menuicons', array('S_MENU_ICONS'	=> $dirslist[$i]));
		}
	}
	return $i;
}

function get_next_ndx($type)
{
	global $db, $ndx, $user;
	$sql = "SELECT *
		FROM " . K_MENUS_TABLE . "
		WHERE menu_type = '" . (int)$type . "'
		ORDER by ndx DESC";

	if ($result = $db->sql_query($sql))
	{
		$row = $db->sql_fetchrow($result);		// just get last block ndx details	//
		$ndx = $row['ndx'];						// only need last ndx returned		//
		$ndx = $ndx + 1; 						// add 1 to index 					//
		return($ndx);
	}
}

function parse_all_groups()
{
	global $db, $template, $user;

	// Get us all the groups
	$sql = 'SELECT group_id, group_name
		FROM ' . GROUPS_TABLE . '
		ORDER BY group_id ASC, group_name';
	$result = $db->sql_query($sql);

	// backward compatability, set up group zero //
	$template->assign_block_vars('groups', array(
		'GROUP_NAME' => $user->lang['NONE'],
		'GROUP_ID'   => 0,
	));

	while ($row = $db->sql_fetchrow($result))
	{
		$group_id = $row['group_id'];
		$group_name = $row['group_name'];

		$group_name = ($user->lang(strtoupper('G_'.$group_name))) ? $user->lang(strtoupper('G_'.$group_name)) : $user->lang(strtoupper($group_name));

		$template->assign_block_vars('groups', array(
			'GROUP_NAME' => $group_name,
			'GROUP_ID'   => $group_id,
			)
		);
	}
	$db->sql_freeresult($result);
}

/*
function reindex_menus($menu_type)
{
	global $db, $user;

	$id_array = array();
	$ndx_array = array();

	$sql = "SELECT m_id, ndx
		FROM  " . K_MENUS_TABLE . "
		WHERE menu_type = '" . $db->sql_escape($menu_type) . "'
		ORDER BY ndx";

	if ($result = $db->sql_query($sql))
	{
		while ($row = $db->sql_fetchrow($result))
		{
			$id_array[] = $row['m_id'];
			$ndx_array[] = $row['ndx'];
		}
	}
	$db->sql_freeresult($result);

	for ($i = 0; $i < $numbr = sizeof($id_array); $i++)
	{
		$j = $i + 1;

		$sql = "UPDATE " . K_MENUS_TABLE . " SET ndx = " . (int)$j . " WHERE m_id = " . $id_array[$i];

		if ($result = $db->sql_query($sql))
		{
			$db->sql_freeresult($result);
		}
		else
		{
			trigger_error($user->lang['COULD_NOT_REINDEX_MENUS'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
		}
		$sql = '';
	}
}
*/

?>