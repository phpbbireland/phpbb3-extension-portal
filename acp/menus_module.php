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

class menus_module
{
	var $u_action;

	function main($module_id, $mode)
	{
		global $db, $user, $auth, $template, $cache, $request;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx, $k_config, $table_prefix;
		global $helper, $root_path, $php_ext, $content_visibility;

		include_once($phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $phpEx);
		include_once($phpbb_root_path . 'ext/phpbbireland/portal/helpers/tables.' . $phpEx);
		include_once($phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_functions.'.$phpEx);

		$this->cache_setup();

		$submit = $request->is_set_post('submit');

		if (!class_exists('sgp_functions_admin'))
		{
			include_once($phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_functions_admin.' . $phpEx);
			$sgp_functions_admin = new sgp_functions_admin();
		}

		/*
		$k_config = $cache->get('k_config');
		$sgp_functions_admin->sgp_acp_set_config('base', $module_id);
		if ($mode != 'edit' && $mode != 'delete' && $mode != 'up' && $mode != 'down')
		{
			//$sgp_functions_admin->sgp_acp_set_config('base', $this->u_action);
		}
		*/

		$user->add_lang_ext('phpbbireland/portal', 'k_menus');
		$user->add_lang_ext('phpbbireland/portal', 'k_variables');

		$this->tpl_name = 'acp_menus';
		$this->page_title = $user->lang['ACP_MENUS'];

		add_form_key('menus');

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('menus'))
			{
				$submit = false;
				$mode = '';
				trigger_error('FORM_INVALID');
			}
			$submit = true;
		}

		// Can not use append_sid here, the $block_id is assigned to the html but unknow to this code //
		// Would require I add a form element and use $request->variable to retrieve it //

		$template->assign_vars(array(
			'U_BACK'        => $this->u_action,
			'PORTAL_JS'     => $portal_js,
			'IMG_PATH'      => $img_path_acp_menu_icons,
			'IMG_PATH_ACP'  => $img_path_acp_icons,
			'CSS_PATH'      => $css_path_acp,
			//'U_MANAGE_PAGES'	=> append_sid("{$phpbb_admin_path}index.$phpEx" , "i={$module_id}&amp;mode=manage"),
		));

		$mode     = $request->variable('mode', '');
		$menu     = $request->variable('menu', 0);
		$menuitem = $request->variable('menuitem', '', false);
		$type     = $request->variable('type', '', false);

		if($submit)
		{
			if($mode == 'nav' || $mode == 'sub' || $mode == 'link')
			{
				$mode = 'add';
			}
		}
		else
		{
			if($mode == 'nav' || $mode == 'sub' || $mode == 'link')
			{
				$store = $mode;
			}
		}

		// bold current row text so things are easier to follow when moving/editing etc... //
		if (($menu) ? $menu : 0)
		{
			$sql = 'UPDATE ' . K_VARS_TABLE . ' SET config_value = ' . (int) $menu . ' WHERE config_name = "k_adm_block"';
			$db->sql_query($sql);
		}
		else
		{
			$sql = 'SELECT config_name, config_value
				FROM ' . K_VARS_TABLE . "
				WHERE config_name = 'k_adm_block'";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$k_config[$row['config_name']] = $row['config_value'];
		}
		$template->assign_var('K_ADM_BLOCK', $k_config['k_adm_block']);

		switch ($mode)
		{
			case 'nav':
				$this->get_menu(NAV_MENUS, $module_id);
				$template->assign_var('S_OPTIONS', 'nav');
			break;
			case 'sub':
				$this->get_menu(SUB_MENUS, $module_id);
				$template->assign_var('S_OPTIONS', 'sub');
			break;
			case 'link':
				$this->get_menu(LINKS_MENUS, $module_id);
				$template->assign_var('S_OPTIONS', 'link');
			break;
			case 'all':
				$this->get_menu(ALL_MENUS, $module_id);
				$template->assign_var('S_OPTIONS', 'all');
			break;
			case 'unalloc':
				$this->get_menu(UNALLOC_MENUS, $module_id);
				$template->assign_var('S_OPTIONS', 'unalloc');
			break;

			case 'edit':

				if ($submit)
				{
					$m_id           = $request->variable('m_id', 0);
					$ndx            = $request->variable('ndx', 0);
					$menu_type      = $request->variable('menu_type', '');
					$menu_icon      = $request->variable('menu_icon', '');
					$name           = utf8_normalize_nfc($request->variable('name', '', true));
					$link_to        = $request->variable('link_to', '');
					$append_sid     = $request->variable('append_sid', 0);
					$append_uid     = $request->variable('append_uid', 0);
					$extern         = $request->variable('extern', 0);
					$soft_hr        = $request->variable('soft_hr', 0);
					$sub_heading    = $request->variable('sub_heading', 0);
					$view           = $request->variable('view', 1);
					$view_all       = $request->variable('view_all', 1);
					$view_groups    = $request->variable('view_groups', '');

					if ($view_all)
					{
						$view_groups = $sgp_functions_admin->get_all_groups();
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

					$sql = 'UPDATE ' . K_MENUS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . " WHERE m_id = " . (int) $m_id;

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

					meta_refresh (1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=" . $mode));
					break;
				}

				// get all groups and fill array //
				$this->parse_all_groups();

				if ($submit == 1)
				{
					$this->get_menu_item($m_id);
				}
				else
				{
					$this->get_menu_item($menu);
				}

				$template->assign_var('S_OPTIONS', 'edit');
				$this->get_menu_icons();
			break;

			case 'delete':

				if (!$menu)
				{
					trigger_error($user->lang['MUST_SELECT_VALID_MENU_DATA'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				if (confirm_box(true))
				{
					$sql = 'SELECT name, m_id
						FROM ' . K_MENUS_TABLE . '
						WHERE m_id = ' . (int) $menu;
					$result = $db->sql_query($sql);

					$name = (string) $db->sql_fetchfield('name');
					$db->sql_freeresult($result);
					$name .= ' ' . $user->lang['MENU'];

					$sql = 'DELETE FROM ' . K_MENUS_TABLE . "
						WHERE m_id = " . (int) $menu;
					$db->sql_query($sql);
					$db->sql_freeresult($result);

					$template->assign_var('L_MENU_REPORT', $name . $user->lang['DELETED'] . '<br />');
					$cache->destroy('sql', K_MENUS_TABLE);

					meta_refresh (1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=" . $type));
					return;
				}
				else
				{
					confirm_box (false, $user->lang['CONFIRM_OPERATION_MENUS'], build_hidden_fields(array(
						'i'       => $module_id,
						'mode'    => $mode,
						'action'  => 'delete',
					)));
				}

				$template->assign_var('L_MENU_REPORT', $user->lang['ACTION_CANCELLED']);

				meta_refresh (1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=" . $type));

			break;

			case 'up':
			case 'down':
				$current_ndx = $current_id = $first_id = $last_id = $first_ndx = $last_ndx = $prev_ndx = $next_ndx = $col_count = $current_count = $error = 0;

				$id_array = array();
				$ndx_array = array();

				// get current menu id //
				$sql = "SELECT m_id, ndx, menu_type
					FROM " . K_MENUS_TABLE . "
					WHERE m_id = " . (int) $menu;

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
					// can't happen //
				}

				if ($current_count + 1 < $col_count)
				{
					$next_ndx = $ndx_array[$current_count + 1];
					$next_id =  $id_array[$current_count + 1];
				}
				else
				{
					// can't happen //;
				}

				if ($mode == 'up')
				{
					// sql is not duplicated
					$sql = "UPDATE " . K_MENUS_TABLE . " SET ndx = " . (int) $prev_ndx . " WHERE m_id = " . (int) $current_id;
					if (!$result = $db->sql_query($sql))
					{
						$error = true;
					}
					$sql = "UPDATE " . K_MENUS_TABLE . " SET ndx = " . (int) $current_ndx . " WHERE m_id = " . (int) $prev_id;
					if (!$result = $db->sql_query($sql))
					{
						$error = true;
					}
				}
				if ($mode == 'down')
				{
					// sql is not duplicated
					$sql = "UPDATE " . K_MENUS_TABLE . " SET ndx = " . (int) $next_ndx . " WHERE m_id = " . (int) $current_id;
					if (!$result = $db->sql_query($sql))
					{
						$error = true;
					}
					$sql = "UPDATE " . K_MENUS_TABLE . " SET ndx = " . (int) $current_ndx . " WHERE m_id = " . (int) $next_id;
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

				meta_refresh (1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=" . $current_menu_type));
			break;

			case 'add':
				if ($submit)
				{
					//$m_id     = $request->variable('m_id', '');
					//$ndx      = $request->variable('ndx', '');
					$menu_type     = $request->variable('menu_type', '');
					$menu_icon     = $request->variable('menu_icon', '');
					$name          = utf8_normalize_nfc($request->variable('name', '', true));
					$link_to       = $request->variable('link_to', '');
					$append_sid    = $request->variable('append_sid', 0);
					$append_uid    = $request->variable('append_uid', 0);
					$extern        = $request->variable('extern', 0);
					$soft_hr       = $request->variable('soft_hr', 0);
					$sub_heading   = $request->variable('sub_heading', 0);
					$view_all      = $request->variable('view_all', 1);
					$view_groups   = $request->variable('view_groups', '');

					if ($menu_type == null || $name == null)
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

					$ndx = $this->get_next_ndx($menu_type);

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

					//fix for the different menus...
					meta_refresh (1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=" . $mode));

					$template->assign_var('L_MENU_REPORT', $user->lang['MENU_CREATED']);

					return;
				}
				else
				{
					// get all groups and fill array //
					$this->parse_all_groups();
					$this->get_menu_icons();

					$template->assign_var('S_OPTIONS', 'add');

					$template->assign_vars(array(
						'S_MENU_ICON' => 'acp.png',
						'S_OPTIONS'   => 'add',
						'S_MENU_TYPE' => $type,
					));

					return;
				}
			break;

			case 'icons':
				$dirslist='';

				$i = $this->get_menu_icons();
				$template->assign_vars(array(
					'S_OPTIONS'          => 'icons',
					'S_HIDE'             => 'HIDE',
					'L_ICONS_REPORT'     => '',
					'S_MENU_ICON_COUNT'  => $i,
					'S_MENU_ICONS_LIST'  => $dirslist,
				));
			break;

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

		//$template->assign_var('U_ACTION', $u_action);
	}
	public function cache_setup()
	{
		global $cache, $k_config;

		$this->cache_k_config();
		$k_config = $cache->get('k_config');
	}
	public function cache_k_config()
	{
		global $db, $cache, $table_prefix;

		if (($k_config = $cache->get('k_config')) !== false)
		{
			$sql = 'SELECT config_name, config_value
				FROM ' . K_VARS_TABLE . '
				WHERE 1';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$k_config[$row['config_name']] = $row['config_value'];
			}
			$db->sql_freeresult($result);
		}
		else
		{
			$k_config = $cached_k_config = array();

			$sql = 'SELECT config_name, config_value
				FROM ' . K_VARS_TABLE;
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				//if (!$row['is_dynamic'])
				//{
					$cached_k_config[$row['config_name']] = $row['config_value'];
				//}
				//else
				//$k_config[$row['config_name']] = $row['config_value'];
			}
			$db->sql_freeresult($result);

			$cache->put('k_config', $cached_k_config);
		}
	}

	public function get_menu($this_one, $module_id)
	{
		global $db, $phpbb_root_path, $phpEx, $template, $request;
		global $phpbb_admin_path, $phpEx, $mode;

		if ($this_one > UN_ALLOC_MENUS && $this_one < ALL_MENUS) // standard menus defined as 1 to 5 //
		{
			$sql = 'SELECT * FROM ' . K_MENUS_TABLE . ' WHERE menu_type = ' . (int) $this_one . ' ORDER BY ndx ASC';
		}
		else if ($this_one == ALL_MENUS)
		{
			$sql = 'SELECT * FROM ' . K_MENUS_TABLE . ' ORDER BY menu_type, ndx ASC';
		}
		else if ($this_one == UN_ALLOC_MENUS)
		{
			$sql = 'SELECT * FROM ' . K_MENUS_TABLE . ' WHERE menu_type = ' . (int) $this_one . ' ORDER BY ndx, menu_type ASC';
		}
		else
		{
			$sql = 'SELECT * FROM ' . K_MENUS_TABLE . ' WHERE menu_type=' . (int) $this_one;
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

					'U_EDIT'    => append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=edit&amp;menu=" . $row['m_id']),
					'U_UP'      => append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=up&amp;menu=" . $row['m_id']),
					'U_DOWN'    => append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=down&amp;menu=" . $row['m_id']),
					'U_DELETE'  => append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=delete&amp;menu=" . $row['m_id'] . "&amp;type=" . $mode),
				));
			}
			$db->sql_freeresult($result);
		}
		else
		{
			trigger_error($user_lang['COULD_NOT_RETRIEVE_BLOCK'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
		}
	}
	public function get_menu_item($item)
	{
		global $db, $template;

		$m_id = $item;

		$sql = 'SELECT *
			FROM ' . K_MENUS_TABLE . '
			WHERE m_id=' . (int) $item;

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
	public function get_menu_icons()
	{
		global $phpbb_root_path, $phpEx, $template, $dirslist, $user;

		$dirslist = ' ';

		$dirs = dir($phpbb_root_path. 'ext/phpbbireland/portal/images/block_images/menu');

		while ($file = $dirs->read())
		{
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
	public function get_next_ndx($type)
	{
		global $db, $ndx, $user;
		$sql = "SELECT *
			FROM " . K_MENUS_TABLE . "
			WHERE menu_type = '" . (int) $type . "'
			ORDER by ndx DESC";

		if ($result = $db->sql_query($sql))
		{
			$row = $db->sql_fetchrow($result);		// just get last block ndx details	//
			$ndx = $row['ndx'];						// only need last ndx returned		//
			$ndx = $ndx + 1; 						// add 1 to index 					//
			return($ndx);
		}
	}
	public function parse_all_groups()
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
}
