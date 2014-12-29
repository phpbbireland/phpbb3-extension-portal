<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbbireland\portal\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string phpEx */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper    $helper    Controller helper object
	* @param \phpbb\request\request      $request            Request object
	* @param \phpbb\template\template    $template           Template object
	* @param \phpbb\user                 $user               User object
	* @param string                      $php_ext   phpEx
	* @return \phpbbireland\portal\event\listener
	* @access public
	*/
	public function __construct(\phpbb\controller\helper $helper, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, $php_ext)
	{
		$this->helper = $helper;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->php_ext = $php_ext;
		$this->page = '';
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'         => 'load_language_on_setup',
			//'core.page_header'        => 'add_portal_page_header_link',
			//'core.page_header'        => 'add_portal_blocks',
			'core.page_header_after'  =>  'add_portal_blocks',
			//'core.page_footer'        => 'add_portal_page_footer_link',

			//'core.page_header_after'	=> 'index_page',

			// ACP event
			'core.permissions'	=> 'add_categories',
			'core.permissions'	=> 'add_permission',
		);
	}

	/*
		add event to add news ...

	*/

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'phpbbireland/portal',
			'lang_set' => 'portal',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}


	/**
	* Add administrative permissions to manage board rules
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/

/*
	public function add_categories($event)
	{
		$categories = $event[$categories];
		$categories['portal']	= 'ACL_CAT_PORTAL';
	}
*/

	public function add_permission($event)
	{
		$categories = $event['categories'];
		$categories['portal'] = 'ACL_CAT_PORTAL';
		$event['categories'] = $categories;

		$permissions = $event['permissions'];
		$permissions['a_k_portal'] = array('lang' => 'ACL_A_PORTAL', 'cat' => 'portal');
		$permissions['a_k_tools'] = array('lang' => 'ACL_A_TOOLS', 'cat' => 'portal');
		$permissions['u_k_tools'] = array('lang' => 'ACL_U_TOOLS', 'cat' => 'portal');
		$event['permissions'] = $permissions;
	}

	public function add_portal_page_header_link($event)
	{
		global $user, $template, $phpbb_container, $phpbb_root_path, $web_path;

		//$this->user->add_lang_ext('phpbbireland/portal', 'kiss_common');

		$this->template->assign_vars(array(
			'U_PORTAL'            => $this->helper->route('phpbbireland/portal', 'portal_base_controller'),
			'U_INDEX'             => $this->helper->route('phpbb/phpbb', 'index'),

			'STARGATE'	          => true,
			'HS'                  => true,
			'S_HIGHSLIDE'         => true,
			'STARGATE_BUILD'      => (isset($config['portal_build'])) ? $config['portal_build'] : '',
			'STARGATE_VERSION'    => (isset($config['portal_version'])) ? $config['portal_version'] : '',
			'S_SHOW_RIGHT_BLOCKS' => true,
			'S_SHOW_LEFT_BLOCKS'  => true,
			'L_PORTAL'            => $user->lang['FORUM_PORTAL'],
			'U_PORTAL'            => append_sid("{$phpbb_root_path}portal.$this->php_ext"),
			'U_PORTAL_ARRANGE'    => append_sid("{$phpbb_root_path}portal.$this->php_ext", "arrange=1"),
			'U_HOME'              => append_sid("{$phpbb_root_path}portal.$this->php_ext"),
			'SITE_LOGO_IMG'       => $logo,
			'SITE_LOGO_IMG_RIGHT' => $logo_right,
		));
	}

	public function add_page_header_link($event)
	{
		$this->template->assign_vars(array(
			'U_PORTAL'  => $this->helper->route('phpbbireland/portal', 'portal_base_controller'),
		));
	}

	public function add_portal_page_footer_link($event)
	{
		global $user, $template, $phpbb_container, $phpbb_root_path, $web_path;

		$this->template->assign_vars(array(
			'U_PORTAL'            => $this->helper->route('phpbbireland/portal', 'portal_base_controller'),
			'U_INDEX'             => $this->helper->route('phpbb/phpbb', 'index'),
		));
	}

