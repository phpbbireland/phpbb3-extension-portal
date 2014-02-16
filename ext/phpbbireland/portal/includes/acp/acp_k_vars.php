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
class acp_k_vars
{
	//var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $cache;
		global $config, $k_config, $SID, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		include($phpbb_root_path . 'includes/sgp_functions.'.$phpEx);
		include($phpbb_root_path . 'includes/sgp_functions_admin.'.$phpEx);

		$user->add_lang('acp/k_vars');
		$this->tpl_name = 'acp_k_vars';
		$this->page_title = 'ACP_K_VARS_CONFIG';

		$form_key = 'acp_k_vars';
		add_form_key($form_key);

		$choice = request_var('switch', '');
		$block = request_var('block', 0);
		$mode	= request_var('mode', '');
		$switch = request_var('switch', '');

		if ($mode = 'config' && $choice == '')
		{
			$choice = 'config';
		}

		if (isset($block))
		{
			$sql = "SELECT id, title, var_file_name
				FROM ". K_BLOCKS_TABLE . "
				WHERE id = " . (int)$block;
			$result = $db->sql_query($sql);

			$row = $db->sql_fetchrow($result);

			$title = strtoupper($row['title']);
			$title = str_replace(' ','_', $row['title']);

			$block_id = $row['id'];
			$var_file_name = $row['var_file_name'];

			$db->sql_freeresult($result);
			get_all_groups();
			get_teams_sort();
		}

		$block = !empty($block) ? $block : 0;
		$action = request_var('action', '');
		$submit = (isset($_POST['submit'])) ? true : false;

		// swicth to other var setups 11 March 2010
		if ($switch)
		{
			get_reserved_words();
			$var_file_name = $switch;
		}

		$template->assign_vars(array( 'S_SWITCH' => $var_file_name ));


		if ($submit && !check_form_key($form_key))
		{
			trigger_error($user->lang['FORM_INVALID']);
		}

		$sql = 'SELECT config_name, config_value
			FROM ' . K_BLOCKS_CONFIG_VAR_TABLE;

		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$k_config[$row['config_name']] = $row['config_value'];

