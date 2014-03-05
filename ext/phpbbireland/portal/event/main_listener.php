<?php

/**
*
* @package Portal Extension
* @copyright (c) 2013 Michael O’Toole (phpbbireland.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbireland\portal\event;

/**
* @ignore
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class main_listener implements EventSubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'	=> 'load_language_on_setup',
			'core.page_header'	=> 'generate_portal',
		);
	}

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;

	/* @var string phpEx */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper    $helper    Controller helper object
	* @param \phpbb\template             $template  Template object
	* @param \phpbb\user                 $user      User object
	* @param string                      $php_ext   phpEx
	*/
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user, $php_ext)
	{
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->php_ext = $php_ext;
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'phpbbireland/portal',
			'lang_set' => 'portal',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

/*
	public function add_page_header_link($event)
	{
		global $user, $template, $phpbb_container, $phpbb_root_path $web_path;

        $this->user->add_lang_ext('phpbbireland/portal', 'kiss_common');

        $this->template->assign_vars(array(
            'U_PORTAL'          => $this->helper->url('portal'),
			'STARGATE'	        => true,
			'HS'                => true,
			'S_HIGHSLIDE'       => true,
			'STARGATE_BUILD'    => (isset($config['portal_build'])) ? $config['portal_build'] : '',
			'STARGATE_VERSION'  => (isset($config['portal_version'])) ? $config['portal_version'] : '',

			'S_SHOW_RIGHT_BLOCKS' => true,
			'S_SHOW_LEFT_BLOCKS'  => true,
			'JS_PATH'             => $web_path . 'js',
			'JS_JQUERY'           => $web_path . 'js/jquery/jquery-2.0.3.min.js',
			//'DEBUG_QUERIES'       => (defined('DEBUG_QUERIES')) ? DEBUG_QUERIES : '',
			//'T_STYLESHEET_PORTAL_OVERLOAD'  => ($css) ? "{$phpbb_root_path}styles/" . $user->theme['theme_path'] . '/theme/portal_' . $css . '.css' : "{$phpbb_root_path}styles/" . $user->theme['theme_path'] . '/theme/portal.css',
			'T_STYLESHEET_PORTAL_COMMON'    => "{$phpbb_root_path}styles/_portal_common/theme/portal_common.css",

			'L_PORTAL'          => $user->lang['FORUM_PORTAL'],
			'U_PORTAL'          => append_sid("{$phpbb_root_path}portal.$this->php_ext"),
			'U_PORTAL_ARRANGE'  => append_sid("{$phpbb_root_path}portal.$this->php_ext", "arrange=1"),
			'U_HOME'            => append_sid("{$phpbb_root_path}portal.$this->php_ext"),

			'SITE_LOGO_IMG'        => $logo,
			'SITE_LOGO_IMG_RIGHT'  => $logo_right,
			'RANDOM_BACK_COLOR'    => $rand_color,
		));
	}

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

	public function generate_portal()
	{
		//var_dump('in function: generate_portal');

		global $auth, $config, $template, $user, $path_helper, $phpbb_root_path, $cache, $phpbb_container, $php_ext;
		global $queries, $cached_queries, $total_queries, $k_config, $k_blocks, $k_menus, $k_pages, $k_groups;

		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->path_helper = $path_helper;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->cache = $cache;

		$this->php_ext = 'php';

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
		$mod_root_jq_path        = $mod_root_path . 'js/jquery/';
		$mod_root_js_path        = $mod_root_path . 'js/';
		$mod_root_common_path    = $mod_root_path . 'styles/common/';
		$mod_common_images_path  = $mod_root_path . 'styles/common/theme/images/';
		$mod_user_template_path  = $mod_root_path . 'styles/'. $user->style['style_path'] . '/';
		$mod_user_jq_path        = $mod_root_path . 'styles/'. $user->style['style_path'] . '/template/js/';
		$mod_image_lang_path     = $mod_root_path . 'styles/'. $user->style['style_path'] . '/theme/' . $user->data['user_lang'];
		$js_version = 'jquery-2.0.3.min.js';


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

		$this->includes_path = $phpbb_root_path . 'ext/phpbbireland/portal/includes/';
		$mod_path = $phpbb_root_path . 'ext/phpbbireland/portal';

		include_once($this->includes_path . 'sgp_functions.' . $this->php_ext);
		include_once($this->includes_path . 'sgp_portal_blocks.' . $this->php_ext);

		if (!function_exists('phpbb_get_user_avatar'))
		{
			include($phpbb_root_path . 'includes/functions_display.'. $this->php_ext);
		}



// temp code //
$this_page = explode(".", $user->page['page']);
if ($this_page[0] == 'app')
{
	$this_page_name = $this_page[1];
	$this_page_name = str_replace('php/', '', $this_page_name);
}
else if ($this_page[0] == 'index')
{
	$this_page_name = $this_page[0];
}
$page_id = get_page_id($this_page_name);

//var_dump($this_page_name); var_dump($page_id);



		$cache_time = (isset($k_config['block_cache_time'])) ? $k_config['block_cache_time'] : '600';

		$set_time = time() + 31536000;
		$reset_time = time() - 31536000;
		$cookie_name = $cookie_value = $css = '';
		$cookie_name = $config['cookie_name'] . '_css';

		if (isset($_COOKIE[$cookie_name]))
		{
			$cookie_value = request_var($cookie_name, 0, false, true);
		}

		$css = request_var('css', 0);
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
			'PAGE'                          => $this_page_name,
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
			'AVATAR_SMALL_IMG'              => (STARGATE) ? get_user_avatar($user->data['user_avatar'], $user->data['user_avatar_type'], '35', '35') : '',
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
}
