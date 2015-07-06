<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbbireland\portal;

class portal
{
	protected $page_title;

	protected $start;

	protected $portal;

	protected $k_config;

	/**
	* Constructor
	* NOTE: The parameters of this method must match in order and type with
	* the dependencies defined in the services.yml file for this service.
	*
	* @param \phpbb\auth				$auth				Auth object
	* @param \phpbb\cache\service		$cache				Cache object
	* @param \phpbb\config				$config				Config object
	* @param \phpbb\db\driver			$db					Database object
	* @param \phpbb\request				$request			Request object
	* @param \phpbb\template			$template			Template object
	* @param \phpbb\user				$user				User object
	* @param \phpbb\content_visibility	$content_visibility	Content visibility object
	* @param \phpbb\controller\helper	$helper				Controller helper object
	* @param string						$root_path			phpBB root path
	* @param string						$php_ext			phpEx
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\cache\service $cache, \phpbb\config\config $config, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, \phpbb\content_visibility $content_visibility, \phpbb\controller\helper $helper, $root_path, $php_ext) {
		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->content_visibility = $content_visibility;
		$this->helper = $helper;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;

		if (!class_exists('bbcode'))
		{
			include($this->root_path . 'includes/bbcode.' . $this->php_ext);
		}
		if (!function_exists('get_user_rank'))
		{
			include($this->root_path . 'includes/functions_display.' . $this->php_ext);
		}

		$this->user->add_lang_ext('phpbbireland/portal', 'kiss_common');
		$this->page_title = $this->user->lang['PORTAL'];
		$this->cache_setup();
	}

	public function cache_setup()
	{
		global $user, $auth, $config, $template, $user, $path_helper, $phpbb_root_path, $phpbb_container;
		global $k_config, $k_menus, $k_blocks, $k_pages, $k_groups, $k_resources;

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

	public function get_page_title()
	{
		return $this->page_title;
	}

	public function base()
	{
		// what do we do here???
		return;
	}
}