/*
	public function add_portal_header($event)
	{
		global $user, $phpbb_root_path, $php_ext;

		if ($event['on_page'][1] == 'app')
		{
			if (utf8_strpos($event['row']['session_page'], 'controller=portal') !== false)
			{
				$event['location_url'] = append_sid($phpbb_root_path . 'app.' . $php_ext, 'controller=portal');
				$event['location'] = $user->lang['PORTAL'];
			}
		}
	}
	public function add_portal_footer($event)
	{
		global $user, $phpbb_root_path, $php_ext;

		if ($event['on_page'][1] == 'app')
		{
			if (utf8_strpos($event['row']['session_page'], 'controller=portal') !== false)
			{
				$event['location_url'] = append_sid($phpbb_root_path . 'app.' . $php_ext, 'controller=portal');
				$event['location'] = $user->lang['PORTAL'];
			}
		}
	}
*/

	public function add_portal_blocks($event)
	{
		//var_dump('in: event\main_listener.php : add_portal_blocks()');

		global $auth, $config, $template, $user, $path_helper, $phpbb_root_path, $phpbb_container;
		global $queries, $cached_queries, $total_queries, $k_config, $k_blocks, $k_menus, $k_pages, $k_groups;

		/*
		// process for phpbb page only, return if portal page //
		$this_page = explode(".", $user->page['page']);
		if ($this_page[1] == 'potal')
		{
			return;
		}
		*/

		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->path_helper = $path_helper;
		$this->phpbb_root_path = $phpbb_root_path;

		$this_page = explode(".", $user->page['page']);
		if ($this_page[0] == 'index')
		{
			$this->page = 'index';
		}
		else if ($this_page[0] == 'app' && str_replace('php/', '', $this_page[1]) == 'portal')
		{
			$this->page = 'portal';
			return;
		}

		$board_url = generate_board_url() . '/';

		$phpbb_path_helper = $phpbb_container->get('path_helper');
		$corrected_path = $phpbb_path_helper->get_web_root_path();
		$web_path = (defined('PHPBB_USE_BOARD_URL_PATH') && PHPBB_USE_BOARD_URL_PATH) ? $board_url : $corrected_path;

		$this->includes_path = $phpbb_root_path . 'ext/phpbbireland/portal/includes/';

		// manually for now //
		$user->style['style_path'] = 'prosilver';

		// some euired paths //
		$mod_root_path           = $phpbb_root_path . 'ext/phpbbireland/portal/';
		$mod_root_image_path     = $mod_root_path . 'images/';

		$mod_root_js_path        = $mod_root_path . 'js/';
		$mod_root_jq_path        = $mod_root_path . 'js/jquery/';
		$js_file = $mod_root_jq_path . 'jquery-2.0.3.min.js';

		$mod_root_common_path    = $mod_root_path . 'styles/common/';
		$mod_common_images_path  = $mod_root_path . 'styles/common/theme/images/';
		$mod_user_template_path  = $mod_root_path . 'styles/'. $user->style['style_path'] . '/';
		$mod_user_jq_path        = $mod_root_path . 'styles/'. $user->style['style_path'] . '/template/js/';
		$mod_image_lang_path     = $mod_root_path . 'styles/'. $user->style['style_path'] . '/theme/' . $user->data['user_lang'];

		if (!$k_config)
		{
			include($phpbb_root_path . 'ext/phpbbireland/portal/includes/functions.' . $this->php_ext);
			$k_config = obtain_k_config();
			$k_blocks = obtain_block_data();
			$k_menus = obtain_k_menus();
			$k_pages = obtain_k_pages();
			$k_groups = obtain_k_groups();
			$k_resources = obtain_k_resources();
		}

		$this->includes_path = $phpbb_root_path . 'ext/phpbbireland/portal/includes/';

		include_once($this->includes_path . 'sgp_functions.' . $this->php_ext);

		$func = new \phpbbireland\portal\includes\func;
		$func->block_modules();

		if (!function_exists('phpbb_get_user_avatar'))
		{
			include($phpbb_root_path . 'includes/functions_display.'. $this->php_ext);
		}

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

		// Generate logged in/logged out status
		if ($user->data['user_id'] != ANONYMOUS)
		{
			$u_login_logout = append_sid("{$phpbb_root_path}ucp.$this->php_ext", 'mode=logout', true, $user->session_id);
			$l_login_logout = sprintf($user->lang['LOGOUT_USER'], $user->data['username']);
		}
		else
		{
			$u_login_logout = append_sid("{$phpbb_root_path}ucp.$this->php_ext", 'mode=login');
			$l_login_logout = $user->lang['LOGIN'];
		}

		if (!defined('STARGATE'))
		{
			define('STARGATE', true);
		}

		$this->template->assign_vars(array(
			'PAGE'                          => $this->page,
			'STARGATE'                      => true,
			'HS'                            => true,

			'JS_PATH'                       => $mod_root_jq_path,
			'JS_JQUERY'                     => $js_file,

			'T_STYLESHEET_PORTAL_COMMON'    => $mod_root_common_path . "/theme/portal_common.css",
			'U_PORTAL'                      => append_sid("{$mod_root_path}portal"),
			'U_PORTAL_ARRANGE'              => append_sid("{$mod_root_path}portal.$this->php_ext", "arrange=1"),
			'U_HOME'                        => append_sid("{$mod_root_path}portal.$this->php_ext"),
			'SITE_LOGO_IMG'                 => $logo,
			'SITE_LOGO_IMG_RIGHT'           => $logo_right,
			'MOD_COMMON_IMAGES_PATH'        => $mod_common_images_path,
			'MOD_IMAGE_LANG_PATH'           => $mod_image_lang_path,
			'MOD_ROOT_PATH'                 => $mod_root_path,
			'MOD_ROOT_IMAGE_PATH'           => $mod_root_image_path,
			'MOD_ROOT_JQ_PATH'              => $mod_root_jq_path,
			'MOD_USER_JQ_PATH'              => $mod_user_jq_path,
			'MOD_USER_TEMPLATE_PATH'        => $mod_user_template_path,
			'MOD_ROOT_JS_PATH'              => $mod_root_js_path,

			'S_HIGHSLIDE'                   => true,
			'COOKIE_NAME'                   => (isset($config['cookie_name'])) ? $config['cookie_name'] : '',
			'STARGATE_BUILD'                => (isset($config['portal_build'])) ? $config['portal_build'] : '',
			'STARGATE_VERSION'              => (isset($config['portal_version'])) ? $config['portal_version'] : '',
			'AVATAR_SMALL_IMG'              => (STARGATE) ? phpbb_get_avatar($user->data['user_avatar'], $user->data['user_avatar_type'], '35', '35') : '',
			'P_USERNAME'                    => (STARGATE) ? $user->data['username'] : '',
			'P_USERNAME_FULL'               => (STARGATE) ? get_username_string('full', $user->data['user_id'], $user->data['username'], $user->data['user_colour']) : '',

			'T_ASSETS_VERSION'              => $config['assets_version'],
			'T_ASSETS_PATH'                 => "{$web_path}assets",
			'T_THEME_PATH'                  => "{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/theme',
			'T_TEMPLATE_PATH'               => "{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/template',
			'T_SUPER_TEMPLATE_PATH'         => "{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/template',
			'T_IMAGES_PATH'                 => "{$web_path}images/",
			'T_SMILIES_PATH'                => "{$web_path}{$config['smilies_path']}/",
			'T_AVATAR_PATH'                 => "{$web_path}{$config['avatar_path']}/",
			'T_AVATAR_GALLERY_PATH'         => "{$web_path}{$config['avatar_gallery_path']}/",
			'T_ICONS_PATH'                  => "{$web_path}{$config['icons_path']}/",
			'T_RANKS_PATH'                  => "{$web_path}{$config['ranks_path']}/",
			'T_UPLOAD_PATH'                 => "{$web_path}{$config['upload_path']}/",
			'T_STYLESHEET_LINK'             => "{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/theme/stylesheet.css?assets_version=' . $config['assets_version'],
			'T_STYLESHEET_LANG_LINK'        => "{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/theme/' . $user->lang_name . '/stylesheet.css?assets_version=' . $config['assets_version'],
			'T_JQUERY_LINK'                 => !empty($config['allow_cdn']) && !empty($config['load_jquery_url']) ? $config['load_jquery_url'] : "{$web_path}assets/javascript/jquery.js?assets_version=" . $config['assets_version'],
			'S_ALLOW_CDN'                   => !empty($config['allow_cdn']),

			'T_THEME_NAME'                  => rawurlencode($user->style['style_path']),
			'T_THEME_LANG_NAME'             => $user->data['user_lang'],
			'T_TEMPLATE_NAME'               => $user->style['style_path'],
			'T_SUPER_TEMPLATE_NAME'         => rawurlencode((isset($user->style['style_parent_tree']) && $user->style['style_parent_tree']) ? $user->style['style_parent_tree'] : $user->style['style_path']),
			'T_IMAGES'                      => 'images',
			'T_SMILIES'                     => $config['smilies_path'],
			'T_AVATAR'                      => $config['avatar_path'],
			'T_AVATAR_GALLERY'              => $config['avatar_gallery_path'],
			'T_ICONS'                       => $config['icons_path'],
			'T_RANKS'                       => $config['ranks_path'],
			'T_UPLOAD'                      => $config['upload_path'],

			'U_LOGIN_LOGOUT'                => $u_login_logout,
			'L_LOGIN_LOGOUT'                => $l_login_logout,
		));
	}
	public function index_page($event)
	{
		/*
		if ($event['on_page'][1] == 'app')
		{
			if (strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/index') === 0)
			{

				//$event['location'] = $this->user->lang('BOARDRULES_VIEWONLINE');
				$event['location_url'] = $this->controller_helper->route('portal_main_controller');
			}
		}
		*/
	}

}
