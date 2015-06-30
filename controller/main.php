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

		///var_dump('main.php > constructor');
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
		///var_dump('main.php > base() page = : ' . $page);

		global $phpbb_container;
		global $k_config, $k_menus, $k_blocks, $k_pages, $k_groups, $k_resources, $phpbb_root_path;//, $block_modules;

		$portal_link = $this->get_portal_link();

		$this->controller_helper->run_initial_tasks();
		$this->controller_helper->generate_all_block();

		$phpbb_path_helper = $phpbb_container->get('path_helper');
		$corrected_path = $phpbb_path_helper->get_web_root_path();

		$web_path = (defined('PHPBB_USE_BOARD_URL_PATH') && PHPBB_USE_BOARD_URL_PATH) ? $board_url : $corrected_path;

		//$mod_style_path	= $this->phpbb_root_path . 'ext/phpbbireland/portal/styles/' . $this->user->style['style_path'];

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

		//$this->template->assign_vars(array(
		//	'EXT_TEMPLATE_PATH'    => $mod_style_path,
		//));

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
			'body' => 'rules.html'
		));

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
