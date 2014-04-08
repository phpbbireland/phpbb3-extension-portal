<?php

//namespace phpbbireland\portal\controller;

	function build_portal()
	{

		global $cache, $user, $auth, $config, $template, $path_helper, $phpbb_root_path, $php_ext, $phpbb_container;
		global $k_config, $k_menus, $k_blocks, $k_pages, $k_groups, $k_resources;

		define('STARGATE', true);
		$php_ext = 'php';

//var_dump($user->data);

		//$this->cache = $cache;

		// Determine board url - we may need it later
		$board_url = generate_board_url() . '/';
		// This path is sent with the base template paths in the assign_vars()
		// call below. We need to correct it in case we are accessing from a
		// controller because the web paths will be incorrect otherwise.
		$phpbb_path_helper = $phpbb_container->get('path_helper');
		$corrected_path = $phpbb_path_helper->get_web_root_path();
		$web_path = (defined('PHPBB_USE_BOARD_URL_PATH') && PHPBB_USE_BOARD_URL_PATH) ? $board_url : $corrected_path;



		// some euired paths //
		$mod_root_path           = $phpbb_root_path . 'ext/phpbbireland/portal/';
		$mod_root_image_path     = $mod_root_path . 'images/';
		$mod_root_jq_path        = $mod_root_path . 'js/jquery/';
		$mod_root_js_path        = $mod_root_path . 'js/';
		$mod_root_common_path    = $mod_root_path . 'styles/common/';
		$mod_common_images_path  = $mod_root_path . 'styles/common/theme/images/';
		$mod_user_template_path  = $mod_root_path . 'styles/prosiver/';
		$mod_user_jq_path        = $mod_root_path . 'styles/prosiver/template/js/';
		$mod_image_lang_path     = $mod_root_path . 'styles/prosiver/theme/' . $user->data['user_lang'];
		$js_version = 'jquery-2.0.3.min.js';

		// First we get the cache or rebuild it...
		if (!$k_config)
		{
			include($phpbb_root_path . 'ext/phpbbireland/portal/includes/functions.' . $php_ext);
			$k_config = obtain_k_config();
			$k_menus = obtain_k_menus();
			$k_blocks = obtain_block_data();
			$k_pages = obtain_k_pages();
			$k_groups = obtain_k_groups();
			$k_resources = obtain_k_resources();
		}


		$includes_path = $phpbb_root_path . 'ext/phpbbireland/portal/includes/';
		$mod_path = $phpbb_root_path . 'ext/phpbbireland/portal';

		global $user, $queries, $cached_queries, $total_queries, $k_config, $k_blocks, $k_menus, $k_pages, $k_groups;

		include_once($includes_path . 'sgp_functions.' . $php_ext);
		//include_once($this->includes_path . 'sgp_portal_blocks.' . $php_ext);

		if (!function_exists('phpbb_get_user_avatar'))
		{
			include($phpbb_root_path . 'includes/functions_display.'. $php_ext);
		}

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

		// support for stereo logos (where left and right images are mirror images) //
		// Introduced this for Startrek style but may use it in others... //
		$logo_right = $logo = sgp_get_rand_logo();
		$logo_right  = str_replace('logos', 'logos/right_images', $logo);

		// new random background image 10 January 2009 Mike //
		//$rand_image = get_random_image('images/rand_backgrounds', false, '', true);
		//$rand_color = basename($rand_image, ".jpg");

		$template->assign_vars(array(
			'PORTAL'=> true,
			'STARGATE'						=> true,
			'HS'                            => true,
			'JS_PATH'                       => $mod_root_jq_path,
			'JS_JQUERY'                     => $mod_root_jq_path . $js_version,
			'T_STYLESHEET_PORTAL_COMMON'	=> $mod_root_common_path . "/theme/portal_common.css",
			'U_PORTAL'			            => append_sid("{$mod_root_path}portal"),
			'U_PORTAL_ARRANGE'	            => append_sid("{$mod_root_path}portal.$php_ext", "arrange=1"),
			'U_HOME'			            => append_sid("{$mod_root_path}portal.$php_ext"),
			'SITE_LOGO_IMG'			        => $logo,
			'SITE_LOGO_IMG_RIGHT'	        => $logo_right,
			'MOD_COMMON_IMAGES_PATH'        => $mod_common_images_path,
			'MOD_IMAGE_LANG_PATH'           => $mod_image_lang_path,

			'MOD_ROOT_PATH'         => $mod_root_path,
			'MOD_ROOT_IMAGE_PATH'   => $mod_root_image_path,
			'MOD_ROOT_JQ_PATH'      => $mod_root_jq_path,
			'MOD_USER_JQ_PATH'      => $mod_user_jq_path,
			'MOD_USER_TEMPLATE_PATH'=> $mod_user_template_path,
			'MOD_ROOT_JS_PATH'      => $mod_root_js_path,

			'S_HIGHSLIDE'			=> true,
			'COOKIE_NAME'			=> (isset($config['cookie_name'])) ? $config['cookie_name'] : '',
			'STARGATE_BUILD'		=> (isset($config['portal_build'])) ? $config['portal_build'] : '',
			'STARGATE_VERSION'		=> (isset($config['portal_version'])) ? $config['portal_version'] : '',
			'AVATAR_SMALL_IMG'		=> (STARGATE) ? get_user_avatar($user->data['user_avatar'], $user->data['user_avatar_type'], '35', '35') : '',
			'P_USERNAME'			=> (STARGATE) ? $user->data['username'] : '',
			'P_USERNAME_FULL'		=> (STARGATE) ? get_username_string('full', $user->data['user_id'], $user->data['username'], $user->data['user_colour']) : '',

			'T_ASSETS_VERSION'		=> $config['assets_version'],
			'T_ASSETS_PATH'			=> "{$web_path}assets",
			'T_THEME_PATH'			=> "{$web_path}styles/" . rawurlencode('prosiver') . '/theme',
			'T_TEMPLATE_PATH'		=> "{$web_path}styles/" . rawurlencode('prosiver') . '/template',
			'T_SUPER_TEMPLATE_PATH'	=> "{$web_path}styles/" . rawurlencode('prosiver') . '/template',
			'T_IMAGES_PATH'			=> "{$web_path}images/",
			'T_SMILIES_PATH'		=> "{$web_path}{$config['smilies_path']}/",
			'T_AVATAR_PATH'			=> "{$web_path}{$config['avatar_path']}/",
			'T_AVATAR_GALLERY_PATH'	=> "{$web_path}{$config['avatar_gallery_path']}/",
			'T_ICONS_PATH'			=> "{$web_path}{$config['icons_path']}/",
			'T_RANKS_PATH'			=> "{$web_path}{$config['ranks_path']}/",
			'T_UPLOAD_PATH'			=> "{$web_path}{$config['upload_path']}/",
			'T_STYLESHEET_LINK'		=> "{$web_path}styles/" . rawurlencode('prosiver') . '/theme/stylesheet.css?assets_version=' . $config['assets_version'],
			'T_STYLESHEET_LANG_LINK'    => "{$web_path}styles/" . rawurlencode('prosiver') . '/theme/' . $user->lang_name . '/stylesheet.css?assets_version=' . $config['assets_version'],
			'T_JQUERY_LINK'			=> !empty($config['allow_cdn']) && !empty($config['load_jquery_url']) ? $config['load_jquery_url'] : "{$web_path}assets/javascript/jquery.js?assets_version=" . $config['assets_version'],
			'S_ALLOW_CDN'			=> !empty($config['allow_cdn']),

			'T_THEME_NAME'			=> rawurlencode('prosiver'),
			'T_THEME_LANG_NAME'		=> $user->data['user_lang'],
			'T_TEMPLATE_NAME'		=> 'prosiver',
			'T_SUPER_TEMPLATE_NAME'	=> rawurlencode((isset($user->style['style_parent_tree']) && $user->style['style_parent_tree']) ? $user->style['style_parent_tree'] : 'prosiver'),
			'T_IMAGES'				=> 'images',
			'T_SMILIES'				=> $config['smilies_path'],
			'T_AVATAR'				=> $config['avatar_path'],
			'T_AVATAR_GALLERY'		=> $config['avatar_gallery_path'],
			'T_ICONS'				=> $config['icons_path'],
			'T_RANKS'				=> $config['ranks_path'],
			'T_UPLOAD'				=> $config['upload_path'],

			//'DEBUG_QUERIES'					=> (defined('DEBUG_QUERIES')) ? DEBUG_QUERIES : '',
			//'T_STYLESHEET_PORTAL_OVERLOAD'	=> ($css) ? "{$phpbb_root_path}styles/" . $user->theme['theme_path'] . '/theme/portal_' . $css . '.css' : "{$phpbb_root_path}styles/" . $user->theme['theme_path'] . '/theme/portal.css',
			//'L_PORTAL'			=> $user->lang['PORTAL'],
			//'U_IMPRINT'			=> append_sid("{$phpbb_root_path}imprint.$this->php_ext"),
			//'U_DISCLAIMER'		=> append_sid("{$phpbb_root_path}disclaimer.$this->php_ext"),
			//'RANDOM_BACK'			=> $rand_image,
			//'RANDOM_BACK_COLOR'		=> $rand_color,
		));

8888888888888
		include($includes_path . 'sgp_portal_blocks.' . $php_ext);


	//include_once($phpbb_root_path . 'includes/functions.' . $phpEx);

	// Generate logged in/logged out status
	if ($user->data['user_id'] != ANONYMOUS)
	{
		$u_login_logout = append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=logout', true, $user->session_id);
		$l_login_logout = sprintf($user->lang['LOGOUT_USER'], $user->data['username']);
	}
	else
	{
		$u_login_logout = append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=login');
		$l_login_logout = $user->lang['LOGIN'];
	}

	$template->assign_vars(array(
		'U_LOGIN_LOGOUT'  => $u_login_logout,
		'L_LOGIN_LOGOUT'  => $l_login_logout,
	));


		/*
		// And now to output the page.
		page_header($this->user->lang('PORTAL'));

		$this->template->set_filenames(array(
			'body' => 'portal.html'
		));

		page_footer();
		return;
		*/
	}
