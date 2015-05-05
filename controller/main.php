<?php
/**
*
* @package Kiss Portal extension for the phpBB Forum Software package 1.0.1
* @copyright (c) 2013 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbbireland\portal\controller;

class main //implements main_interface
{
	/**
	* phpBB Config object
	* @var \phpbb\config\config
	*/
	protected $config;

	/** phpBB helper
	* @var \phpbb\helper\helper
	*/
	protected $helper;

	/**
	* phpBB path helper
	* @var \phpbb\path_helper
	*/
	protected $path_helper;

	/**
	* phpbbireland Portal controller helper
	* @var \phpbbireland\portal\controller\helper
	*/
	protected $controller_helper;

	/**
	* Template object
	* @var \phpbb\template
	*/
	protected $template;

	/**
	* User object
	* @var \phpbb\user
	*/
	protected $user;

	/**
	* phpBB root path
	* @var string
	*/
	protected $phpbb_root_path;

	/**
	* PHP file extension
	* @var string
	*/
	protected $php_ext;

	/**
	* Portal root path
	* @var string
	*/
	protected $portal_root_path;

	/**
	* Portal includes path
	* @var string
	*/
	protected $includes_path;

	/**
	* Constructor
	*
	* @param \phpbb\config\config                $this->config             Config object
	* @param \phpbb\controller\helper            $controller_helper  Controller helper object
	* @param \phpbb\path_helper                  $path_helper        phpBB path helper
	* @param \phpbbireland\portal                $portal             Portal object
	* @param \phpbb\template\template            $template           Template object
	* @param \phpbb\user                         $user               User object
	* @param string                              $phpbb_root_path    phpBB root path
	* @param string                              $php_ext            phpEx
	*
	* @return \phpbbireland\portal\controller\main
	* @access public
	*/
	public function __construct($config, \phpbb\controller\helper $helper, $controller_helper, $template, $user, $path_helper, $phpbb_root_path, $php_ext)
	{


		global $portal_root_path;

		$this->config = $config;
		$this->controller_helper = $controller_helper;
		$this->template = $template;
		$this->user = $user;
		$this->helper = $helper;
		$this->path_helper = $path_helper;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;

		$this->includes_path = $phpbb_root_path . 'ext/phpbbireland/portal/includes/';
		$this->portal_root_path = $phpbb_root_path . 'ext/phpbbireland/portal/';

//		$portal_root_path = $this->root_path;
	}


	public function display()
	{
		if (empty($this->config['portal_enabled']))
		{
			redirect(append_sid("{$this->root_path}index.$this->php_ext"));
		}
		return $this->base();
	}


	/**
	* Base controller to be accessed with the URL /portal/{page}
	*
	* @param	bool	$display_pagination		Force to hide the pagination
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/

	public function base($page = 'portal')
	{
		global $phpbb_container;
		global $k_config, $k_menus, $k_blocks, $k_pages, $k_groups, $k_resources, $phpbb_root_path;//, $block_modules;

		$portal_link = $this->get_portal_link();

		$this->controller_helper->run_initial_tasks();
		$this->controller_helper->generate_all_block();

		$phpbb_path_helper = $phpbb_container->get('path_helper');
		$corrected_path = $phpbb_path_helper->get_web_root_path();

		$web_path = (defined('PHPBB_USE_BOARD_URL_PATH') && PHPBB_USE_BOARD_URL_PATH) ? $board_url : $corrected_path;

		$mod_style_path	= $this->phpbb_root_path . 'ext/phpbbireland/portal/styles/' . $this->user->style['style_path'] . '/';

		if (!class_exists('bbcode'))
		{
			include($this->phpbb_root_path . 'includes/bbcode.' . $this->php_ext);
		}
		if (!function_exists('get_user_rank'))
		{
			include($this->phpbb_root_path . 'includes/functions_display.' . $this->php_ext);
		}
		if (!function_exists('phpbb_get_user_avatar'))
		{
			include($this->phpbb_root_path . 'includes/functions_display.'. $this->php_ext);
		}

		$this->get_portal_cache();

		$this->template->assign_vars(array(
			'EXT_TEMPLATE_PATH'    => $mod_style_path,
		));

		// Generate logged in/logged out status
		if ($this->user->data['user_id'] != ANONYMOUS)
		{
			$s_login_logout = append_sid("{$phpbb_root_path}ucp.$this->php_ext", 'mode=logout', true, $this->user->session_id);
			$l_login_logout = sprintf($this->user->lang['LOGOUT_USER'], $this->user->data['username']);
		}
		else
		{
			$s_login_logout = append_sid("{$phpbb_root_path}ucp.$this->php_ext", 'mode=login');
			$l_login_logout = $this->user->lang['LOGIN'];
		}

		$this->template->assign_vars(array(
			'S_LOGIN_ACTION'  => $s_login_logout,
			'L_LOGIN_LOGOUT'  => $l_login_logout,
		));

		$this->assign_images($this->config['portal_user_info'], $this->config['portal_pick_buttons']);
		$this->page_title = $this->user->lang['PORTAL'];

		return $this->helper->render('portal_body.html', $this->page_title);
	}



	public function rules()
	{
		$mod_style_path	= $this->phpbb_root_path . 'ext/phpbbireland/portal/styles/' . $this->user->style['style_path'] . '/';

		$this->user->add_lang_ext('phpbbireland/portal', 'kiss_common');

		$basic_rules = $this->user->lang['RULES_TEXT'];

		$this->get_portal_cache();
//		$this->block_modules();

		$this->template->assign_block_vars('rules', array(
			'TO_DAY' => $this->user->format_date(time(), false, true),
			'RULES'  => $basic_rules,
		));

		// Output page
		page_header($this->user->lang['RULES_HEADER']);

		$this->template->assign_vars(array(
			'EXT_TEMPLATE_PATH'    => $mod_style_path,
		));

		$this->template->set_filenames(array(
			'body' => 'rules.html')
		);

		page_footer();

		$this->page_title = $this->user->lang['RULES'];
		return $this->helper->render('rules.html', $this->page_title);
	}


	public function assign_images($assign_user_buttons, $assign_post_buttons)
	{
		// may extend to add portal images //
		$this->template->assign_vars(array(
			'REPORTED_IMG'	=> $this->user->img('icon_topic_reported', 'POST_REPORTED'),
		));

		if ($assign_user_buttons)
		{
			$this->template->assign_vars(array(
				'PROFILE_IMG'  => $this->user->img('icon_user_profile', 'READ_PROFILE'),
				'SEARCH_IMG'   => $this->user->img('icon_user_search', 'SEARCH_USER_POSTS'),
				'PM_IMG'       => $this->user->img('icon_contact_pm', 'SEND_PRIVATE_MESSAGE'),
				'EMAIL_IMG'    => $this->user->img('icon_contact_email', 'SEND_EMAIL'),
				'WWW_IMG'      => $this->user->img('icon_contact_www', 'VISIT_WEBSITE'),
				'ICQ_IMG'      => $this->user->img('icon_contact_icq', 'ICQ'),
				'AIM_IMG'      => $this->user->img('icon_contact_aim', 'AIM'),
				'MSN_IMG'      => $this->user->img('icon_contact_msnm', 'MSNM'),
				'YIM_IMG'      => $this->user->img('icon_contact_yahoo', 'YIM'),
				'JABBER_IMG'   => $this->user->img('icon_contact_jabber', 'JABBER'),
			));
		}

		if ($assign_post_buttons)
		{
			$this->template->assign_vars(array(
				'QUOTE_IMG'   => $this->user->img('icon_post_quote', 'REPLY_WITH_QUOTE'),
				'EDIT_IMG'    => $this->user->img('icon_post_edit', 'EDIT_POST'),
				'DELETE_IMG'  => $this->user->img('icon_post_delete', 'DELETE_POST'),
				'INFO_IMG'    => $this->user->img('icon_post_info', 'VIEW_INFO'),
				'REPORT_IMG'  => $this->user->img('icon_post_report', 'REPORT_POST'),
				'WARN_IMG'    => $this->user->img('icon_user_warn', 'WARN_USER'),
			));
		}
	}


	/**
	* Main portal block generation code...
	* to use this code with portal and all other phpbb it needs to be moved to includes/?
	*
	*/
	public function block_modules()
	{
		global $db, $k_config;

		static $page_id = 0;
		$page = '';

		$blocks_width 	   = $this->config['blocks_width'];
		$blocks_enabled    = $this->config['blocks_enabled'];
		$block_cache_time  = $k_config['k_block_cache_time_default'];
		$use_block_cookies = (isset($k_config['use_block_cookies'])) ? $k_config['use_block_cookies'] : 0;

		if (!$blocks_enabled)
		{
			$this->template->assign_vars(array(
				'PORTAL_MESSAGE' => $this->user->lang('BLOCKS_DISABLED'),
			));
		}

		$all = '';
		$show_center = $show_left = $show_right = false;
		$LB = $CB = $RB = array();
		$active_blocks = array();

		// if styles use large block images change path to images //
		$block_image_path = $this->phpbb_root_path . 'ext/phpbbireland/portal/images/block_images/block/';
		$big_image_path = $this->phpbb_root_path . 'ext/phpbbireland/portal/images/block_images/large/';
		$my_root_path = $this->phpbb_root_path . 'ext/phpbbireland/portal/';

		include_once($this->phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $this->php_ext);
		include_once($this->phpbb_root_path . 'ext/phpbbireland/portal/includes/kiss_functions.' . $this->php_ext);


		// Each member can have a different block layouts, so grab data using user_id //
		$user_id = $this->user->data['user_id'];
		$sql = "SELECT group_id, user_type, user_style, user_avatar, user_avatar_type, username, user_left_blocks, user_center_blocks, user_right_blocks
			FROM " . USERS_TABLE . "
			WHERE user_id = $user_id";

		if ($result = $db->sql_query($sql))
		{
			$row = $db->sql_fetchrow($result);

			$user_avatar = $row['user_avatar'];
			$user_style = $row['user_style'];
			$usertype = $row['user_type'];
			$groupid = $row['group_id'];

			$left = $row['user_left_blocks'];
			$LB = explode(',', $left);
			$center = $row['user_center_blocks'];
			$CB = explode(',', $center);
			$right = $row['user_right_blocks'];
			$RB = explode(',', $right);

			$LCR = array_merge((array) $LB, (array) $CB, (array) $RB);
			$left .= ',';
			$center .= ',';

			$all .= $left .= $center .= $right;
		}
		else
		{
			trigger_error($this->user->lang['ERROR_USER_TABLE']);
		}

		// Process block positions for members only //
		if ($row['group_id'] != ANONYMOUS)
		{
			if (isset($_COOKIE[$this->config['cookie_name'] . '_sgp_left']) || isset($_COOKIE[$this->config['cookie_name'] . '_sgp_center']) || isset($_COOKIE[$this->config['cookie_name'] . '_sgp_right']) && $use_block_cookies)
			{
				$left = $request->variable($this->config['cookie_name'] . '_sgp_left', '', false, true);
				$left = str_replace("left[]=", "", $left);
				$left = str_replace("&amp;", ',', $left);
				$LBA = explode(',', $left);

				$center = $request->variable($this->config['cookie_name'] . '_sgp_center', '', false, true);
				$center = str_replace("center[]=", "", $center);
				$center = str_replace("&amp;", ',', $center);
				$CBA = explode(',', $center);

				$right = $request->variable($this->config['cookie_name'] . '_sgp_right', '', false, true);
				$right = str_replace("right[]=", "", $right);
				$right = str_replace("&amp;", ',', $right);
				$RBA = explode(',', $right);

				// store cookie data (block positions in user table)
				if (!empty($left))
				{
					$sql = 'UPDATE ' . USERS_TABLE . '
						SET user_left_blocks = ' . "'" . $db->sql_escape($left) . "'" . ', user_center_blocks = ' . "'" . $db->sql_escape($center) . "'" . ', user_right_blocks = ' . "'" . $db->sql_escape($right) . "'" . '
						WHERE user_id = ' . (int) $this->user->data['user_id'];
					$db->sql_query($sql);
					// set switch clear cookies now that we have them stored (we use javascript)//
					$template->assign_vars(array(
						'S_CLEAR_CACHE' => true
					));
				}
			}

			if (empty($row['user_left_blocks']))
			{
				$sql = "SELECT *
					FROM " . K_BLOCKS_TABLE . "
					WHERE active = 1
						AND (view_pages <> '0')
						ORDER BY ndx ASC";
			}
			else
			{
				$sql = "SELECT *
					FROM " . K_BLOCKS_TABLE . "
					WHERE active = 1
						AND (view_pages <> '0')
						AND " . $db->sql_in_set('id', $LCR) . "
					ORDER BY find_in_set(id,'" . $all . "')";
			}
		}
		else
		{
			$sql = "SELECT *
				FROM " . K_BLOCKS_TABLE . "
				WHERE active = 1
						AND (view_pages <> '0')
						ORDER BY ndx ASC";
		}

		$result = $db->sql_query($sql, $block_cache_time);

		unset($LCR);

		while ($row = $db->sql_fetchrow($result))
		{
			$active_blocks[] = $row;
			$arr[$row['id']] = explode(','  , $row['view_pages']);
		}


		if ($page == '')
		{
			$page = $this->page_name();
		}

		if ($page_id == 0)
		{
			$page_id = get_page_id($page);

			if ($page_id == 0)
			{
				//var_dump('Cannot process for: ' . $page . ' page');
				return;
			}
		}

		foreach ($active_blocks as $active_block)
		{
			$filename = substr($active_block['html_file_name'], 0, strpos($active_block['html_file_name'], '.'));
			if (file_exists($this->phpbb_root_path . 'ext/phpbbireland/portal/blocks/' . $filename . '.' . $this->php_ext))
			{
				if (in_array($page_id, $arr[$active_block['id']]))
				{
					include($this->phpbb_root_path . 'ext/phpbbireland/portal/blocks/' . $filename . '.' . $this->php_ext);
				}
			}
		}
		$db->sql_freeresult($result);

		if (!function_exists('group_memberships'))
		{
			include($this->phpbb_root_path . 'includes/functions_user.'. $this->php_ext);
		}
		$memberships = array();
		$memberships = group_memberships(false, $this->user->data['user_id'], false);

		// Main processing of block data here //
		if ($active_blocks)
		{
			$L = $R = $C = 0;
			foreach ($active_blocks as $row)
			{
				$block_position = $row['position'];

				// override default position with user designated position //
				if (in_array($row['id'], $LB))
				{
					$block_position		= 'L';
				} else if (in_array($row['id'], $CB))
				{
					$block_position		= 'C';
				} else if (in_array($row['id'], $RB))
				{
					$block_position		= 'R';
				}

				$block_id           = $row['id'];
				$block_ndx          = $row['ndx'];
				$block_title        = $row['title'];
				$block_active       = $row['active'];
				$block_type         = $row['type'];
				$block_view_groups  = $row['view_groups'];
				$block_view_all     = $row['view_all'];
				$block_scroll       = $row['scroll'];
				$block_height       = $row['block_height'];
				$html_file_name     = $row['html_file_name'];
				$img_file_name      = $row['img_file_name'];
				$view_pages         = $row['view_pages'];

				$arr = explode(',', $view_pages);
				$grps = explode(",", $block_view_groups);

				$process_block = false;
				$block_title = get_menu_lang_name($row['title']);

				// process blocks for different groups //
				if ($memberships)
				{
					foreach ($memberships as $member)
					{
						// First we check to see if the view_all over-ride is set (saves having to enter all groups) //
						if ($block_view_all)
						{
							$process_block = true;
						}
						else
						{
							for ($j = 0; $j < $jcount = count($grps); $j++) // now we loop for all group the user is in //
							{
								if ($grps[$j] == $member['group_id'])
								{
									$process_block = true;
								}
							}
						}
					}

				}
				$this_page_name = $this_page[1];
				$this_page_name = str_replace('php/', '', $this_page_name);
				$page_id = get_page_id($this_page_name);

				if ($page_id == 0)
				{
					$page_id = $this_page[0];
					$page_id = get_page_id($this_page[0]);
				}

				if ($process_block && in_array($page_id, $arr))
				{
					switch ($block_position)
					{
						case 'L':
								$left_block_ary[$L]    = $html_file_name;
								$left_block_id[$L]     = $block_id;
								$left_block_ndx[$L]    = $block_ndx;
								$left_block_title[$L]  = $block_title;
								$left_block_img[$L]    = $img_file_name;
								$left_block_scroll[$L] = $block_scroll;
								$left_block_height[$L] = $block_height;
								$L++;
								$show_left = true;//show_blocks($this_page_name, $block_position);
						break;
						case 'C':
								$center_block_ary[$C]    = $html_file_name;
								$center_block_id[$C]     = $block_id;
								$center_block_ndx[$C]    = $block_ndx;
								$center_block_title[$C]  = $block_title;
								$center_block_img[$C]    = $img_file_name;
								$center_block_scroll[$C] = $block_scroll;
								$center_block_height[$C] = $block_height;
								$C++;
								$show_center = true;//show_blocks($this_page_name, $block_position);
						break;
						case 'R':
								$right_block_ary[$R]    = $html_file_name;
								$right_block_id[$R]     = $block_id;
								$right_block_ndx[$R]    = $block_ndx;
								$right_block_title[$R]  = $block_title;
								$right_block_img[$R]    = $img_file_name;
								$right_block_scroll[$R] = $block_scroll;
								$right_block_height[$R] = $block_height;
								$R++;
								$show_right = true;//show_blocks($this_page_name, $block_position);
						break;
						default:
					}
				}
			}
		}

		unset($active_blocks);

		if (isset($left_block_ary) && $show_left)
		{
			foreach ($left_block_ary as $block => $value)
			{
				$this->template->assign_block_vars('left_block_files', array(
					'LEFT_BLOCKS'           => $this->build_block_modules($value),
					'LEFT_BLOCK_ID'         => 'L_' .$left_block_id[$block],
					'LEFT_BLOCK_TITLE'      => $left_block_title[$block],
					'LEFT_BLOCK_SCROLL'     => $left_block_scroll[$block],
					'LEFT_BLOCK_HEIGHT'     => $left_block_height[$block],
					'LEFT_BLOCK_IMG'        => ($left_block_img[$block]) ? $block_image_path . $left_block_img[$block] : $block_image_path . 'none.gif',
					'LEFT_BLOCK_IMG_2'      => (file_exists($big_image_path . $left_block_img[$block])) ? $big_image_path  . $left_block_img[$block] : $big_image_path . 'none.png',
					'S_CONTENT_FLOW_BEGIN'  => ($this->user->lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
					'S_CONTENT_FLOW_END'    => ($this->user->lang['DIRECTION'] == 'ltr') ? 'right' : 'left',
				));
			}
		}

		if (isset($right_block_ary) && $show_right)
		{
			foreach ($right_block_ary as $block => $value)
			{
				$this->template->assign_block_vars('right_block_files', array(
					'RIGHT_BLOCKS'          => $this->build_block_modules($value),
					'RIGHT_BLOCK_ID'        => 'R_' .$right_block_id[$block],
					'RIGHT_BLOCK_TITLE'     => $right_block_title[$block],
					'RIGHT_BLOCK_SCROLL'    => $right_block_scroll[$block],
					'RIGHT_BLOCK_HEIGHT'    => $right_block_height[$block],
					'RIGHT_BLOCK_IMG'       => ($right_block_img[$block]) ? $block_image_path . $right_block_img[$block] : $block_image_path . 'none.gif',
					'RIGHT_BLOCK_IMG_2'     => (file_exists($big_image_path . $right_block_img[$block])) ? $big_image_path  . $right_block_img[$block] : $big_image_path . 'none.png',
					'S_CONTENT_FLOW_BEGIN'  => ($this->user->lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
					'S_CONTENT_FLOW_END'    => ($this->user->lang['DIRECTION'] == 'ltr') ? 'right' : 'left',
				));
			}
		}

		if (isset($center_block_ary) && $show_center)
		{
			foreach ($center_block_ary as $block => $value)
			{
				$template->assign_block_vars('center_block_files', array(
					'CENTER_BLOCKS'        => $this->build_block_modules($value),
					'CENTER_BLOCK_ID'      => 'C_' .$center_block_id[$block],
					'CENTER_BLOCK_TITLE'   => $center_block_title[$block],
					'CENTER_BLOCK_SCROLL'  => $center_block_scroll[$block],
					'CENTER_BLOCK_HEIGHT'  => $center_block_height[$block],
					'CENTER_BLOCK_IMG'     => ($center_block_img[$block]) ? $block_image_path . $center_block_img[$block] : $block_image_path . 'none.gif',
					'CENTER_BLOCK_IMG_2'   => (file_exists($big_image_path . $center_block_img[$block])) ? $big_image_path  . $center_block_img[$block] : $big_image_path . 'none.png',
					'S_CONTENT_FLOW_BEGIN' => ($this->user->lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
					'S_CONTENT_FLOW_END'   => ($this->user->lang['DIRECTION'] == 'ltr') ? 'right' : 'left',
				));
			}
		}

		$this->template->assign_vars(array(
			'T_THEME_PATH'            => $this->phpbb_root_path . 'ext/phpbbireland/portal/style/' . rawurlencode($this->user->style['style_path']) . '/theme/images/',
			'AVATAR'                  => get_user_avatar($this->user->data['user_avatar'], $this->user->data['user_avatar_type'], $this->user->data['user_avatar_width'], $this->user->data['user_avatar_height']),
			'BLOCK_WIDTH'             => $blocks_width . 'px',
			'PORTAL_ACTIVE'           => $this->config['portal_enabled'],
			'PORTAL_BUILD'            => $this->config['portal_build'],
			'PORTAL_VERSION'          => $this->config['portal_version'],
			'READ_ARTICLE_IMG'        => $this->user->img('btn_read_article', 'READ_ARTICLE'),
			'POST_COMMENTS_IMG'       => $this->user->img('btn_post_comments', 'POST_COMMENTS'),
			'VIEW_COMMENTS_IMG'       => $this->user->img('btn_view_comments', 'VIEW_COMMENTS'),
			'SITE_NAME'               => $this->config['sitename'],
			'S_SHOW_LEFT_BLOCKS'      => $show_left,
			'S_SHOW_RIGHT_BLOCKS'     => $show_right,
			'S_BLOCKS_ENABLED'        => $blocks_enabled,
			'S_K_FOOTER_IMAGES_ALLOW' => ($k_config['k_footer_images_allow']) ? true : false,
			'S_USER_LOGGED_IN'        => ($this->user->data['user_id'] != ANONYMOUS) ? true : false,
			'S_CONTENT_FLOW_BEGIN'    => ($this->user->lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
			'S_CONTENT_FLOW_END'      => ($this->user->lang['DIRECTION'] == 'ltr') ? 'right' : 'left',
			'USER_NAME'               => $this->user->data['username'],
			'USERNAME_FULL'           => get_username_string('full', $this->user->data['user_id'], $this->user->data['username'], $this->user->data['user_colour']),
			'U_INDEX'                 => append_sid("{$this->phpbb_root_path}index.$this->php_ext"),
			'U_STAFF'                 => append_sid("{$this->phpbb_root_path}memberlist.$this->php_ext", 'mode=leaders'),
			'U_SEARCH_BOOKMARKS'      => append_sid("{$this->phpbb_root_path}ucp.$this->php_ext", 'i=main&mode=bookmarks'),
		));
	}

	public function build_block_modules($block_file)
	{
		$this->template->set_filenames(array('block' => 'blocks/' . $block_file));
		return $this->template->assign_display('block', true);
	}

	public function get_portal_cache()
	{
		global $k_config;

		if (!isset($k_config))
		{
			include($this->phpbb_root_path . 'ext/phpbbireland/portal/includes/functions.' . $this->php_ext);
			$k_config = obtain_k_config();
			$k_menus = obtain_k_menus();
			$k_blocks = obtain_block_data();
			$k_pages = obtain_k_pages();
			$k_groups = obtain_k_groups();
			$k_resources = obtain_k_resources();
		}
	}

	public function get_portal_link()
	{
		$portal_link = '';

		if (strpos($this->user->data['session_page'], '/portal') === false)
		{
			$portal_link = $this->helper->route('phpbbireland_portal_controller');
		}
		else
		{
			$portal_link = $this->path_helper->remove_web_root_path($this->helper->route('phpbbireland_portal_controller'));
		}
		return($portal_link);
	}


	public function page_name()
	{
		$this_page = explode(".", $this->user->page['page']);

		if ($this_page[0] == 'app')
		{
			$this_page_name = explode("/", $this_page[1]);
			return($this_page_name[1]);
		}
		else
		{
			$this_page_name = $this_page[0];
			return($this_page_name);
		}
	}
}
