<?php
/**
*
* @package phpbbireland 1.0.2
* @copyright (c) 2013 phpbbireland
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbireland\portal\controller;

class helper
{
	/**
	* Auth object
	* @var \phpbb\auth\auth
	*/
	protected $auth;

	/**
	* phpBB Config object
	* @var \phpbb\config\config
	*/
	protected $config;

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
	protected $root_path;

	/**
	* phpBB path helper
	* @var \phpbb\path_helper
	*/
	protected $path_helper;

	/**
	* Portal Helper object
	* @var \phpbbireland\portal\includes\helper
	*/
	protected $portal_helper;

	/**
	* Portal modules array
	* @var array
	*/
	protected $portal_modules; // later

	/**
	* Constructor
	* NOTE: The parameters of this method must match in order and type with
	* the dependencies defined in the services.yml file for this service.
	* @param \phpbb\auth\auth $auth Auth object
	* @param \phpbb\config\config $config phpBB Config object
	* @param \phpbb\template $template Template object
	* @param \phpbb\user $user User object
	* @param \phpbb\path_helper $path_helper phpBB path helper
	* @param \phpbbireland\portal\includes\helper $portal_helper Portal helper class
	* @param string $phpbb_root_path phpBB root path
	* @param string $php_ext PHP file extension
	*/
	public function __construct($auth, $config, $template, $user, $path_helper, $portal_helper, $phpbb_root_path, $php_ext)
	{
//?var_dump('helper.php > constructor');

		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->path_helper = $path_helper;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->portal_helper = $portal_helper;

		//$this->root_path = $phpbb_root_path . 'ext/phpbbireland/portal/';
	}


	/**
	* Main block/module processing... grab all valid blocks and generate template etc
	*
	* @return null
	*/
	public function generate_all_block()
	{
//var_dump('helper.php > generate_all_block()');

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
		$mod_style_path	= $this->phpbb_root_path . 'ext/phpbbireland/portal/styles/' . $this->user->style['style_path'] . '/';



		$this->template->assign_vars(array(
			'EXT_TEMPLATE_PATH'	=> $mod_style_path,
		));

		include_once($this->phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $this->php_ext);
		include_once($this->phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_functions.' . $this->php_ext);


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
					$this->template->assign_vars(array(
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
			$page_id = get_page_id($page);
		}
//?var_dump('helper.php > Page = ' . $page);
//?var_dump('helper.php > Page id = ' . $page_id);

		if ($page_id == 0)
		{
			//////////////var_dump('No Page ID');
			return;
		}

		//var_dump($active_blocks);

		foreach ($active_blocks as $active_block)
		{
			$filename = substr($active_block['html_file_name'], 0, strpos($active_block['html_file_name'], '.'));
			//var_dump('Block Filename: '. $filename);

			if (file_exists($this->phpbb_root_path . 'ext/phpbbireland/portal/blocks/' . $filename . '.' . $this->php_ext))
			{
				//var_dump('File exists: '. $filename);

				if (in_array($page_id, $arr[$active_block['id']]))
				{
					//var_dump('Processed: '. $filename);
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
				$this->template->assign_block_vars('center_block_files', array(
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


		if (!defined('KISS'))
		{
			define('KISS', true);
		}

		$this->template->assign_vars(array(
			'KISS'                    => true,

			'T_THEME_PATH'            => $this->phpbb_root_path . 'ext/phpbbireland/portal/style/' . rawurlencode($this->user->style['style_path']) . '/theme/images/',

			'AVATAR'                  => get_user_avatar($this->user->data['user_avatar'], $this->user->data['user_avatar_type'], $this->user->data['user_avatar_width'], $this->user->data['user_avatar_height']),

			'BLOCK_WIDTH'             => $blocks_width . 'px',
			'PORTAL_ACTIVE'           => $this->config['portal_enabled'],
			'PORTAL_BUILD'            => $this->config['portal_build'],
			'PORTAL_VERSION'          => $this->config['portal_version'],

			//'EXT_TEMPLATE_PATH'       => $mod_style_path,

			'READ_ARTICLE_IMG'        => $this->user->img('btn_read_article', 'READ_ARTICLE'),
			'POST_COMMENTS_IMG'       => $this->user->img('btn_post_comments', 'POST_COMMENTS'),
			'VIEW_COMMENTS_IMG'       => $this->user->img('btn_view_comments', 'VIEW_COMMENTS'),

			'SITE_NAME'               => $this->config['sitename'],

			'S_BLOCKS_ENABLED'        => $blocks_enabled,
			'S_CONTENT_FLOW_BEGIN'    => ($this->user->lang['DIRECTION'] == 'ltr') ? 'left' : 'right',
			'S_CONTENT_FLOW_END'      => ($this->user->lang['DIRECTION'] == 'ltr') ? 'right' : 'left',
			'S_K_FOOTER_IMAGES_ALLOW' => ($k_config['k_footer_images_allow']) ? true : false,

			'S_SHOW_LEFT_BLOCKS'      => $show_left,
			'S_SHOW_RIGHT_BLOCKS'     => $show_right,

			'S_USER_LOGGED_IN'        => ($this->user->data['user_id'] != ANONYMOUS) ? true : false,


			'USER_NAME'               => $this->user->data['username'],
			'USERNAME_FULL'           => get_username_string('full', $this->user->data['user_id'], $this->user->data['username'], $this->user->data['user_colour']),

			'U_INDEX'                 => append_sid("{$this->phpbb_root_path}index.{$this->php_ext}"),
			'U_STAFF'                 => append_sid("{$this->phpbb_root_path}memberlist.{$this->php_ext}", 'mode=leaders'),
			'U_SEARCH_BOOKMARKS'      => append_sid("{$this->phpbb_root_path}ucp.{$this->php_ext}", 'i=main&mode=bookmarks'),
		));
	}

	public function build_block_modules($block_file)
	{
		////var_dump('helper.php > build_block_modules(' . $block_file . ') - called for each block!');

		$this->template->set_filenames(array('block' => 'blocks/' . $block_file));
		return $this->template->assign_display('block', true);
	}

	/**
	* Check if user should can access this page, if not redirect to index
	*
	* @return null
	*/
	protected function check_permission()
	{
//?var_dump('helper.php > check_permission()');
		if (!isset($this->config['portal_enabled']) || !$this->auth->acl_get('u_k_portal'))
		{
			redirect(append_sid($this->phpbb_root_path . 'index' . '.' . $this->php_ext));
		}
	}

	/**
	* get portal root
	*
	* @return link
	*/
	public function get_portal_link()
	{
//?var_dump('helper.php > get_portal_link()');
		if (strpos($this->user->data['session_page'], '/portal') === false)
		{
			$portal_link = $this->controller_helper->route('phpbbireland_portal_controller');
		}
		else
		{
			$portal_link = $this->path_helper->remove_web_root_path($this->controller_helper->route('phpbbireland_portal_controller'));
		}
		return($portal_link);
	}


	/**
	* grab the portal cache
	*
	* @return null
	*/
	public function load_cache()
	{
//?var_dump('helper.php > load_cache()');
		global $k_config;

		if (!isset($k_config))
		{
			include($this->phpbb_root_path . 'ext/phpbbireland/portal/includes/functions.' . $this->php_ext);
			$k_config = obtain_k_config();
			$k_blocks = obtain_block_data();
			$k_menus = obtain_k_menus();
			$k_pages = obtain_k_pages();
			$k_groups = obtain_k_groups();
			$k_resources = obtain_k_resources();
		}
	}


	/**
	* initialise things here
	*
	* @return null
	*/
	public function run_initial_tasks()
	{
//?var_dump('helper.php > run_initial_tasks()');

		$this->includes_path = $this->phpbb_root_path . 'ext/phpbbireland/portal/includes/';
		$mod_root_path	= $this->phpbb_root_path . 'ext/phpbbireland/portal/';

		// Check permissions
		$this->check_permission();

		// Load language file
		$this->user->add_lang_ext('phpbbireland/portal', 'portal');

		// load cache
		$this->load_cache();
	}

	public function page_name()
	{
//?var_dump('helper.php > page_name()');
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
