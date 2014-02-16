<?php
/**
*
* @package Portal Extension 2.0
* @copyright (c) 2013 Michael O’Toole (phpbbireland.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbireland\portal\controller;

class main
{
	/**
	* Auth object
	* @var phpbb_auth
	*/
	private $auth;

	/**
	* phpBB Config object
	* @var phpbb_config_db
	*/
	private $config;

	/**
	* Template object
	* @var phpbb_template
	*/
	private $template;

	/**
	* User object
	* @var phpbb_user
	*/
	private $user;

	/**
	* phpBB root path
	* @var string
	*/
	private $phpbb_root_path;

	/**
	* PHP file extension
	* @var string
	*/
	private $php_ext;

	/**
	* Portal root path
	* @var string
	*/
	private $root_path;

	/**
	* Portal includes path
	* @var string
	*/
	private $includes_path;

	/**
	* phpBB path helper
	* @var \phpbb\path_helper
	*/
	protected $path_helper;

	/**
	* phpbbireland Block service collection
	* @var phpbb\di\service_collection
	*/
	public $blocks;

	/**
	* Constructor
	* NOTE: The parameters of this method must match in order and type with
	* the dependencies defined in the services.yml file for this service.
	*
	* @param phpbb_auth                    $auth             Auth object
	* @param phpbb_config_db               $config           phpBB Config object
	* @param phpbb_template                $template         Template object
	* @param phpbb_user                    $user             User object
	* @param \phpbb\controller\helper      $helper           Controller helper object
	* @param \phpbb\path_helper            $path_helper      phpBB path helper
	* @param string                        $phpbb_root_path  phpBB root path
	* @param string                        $php_ext          PHP file extension
	* @param \phpbb\di\service_collection  $blocks           phpbbireland blocks service collection
	*/

	public function __construct($auth, $config, $template, $user, $path_helper, $phpbb_root_path, $php_ext)
	{
		global $portal_root_path;
		global $phpbb_root_path;
		global $php_ext;
		global $template;
		global $user;

		if (!$php_ext) $php_ext = '.php';


		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->path_helper = $path_helper;
		$this->portal = $path_portal;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->register_modules($blocks);

		//var_dump($this->template);

		$this->includes_path = $phpbb_root_path . 'ext/phpbbireland/portal/includes/';
		$this->root_path = $phpbb_root_path . 'ext/phpbbireland/portal/';
		$portal_root_path = $this->root_path;

/*
		if (!function_exists('obtain_k_config'))
		{
			//include($this->includes_path . 'constants' . $this->php_ext);
			//include($this->includes_path . 'functions' . $this->php_ext);
		}
*/
	}

	/**
	* Extension front handler method. This is called automatically when your extension is accessed
	* through index.php?ext=example/foobar
	* @return null
	*/
	public function build_portal()
	{
		global $user, $auth, $config, $template, $user, $path_helper, $phpbb_root_path, $php_ext, $phpbb_container;
		global $k_config, $k_menus, $k_blocks, $k_pages, $k_groups, $k_resources;


		// Determine board url - we may need it later
		$board_url = generate_board_url() . '/';
		// This path is sent with the base template paths in the assign_vars()
		// call below. We need to correct it in case we are accessing from a
		// controller because the web paths will be incorrect otherwise.
		$phpbb_path_helper = $phpbb_container->get('path_helper');
		$corrected_path = $phpbb_path_helper->get_web_root_path();
		$web_path = (defined('PHPBB_USE_BOARD_URL_PATH') && PHPBB_USE_BOARD_URL_PATH) ? $board_url : $corrected_path;


		// First we get the cache or rebuild it...
		if(!$k_config)
		{
			include($phpbb_root_path . 'ext/phpbbireland/portal/includes/functions' . $php_ext);
			$k_config = obtain_k_config();
			$k_menus = obtain_k_menus();
			$k_blocks = obtain_block_data();
			$k_pages = obtain_k_pages();
			$k_groups = obtain_k_groups();
			$k_resources = obtain_k_resources(k_resources);
		}

		//var_dump($k_menus);
		//var_dump($k_groups);
		//var_dump($k_pages);

		//$this->check_permission();
		//$this->user->add_lang_ext('phpbbireland/portal', 'portal');

		$this->includes_path = $phpbb_root_path . 'ext/phpbbireland/portal/includes/';

		global $user, $queries, $cached_queries, $total_queries, $k_config, $k_blocks, $k_menus, $k_pages, $k_groups;

		include_once($this->includes_path . 'sgp_functions' . $php_ext);
		include_once($this->includes_path . 'sgp_portal_blocks' . $php_ext);

		if (!function_exists('phpbb_get_user_avatar'))
		{
			include($this->includes_path . 'functions_display'. $php_ext);
		}

		$cache_time = (isset($k_config['block_cache_time'])) ? $k_config['block_cache_time'] : '600';
//var_dump("{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/theme');
		$this->template->assign_vars(array(
			'ROOT_PATH'             => $this->root_path . 'js/jquery/',
			'S_HIGHSLIDE'			=> true,
			'COOKIE_NAME'			=> (isset($config['cookie_name'])) ? $config['cookie_name'] : '',
			'STARGATE_BUILD'		=> (isset($config['portal_build'])) ? $config['portal_build'] : '',
			'STARGATE_VERSION'		=> (isset($config['portal_version'])) ? $config['portal_version'] : '',

			'AVATAR_SMALL_IMG'		=> (STARGATE) ? get_user_avatar($user->data['user_avatar'], $user->data['user_avatar_type'], '35', '35') : '',
			'P_USERNAME'			=> (STARGATE) ? $user->data['username'] : '',
			'P_USERNAME_FULL'		=> (STARGATE) ? get_username_string('full', $user->data['user_id'], $user->data['username'], $user->data['user_colour']) : '',


			'T_PORTAL_THEME_PATH'	=> "{$web_path}styles/_portal_common/theme",


		'T_ASSETS_VERSION'		=> $config['assets_version'],
		'T_ASSETS_PATH'			=> "{$web_path}assets",
		'T_THEME_PATH'			=> "{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/theme',
		'T_TEMPLATE_PATH'		=> "{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/template',
		'T_SUPER_TEMPLATE_PATH'	=> "{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/template',
		'T_IMAGES_PATH'			=> "{$web_path}images/",
		'T_SMILIES_PATH'		=> "{$web_path}{$config['smilies_path']}/",
		'T_AVATAR_PATH'			=> "{$web_path}{$config['avatar_path']}/",
		'T_AVATAR_GALLERY_PATH'	=> "{$web_path}{$config['avatar_gallery_path']}/",
		'T_ICONS_PATH'			=> "{$web_path}{$config['icons_path']}/",
		'T_RANKS_PATH'			=> "{$web_path}{$config['ranks_path']}/",
		'T_UPLOAD_PATH'			=> "{$web_path}{$config['upload_path']}/",
		'T_STYLESHEET_LINK'		=> "{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/theme/stylesheet.css?assets_version=' . $config['assets_version'],
		'T_STYLESHEET_LANG_LINK'    => "{$web_path}styles/" . rawurlencode($user->style['style_path']) . '/theme/' . $user->lang_name . '/stylesheet.css?assets_version=' . $config['assets_version'],
		'T_JQUERY_LINK'			=> !empty($config['allow_cdn']) && !empty($config['load_jquery_url']) ? $config['load_jquery_url'] : "{$web_path}assets/javascript/jquery.js?assets_version=" . $config['assets_version'],
		'S_ALLOW_CDN'			=> !empty($config['allow_cdn']),

		'T_THEME_NAME'			=> rawurlencode($user->style['style_path']),
		'T_THEME_LANG_NAME'		=> $user->data['user_lang'],
		'T_TEMPLATE_NAME'		=> $user->style['style_path'],
		'T_SUPER_TEMPLATE_NAME'	=> rawurlencode((isset($user->style['style_parent_tree']) && $user->style['style_parent_tree']) ? $user->style['style_parent_tree'] : $user->style['style_path']),
		'T_IMAGES'				=> 'images',
		'T_SMILIES'				=> $config['smilies_path'],
		'T_AVATAR'				=> $config['avatar_path'],
		'T_AVATAR_GALLERY'		=> $config['avatar_gallery_path'],
		'T_ICONS'				=> $config['icons_path'],
		'T_RANKS'				=> $config['ranks_path'],
		'T_UPLOAD'				=> $config['upload_path'],




		));

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

		include($this->includes_path . 'sgp_functions' . $this->php_ext);
		include($this->includes_path . 'sgp_portal_blocks' . $this->php_ext);

		// support for stereo logos (where left and right images are mirror images) //
		// Introduced this for Startrek style but may use it in others... //
		$logo_right = $logo = sgp_get_rand_logo();
		$logo_right  = str_replace('logos', 'logos/right_images', $logo);

		// new random background image 10 January 2009 Mike //
		//$rand_image = get_random_image('images/rand_backgrounds', false, '', true);
		$rand_color = basename($rand_image, ".jpg");

		$this->template->assign_vars(array(
			'STARGATE'						=> true,
			'HS'                            => true,
			'JS_PATH'                       => $web_path . 'js',
			'JS_JQUERY'                     => $web_path . 'js/jquery/jquery-2.0.3.min.js',
			'DEBUG_QUERIES'					=> (defined('DEBUG_QUERIES')) ? DEBUG_QUERIES : '',

			'T_STYLESHEET_PORTAL_OVERLOAD'	=> ($css) ? "{$phpbb_root_path}styles/" . $user->theme['theme_path'] . '/theme/portal_' . $css . '.css' : "{$phpbb_root_path}styles/" . $user->theme['theme_path'] . '/theme/portal.css',
			'T_STYLESHEET_PORTAL_COMMON'	=> "{$phpbb_root_path}styles/_portal_common/theme/portal_common.css",

			'L_PORTAL'			=> $user->lang['FORUM_PORTAL'],
			'U_PORTAL'			=> append_sid("{$phpbb_root_path}portal.$this->php_ext"),
			'U_PORTAL_ARRANGE'	=> append_sid("{$phpbb_root_path}portal.$this->php_ext", "arrange=1"),
			'U_HOME'			=> append_sid("{$phpbb_root_path}portal.$this->php_ext"),
			'U_IMPRINT'			=> append_sid("{$phpbb_root_path}imprint.$this->php_ext"),
			'U_DISCLAIMER'		=> append_sid("{$phpbb_root_path}disclaimer.$this->php_ext"),

			'SITE_LOGO_IMG'			=> $logo,
			'SITE_LOGO_IMG_RIGHT'	=> $logo_right,
			//'RANDOM_BACK'			=> $rand_image,
			'RANDOM_BACK_COLOR'		=> $rand_color,
		));

		// And now to output the page.
		page_header($this->user->lang('PORTAL'));

		$this->template->set_filenames(array(
			'body' => 'portal.html'
		));

		page_footer();
		return;
	}

	// check if user should be able to access this page
	private function check_permission()
	{return;
		//if (!isset($this->config['portal_enabled']) || !$this->auth->acl_get('u_view_portal'))
		if (!isset($this->config['portal_enabled']))
		{
			redirect(append_sid($this->phpbb_root_path . 'index' . $this->php_ext));
		}
	}

	public function build()
	{
		$this->check_permission();

		$this->user->add_lang_ext('phpbbireland/portal', 'portal');
	}
	/**
	* Register list of phpbbireland Portal blocks
	*
	* @param \phpbb\di\service_collection    $blocks    phpbbireland blocks service collection
	* @return null
	*/

	protected function register_modules($blocks)
	{
		return;
		foreach ($blocks as $current_module)
		{
			$class_name = '\\' . get_class($current_module);
			if (!isset($this->blocks[$class_name]))
			{
				$this->blocks[$class_name] = $current_module;
			}
		}
	}
}
