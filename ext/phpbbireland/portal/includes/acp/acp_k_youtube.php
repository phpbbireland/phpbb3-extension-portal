<?php
/**
*
* @package acp Stargate Portal
* @version $Id: acp_k_youtube.php 305 2009-01-01 16:03:23Z Michealo $
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

class acp_k_youtube
{

	var $u_action;
	//var $action;

	function main($video_id, $mode)
	{
		global $db, $user, $auth, $template, $cache;
		global $config, $SID, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		include($phpbb_root_path . 'includes/sgp_functions.' . $phpEx);

		$user->add_lang('acp/k_youtube');
		$this->tpl_name = 'acp_k_youtube';
		$this->page_title = 'ACP_YOUTUBE';

		$form_key = 'acp_k_youtube';
		add_form_key($form_key);

		$mode = request_var('mode', '');
		$video_id = request_var('video_id', '');
		$action = request_var('config', '');
		$submit = (isset($_POST['submit'])) ? true : false;

		$action = (isset($_POST['add_video'])) ? 'add' : ((isset($_POST['save'])) ? 'save' : ((isset($_POST['config'])) ? 'config' : $action));

		switch ($action)
		{
			case 'config':
				$template->assign_var('MESSAGE', $user->lang['SWITCHING']);

				meta_refresh (1, "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_vars&amp;mode=config&amp;switch=k_youtube_vars.html");
			break;

			case 'add':
				$mode = '';
				meta_refresh (0, "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_youtube&amp;mode=add");
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
			'U_BACK'    => "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_youtube",
			'U_ADD'     => "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_youtube&amp;mode=add",
			'U_EDIT'    => "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_youtube&amp;mode=edit" . '&amp;video_id=' . $video_id,
			'U_DELETE'  => "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_youtube&amp;mode=delete" . '&amp;video_id=' . $video_id,
			'U_BROWSE'  => "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_youtube&amp;mode=browse",
			'S_OPT'     => 'browse',
		));

		switch ($mode)
		{
			case 'edit':

				if ($submit)
				{
					$video_id        = request_var('video_id', 0);
					$video_link      = request_var('video_link', '');
					$video_rating    = request_var('video_rating', '');
					$video_category  = utf8_normalize_nfc(request_var('video_category', '', true));
					$video_who       = utf8_normalize_nfc(request_var('video_who', '', true));
					$video_title     = utf8_normalize_nfc(request_var('video_title', '', true));
					$video_comment   = utf8_normalize_nfc(request_var('video_comment', '', true));
					$video_poster_id = request_var('video_poster_id', '');

					$sql_ary = array(
						//'video_id'	=> $video_id,
						'video_link'        => $video_link,
						'video_category'    => $video_category,
						'video_who'         => $video_who,
						'video_rating'      => $video_rating,
						'video_title'       => $video_title,
						'video_comment'     => $video_comment,
						//'video_poster_id' => $video_poster_id,
					);

					$sql = 'UPDATE ' . K_YOUTUBE_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . " WHERE video_id = " . (int)$video_id;

					if (!$result = $db->sql_query($sql))
					{
						trigger_error($user->lang['ERROR_PORTAL_VIDEO'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . ', line ' . __LINE__);
					}

					$template->assign_vars(array(
						'MESSAGE' => $user->lang['SAVED'] . '</font><br />',
						'S_OPT'   => 'saving',
					));

					meta_refresh(0, "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_youtube&amp;mode=browse");
				}

				get_video_item($video_id);

				$template->assign_var('S_OPTION', 'edit');
			break;

			case 'delete':

				//get the title of the video to make delete clearer to the user...
				$video_name = get_video_item($video_id);

				if (confirm_box(true))
				{
					$sql = 'DELETE FROM ' . K_YOUTUBE_TABLE . '
						WHERE video_id = ' . (int)$video_id;

					if (!$result = $db->sql_query($sql))
					{
						trigger_error($user->lang['ERROR_PORTAL_VIDEO'] . basename(dirname(__FILE__)) . '/' . basename(__FILE__) . ', line ' . __LINE__);
					}

					$template->assign_vars(array(
						'MESSAGE' =>  $user->lang['DELETING'] . $video_id . '<br />',
						'S_OPT'   => 'delete', //not a language var
					));

					$cache->destroy('sql', K_YOUTUBE_TABLE);

					meta_refresh(1, "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_youtube&amp;mode=browse");
					break;
				}
				else
				{
					confirm_box(false, sprintf($user->lang['CONFIRM_OPERATION_YOUTUBE'], $video_name), build_hidden_fields(array(
						'id'     => $video_id,
						'mode'   => $mode,
						'action' => 'delete'))
					);
				}

				$template->assign_var('MESSAGE', $user->lang['ACTION_CANCELLED']);
				meta_refresh(1, "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_youtube&amp;mode=browse");
			break;

			case 'add':

				if ($submit)
				{
					//$video_id      = request_var('video_id', '');
					$video_link      = request_var('video_link', '');
					$video_rating    = request_var('video_rating', '');
					$video_category  = utf8_normalize_nfc(request_var('video_category', '', true));
					$video_who       = utf8_normalize_nfc(request_var('video_who', '', true));
					$video_title     = utf8_normalize_nfc(request_var('video_title', '', true));
					$video_comment   = utf8_normalize_nfc(request_var('video_comment', '', true));
					$video_poster_id = request_var('video_poster_id', '');

					if (strstr($video_link, 'None'))
					{
						$video_link = '';
					}

	               $sql_array = array(
					   'video_category'     => $video_category,
						'video_who'         => $video_who,
						'video_link'        => $video_link,
						'video_title'       => $video_title,
						'video_rating'      => $video_rating,
						'video_comment'     => $video_comment,
						'video_poster_id'   => $user->data['user_id'],
                    );
		           $db->sql_query('INSERT INTO ' . K_YOUTUBE_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_array));

					meta_refresh(0, "{$phpbb_root_path}adm/index.$phpEx$SID&amp;i=k_youtube&amp;mode=browse");

					$template->assign_var('L_MENU_REPORT', $user->lang['VIDEO_CREATED']);

					$cache->destroy('sql', K_YOUTUBE_TABLE);
					break;
				}
				else
				{
					get_video_item(0);
					$template->assign_vars(array(
						'S_OPTION' => 'add',
						'MESSAGE' =>  $user->lang['UTUBE_SAMPLE_DATA'] . '<br />',
					));
					$mode = 'add';
				}
			break;

			case 'config':
			break;

			case 'browse':
				get_youtube_data();
			break;

			case 'default':
			break;
		}

		$template->assign_var('U_ACTION', $this->u_action);
	}
}

function get_youtube_data()
{
	global $db, $template;//, $s_hidden_fields;

	$sql = 'SELECT *
		FROM ' . K_YOUTUBE_TABLE;

	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('youtubes', array(
			'VIDEO_ID'        => $row['video_id'],
			'VIDEO_LINK'      => $row['video_link'],
			'VIDEO_CATEGORY'  => $row['video_category'],
			'VIDEO_WHO'       => $row['video_who'],
			'VIDEO_RATING'    => $row['video_rating'],
			'VIDEO_TITLE'     => $row['video_title'],
			'VIDEO_COMMENT'   => $row['video_comment'],
			'VIDEO_POSTER_ID' => $row['video_poster_id'],
			'VIDEO_POSTER'    => get_user_data('name', $row['video_poster_id']),

		));
	}
	$db->sql_freeresult($result);
}

// Assigns data, also returns the video name of delete option where video_id == 0//
function get_video_item($video_id)
{
	global $db, $template;//, $s_hidden_fields;
	$copy = false;

	if ($video_id == 0) // used for copying a tag //
	{
		$copy = true;
		$sql = 'SELECT *
			FROM ' . K_YOUTUBE_TABLE . '
			WHERE video_id = 1';
	}
	else
	{
		$sql = 'SELECT *
			FROM ' . K_YOUTUBE_TABLE . '
			WHERE video_id = ' . (int)$video_id;
	}

	$result = $db->sql_query($sql);

	if ($result = $db->sql_query($sql))
	{
		$row = $db->sql_fetchrow($result);
	}

	$template->assign_vars(array(
		'VIDEO_ID'         => ($video_id == 0) ? '' : $row['video_id'],
		'VIDEO_LINK'       => $row['video_link'],
		'VIDEO_CATEGORY'   => $row['video_category'],
		'VIDEO_WHO'        => $row['video_who'],
		'VIDEO_RATING'     => $row['video_rating'],
		'VIDEO_TITLE'      => $row['video_title'],
		'VIDEO_COMMENT'    => $row['video_comment'],
		'VIDEO_POSTER_ID'  => $row['video_poster_id'],
		'VIDEO_POSTER'     => get_user_data('name', $row['video_poster_id']),
	));

	$db->sql_freeresult($result);

	if ($video_id != 0)
	{
		return($row['video_title']);
	}
	else
	{
		return('');
	}
}
?>