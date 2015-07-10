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

class pages_module
{
	var $u_action;

	function main($module_id, $mode)
	{
		global $db, $user, $auth, $template, $cache, $request;
		global $config, $SID, $phpbb_root_path, $phpbb_admin_path, $phpEx, $k_config, $table_prefix, $cache;

		include_once($phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $phpEx);

		if(!function_exists('obtain_k_config'))
		{
			include($phpbb_root_path . 'ext/phpbbireland/portal/includes/functions.' . $phpEx);
			$k_config = obtain_k_config();
		}

		$img_path  = $phpbb_root_path . 'ext/phpbbireland/portal/images/block_images/block/';
		$img_path_acp = $phpbb_root_path . 'ext/phpbbireland/portal/adm/images/';
		$portal_js = $phpbb_root_path . 'ext/phpbbireland/portal/js/portal.js';

		$user->add_lang_ext('phpbbireland/portal', 'k_pages');
		$this->tpl_name = 'acp_pages';
		$this->page_title = $user->lang['ACP_PAGES'];
		add_form_key('pages');

		if (!class_exists('sgp_functions_admin'))
		{
			require($phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_functions_admin.' . $phpEx);
			$sgp_functions_admin = new sgp_functions_admin();
		}

		$mode = $request->variable('mode', '');
		$page_id = $request->variable('page_id', 0);
		$action	= $request->variable('config', '');
		$tag_id = $request->variable('tag_id', '');

		$current_pages = array();

		$submit = false;
		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('pages'))
			{
				$submit = false;
				$mode = '';
				trigger_error('FORM_INVALID');
			}
			$submit = true;
		}

		if ($tag_id != '')
		{
			$mode = 'add';
		}

		switch ($action)
		{
			case 'config':
				$template->assign_var('MESSAGE', $user->lang['SWITCHING']);

				meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", "i=k_vars&amp;mode=config&amp;switch=k_pages"));
			break;

			default:
			break;
		}

