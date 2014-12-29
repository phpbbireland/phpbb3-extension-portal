<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbbireland\portal\controller;

class main_controller implements main_interface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbbireland\portal */
	protected $portal;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/* @var string phpBB root path */
	protected $root_path;

	/** @var string phpEx */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\config\config                $config             Config object
	* @param \phpbb\controller\helper            $helper             Controller helper object
	* @param \phpbbireland\portal                $portal             Portal object
	* @param \phpbb\template\template            $template           Template object
	* @param \phpbb\user                         $user               User object
	* @param string                              $root_path          phpBB root path
	* @param string                              $php_ext            phpEx
	* @return \phpbbireland\portal\controller\main
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\controller\helper $helper, \phpbbireland\portal\portal $portal, $root_path, $php_ext)
	{
		$this->config = $config;
		$this->helper = $helper;
		$this->portal = $portal;
		$this->template = $template;
		$this->user = $user;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
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
		global $cache, $user, $auth, $config, $request, $template, $path_helper, $phpbb_root_path, $phpbb_container;
		global $k_config, $k_menus, $k_blocks, $k_pages, $k_groups, $k_resources;
		global $ready_modules;

		//var_dump('in: portal.php : base()');

		// Determine board url - we may need it later
		$board_url = generate_board_url() . '/';

		// This path is sent with the base template paths in the assign_vars()
		// call below. We need to correct it in case we are accessing from a
		// controller because the web paths will be incorrect otherwise.

		$phpbb_path_helper = $phpbb_container->get('path_helper');
		$corrected_path = $phpbb_path_helper->get_web_root_path();

		$web_path = (defined('PHPBB_USE_BOARD_URL_PATH') && PHPBB_USE_BOARD_URL_PATH) ? $board_url : $corrected_path;

		// some reuired paths //
		$mod_root_path           = $phpbb_root_path . 'ext/phpbbireland/portal/';
		$mod_root_image_path     = $mod_root_path . 'images/';
		$mod_root_jq_path        = $mod_root_path . 'js/jquery/';
		$mod_root_js_path        = $mod_root_path . 'js/';
		$mod_root_common_path    = $mod_root_path . 'styles/common/';
		$mod_common_images_path  = $mod_root_path . 'styles/common/theme/images/';
		$mod_user_template_path  = $mod_root_path . 'styles/prosilver/';
		$mod_user_jq_path        = $mod_root_path . 'styles/prosilver/template/js/';
		$mod_image_lang_path     = $mod_root_path . 'styles/prosilver/theme/' . $user->data['user_lang'];
		$js_version = 'jquery-2.0.3.min.js';

		if (!class_exists('bbcode'))
		{
			include($this->root_path . 'includes/bbcode.' . $this->php_ext);
		}
		if (!function_exists('get_user_rank'))
		{
			include($this->root_path . 'includes/functions_display.' . $this->php_ext);
		}
		if (!function_exists('phpbb_get_user_avatar'))
		{
			include($phpbb_root_path . 'includes/functions_display.'. $this->php_ext);
		}

		$this->get_cache();

		$includes_path = $phpbb_root_path . 'ext/phpbbireland/portal/includes/';
		$mod_path = $phpbb_root_path . 'ext/phpbbireland/portal';

		global $user, $queries, $cached_queries, $total_queries, $k_config, $k_blocks, $k_menus, $k_pages, $k_groups;

		include_once($includes_path . 'sgp_functions.' . $this->php_ext);

		$cache_time = (isset($k_config['block_cache_time'])) ? $k_config['block_cache_time'] : '600';

		$set_time = time() + 31536000;
		$reset_time = time() - 31536000;
		$cookie_name = $cookie_value = $css = '';
		$cookie_name = $config['cookie_name'] . '_css';

		if (isset($_COOKIE[$cookie_name]))
		{
			$cookie_value = $request->variable($cookie_name, 0, false, true);
		}

		$css = $request->variable('css', 0);
		if ($css) // set css //
		{
			$user->set_cookie('css', $css, $set_time);
		}
		else if ($cookie_value) // cookie set so use it //
		{
			$css = $cookie_value;
		}

		$logo_right = $logo = sgp_get_rand_logo();
		$logo_right  = str_replace('logos', 'logos/right_images', $logo);

		if (!defined('STARGATE'))
		{
			define('STARGATE', true);
		}

		$template->assign_vars(array(
			'U_SITE_HOME'                   => append_sid("{$mod_root_path}portal"),
			'L_SITE_HOME'                   => $user->lang['PORTAL'],
			'PORTAL'=> true,
			'STARGATE'                      => true,
			'HS'                            => true,
			'JS_PATH'                       => $mod_root_jq_path,
			'JS_JQUERY'                     => $mod_root_jq_path . $js_version,
			'T_STYLESHEET_PORTAL_COMMON'    => $mod_root_common_path . "/theme/portal_common.css",
			'U_PORTAL'                      => append_sid("{$mod_root_path}portal"),
			'U_PORTAL_ARRANGE'              => append_sid("{$mod_root_path}portal.$this->php_ext", "arrange=1"),
			'U_HOME'                        => append_sid("{$mod_root_path}portal.$this->php_ext"),
			'SITE_LOGO_IMG'                 => $logo,
			'SITE_LOGO_IMG_RIGHT'           => $logo_right,
			'MOD_COMMON_IMAGES_PATH'        => $mod_common_images_path,
			'MOD_IMAGE_LANG_PATH'           => $mod_image_lang_path,

			'MOD_ROOT_PATH'             => $mod_root_path,
			'MOD_ROOT_IMAGE_PATH'       => $mod_root_image_path,
			'MOD_ROOT_JQ_PATH'          => $mod_root_jq_path,
			'MOD_USER_JQ_PATH'          => $mod_user_jq_path,
			'MOD_USER_TEMPLATE_PATH'    => $mod_user_template_path,
			'MOD_ROOT_JS_PATH'          => $mod_root_js_path,

			'S_HIGHSLIDE'               => true,
			'COOKIE_NAME'               => (isset($config['cookie_name'])) ? $config['cookie_name'] : '',
			'STARGATE_BUILD'            => (isset($config['portal_build'])) ? $config['portal_build'] : '',
			'STARGATE_VERSION'          => (isset($config['portal_version'])) ? $config['portal_version'] : '',
			'AVATAR_SMALL_IMG'          => (STARGATE) ? get_user_avatar($user->data['user_avatar'], $user->data['user_avatar_type'], '35', '35') : '',
			'P_USERNAME'                => (STARGATE) ? $user->data['username'] : '',
			'P_USERNAME_FULL'           => (STARGATE) ? get_username_string('full', $user->data['user_id'], $user->data['username'], $user->data['user_colour']) : '',

			'T_ASSETS_VERSION'          => $config['assets_version'],
			'T_ASSETS_PATH'             => "{$web_path}assets",
			'T_THEME_PATH'              => "{$web_path}styles/" . rawurlencode('prosiver') . '/theme',
			'T_TEMPLATE_PATH'           => "{$web_path}styles/" . rawurlencode('prosiver') . '/template',
			'T_SUPER_TEMPLATE_PATH'     => "{$web_path}styles/" . rawurlencode('prosiver') . '/template',
			'T_IMAGES_PATH'             => "{$web_path}images/",
			'T_SMILIES_PATH'            => "{$web_path}{$config['smilies_path']}/",
			'T_AVATAR_PATH'             => "{$web_path}{$config['avatar_path']}/",
			'T_AVATAR_GALLERY_PATH'     => "{$web_path}{$config['avatar_gallery_path']}/",
			'T_ICONS_PATH'              => "{$web_path}{$config['icons_path']}/",
			'T_RANKS_PATH'              => "{$web_path}{$config['ranks_path']}/",
			'T_UPLOAD_PATH'             => "{$web_path}{$config['upload_path']}/",
			'T_STYLESHEET_LINK'         => "{$web_path}styles/" . rawurlencode('prosiver') . '/theme/stylesheet.css?assets_version=' . $config['assets_version'],
			'T_STYLESHEET_LANG_LINK'    => "{$web_path}styles/" . rawurlencode('prosiver') . '/theme/' . $user->lang_name . '/stylesheet.css?assets_version=' . $config['assets_version'],
			'T_JQUERY_LINK'             => !empty($config['allow_cdn']) && !empty($config['load_jquery_url']) ? $config['load_jquery_url'] : "{$web_path}assets/javascript/jquery.js?assets_version=" . $config['assets_version'],
			'S_ALLOW_CDN'               => !empty($config['allow_cdn']),

			'T_THEME_NAME'              => rawurlencode('prosiver'),
			'T_THEME_LANG_NAME'         => $user->data['user_lang'],
			'T_TEMPLATE_NAME'           => 'prosiver',
			'T_SUPER_TEMPLATE_NAME'     => rawurlencode((isset($user->style['style_parent_tree']) && $user->style['style_parent_tree']) ? $user->style['style_parent_tree'] : 'prosiver'),
			'T_IMAGES'                  => 'images',
			'T_SMILIES'                 => $config['smilies_path'],
			'T_AVATAR'                  => $config['avatar_path'],
			'T_AVATAR_GALLERY'          => $config['avatar_gallery_path'],
			'T_ICONS'                   => $config['icons_path'],
			'T_RANKS'                   => $config['ranks_path'],
			'T_UPLOAD'                  => $config['upload_path'],
		));

		$this->block_modules();

		// Generate logged in/logged out status
		if ($user->data['user_id'] != ANONYMOUS)
		{
			$s_login_logout = append_sid("{$phpbb_root_path}ucp.$this->php_ext", 'mode=logout', true, $user->session_id);
			$l_login_logout = sprintf($user->lang['LOGOUT_USER'], $user->data['username']);
		}
		else
		{
			$s_login_logout = append_sid("{$phpbb_root_path}ucp.$this->php_ext", 'mode=login');
			$l_login_logout = $user->lang['LOGIN'];
		}

		$template->assign_vars(array(
			'S_LOGIN_ACTION'  => $s_login_logout,
			'L_LOGIN_LOGOUT'  => $l_login_logout,
		));

		$this->portal->base();

		$this->assign_images($this->config['portal_user_info'], $this->config['portal_pick_buttons']);

		return $this->helper->render('portal_body.html', $this->portal->get_page_title());
	}



	public function rules()
	{
		global $user, $template;

		$basic_rules = $user->lang['RULES_TEXT'];

		$this->block_modules();

		$template->assign_block_vars('rules', array(
			'TO_DAY' => $user->format_date(time(), false, true),
			'RULES'  => $basic_rules,
		));

		// Output page
		page_header($user->lang['RULES_HEADER']);

		$template->set_filenames(array(
			'body' => 'rules.html')
		);

		page_footer();

		//return $this->rules();
		return $this->helper->render('rules.html', $this->portal->get_page_title());
	}

	public function assign_images($assign_user_buttons, $assign_post_buttons)
	{
		// may extend to add portal images //
		$this->template->assign_vars(array(
			'REPORTED_IMG'			=> $this->user->img('icon_topic_reported', 'POST_REPORTED'),
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

	public function block_modules()
	{
		global $phpbb_root_path, $config, $phpEx, $table_prefix;
		global $db, $user, $avatar_img, $template, $auth;
		global $k_config, $k_groups, $k_blocks;

		$block_cache_time  = $k_config['k_block_cache_time_default'];
		$blocks_width 	   = $config['blocks_width'];
		$blocks_enabled    = $config['blocks_enabled'];
		$use_block_cookies = (isset($k_config['use_block_cookies'])) ? $k_config['use_block_cookies'] : 0;

		if (!$blocks_enabled)
		{
			$template->assign_vars(array(
				'PORTAL_MESSAGE' => $user->lang('BLOCKS_DISABLED'),
			));
		}

		$all = '';
		$show_center = $show_left = $show_right = false;
		$LB = $CB = $RB = array();
		$active_blocks = array();

		// if styles use large block images change path to images //
		$block_image_path = $phpbb_root_path . 'ext/phpbbireland/portal/images/block_images/block/';
		$big_image_path = $phpbb_root_path . 'ext/phpbbireland/portal/images/block_images/large/';
		$my_root_path = $phpbb_root_path . 'ext/phpbbireland/portal/';

		$this_page = explode(".", $user->page['page']);
		$user_id = $user->data['user_id'];

		include_once($phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_functions.' . $this->php_ext);

		// Grab data for this user //
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
			trigger_error($user->lang['ERROR_USER_TABLE']);
		}

		// Process block positions for members only //
		if ($row['group_id'] != ANONYMOUS)
		{
			if (isset($_COOKIE[$config['cookie_name'] . '_sgp_left']) || isset($_COOKIE[$config['cookie_name'] . '_sgp_center']) || isset($_COOKIE[$config['cookie_name'] . '_sgp_right']) && $use_block_cookies)
			{
				$left = $request->variable($config['cookie_name'] . '_sgp_left', '', false, true);
				$left = str_replace("left[]=", "", $left);
				$left = str_replace("&amp;", ',', $left);
				$LBA = explode(',', $left);

				$center = $request->variable($config['cookie_name'] . '_sgp_center', '', false, true);
				$center = str_replace("center[]=", "", $center);
				$center = str_replace("&amp;", ',', $center);
				$CBA = explode(',', $center);

				$right = $request->variable($config['cookie_name'] . '_sgp_right', '', false, true);
				$right = str_replace("right[]=", "", $right);
				$right = str_replace("&amp;", ',', $right);
				$RBA = explode(',', $right);

				// store cookie data (block positions in user table)
				if (!empty($left))
				{
					$sql = 'UPDATE ' . USERS_TABLE . '
						SET user_left_blocks = ' . "'" . $db->sql_escape($left) . "'" . ', user_center_blocks = ' . "'" . $db->sql_escape($center) . "'" . ', user_right_blocks = ' . "'" . $db->sql_escape($right) . "'" . '
						WHERE user_id = ' . (int) $user->data['user_id'];
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

		$this_page_name = $this_page[1];
		$this_page_name = str_replace('php/', '', $this_page_name);
		$page_id = get_page_id($this_page_name);

		if ($page_id == 0)
		{
			$page_id = $this_page[0];
			$page_id = get_page_id($this_page[0]);
		}

		foreach ($active_blocks as $active_block)
		{
			$filename = substr($active_block['html_file_name'], 0, strpos($active_block['html_file_name'], '.'));
			if (file_exists($phpbb_root_path . 'ext/phpbbireland/portal/blocks/' . $filename . '.' . $this->php_ext))
			{
				if (in_array($page_id, $arr[$active_block['id']]))
				{
					include($phpbb_root_path . 'ext/phpbbireland/portal/blocks/' . $filename . '.' . $this->php_ext);
				}
			}
		}
		$db->sql_freeresult($result);

		if (!function_exists('group_memberships'))
		{
			include($phpbb_root_path . 'includes/functions_user.'. $this->php_ext);
		}
		$memberships = array();
		$memberships = group_memberships(false, $user->data['user_id'], false);

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
					//var_dump($html_file_name);
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
				$template->assign_block_vars('left_block_files', array(
					'LEFT_BLOCKS'           => $this->build_block_modules($value),
					'LEFT_BLOCK_ID'         => 'L_' .$left_block_id[$block],
					'LEFT_BLOCK_TITLE'      => $left_block_title[$block],
					'LEFT_BLOCK_SCROLL'     => $left_block_scroll[$block],
					'LEFT_BLOCK_HEIGHT'     => $left_block_height[$block],
					'LEFT_BLOCK_IMG'        => ($left_block_img[$block]) ? $block_image_path . $left_block_img[$block] : $block_image_path . 'none.gif',
					'LEFT_BLOCK_IMG_2'      => (file_exists($big_image_path . $left_block_img[$block])) ? $big_image_path  . $left_block_img[$block] : $big_image_path . 'none.png',
					'S_CONTENT_FLOW_BEGIN'  => ($user->lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
					'S_CONTENT_FLOW_END'    => ($user->lang['DIRECTION'] == 'ltr') ? 'right' : 'left',
				));
			}
		}

		if (isset($right_block_ary) && $show_right)
		{
			foreach ($right_block_ary as $block => $value)
			{
				$template->assign_block_vars('right_block_files', array(
					'RIGHT_BLOCKS'          => $this->build_block_modules($value),
					'RIGHT_BLOCK_ID'        => 'R_' .$right_block_id[$block],
					'RIGHT_BLOCK_TITLE'     => $right_block_title[$block],
					'RIGHT_BLOCK_SCROLL'    => $right_block_scroll[$block],
					'RIGHT_BLOCK_HEIGHT'    => $right_block_height[$block],
					'RIGHT_BLOCK_IMG'       => ($right_block_img[$block]) ? $block_image_path . $right_block_img[$block] : $block_image_path . 'none.gif',
					'RIGHT_BLOCK_IMG_2'     => (file_exists($big_image_path . $right_block_img[$block])) ? $big_image_path  . $right_block_img[$block] : $big_image_path . 'none.png',
					'S_CONTENT_FLOW_BEGIN'  => ($user->lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
					'S_CONTENT_FLOW_END'    => ($user->lang['DIRECTION'] == 'ltr') ? 'right' : 'left',
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
					'S_CONTENT_FLOW_BEGIN' => ($user->lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
					'S_CONTENT_FLOW_END'   => ($user->lang['DIRECTION'] == 'ltr') ? 'right' : 'left',
				));
			}
		}

		$template->assign_vars(array(
			'T_THEME_PATH'            => $phpbb_root_path . 'ext/phpbbireland/portal/style/' . rawurlencode($user->style['style_path']) . '/theme/images/',
			'AVATAR'                  => get_user_avatar($user->data['user_avatar'], $user->data['user_avatar_type'], $user->data['user_avatar_width'], $user->data['user_avatar_height']),
			'BLOCK_WIDTH'             => $blocks_width . 'px',
			'PORTAL_ACTIVE'           => $config['portal_enabled'],
			'PORTAL_BUILD'            => $config['portal_build'],
			'PORTAL_VERSION'          => $config['portal_version'],
			'READ_ARTICLE_IMG'        => $user->img('btn_read_article', 'READ_ARTICLE'),
			'POST_COMMENTS_IMG'       => $user->img('btn_post_comments', 'POST_COMMENTS'),
			'VIEW_COMMENTS_IMG'       => $user->img('btn_view_comments', 'VIEW_COMMENTS'),
			'SITE_NAME'               => $config['sitename'],
			'S_USER_LOGGED_IN'        => ($user->data['user_id'] != ANONYMOUS) ? true : false,
			'S_SHOW_LEFT_BLOCKS'      => $show_left,
			'S_SHOW_RIGHT_BLOCKS'     => $show_right,
			'S_BLOCKS_ENABLED'        => $blocks_enabled,
			'S_K_FOOTER_IMAGES_ALLOW' => ($k_config['k_footer_images_allow']) ? true : false,
			'S_CONTENT_FLOW_BEGIN'    => ($user->lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
			'S_CONTENT_FLOW_END'      => ($user->lang['DIRECTION'] == 'ltr') ? 'right' : 'left',
			'USER_NAME'               => $user->data['username'],
			'USERNAME_FULL'           => get_username_string('full', $user->data['user_id'], $user->data['username'], $user->data['user_colour']),
			'U_INDEX'                 => append_sid("{$phpbb_root_path}index.$this->php_ext"),
			///'U_PORTAL'                => append_sid("{$phpbb_root_path}portal.$this->php_ext"),
			//'U_PORTAL'                => append_sid("{$phpbb_root_path}portal"),
			//'U_PORTAL_ARRANGE'        => append_sid("{$phpbb_root_path}portal.$this->php_ext", "arrange=1"),
			'U_STAFF'                 => append_sid("{$phpbb_root_path}memberlist.$this->php_ext", 'mode=leaders'),
			'U_SEARCH_BOOKMARKS'      => append_sid("{$phpbb_root_path}ucp.$this->php_ext", 'i=main&mode=bookmarks'),
		));
	}

	public function build_block_modules($block_file)
	{
		$this->template->set_filenames(array('block' => 'blocks/' . $block_file));
		return $this->template->assign_display('block', true);
	}

	public function get_cache()
	{
		global $k_config, $phpbb_root_path;

		if (!$k_config)
		{
			include($phpbb_root_path . 'ext/phpbbireland/portal/includes/functions.' . $this->php_ext);
			$k_config = obtain_k_config();
			$k_menus = obtain_k_menus();
			$k_blocks = obtain_block_data();
			$k_pages = obtain_k_pages();
			$k_groups = obtain_k_groups();
			$k_resources = obtain_k_resources();
		}
	}
}