			$template->assign_var('S_' . (strtoupper($row['config_name'])), $row['config_value']);
		}
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'S_OPT' => 'config',
			'MESSAGE' => '',
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
				$k_announce_type                 = request_var('k_announce_type', '');
				$k_news_type                     = request_var('k_news_type', '');
				$k_links_forum_id                = request_var('k_links_forum_id', '');

				$k_recent_topics_search_exclude  = request_var('k_recent_topics_search_exclude', '');
				$k_post_types                    = request_var('k_post_types', '');
				$k_announce_allow                = request_var('k_announce_allow', 1);
				$k_announce_item_max_length      = request_var('k_announce_item_max_length', 0);
				$k_announce_to_display           = request_var('k_announce_to_display', 5);
				$k_allow_rotating_logos          = request_var('k_allow_rotating_logos', 1);
				$k_bots_to_display               = request_var('k_bots_to_display', 10);
				$k_block_cache_time_default      = request_var('k_block_cache_time_default', 600);
				$k_block_cache_time_fast         = request_var('k_block_cache_time_fast', 10);
				$k_blocks_display_globally       = request_var('k_blocks_display_globally', 1);
				$k_block_cache_time_short        = request_var('k_block_cache_time_short', 10);
				$k_bot_display_allow             = request_var('k_bot_display_allow', 1);
				$k_footer_images_allow           = request_var('k_footer_images_allow', 1);
				$k_news_allow                    = request_var('k_news_allow', 1);
				$k_news_items_to_display         = request_var('k_news_items_to_display', 5);
				$k_news_item_max_length          = request_var('k_news_item_max_length', 0);
				$k_recent_topics_to_display      = request_var('k_recent_topics_to_display', 10);
				$k_recent_topics_per_forum       = request_var('k_recent_topics_per_forum', 5);
				$k_recent_search_days            = request_var('k_recent_search_days', 7);
				$k_last_online_max               = request_var('k_last_online_max', 5);
				$k_links_to_display              = request_var('k_links_to_display', 5);
				$k_quick_reply                   = request_var('k_quick_reply', 1);
				$k_smilies_show                  = request_var('k_smilies_show', 1);

				$k_teams                         = request_var('k_teams', '');
				$k_teams_display_this_many       = request_var('k_teams_display_this_many', 1);
				$k_teams_sort                    = request_var('k_teams_sort', '');

				$k_top_topics_days               = request_var('k_top_topics_days', 7);
				$k_top_topics_max                = request_var('k_top_topics_max', 5);
				$k_top_posters_to_display        = request_var('k_top_posters_to_display', 5);
				$k_yourtube_link                 = request_var('k_yourtube_link', '');
				$k_yourtube_auto                 = request_var('k_yourtube_auto', 1);
				$k_yourtube_link_limit           = request_var('k_yourtube_link_limit', 5);
				//$k_yourtube_list                 = request_var('k_yourtube_list', '');
				$k_referrals_to_display          = request_var('k_referrals_to_display', '');
				$k_allow_acronyms                = request_var('k_allow_acronyms', 0);
				$k_max_block_avatar_width        = request_var('k_max_block_avatar_width', 0);
				$k_max_block_avatar_height       = request_var('k_max_block_avatar_height', 0);
				$k_max_block_avatar_width        = request_var('k_max_block_avatar_width', 0);
				$k_max_block_avatar_height       = request_var('k_max_block_avatar_height', 0);
				$k_teampage_memberships          = request_var('k_teampage_memberships', 0);
				$k_tooltips_active               = request_var('k_tooltips_active', 1);
				$k_tooltips_which                = request_var('k_tooltips_which', 0);
				$k_allow_rand_avatar             = request_var('k_allow_rand_avatar', 0);

				$k_donations_years               = request_var('k_donations_years', '2000');
				$k_donations_max                 = request_var('k_donations_max', '100');

				$k_top_downloads_to_display      = request_var('k_top_downloads_to_display', 5);
				$k_top_downloads_search_exclude  = request_var('k_top_downloads_search_exclude', '');
				$k_top_downloads_search_days     = request_var('k_top_downloads_search_days', 0);
				$k_top_downloads_per_forum       = request_var('k_top_downloads_per_forum', 0);
				$k_top_downloads_types           = request_var('k_top_downloads_types', '');

				$k_ma_max_avatars                = request_var('k_ma_max_avatars', '0');
				$k_ma_columns                    = request_var('k_ma_columns', '5');
				$k_ma_rows                       = request_var('k_ma_rows', '1');
				$k_ma_avatar_max_width           = request_var('k_ma_avatar_max_width', '90');
				$k_ma_display_logged_in_only     = request_var('k_ma_display_logged_in_only', '0');
				$k_ma_user_has_posted            = request_var('k_ma_user_has_posted', '0');
				$k_ma_user_active                = request_var('k_ma_user_active', '0');

				if ($k_ma_rows <= 0)
				{
					$k_ma_rows = 1;
				}

				if ($k_max_block_avatar_width == 0 || $k_max_block_avatar_height == 0)
				{
					$k_max_block_avatar_width = $config['avatar_max_width'];
					$k_max_block_avatar_height = $config['avatar_max_height'];
				}
				else if ($k_max_block_avatar_width > $config['avatar_max_width'] || $k_max_block_avatar_height > $config['avatar_max_height'])
				{
					$k_max_block_avatar_width = $config['avatar_max_width'];
					$k_max_block_avatar_height = $config['avatar_max_height'];
				}

				switch ($k_announce_type)
				{
					case 2:  $k_announce_type = POST_ANNOUNCE;
					break;

					case 3:  $k_announce_type = POST_GLOBAL;
					break;

					default: $k_announce_type = 0;
					break;
				}
				switch ($k_news_type)
				{
					case 4:  $k_news_type = POST_NEWS;
					break;

					case 5:  $k_news_type = POST_NEWS_GLOBAL;
					break;

					default: $k_news_type = 0;
					break;
				}

				// all data is escaped in sgp_acp_set_config //
				sgp_acp_set_config('k_allow_rotating_logos', $k_allow_rotating_logos);
				sgp_acp_set_config('k_announce_allow', $k_announce_allow);
				sgp_acp_set_config('k_bot_display_allow', $k_bot_display_allow);
				sgp_acp_set_config('k_footer_images_allow', $k_footer_images_allow);
				sgp_acp_set_config('k_announce_type', $k_announce_type);
				sgp_acp_set_config('k_blocks_display_globally', $k_blocks_display_globally);
				sgp_acp_set_config('k_smilies_show', $k_smilies_show);
				sgp_acp_set_config('k_announce_to_display', $k_announce_to_display);
				sgp_acp_set_config('k_bots_to_display', $k_bots_to_display);
				sgp_acp_set_config('k_announce_item_max_length', $k_announce_item_max_length);
				sgp_acp_set_config('k_links_forum_id', $k_links_forum_id);
				sgp_acp_set_config('k_links_to_display', $k_links_to_display);
				sgp_acp_set_config('k_news_allow', $k_news_allow);
				sgp_acp_set_config('k_news_type', $k_news_type);
				sgp_acp_set_config('k_news_items_to_display', $k_news_items_to_display);
				sgp_acp_set_config('k_news_item_max_length', $k_news_item_max_length);
				sgp_acp_set_config('k_post_types', $k_post_types);
				sgp_acp_set_config('k_recent_topics_to_display', $k_recent_topics_to_display);
				sgp_acp_set_config('k_recent_topics_per_forum', $k_recent_topics_per_forum);
				sgp_acp_set_config('k_recent_topics_search_exclude', $k_recent_topics_search_exclude);
				sgp_acp_set_config('k_recent_search_days', $k_recent_search_days);
				sgp_acp_set_config('k_teams', $k_teams);
				sgp_acp_set_config('k_teams_display_this_many', $k_teams_display_this_many);
				sgp_acp_set_config('k_teams_sort', $k_teams_sort);

				sgp_acp_set_config('k_top_posters_to_display', $k_top_posters_to_display);
				sgp_acp_set_config('k_top_topics_max', $k_top_topics_max);
				sgp_acp_set_config('k_top_topics_days', $k_top_topics_days);
				sgp_acp_set_config('k_referrals_to_display', $k_referrals_to_display);
				sgp_acp_set_config('k_last_online_max', $k_last_online_max);
				sgp_acp_set_config('k_quick_reply', $k_quick_reply);
				sgp_acp_set_config('k_block_cache_time_default', $k_block_cache_time_default);
				sgp_acp_set_config('k_block_cache_time_fast', $k_block_cache_time_fast);
				sgp_acp_set_config('k_yourtube_link', $k_yourtube_link);
				sgp_acp_set_config('k_yourtube_auto', $k_yourtube_auto);
				sgp_acp_set_config('k_yourtube_link_limit', $k_yourtube_link_limit);
				//sgp_acp_set_config('k_yourtube_list', $k_yourtube_list);
				sgp_acp_set_config('k_block_cache_time_short', $k_block_cache_time_short);
				sgp_acp_set_config('k_allow_acronyms', $k_allow_acronyms);
				sgp_acp_set_config('k_max_block_avatar_width', $k_max_block_avatar_width);
				sgp_acp_set_config('k_max_block_avatar_height', $k_max_block_avatar_height);
				sgp_acp_set_config('k_teampage_memberships', $k_teampage_memberships);
				sgp_acp_set_config('k_tooltips_active', $k_tooltips_active);
				sgp_acp_set_config('k_tooltips_which', $k_tooltips_which);
				sgp_acp_set_config('k_allow_rand_avatar', $k_allow_rand_avatar);
				sgp_acp_set_config('k_donations_years', $k_donations_years);
				sgp_acp_set_config('k_donations_max', $k_donations_max);
				sgp_acp_set_config('k_top_downloads_to_display', $k_top_downloads_to_display);
				sgp_acp_set_config('k_top_downloads_search_exclude', $k_top_downloads_search_exclude);
				sgp_acp_set_config('k_top_downloads_search_days', $k_top_downloads_search_days);
				sgp_acp_set_config('k_top_downloads_per_forum', $k_top_downloads_per_forum);
				sgp_acp_set_config('k_top_downloads_types', $k_top_downloads_types);


				sgp_acp_set_config('k_ma_max_avatars', $k_ma_max_avatars);
				sgp_acp_set_config('k_ma_columns', $k_ma_columns);
				sgp_acp_set_config('k_ma_rows', $k_ma_rows);
				sgp_acp_set_config('k_ma_avatar_max_width', $k_ma_avatar_max_width);
				sgp_acp_set_config('k_ma_display_logged_in_only', $k_ma_display_logged_in_only);
				sgp_acp_set_config('k_ma_user_has_posted', $k_ma_user_has_posted);
				sgp_acp_set_config('k_ma_user_active', $k_ma_user_active);

				$mode = 'reset';

				$template->assign_vars(array(
					'S_OPT' => 'saving',
					'MESSAGE' => $user->lang['SAVED'],
				));

				$cache->destroy('_k_config');
				$cache->destroy('sql', K_BLOCKS_CONFIG_VAR_TABLE);

				if ($block)
				{
					meta_refresh (0, append_sid("{$phpbb_admin_path}index.$phpEx", "i=k_vars&amp;mode=config&amp;block=" . $block));
				}
				else
				{
					meta_refresh (0, append_sid("{$phpbb_admin_path}index.$phpEx", "i=k_vars&amp;mode=config&amp;switch=" . $switch));
				}
				return;
			}
			case 'default': break;
		}

		switch ($action)
		{
			case 'submit':  $mode = 'reset';
			break;

			case 'default':
			break;
		}

	}
}

?>