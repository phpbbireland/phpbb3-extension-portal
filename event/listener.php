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
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var  */
	protected $controller_helper;

	protected $helper;

	/** @var \phpbb\path_helper */
	protected $path_helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string phpEx */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\config\config        $config phpBB config
	* @param \phpbb\controller\helper    $controller_helper        Controller helper object
	* @param \phpbb\path_helper          $path_helper   phpBB path helper
	* @param \phpbb\template\template    $template      Template object
	* @param \phpbb\user                 $user          User object
	* @param string                      $php_ext       phpEx
	* @return \phpbbireland\portal\event\listener
	* @access public
	*/
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\config\config $config,
		\phpbb\controller\helper $controller_helper, $helper,
		\phpbb\path_helper $path_helper,
		\phpbb\template\template $template,
		\phpbb\user $user, $php_ext)
	{
		///var_dump('listener.php > constructor');
		$this->auth = $auth;
		$this->config = $config;
		$this->controller_helper = $controller_helper;
		$this->helper = $helper;
		$this->path_helper = $path_helper;
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
		///var_dump('listener.php > getSubscribedEvents()');
		return array(
			'core.user_setup'			=> 'load_language_on_setup',
			//'core.page_header'			=> 'process_blocks_for_phpbb_pages',
			'core.page_header'			=> 'add_portal_link',
			//'core.page_header_after'	=> 'add_portal_left_blocks',
			//'core.page_footer'			=> 'add_portal_right_blocks',
			//'core.page_copyright_after'	=> 'add_portal_copyright',
			//'core.page_footer_after'	=> 'add_portal_scripts',

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
		///var_dump('listener.php > load_language_on_setup(...)');
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'phpbbireland/portal',
			'lang_set' => 'portal',
		);
		$event['lang_set_ext'] = $lang_set_ext;
		$this->user->add_lang_ext('phpbbireland/portal', 'kiss_common');
	}

	public function add_permission($event)
	{
		///var_dump('listener.php > add_permission(...)');
		$categories = $event['categories'];
		$categories['portal'] = 'ACL_CAT_PORTAL';
		$event['categories'] = $categories;

		$permissions = $event['permissions'];
		$permissions['a_k_portal'] = array('lang' => 'ACL_A_PORTAL', 'cat' => 'portal');
		$permissions['a_k_tools'] = array('lang' => 'ACL_A_TOOLS', 'cat' => 'portal');
		$permissions['u_k_tools'] = array('lang' => 'ACL_U_TOOLS', 'cat' => 'portal');
		$event['permissions'] = $permissions;
	}

	/**
	* Add portal link if user is authed to see it
	*
	* @return null
	*/
	public function add_portal_link($event)
	{
		///var_dump('listener.php > add_portal_links');
		global $phpbb_root_path;

		if (!$this->can_access_portal())
		{
			return;
		}

		$page = '';

		$portal_link = $this->get_portal_link();
		$portal_link = str_replace('/app.php', '', $portal_link);

		///var_dump('listener->get_portal_links' . '= ' . $portal_link);

		$mod_style_path	= $phpbb_root_path . 'ext/phpbbireland/portal/styles/' . $this->user->style['style_path'] . '/';

		/**
		*  we need some method of generating blocks for phpBB pages when not on portal page
		*  if we are on the portal the main controlle does this for us
		*/
		$page = $this->page_name();

		$this->template->assign_vars(array(
			'KISS'					=> true,
			'U_PORTAL'				=> $portal_link,
			'L_PORTAL'				=> $this->user->lang['PORTAL'],
			'EXT_TEMPLATE_PATH'		=> $mod_style_path,
			'PAGE'					=> $page,
			'S_SHOW_RIGHT_BLOCKS'	=> true,
			'S_SHOW_LEFT_BLOCKS'	=> true,
		));

		if ($page != 'portal')
		{
			$this->process_blocks_for_phpbb_pages();
		}
	}

	/**
	* Add portal link if user is authed to see it
	*
	* @return null
	*/

	public function add_portal_links($event)
	{
		///var_dump('listener.php > add_portal_links(...)');
		global $phpbb_container, $portal_link;

		$this->template->assign_vars(array(
			'U_PORTAL'            => $portal_link,
			'U_INDEX'             => $this->helper->route('phpbb/phpbb', 'index'),
			'STARGATE_BUILD'      => (isset($this->config['portal_build'])) ? $this->config['portal_build'] : '',
			'STARGATE_VERSION'    => (isset($this->config['portal_version'])) ? $this->config['portal_version'] : '',
			'S_SHOW_RIGHT_BLOCKS' => true,
			'S_SHOW_LEFT_BLOCKS'  => true,
			'L_PORTAL'            => $this->user->lang['FORUM_PORTAL'],
			'SITE_LOGO_IMG'       => $logo,
			'SITE_LOGO_IMG_RIGHT' => $logo_right,

		));
	}


	public function add_portal_left_blocks($event)
	{
		;
	}

	public function add_portal_right_blocks($event)
	{
		;
	}

	public function add_portal_scripts($event)
	{
		;
	}

	public function index_page($event)
	{
		;
	}

	public function process_blocks_for_phpbb_pages()
	{
		global $phpbb_container, $request, $phpbb_root_path, $user;
		global $queries, $cached_queries, $total_queries, $k_config, $k_blocks, $k_menus, $k_pages, $k_groups;

		if (!defined('KISS'))
		{
			define('KISS', true);
		}

		$this->includes_path = $phpbb_root_path . 'ext/phpbbireland/portal/includes/';

		$mod_root_path	= $phpbb_root_path . 'ext/phpbbireland/portal/';

		if (!isset($k_config))
		{
			include($phpbb_root_path . 'ext/phpbbireland/portal/includes/functions.' . $this->php_ext);
			$k_config = obtain_k_config();
			$k_blocks = obtain_block_data();
			$k_menus = obtain_k_menus();
			$k_pages = obtain_k_pages();
			$k_groups = obtain_k_groups();
			$k_resources = obtain_k_resources();
		}

		/* this is the ideal code as all calls to generate portal data can be handled by one file however the template files are not found with this method?
		$this->helper->run_initial_tasks();
		$this->helper->generate_all_block();
		*/

		$this->includes_path = $phpbb_root_path . 'ext/phpbbireland/portal/includes/';
		include_once($this->includes_path . 'sgp_functions.' . $this->php_ext);
		$func = new \phpbbireland\portal\includes\func;
		$func->process_block_modules();

		if (!function_exists('phpbb_get_user_avatar'))
		{
			include($phpbb_root_path . 'includes/functions_display.'. $this->php_ext);
		}

		$set_time = time() + 31536000;
		$reset_time = time() - 31536000;
		$cookie_name = $cookie_value = $css = '';
		$cookie_name = $this->config['cookie_name'] . '_css';

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
	}


	// get root to portal //
	public function get_portal_link()
	{
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

	// check access //
	protected function can_access_portal()
	{
		return $this->auth->acl_get('u_k_portal') && $this->config['portal_enabled'];
	}

	/**
	* Show users as viewing the portals on Who Is Online page
	*
	* @param object $event The event object
	* @return null
	*/
	public function viewonline_page($event)
	{
		if ($event['on_page'][1] == 'app' && strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/portal') === 0)
		{
			$event['location'] = $this->user->lang('VIEWING_PORTAL');
			$event['location_url'] = $this->controller_helper->route('phpbbireland_portal_controller');
		}
	}

	// get the current page name //
	public function page_name()
	{
		global $user;

		$this_page = explode(".", $user->page['page']);

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
