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

if (!defined('IN_PHPBB'))
{
   exit;
}

class blocks_module
{
	var $u_action;

	function main($module_id, $mode)
	{
		global $db, $user, $auth, $template, $cache, $request;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx, $k_config, $table_prefix;
		global $helper, $root_path, $php_ext, $content_visibility;

		$submit = $request->is_set_post('submit');

		include_once($phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $phpEx);
		include_once($phpbb_root_path . 'ext/phpbbireland/portal/helpers/tables.' . $phpEx);

		$this->cache_setup();

		if (!class_exists('sgp_functions_admin'))
		{
			require($phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_functions_admin.' . $phpEx);
			$sgp_functions_admin = new sgp_functions_admin();
		}

		$k_config = $cache->get('k_config');

		$user->add_lang_ext('phpbbireland/portal', 'k_blocks');
		$user->add_lang_ext('phpbbireland/portal', 'k_variables');

		$this->tpl_name = 'acp_blocks';
		$this->page_title = $user->lang['ACP_BLOCKS'];
		add_form_key('acp_blocks');

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('acp_blocks'))
			{
				$submit = false;
				$mode = '';
				trigger_error('FORM_INVALID');
			}
			$submit = true;
		}

		// Do not write values if there is an error
		/*
		if (sizeof($error))
		{
			$mode = '';
			$submit = false;
		}
		*/

		// Can not use append_sid here, the $block_id is assigned to the html but unknow to this code //
		// Would require I add a form element and use $request->variable to retrieve it //

		$template->assign_vars(array(
			'U_BACK'        => $this->u_action,
			'PORTAL_JS'     => $portal_js,
			'IMG_PATH'      => $img_path_acp_block_icons,
			'IMG_PATH_ACP'  => $img_path_acp_icons,
			'CSS_PATH'      => $css_path_acp,
			'U_MANAGE_PAGES'	=> append_sid("{$phpbb_admin_path}index.$phpEx" , "i={$module_id}&amp;mode=manage"),
		));

		$mode     = $request->variable('mode', '');
		$block    = $request->variable('block', 0);

		// bold current row text so things are easier to follow when moving/editing etc... //
		if (($block) ? $block : 0)
		{
			$sql = 'UPDATE ' . K_VARS_TABLE . ' SET config_value = ' . (int) $block . ' WHERE config_name = "k_adm_block"';
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
			case 'up':
			case 'down':

				$ids = $ndxs = array();
				$out_of_wack = false;

				// get current block data using $block var //
				$sql = "SELECT id, ndx, position
					FROM " . K_BLOCKS_TABLE . "
					WHERE id = " . (int) $block;

				$result = $db->sql_query_limit($sql, 1);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				$current_id  = $row['id'];
				$current_ndx = $row['ndx'];
				$position    = $row['position'];

				// get current block data suing $block var //
				$sql = "SELECT *FROM " . K_BLOCKS_TABLE . "
					WHERE position = '" . $db->sql_escape($position) . "'" . "
					ORDER BY ndx";
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$ids[] = (int) $row['id'];
					$ndxs[] = (int) $row['ndx'];
				}
				$db->sql_freeresult($result);

				// are ndx sequential

				for ($i = 0; $i < $count = count($ids); $i++)
				{
					if ($ndxs[$i] != $i + 1)
					{
						$out_of_wack = true;
					}
				}

				if ($out_of_wack)
				{
					for ($i = 0; $i < $count = count($ids); $i++)
					{
						$ndxs[$i] = $i + 1;
						$sql = "UPDATE " . K_BLOCKS_TABLE . " SET ndx = " . $ndxs[$i] . " WHERE id = " . $ids[$i];
						$results = $db->sql_query($sql);
					}
					$db->sql_freeresult($result);

					$template->assign_vars(array(
						'S_BUTTON_HIDE'  => true,
						'BLOCK_REPORT'   => $user->lang['BLOCKS_AUTO_REINDEXED'],
					));

					//$cache->destroy('sql', $k_blocks);

					meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=". $mode));
					//meta_refresh(1, ($k_config['base']) ? $k_config['base'] : $this->u_action);
					return;
				}

				$to_move = $move_to = array();

				// get current block data//
				$sql = "SELECT id, ndx, position
					FROM " . K_BLOCKS_TABLE . "
					WHERE id = " . (int) $block;

