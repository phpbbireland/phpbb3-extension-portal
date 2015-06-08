<?php
/**
*
* @package phpbbireland 1.0.2
* @copyright (c) 2013 phpbbireland
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* Generate all portal data for all pages...
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
		///var_dump('helper.php > constructor');

		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->path_helper = $path_helper;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->portal_helper = $portal_helper;
	}

	/**
	* Main block/module processing... grab all valid blocks and generate template etc
	*
	* @return null
	*/
	public function generate_all_block()
	{
		///var_dump('helper.php > generate_all_block()');

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

		$this->includes_path = $this->phpbb_root_path . 'ext/phpbbireland/portal/includes/';
		include_once($this->includes_path . 'sgp_functions.' . $this->php_ext);
		$func = new \phpbbireland\portal\includes\func;
		$func->process_block_modules();

	}

	/**
	* Check if user should can access this page, if not redirect to index
	*
	* @return null
	*/
	protected function check_permission()
	{
		///var_dump('helper.php > check_permission()');
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
		//var_dump('helper.php > get_portal_link()');
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
		///var_dump('helper.php > load_cache()');
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
		///var_dump('helper.php > run_initial_tasks()');

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
		$this_page = explode(".", $this->user->page['page']);

		///var_dump('helper.php > page_name(): ' . $this_page[0]);

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
