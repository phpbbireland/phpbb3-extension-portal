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
class acp_k_newsfeeds
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $cache;
		global $k_config, $SID, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		include($phpbb_root_path . 'includes/sgp_functions.'.$phpEx);

		$message ='';

		$user->add_lang('acp/k_newsfeeds');
		$this->tpl_name = 'acp_k_newsfeeds';
		$this->page_title = 'ACP_K_NEWSFEEDS';

		$form_key = 'acp_k_newsfeeds';
		add_form_key($form_key);

		// Set up general vars
		$action = request_var('action', '');

		$action = (isset($_POST['edit'])) ? 'edit' : $action;
		$action = (isset($_POST['add'])) ? 'add' : $action;
		$action = (isset($_POST['save'])) ? 'save' : $action;

		$feed_id = request_var('id', 0);
		$rss_feeds_enabled = request_var('rss_feeds_enabled', 0);

		$sql = 'SELECT config_name, config_value
			FROM ' . K_BLOCKS_CONFIG_VAR_TABLE . '';

		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$k_config[$row['config_name']] = $row['config_value'];
		}

		switch ($action)
		{
			case 'editfeeds':
	 			if ($action == 'editfeeds')
				{
					$config_feeds_cache_time = request_var('rss_feeds_cache_time', 0);
					$config_feeds_items_limit = request_var('rss_feeds_items_limit', 0);
					$config_feeds_random_limit = request_var('rss_feeds_random_limit', 0);
					$config_feeds_rss_enabled = request_var('rss_feeds_enabled', 0);
					$config_feeds_type = request_var('rss_feeds_type', '');
				}
				$type = $k_config['rss_feeds_type'];

				$template->assign_vars(array(
					'S_EDIT_FEED'           => true,
					'U_BACK'                => $this->u_action,
					'U_ACTION_EDIT_COLUMN'  => $this->u_action . '&amp;action=editfeeds',
					'S_FOPEN'               => ($type=='fopen') ? true : false,

					'FEED_CACHE_TIME'       => $k_config['rss_feeds_cache_time'],
					'FEED_ITEMS_LIMIT'      => $k_config['rss_feeds_items_limit'],
					'FEED_TYPE'             => $k_config['rss_feeds_type'],
					'FEED_RANDOM_LIMIT'     => $k_config['rss_feeds_random_limit'],
					'S_RSS_FEEDS_ENABLED'   => $k_config['rss_feeds_enabled'],
				));
			break;

			case 'savefeeds':

				$config_feeds_cache_time = request_var('rss_feeds_cache_time', 0);
				$config_feeds_items_limit = request_var('rss_feeds_items_limit', 0);
				$config_feeds_random_limit = request_var('rss_feeds_random_limit', 0);
				$config_feeds_rss_enabled = request_var('rss_feeds_enabled', 0);
				$config_feeds_type = request_var('rss_feeds_type','');

 				if ($action == 'savefeeds')
				{
					$db->sql_query('UPDATE ' . K_BLOCKS_CONFIG_VAR_TABLE . ' SET config_value = ' . (int)$config_feeds_cache_time . ' WHERE config_name = "rss_feeds_cache_time"');
					$db->sql_query('UPDATE ' . K_BLOCKS_CONFIG_VAR_TABLE . ' SET config_value = ' . (int)$config_feeds_items_limit . ' WHERE config_name = "rss_feeds_items_limit"');
					$db->sql_query('UPDATE ' . K_BLOCKS_CONFIG_VAR_TABLE . ' SET config_value = ' . (int)$config_feeds_random_limit . ' WHERE config_name = "rss_feeds_random_limit"');
					$db->sql_query('UPDATE ' . K_BLOCKS_CONFIG_VAR_TABLE . ' SET config_value = ' . (int)$config_feeds_rss_enabled . ' WHERE config_name = "rss_feeds_enabled"');
 					$db->sql_query('UPDATE ' . K_BLOCKS_CONFIG_VAR_TABLE . ' SET config_value = "' . $db->sql_escape($config_feeds_type) . '" WHERE config_name = "rss_feeds_type"');

					//clear cache - not working
					@unlink( $phpbb_root_path . 'cache/rsscache_*.dat' );

					$message = $user->lang['CONFIG_UPDATED'];
				}
				$db->sql_query($sql);

				$cache->destroy('k_config');

				trigger_error($message . adm_back_link($this->u_action));

				$template->assign_vars(array(
					'S_SAVE_FEEDS'          => true,
					'U_BACK'                => $this->u_action,
					'U_ACTION_SAVE_COLUMN'  => $this->u_action . '&amp;action=savefeeds',
					'FEED_CACHE_TIME'       => $k_config['rss_feeds_cache_time'],
					'FEED_ITEMS_LIMIT'      => $k_config['rss_feeds_items_limit'],
					'FEED_RANDOM_LIMIT'     => $k_config['rss_feeds_random_limit'],
					'S_RSS_FEEDS_ENABLED'   => $k_config['rss_feeds_ensbled'],
					'FEED_TYPE'             => $k_config['rss_feeds_type'],
				));

			break;

			case 'save':

   				$feed_title         = request_var('feed_title', '', true);
   				$feed_show          = request_var('feed_show', 0);
   				$feed_url           = request_var('feed_url', '', true);
   				$feed_position      = request_var('feed_position', 0);
				$feed_description   = request_var('feed_description', 0);

				$sql_ary = array(
					'feed_title'        => $feed_title,
					'feed_show'         => $feed_show,
					'feed_url'          => $feed_url,
					'feed_position'     => $feed_position,
					'feed_description'  => $feed_description,
				);

				if ($feed_id)
				{
				    $sql = 'UPDATE ' . K_NEWSFEEDS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . " WHERE feed_id = " . (int)$feed_id;
				    $message = $user->lang['FEED_UPDATED'];
				}
				else
				{
				     $sql = 'INSERT INTO ' . K_NEWSFEEDS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
				     $message = $user->lang['FEED_ADDED'];
				}
				$db->sql_query($sql);

				$cache->destroy('feed_title');
				$cache->destroy('k_config');

				trigger_error($message . adm_back_link($this->u_action));

			break;

			case 'delete':

				// Ok, we want to delete a feed_title
				if ($feed_id)
				{
					$sql = 'DELETE FROM ' . K_NEWSFEEDS_TABLE . "
						WHERE feed_id = " . (int)$feed_id;
					$db->sql_query($sql);

					$cache->destroy('feed_title');
					$cache->destroy('k_config');

					trigger_error($user->lang['FEED_REMOVED'] . adm_back_link($this->u_action));
				}
				else
				{
					trigger_error($user->lang['MUST_SELECT_FEED'] . adm_back_link($this->u_action), E_USER_WARNING);
				}
			break;

			case 'edit':
			case 'add':

				$sql = 'SELECT *
					FROM ' . K_NEWSFEEDS_TABLE . '
					ORDER BY feed_title ASC, feed_url ASC';
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{

 				    if ($action == 'edit' && $feed_id == $row['feed_id'])
					{
   						$feed_title        = $row;
   						$feed_show         = $row;
   						$feed_url          = $row;
   						$feed_position     = $row;
						$feed_description  = $row;
					}
				}
				$db->sql_freeresult($result);

				if ($action == 'add')
				{
   					$feed_title         = $row;
   					$feed_show          = $row;
   					$feed_url           = $row;
   					$feed_position      = $row;
					$feed_description   = $row;
				}

				$template->assign_vars(array(
					'S_EDIT'         => true,
					'U_BACK'         => $this->u_action,
					'U_ACTION'       => $this->u_action . '&amp;id=' . $feed_id,
					'FEED_TITLE'     => (isset($feed_title['feed_title'])) ? $feed_title['feed_title'] : '',
					'FEED_URL'       => (isset($feed_url['feed_url'])) ? $feed_url['feed_url'] : '',
					'FEED_POSITION'  => $feed_position['feed_position'],
					'FEED_SHOW'      => $feed_show['feed_show'],
					'FEED_DESCRIPTION_SHOW'	=> $feed_description['feed_description'],
				));
				return;

			break;

			default:
			break;
		}

		$template->assign_vars(array(
			'U_ACTION'		=> $this->u_action)
		);

		$sql = 'SELECT *
			FROM ' . K_NEWSFEEDS_TABLE . '
			ORDER BY feed_title ASC, feed_url ASC';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$active = ($row['feed_show'] == '1') ? $user->lang['YES'] : (($row['feed_show'] == '0') ? $user->lang['NO'] : $user->lang['GUEST']);
			$description = ($row['feed_description'] == '1') ? $user->lang['YES'] : (($row['feed_description'] == '0') ? $user->lang['NO'] : $user->lang['GUEST']);

			switch ($row['feed_position'])
			{
				case '0':
					$position = $user->lang['LEFT'];
				break;
				case '1':
					$position = $user->lang['RIGHT'];
				break;
				case '2':
					$position = $user->lang['CENTRED'];
				break;
				default:
					$position = $user->lang['NOT_SET'];
				break;
			}

			$template->assign_block_vars('feeds', array(
				'FEED_ID'               => $row['feed_id'],
				'FEED_TITLE'            => $row['feed_title'],
				'FEED_URL'              => $row['feed_url'],
				'FEED_POSITION'         => $row['feed_position'],
				'FEED_SHOW'             => $active,
				'FEED_SHOW_POSITION'    => $position,
				'FEED_SHOW_DESCRIPTION' => $description,
				'U_EDIT'                => $this->u_action . '&amp;action=edit&amp;id=' . $row['feed_id'],
				'U_DELETE'              => $this->u_action . '&amp;action=delete&amp;id=' . $row['feed_id']
			));
		}

		$template->assign_block_vars('rss_column', array(
			'FEED_CACHE_TIME'      => $k_config['rss_feeds_cache_time'],
			'FEED_ITEMS_LIMIT'     => $k_config['rss_feeds_items_limit'],
			'FEED_RANDOM_LIMIT'    => $k_config['rss_feeds_random_limit'],
			'S_RSS_FEEDS_ENABLED'  => $k_config['rss_feeds_enabled'],
			'FEED_TYPE'            => $k_config['rss_feeds_type'],
			'U_EDIT_FEEDS'         => $this->u_action . '&amp;action=editfeeds'
		));

		$db->sql_freeresult($result);
	}
}

?>