				if (!$result = $db->sql_query_limit($sql, 1))
				{
					trigger_error($user->lang['ERROR_PORTAL_BLOCKS'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
				}

				// Added function to reindex blocks after a block deletion Dicky
				if (isset($current_position) && $current_position != $position)
				{
					$index_start = $this->get_lowest_ndx($current_position);
					$this->reindex_column($current_position, $index_start);
				}

				$row = $db->sql_fetchrow($result);
				$to_move['id'] = (int) $row['id'];
				$to_move['ndx']  = $temp = (int) $row['ndx'];

				// position is char 'L', 'R', 'C' (char) //
				$position = $row['position'];

				if ($mode == 'up')
				{
					$temp = $temp - 1;
				}
				if ($mode == 'down')
				{
					$temp = $temp + 1;
				}

				// get move_to block data//
				$sql = "SELECT id, ndx, position FROM " . K_BLOCKS_TABLE . "
					WHERE ndx =  '" . (int) $temp . "'
						AND position = '" . $db->sql_escape($position) . "'";

				if (!$result = $db->sql_query_limit($sql, 1))
				{
					trigger_error($user->lang['BLOCK_MOVE_ERROR'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
				}

				$row = $db->sql_fetchrow($result);

				$move_to['id'] = (int) $row['id'];
				$move_to['ndx']  = (int) $row['ndx'];

				// fix block index if out of wack, reindex and re-run code //
				if ($move_to['ndx'] != $temp || $move_to['id'] == '')
				{
					$this->index_column_fix($position);

					$to_move = $move_to = array();

					// get current block data//
					$sql = "SELECT id, ndx, position FROM " . K_BLOCKS_TABLE . "
						WHERE id = " . (int) $block;

					if (!$result = $db->sql_query_limit($sql, 1))
					{
						trigger_error($user->lang['ERROR_PORTAL_BLOCKS'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
					}

					// Added function to reindex blocks after a block deletion Dicky
					if (isset($current_position) && $current_position != $position)
					{
						$index_start = $this->get_lowest_ndx($current_position);
						$this->reindex_column($current_position, $index_start);
					}

					$row = $db->sql_fetchrow($result);

					$to_move['id'] = (int) $row['id'];
					$to_move['ndx']  = (int) $temp = $row['ndx'];

					$position = $row['position'];

					if ($mode == 'up')
					{
						$temp = $temp - 1;
					}
					if ($mode == 'down')
					{
						$temp = $temp + 1;
					}

					// get move_to block data//
					$sql = "SELECT id, ndx, position FROM " . K_BLOCKS_TABLE . "
						WHERE ndx =  '" . (int) $temp . "'
							AND position = '" . $db->sql_escape($position) . "'";

					if (!$result = $db->sql_query_limit($sql, 1))
					{
						trigger_error($user->lang['BLOCK_MOVE_ERROR'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
					}

					$row = $db->sql_fetchrow($result);

					$move_to['id'] = (int) $row['id'];
					$move_to['ndx']  = (int) $row['ndx'];
				}

				if ($mode == 'up')// mod validation note... sql is not repeated!
				{
					// sql is not duplicated
					$sql = "UPDATE " . K_BLOCKS_TABLE . " SET ndx = " . $to_move['ndx'] . " WHERE id = " . $move_to['id'];
					if (!$result = $db->sql_query($sql))
					{
						trigger_error($user->lang['BLOCK_MOVE_ERROR'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
					}

					$sql = "UPDATE " . K_BLOCKS_TABLE . " SET ndx = " . $move_to['ndx'] . " WHERE id = " . $to_move['id'];
					if (!$result = $db->sql_query($sql))
					{
						trigger_error($user->lang['BLOCK_MOVE_ERROR'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
					}
				}

				if ($mode == 'down')// mod validation note... sql is not repeated!
				{
					// sql is not duplicated
					$sql = "UPDATE " . K_BLOCKS_TABLE . " SET ndx = " . $move_to['ndx'] . " WHERE id = " . $to_move['id'];
					if (!$result = $db->sql_query($sql))
					{
						trigger_error($user->lang['BLOCK_MOVE_ERROR'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
					}

					$sql = "UPDATE " . K_BLOCKS_TABLE . " SET ndx = " . $to_move['ndx'] . " WHERE id = " . $move_to['id'];
					if (!$result = $db->sql_query($sql))
					{
						trigger_error($user->lang['BLOCK_MOVE_ERROR'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
					}
				}

				$template->assign_var('BLOCK_REPORT', $user->lang['BLOCK_UPDATING']);

				if ($position)
				{
					$mode = $position;
				}
				else
				{
					$mode = 'manage';
				}

				//$cache->destroy('sql', $k_blocks);

				meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=". $mode));

				return;
			break;

			case 'add':
				if ($submit)
				{
					if ($request->variable('html_file_name','') == "" || $request->variable('title', '') == "")
					{
						$message = $user->lang['MISSING_BLOCK_DATA'];

						$template->assign_var('BLOCK_REPORT', $message .'<br />');

						meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=add"));
						return;
					}

					$title             = utf8_normalize_nfc($request->variable('title', '', true));
					$position          = $request->variable('position', '');
					$active            = $request->variable('active', 1);
					$type              = $request->variable('type', '');
					$scroll            = $request->variable('scroll', 0);
					$view_groups       = $request->variable('view_groups', '');
					$view_all          = $request->variable('view_all', 1);
					$view_pages        = $request->variable('view_pages', '');
					$html_file_name    = $request->variable('html_file_name', '');
					$var_file_name     = $request->variable('var_file_name', '');
					$img_file_name     = $request->variable('img_file_name', '');
					$has_vars          = $request->variable('has_vars', 0);
					$minimod_based     = $request->variable('minimod_based', 0);
					$mod_block_id      = $request->variable('mod_block_id', 0);
					$block_cache_time  = $request->variable('block_cache_time', 600);

					if ($html_file_name == '...')
					{
						$html_file_name = '';
					}
					if ($has_vars == 0)
					{
						$var_file_name = '';
					}
					if ($minimod_based == 0)
					{
						$mod_block_id = '0';
					}

					$view_page_id = $request->variable('view_page_id', array(0));

					for ($i = 0; $i < count($view_page_id); $i++)
					{
						$view_pages .= $view_page_id[$i];

						if ($i < count($view_page_id) - 1)
						{
							$view_pages .= ',';
						}
					}

					$ndx = $this->get_next_ndx($position);		// get the next ndx to use for this position	//

					$sql_array = array(
						'ndx'               => $ndx,
						'title'             => $title,
						'active'            => $active,
						'html_file_name'    => $html_file_name,
						'var_file_name'     => $var_file_name,
						'img_file_name'     => $img_file_name,
						'position'          => $position,
						'view_groups'       => $view_groups,
						'view_all'          => $view_all,
						'view_pages'        => $view_pages,
						'scroll'            => $scroll,
						'has_vars'          => $has_vars,
						'minimod_based'     => $minimod_based,
						'mod_block_id'      => $mod_block_id,
						'block_cache_time'  => $block_cache_time,
					);

					if (!$db->sql_query('INSERT INTO ' . K_BLOCKS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_array)))
					{
						trigger_error($user->lang['COULD_NOT_ADD_BLOCK'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
					}
					else
					{
						$message = $user->lang['BLOCK_ADDED'];
						$template->assign_var('BLOCK_REPORT', $title . $message . '<br />');
					}

					//$cache->destroy('sql', $k_blocks);

					meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage"));
					return;
				}
				else
				{
					$this->parse_all_groups();
					$this->get_all_pages(0);
					$this->get_all_vars_files($block);

					//Get all installed blocks//
					$data='';
					$c = 0;

					$sql = 'SELECT * FROM ' . K_BLOCKS_TABLE;
					if ($result = $db->sql_query($sql))
					{
						while ($row = $db->sql_fetchrow($result))
						{
							$data .= $row['id'];
							$data .=' ';
							$c++;
						}
					}

					$dirslist = '... '; // use ... for empty //

					$dirs = $this->dir_file_exists($phpbb_root_path . 'ext/phpbbireland/portal/styles/common/template/blocks/');

					while ($file = $dirs->read())
					{
						if ($file != '.' && $file != '..' && !stripos($file, ".bak") && strpos($file, 'lock_'))
						{
							$dirslist .= "$file ";
						}
					}

					closedir($dirs->handle);

					$dirslist = explode(" ", $dirslist);
					sort($dirslist);

					for ($i = 0; $i < sizeof($dirslist); $i++)
					{
						if ($dirslist[$i] != '')
						{
							$template->assign_block_vars('html_file_name', array('S_BLOCK_FILE_HTML' => $dirslist[$i]));
						}
					}

					$dirslist = ''; // use ... for empty //

					$dirs = $this->dir_file_exists($phpbb_root_path . 'ext/phpbbireland/portal/images/block_images/block');

					while ($file = $dirs->read())
					{
						if (stripos($file, ".gif") ||  stripos($file, ".png"))
						{
							$dirslist .= "$file ";
						}
					}

					closedir($dirs->handle);

					$dirslist = explode(" ", $dirslist);
					sort($dirslist);

					for ($i = 0; $i < sizeof($dirslist); $i++)
					{
						if ($dirslist[$i] != '')
						{
							$template->assign_block_vars('img_file_name', array('S_BLOCK_FILE_I' => $dirslist[$i]));
						}
					}
					$dirslist = '';

					// pass default empty/blank image //
					$template->assign_vars(array(
						'S_FNAME_I' => '_blank.gif',
						'S_OPTIONS' => strtolower($mode),
					));
				}
			break;

			case 'edit':

				if ($submit)
				{
					$id                = $request->variable('id', 0);
					$ndx               = $request->variable('ndx', 0);
					$title             = utf8_normalize_nfc($request->variable('title', '', true));
					$position          = $request->variable('position', '');
					$type              = $request->variable('type', '');
					$active            = $request->variable('active', 1);
					$view_groups       = $request->variable('view_groups', '');
					$view_all          = $request->variable('view_all', 1);
					$view_pages        = $request->variable('view_pages', '');
					$scroll            = $request->variable('scroll', 0);
					$has_vars          = $request->variable('has_vars', 0);
					$minimod_based     = $request->variable('minimod_based', 0);
					$mod_block_id      = $request->variable('mod_block_id', 0);
					$html_file_name    = $request->variable('html_file_name', '');
					$var_file_name     = $request->variable('var_file_name', '');
					$img_file_name     = $request->variable('img_file_name', '');
					$block_cache_time  = $request->variable('block_cache_time', 600);

					$view_page_id = $request->variable('view_page_id', array(0));

					for ($i = 0; $i < count($view_page_id); $i++)
					{
						$view_pages .= $view_page_id[$i];

						if ($i < count($view_page_id) - 1)
						{
							$view_pages .= ',';
						}
					}

					// this shoud not happen but just in case //
					if (!$id)
					{
						$template->assign_var('BLOCK_REPORT', $user->lang['UNKNOWN_ERROR']);
						$submit = false;
						return;
					}

					if ($type == 1)
					{
						$type = 'H'; //html
					}
					else
					{
						$type = 'B'; //bbcode
					}

					if ($view_all)
					{
						$view_groups = $sgp_functions_admin->get_all_groups();
						if ($view_groups == '')
						{
							$view_groups = 0;
						}
					}

					if ($html_file_name == '...')
					{
						$html_file_name = '';
					}
					if ($var_file_name == '...')
					{
						$var_file_name = '';
					}

					/*
					if ($has_vars == 0)
					{
						$var_file_name = '';
					}*/

					if ($minimod_based == 0)
					{
						$mod_block_id = '0';
					}

					// get the current block position //
					$current_position = $this->get_current_position($id);

					// if moving block position (column) //
					if ($current_position != $position)
					{
						$ndx = $this->get_next_ndx($position);
					}

					$sql_ary = array(
						'ndx'               => $ndx,
						'active'            => $active,
						'title'             => $title,
						'position'          => $position,
						'type'              => $type,
						'html_file_name'    => $html_file_name,
						'var_file_name'     => $var_file_name,
						'img_file_name'     => $img_file_name,
						'view_groups'       => $view_groups,
						'view_pages'        => $view_pages,
						'view_all'          => $view_all,
						'scroll'            => $scroll,
						'has_vars'          => $has_vars,
						'minimod_based'     => $minimod_based,
						'mod_block_id'      => $mod_block_id,
						'block_cache_time'  => $block_cache_time,
					);

					$sql = 'UPDATE ' . K_BLOCKS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . " WHERE id = " . (int) $id;

					if (!$result = $db->sql_query($sql))
					{
						trigger_error($user->lang['COULD_NOT_EDIT_BLOCK'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
					}

					if ($has_vars)
					{
						$var_path = 'ext/phpbbireland/portal/acp/variables/';

						$file = $var_file_name;
						$file = str_replace('.html', '.php', $file);

						if ($file == 'k_the_teams_vars.php')
						{
							$sgp_functions_admin->get_teams_sort();
						}

						if (!file_exists($phpbb_root_path . $var_path . $file))
						{
							trigger_error('NO_FILENAME' . $phpbb_root_path . $var_path . $file);
						}

						include_once($phpbb_root_path . $var_path . $file);
					}

					$template->assign_var('BLOCK_REPORT', $user->lang['SAVING']);

					//$cache->destroy('sql', $k_blocks);

					$this->delete_this_block_cached_file($html_file_name);

					if ($position)
					{
						$mode = $position;
					}
					else
					{
						$mode = 'manage';
					}

					meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=". $mode));
					return;
				}

				// search our common styles folder for all available html files //
				$dirslist = '... ';
				$dirs = $this->dir_file_exists($phpbb_root_path . 'ext/phpbbireland/portal/styles/common/template/blocks');

				while ($file = $dirs->read())
				{
					if ($file != '.' && $file != '..' && !stripos($file, ".bak"))
					{
						$dirslist .= "$file ";
					}
				}
				closedir($dirs->handle);
				$dirslist = explode(" ", $dirslist);
				sort($dirslist);
				for ($i = 0; $i < sizeof($dirslist); $i++)
				{
					if ($dirslist[$i] != '')
					{
						$template->assign_block_vars('html_file_name', array('S_BLOCK_FILE_HTML' => $dirslist[$i]));
					}
				}

				// get all available block images //
				$dirslist = '';
				$dirs = $this->dir_file_exists($phpbb_root_path . 'ext/phpbbireland/portal/images/block_images/block');

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
				for ($i = 0; $i < sizeof($dirslist); $i++)
				{
					if ($dirslist[$i] != '')
					{
						$template->assign_block_vars('img_file_name', array('S_BLOCK_FILE_I' => $dirslist[$i]));
					}
				}
				$dirslist = '';

				$template->assign_var('S_OPTIONS', 'add'); // not a language var //

				if ($block == '' || $block == 0) // just checking!
				{
					trigger_error($user->lang['NO_BLOCK_ID'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				$sql = 'SELECT * FROM ' . K_BLOCKS_TABLE . ' WHERE id=' . (int) $block;

				if ($result = $db->sql_query($sql))
				{
					$row = $db->sql_fetchrow($result);
				}

				if ($row['img_file_name'] == '')
				{
					$row['img_file_name'] = 'default.gif';
				}

				$template->assign_vars(array(
					'S_ID'             => $row['id'],
					'S_NDX'            => $row['ndx'],
					'S_TITLE'          => $row['title'],
					'S_POSITION'       => $row['position'],
					'S_ACTIVE'         => $row['active'],
					'S_TYPE'           => $row['type'],
					'S_FNAME_H'        => $row['html_file_name'],
					'S_FNAME_V'        => $row['var_file_name'],
					'S_FNAME_I'        => $row['img_file_name'],
					'S_VIEW_GROUPS'    => $row['view_groups'],
					'S_VIEW_ALL'       => $row['view_all'],
					'S_VIEW_PAGES'     => $row['view_pages'],
					'S_SCROLL'         => $row['scroll'],
					'S_HAS_VARS'       => $row['has_vars'],
					'S_MINIMOD_BASED'  => $row['minimod_based'],
					'S_MOD_BLOCK_ID'   => $row['mod_block_id'],
					'BLOCK_CACHE_TIME' => $row['block_cache_time'],
					'S_BLOCK_VAR'      => ($row['var_file_name']) ? $row['var_file_name'] : '',
				));

				$sgp_functions_admin->get_link_images();

				// get all groups and fill array //
				$this->get_all_pages($block);
				$this->parse_all_groups();
				$this->get_all_vars_files($block);

				if ($row['has_vars'] && $row['var_file_name'])
				{
					$var_path = 'ext/phpbbireland/portal/acp/variables/';

					$file = $row['var_file_name'];

					$file = str_replace('.html', '.php', $file);

					if ($file == 'k_the_teams_vars.php')
					{
						$sgp_functions_admin->get_teams_sort();
					}

					if (!file_exists($phpbb_root_path . $var_path . $file))
					{
						$error = sprintf($user->lang['NO_VAR_FILE'], $phpbb_root_path . $var_path . $file);
						trigger_error($error . adm_back_link($this->u_action), E_USER_NOTICE);
					}

					include_once($phpbb_root_path . $var_path . $file);
				}

				$db->sql_freeresult($result);

				$template->assign_var('S_OPTIONS', strtolower($mode));

			break;

			case 'delete':

				if (!$block)
				{
					trigger_error($user->lang['MUST_SELECT_VALID_BLOCK_DATA'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				if (confirm_box(true))
				{
					$sql = 'SELECT title, id, position
						FROM ' . K_BLOCKS_TABLE . '
						WHERE id = ' . (int) $block;
					$result = $db->sql_query($sql);

					$row = $db->sql_fetchrow($result);

					$title = $row['title'];
					$id = $row['id'];
					$position = $row['position'];

					$db->sql_freeresult($result);

					// Get lowest ndx for current position block Dicky
					$index_start = $this->get_lowest_ndx($position);

					$sql = "DELETE FROM " . K_BLOCKS_TABLE . "
						WHERE id = " . (int) $block;
					$db->sql_query($sql);

					// Added function to reindex blocks after a block deletion
					$this->reindex_column($position, $index_start);

					$template->assign_var('BLOCK_REPORT', $title . $user->lang['BLOCK_DELETED'] . '<br />');

					//$cache->destroy('sql', $k_blocks);

					meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage"));

					break;

				}
				else
				{
					confirm_box(false, $user->lang['CONFIRM_OPERATION_BLOCKS'], build_hidden_fields(array(
						'i'       => $module_id,
						'mode'    => $mode,
						'action'  => 'delete',
					)));
				}
				$template->assign_var('BLOCK_REPORT', $user->lang['ACTION_CANCELLED']);
				meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage"));
			break;

			case 'reindex':

				$index_no = 1;

				if (confirm_box(true))
				{
					$thispos = $newpos = '';
					$list = array();

					$sql = 'SELECT * FROM ' . K_BLOCKS_TABLE . ' ORDER by position, ndx';

					if ($result = $db->sql_query($sql))
					{
						while ($row = $db->sql_fetchrow($result))
						{
							$thispos = $row['position'];
							if ($thispos == $newpos)
							{
								$index_no++;
							}
							else
							{
								$index_no = 1;
							}

							$sql = "UPDATE " . K_BLOCKS_TABLE . " SET ndx = '" . (int) $index_no . "' WHERE id = " . (int) $row['id'];

							//reset ndx to 1 when position changes
							$newpos = $row['position'];

							$results = $db->sql_query($sql);

							if (!$results)
							{
								trigger_error($user->lang['RE-INDEXING BLOCKS'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
							}
						}
					}

					$db->sql_freeresult($result);

					$template->assign_vars(array(
						'S_BUTTON_HIDE'	=> true,
						'BLOCK_REPORT' => $user->lang['BLOCKS_REINDEXED'],
					));

					//$cache->destroy('sql', $k_blocks);

					meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage"));

					break;

				}
				else
				{
					confirm_box(false, $user->lang['CONFIRM_OPERATION_BLOCKS_REINDEX'], build_hidden_fields(array(
						'i'			=> $module_id,
						'mode'		=> $mode,
						'action'	=> 'reindex',
					)));
				}

				$template->assign_var('BLOCK_REPORT', $user->lang['ACTION_CANCELLED']);

				meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage"));
			break;

			case 'tools':
						$template->assign_var('S_OPTIONS', 'tools'); // not  language var //
			break;

			case 'l':
			case 'c':
			case 'r':
			case 'L':
			case 'C':
			case 'R':
			case '1':
			case '2':
			case '3':
			case 'manage':

				$template->assign_var('S_TYPE', $mode);

				if ($mode != 'manage')
				{
					$sql = "SELECT * FROM " . K_BLOCKS_TABLE . " WHERE position = '" . $db->sql_escape($mode) . "' ORDER by ndx, type";
				}
				else
				{
					$sql = 'SELECT * FROM ' . K_BLOCKS_TABLE . ' ORDER by position, ndx';
				}

				$l_b_first = $r_b_first = $c_b_first = $l_b_last = $r_b_last = $c_b_last = 1;

				if ($result = $db->sql_query($sql))
				{
					while ($row = $db->sql_fetchrow($result))
					{
						if ($row['img_file_name'] == '')
						{
							$row['img_file_name'] = 'default.gif';
						}

						if ($row['position'] == 'L')
						{
							$l_b_last = $l_b_last + 1;
						}
						else if ($row['position'] == 'R')
						{
							$r_b_last = $r_b_last + 1;
						}
						else if ($row['position'] == 'C')
						{
							$c_b_last = $c_b_last + 1;
						}

						$template->assign_block_vars('bdata', array(
							'S_ID'               => $row['id'],
							'S_NDX'              => $row['ndx'],
							'S_TITLE'            => $row['title'],
							'S_POSITION'         => $row['position'],
							'S_ACTIVE'           => $row['active'],
							'S_TYPE'             => $row['type'],
							'S_FNAME_H'          => $row['html_file_name'],
							'S_FNAME_V'          => $row['var_file_name'],
							'S_FNAME_I'          => $row['img_file_name'],
							'S_VIEW_GROUPS'      => $row['view_groups'],
							'S_VIEW_ALL'         => $row['view_all'],
							'S_VIEW_PAGES'       => $row['view_pages'],
							'S_SCROLL'           => $row['scroll'],
							'S_HAS_VARS'         => $row['has_vars'],
							'S_MINIMOD_BASED'    => $row['minimod_based'],
							'S_MOD_BLOCK_ID'     => $row['mod_block_id'],
							'S_BLOCK_CACHE_TIME' => $row['block_cache_time'],
							'S_BLOCK'            => ($row['id'] == $block) ? $block : '.....',

							'U_EDIT2'    => append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=edit&amp;block=" . $row['id']),
							'U_UP'       => append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=up&amp;block=" . $row['id']),
							'U_DOWN'     => append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=down&amp;block=" . $row['id']),
							'U_DELETE'   => append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=delete&amp;block=" . $row['id']),
							'U_SET_VARS' => append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=config&amp;block=" . $row['id']),
						));
					}

					$db->sql_freeresult($result);
				}

				//$template->assign_var('U_ACTION', $this->u_action);

				$template->assign_vars(array(
					'S_OPTIONS'	=> strtolower($mode),
					'S_LBL' => $l_b_last-1,
					'S_RBL' => $r_b_last-1,
					'S_CBL' => $c_b_last-1,
					'S_LRC' => '1',
				));

				//$module_id = $request->variable('i', '');

			break;

			case 'reset':
				$sql = "UPDATE " . USERS_TABLE . " SET user_left_blocks = '', user_center_blocks = '', user_right_blocks = '';";

				if (!$result = $db->sql_query($sql))
				{
					trigger_error($user->lang['COULD_NOT_RESET_BLOCKS'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
				}

				$template->assign_var('BLOCK_REPORT', $user->lang['BLOCK_LAYOUT_RESET']);
				meta_refresh(2, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}"));
				return;

			case 'config':

				if ($submit)
				{
					meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage"));
					return;
				}

				$block_file = $this->get_varfile($block);

				$template->assign_vars(array(
					'U_BACK'			=> $this->u_action,
					'PORTAL_JS'         => $portal_js,
					'IMG_PATH'          => $img_path_acp_block_icons,
					'IMG_PATH_ACP'      => $img_path_acp_icons,
					'CSS_PATH'          => $css_path_acp,
					'S_OPTIONS'         => 'config',
					'S_BLOCK_VAR'       => $block_file
				));

				process_block_variables($block_file, $submit, $module_id);

			break;

			case 'default':
			break;
		}
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

	/**
	* Takes the $id of a given block (20 August 2010)
	*
	* (a) sets the page id, name and checkboxes for this block and any pages it is displayed in...
	* (b) creates the pages array
	*
	**/
	public function get_all_pages($id)
	{
		global $db, $template, $request, $phpbb_root_path, $phpbb_admin_path, $phpEx, $user;

		if ($id != 0)
		{
			$sql = 'SELECT id, view_pages FROM ' . K_BLOCKS_TABLE . ' WHERE id=' . (int) $id;

			if ($result = $db->sql_query($sql))
			{
				$row = $db->sql_fetchrow($result);
			}
			$db->sql_freeresult($result);
			$arr = explode(','  , $row['view_pages']);
		}

		// Get all pages
		$sql = 'SELECT page_id, page_name
			FROM ' . K_PAGES_TABLE . '
			ORDER BY page_id ASC, page_name';
		$result = $db->sql_query($sql);

		$template->assign_block_vars('pages', array(
			'PAGE_NAME'		=> $user->lang['NONE'],
			'PAGE_ID'		=> 0,
		));

		while ($row = $db->sql_fetchrow($result))
		{
			$page_id = $row['page_id'];
			$page_name = $row['page_name'];
			$template->assign_block_vars('pages', array(
				'PAGE_NAME'  => $page_name,
				'PAGE_ID'    => $page_id,
				'IS_CHECKED' => ($id) ? (in_array($page_id, $arr)) ? true : false : '',
			));
		}
		$db->sql_freeresult($result);
	}
	public function get_varfile($id)
	{
		global $db, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$sql = "SELECT var_file_name FROM " . K_BLOCKS_TABLE . " WHERE id = '" . (int) $id . "'";

		if ($result = $db->sql_query($sql))
		{
			$row = $db->sql_fetchrow($result);
		}
		return($row['var_file_name']);
	}
	public function get_next_ndx($pos)
	{
		global $db;

		$sql = "SELECT * FROM " . K_BLOCKS_TABLE . " WHERE position = '" . $db->sql_escape($pos) . "' ORDER by ndx DESC";

		if ($result = $db->sql_query($sql))
		{
			$row = $db->sql_fetchrow($result);    // just get last block ndx details  //
			$ndx = $row['ndx'];                   // only need last ndx returned      //
			$ndx = $ndx + 1;                      // add 1 to index                   //
			return($ndx);
		}
	}
	public function get_current_position($my_id)
	{
		global $db, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$sql = "SELECT position FROM " . K_BLOCKS_TABLE . " WHERE id = '" . (int) $my_id . "'";

		if ($result = $db->sql_query($sql))
		{
			$row = $db->sql_fetchrow($result);
			$position = $row['position'];

			return($position);
		}
		return(-1);
	}
	public function which_group($id)
	{
		global $db, $template, $user;

		// Get all the groups
		$sql = 'SELECT group_name
			FROM ' . GROUPS_TABLE . '
			WHERE group_id = ' . (int) $id;

		$result = $db->sql_query($sql);

		$name = $db->sql_fetchfield('group_name');

		$db->sql_freeresult($result);

		if ($name == '')
		{
			return('.. ');
		}
		else
		{
			return ($name);
		}
	}
	public function get_all_vars_files($block)
	{
		global $template, $user, $phpbb_admin_path, $phpbb_root_path;

		$dirslist = "... "; // use "..." for empty //s

		$dirs = $this->dir_file_exists($phpbb_root_path . 'ext/phpbbireland/portal/adm/style/k_block_vars');

		while ($file = $dirs->read())
		{
			if (!stripos($file, ".bak"))
			{
				if ($file != '.' and $file != '..')
				{
					$dirslist .= "$file ";
				}
			}
		}
		closedir($dirs->handle);

		$dirslist = explode(" ", $dirslist);
		sort($dirslist);

		for ($i = 0; $i < sizeof($dirslist); $i++)
		{
			if ($dirslist[$i] != '')
			{
				$template->assign_block_vars('var_file_name', array('S_VAR_FILE_NAME' => $dirslist[$i]));
			}
		}
		$dirslist = '';
	}
	public function reindex_column($position, $idx)
	{
		global $db, $user;

		$sql = "SELECT id, ndx
			FROM  " . K_BLOCKS_TABLE . "
			WHERE position = '" . $db->sql_escape($position) . "'
			ORDER BY ndx";
		if ($result = $db->sql_query($sql))
		{
			$i = $idx;
			while ($row = $db->sql_fetchrow($result))
			{
				$id = $row['id'];
				$sql = 'UPDATE ' . K_BLOCKS_TABLE . ' SET ndx = ' . $i . ' WHERE id = ' . $id;
				if (!$result = $db->sql_query($sql))
				{
					trigger_error($user->lang['COULD_NOT_REINDEX_BLOCKS'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
				}
				++$i;
			}
		}
		$db->sql_freeresult($result);
	}
	public function delete_this_block_cached_file($thisfile)
	{
		return;

		global $cache, $phpbb_root_path, $user;

		$thisfile .= '.php';

		$dirslist = '';

		$dirs = $this->dir_file_exists($phpbb_root_path . 'cache');

		while ($file = $dirs->read())
		{
			if (strpos($file, $thisfile))
			{
				$cache->destroy($file);
				//unset($file);
			}
		}

		closedir($dirs->handle);
	}
	public function delete_all_block_cached_files()
	{
		return;

		global $cache, $phpbb_root_path, $user;

		$dirslist = '';

		$dirs = $this->dir_file_exists($phpbb_root_path . 'cache');

		while ($file = $dirs->read())
		{
			if (stripos($file, "_blocks.block_"))
			{
				$cache->destroy($file);
			}
		}
		closedir($dirs->handle);
	}
	// Get lowest ndx for current position block
	public function get_lowest_ndx($position)
	{
		global $db;

		$sql = "SELECT ndx
			FROM  " . K_BLOCKS_TABLE . "
			WHERE position = '" . $db->sql_escape($position) . "'
			ORDER BY ndx ASC";
		$result = $db->sql_query_limit($sql, 1);

		$index_start = (int) $db->sql_fetchfield('ndx');
		$db->sql_freeresult($result);

		return ($index_start);
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
			'GROUP_NAME'  => $user->lang['NONE'],
			'GROUP_ID'    => 0,
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
	public function dir_file_exists($file)
	{
		global $user;

		if (!file_exists($file))
		{
			trigger_error(sprintf($user->lang['MISSING_FILE_OR_FOLDER'], $file));
		}
		return(dir($file));
	}
	public function index_column_fix($position)
	{
		global $db, $user;

		$id_array = array();
		$ndx_array = array();

		$sql = "SELECT id, ndx
			FROM  " . K_BLOCKS_TABLE . "
			WHERE position = '" . $db->sql_escape($position) . "'
			ORDER BY ndx";

		if ($result = $db->sql_query($sql))
		{
			while ($row = $db->sql_fetchrow($result))
			{
				$id_array[] = $row['id'];
				$ndx_array[] = $row['ndx'];
			}
		}
		$db->sql_freeresult($result);

		for ($i = 0; $i < sizeof($id_array); $i++)
		{
			if ($id_array[$i] != $i + 1)
			{
				$j = $i + 1;

				$sql = "";
				$sql = "UPDATE " . K_BLOCKS_TABLE . " SET ndx = " . (int) $j . " WHERE id = " . $id_array[$i];

				if ($result = $db->sql_query($sql))
				{
					$db->sql_freeresult($result);
				}
				else
				{
					trigger_error($user->lang['COULD_NOT_REINDEX_BLOCKS'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
				}
			}
		}
	}
}

function get_k_config()
{
	global $db, $k_config;

	$sql = 'SELECT config_name, config_value
	FROM ' . K_VARS_TABLE;

	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$k_config[$row['config_name']] = $row['config_value'];
	}
	$db->sql_freeresult($result);
}