		if ($submit && !check_form_key('pages'))
		{
			$submit = false;
			$mode = '';
			trigger_error($user->lang['FORM_INVALID'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
		}

		if ($submit)
		{
			$mod_pages = $request->variable('k_mod_folders', '');

			// trap trailing commas in mod pages //
			if ($mod_pages && $mod_pages[strlen($mod_pages) - 1] == ',')
			{
				trigger_error($user->lang['TRAILING_COMMA'] . adm_back_link(append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage")), E_USER_WARNING);
			}

			//  We check to see  the mod folder exists, if not return... //
			$mod_pages = str_replace(' ', '', $mod_pages);

			// has mod folder been updated/modified //
			if (strcmp($mod_pages, $k_config['k_mod_folders'] != 0))
			{
				$mods_folder_array = explode(',', $mod_pages);

				foreach($mods_folder_array as $folder)
				{
					$folder = trim($folder);
					if (!file_exists($phpbb_root_path . $folder))
					{
						$submit = false;
						$mod_pages = '';
						trigger_error($user->lang['NO_MOD_FOLDER'] . $folder . adm_back_link(append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage")), E_USER_WARNING);
					}
				}

				$template->assign_vars(array(
					//'MESSAGE'  => $user->lang['FOLDER_ADDED'] . ' ' . $folder,
					'MESSAGE'  => $user->lang['FOLDER_ADDED'],
				));
			}
			$sgp_functions_admin->sgp_acp_set_config('k_mod_folders', $mod_pages);
		}

		$template->assign_vars(array(
			'U_BACK'        => append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage"),
			'U_ADD'         => append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=add"),
			'U_MANAGE'      => append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage"),
			'S_OPT'         => 'S_MANAGE',
			'S_PAGE'        => isset($k_config['k_landing_page']) ? $k_config['k_landing_page'] : 'portal',
			'IMG_PATH_ACP'	=> $img_path_acp,
		));

		switch ($mode)
		{
			case 'delete':

				$page_name = get_page_filename($page_id);

				if (confirm_box(true))
				{
					$sql = 'DELETE FROM ' . K_PAGES_TABLE . '
						WHERE page_id = ' . (int) $page_id;

					if (!$result = $db->sql_query($sql))
					{
						trigger_error($user->lang['ERROR_PORTAL_PAGES'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . $user->lang['LINE'] . __LINE__);
					}

					$cache->destroy('sql', K_PAGES_TABLE);

					$template->assign_vars(array(
						'S_OPTION' => 'processing',
						'MESSAGE'  => $user->lang['REMOVING_PAGES'] . $page_name,
					));

					meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage"));
					break;
				}
				else
				{
					confirm_box(false, sprintf("%s (%s)", $user->lang['DELETE_FROM_LIST'], $page_name), build_hidden_fields(array(
						'id'      => $page_id,
						'mode'    => $mode,
						'action'  => 'delete'))
					);
				}

				$template->assign_var('MESSAGE', $user->lang['ACTION_CANCELLED']);

				meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage"));

			break;

			case 'add':
				if ($submit)
				{
					// drop extension
					$tag_id = str_replace('.php', '', $tag_id);

					// skip the spacer //
					if ($tag_id == '..')
					{
						$template->assign_vars(array(
							'S_OPTION' => 'processing', // not lang var
							'MESSAGE'  => sprintf($user->lang['ERROR_PAGE'], $tag_id),
						));
						meta_refresh(2, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage"));
						return;
					}

					if (in_array($tag_id, $current_pages))
					{
						break;
					}

					$sql_array = array(
						'page_name'	=> $tag_id,
					);

					$db->sql_query('INSERT INTO ' . K_PAGES_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_array));

					meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage"));

					$template->assign_vars(array(
						'S_OPTION' => 'processing', // not lang var
						'MESSAGE'  => $user->lang['ADDING_PAGES'],
					));

					$cache->destroy('sql', K_PAGES_TABLE);
					break;
				}
			break;

			case 'land':

				$page_name = get_page_filename($page_id);

				$sgp_functions_admin->sgp_acp_set_config('k_landing_page', $page_name, 1);

				$template->assign_vars(array(
					'S_OPTION' => 'processing',
					'MESSAGE'  => $user->lang['LANDING_PAGE_SET'] . ': '. $page_name,
				));

				$cache->destroy('k_config');
				$cache->destroy('sql', K_VARS_TABLE);

				meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage"));
			break;

			case 'config':
			break;

			case 'manage':
				get_all_available_files();
				get_pages_data();
			break;

			case 'default':
			break;
		}

		$template->assign_var('U_ACTION', $this->u_action);
	}
}

function get_pages_data()
{
	global $db, $template, $phpbb_admin_path, $phpEx, $k_config;
	global $current_pages, $module_id;

	$sql = 'SELECT *
		FROM ' . K_PAGES_TABLE ;

	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$current_pages = $row['page_name'];

		$template->assign_block_vars('phpbbpages', array(
			'S_PAGE_ID'     => $row['page_id'],
			'S_PAGE_NAME'   => $row['page_name'],
			'U_EDIT'        => append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=edit&amp;page_id=" . $row['page_id']),
			'U_DELETE'      => append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=delete&amp;page_id=" . $row['page_id']),
			'U_LAND'        => append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=land&amp;page_id=" . $row['page_id']),
		));
	}
	$db->sql_freeresult($result);

	$template->assign_vars(array(
		'S_OPTION'       => 'manage',
		'K_MOD_FOLDERS'  => $k_config['k_mod_folders'],
	));
}

/**
* get all pages
* don't include code files, only include pages...
*/
function get_all_available_files()
{
	global $phpbb_root_path, $phpEx, $template, $dirslist, $db, $user, $k_config, $phpbb_admin_path, $module_id;

	include($phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_functions.'.$phpEx);

	$page_name = '';
	$dirslist = $store = ' ';

	$illegal_files = array(".htaccess", "app.$phpEx", "common.$phpEx", "report.$phpEx", "feed.$phpEx", "cron.$phpEx", "config.$phpEx", "csv.$phpEx", "style.$phpEx", "sgp_ajax.$phpEx", "sgpical.$phpEx", "rss.$phpEx");

	$sql = 'SELECT page_name
		FROM ' . K_PAGES_TABLE . '
		ORDER BY page_name ASC';
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$page_name .= $row['page_name'] . '.php, ';
	}
	$db->sql_freeresult($result);

	$arr = explode(', ', $page_name);

	// grab php files in phpbb_root_path //
	$dirs = dir($phpbb_root_path);
	while ($file = $dirs->read())
	{
		if ($file != '.' && $file != '..' && stripos($file, ".php") && !stripos($file, ".bak") && !in_array($file, $arr, true))
		{
			if (!in_array($file, $illegal_files))
			{
				$dirslist .= "$file ";
			}
		}
	}
	closedir($dirs->handle);

	// grab files in phpbb_root_path/mod folders //
	$dirs = dir($phpbb_root_path);
	$mods_folder_array = explode(',', $k_config['k_mod_folders']);

	while ($file = $dirs->read())
	{
		if (in_array($file, $mods_folder_array, true) && $file == '.' || $file == '..' && $k_config['k_mod_folders'] != '')
		{
			$mods_folder_array = explode(',', $k_config['k_mod_folders']);

			foreach($mods_folder_array as $folder)
			{
				$folder = trim($folder);

				if (!file_exists($phpbb_root_path . $folder))
				{
					trigger_error($user->lang['NO_MOD_FOLDER'] . $folder . adm_back_link(append_sid("{$phpbb_admin_path}index.$phpEx", "i={$module_id}&amp;mode=manage")), E_USER_WARNING);
				}

				$dirs = dir($phpbb_root_path . $folder);

				while ($file = $dirs->read())
				{
					if ($file != '.' && $file != '..' && stripos($file, ".php") && !stripos($file, ".bak") && !in_array($folder .'/'. $file, $arr, true))
					{
						$illegal_files_array = array($folder . '/' . 'dummy.' . $phpEx);

						$temp = $folder . '/' . $file;

						if (!in_array($temp, $illegal_files_array, true))
						{
							$store .= $temp. " ";
						}
					}
				}
			}
			$dirslist .= $store;
		}

	}

	closedir($dirs->handle);

	$dirslist = explode(" ", $dirslist);
	//sort($dirslist);

	$phpbb_files = '';
	$files_found = 0;

	// As we use onchange event we need an empty line first //
	$phpbb_files .= '<option value="' . ' ' . '">' . ' ' . '</option>';

	foreach ($dirslist as $file)
	{
		if ($file != '')
		{
			$files_found++;
			$phpbb_files .= '<option value="' . $file  . '"' . (($files_found == 0) ? ' selected="selected"' : '') . '>' . $file . '</option>';
		}
	}

	$template->assign_vars(array(
		'S_PHPBB_FILES' => $phpbb_files,
		'S_FILES_FOUND' => $files_found,
	));
}

/**
* simply return the page/file name for clarity
**/
function get_page_filename($page_id)
{
	global $db, $template;

	$sql = 'SELECT *
		FROM ' . K_PAGES_TABLE . '
		WHERE page_id = ' . $db->sql_escape($page_id);
	$result = $db->sql_query($sql);

	if ($result = $db->sql_query($sql))
	{
		$row = $db->sql_fetchrow($result);
	}

	$template->assign_vars(array(
		'PAGE_ID'   => $row['page_id'],
		'PAGE_NAME' => $row['page_name'],
	));

	$db->sql_freeresult($result);

	return($row['page_name']);
}
