<?php

/**
*
* @package NV Newspage Extension
* @copyright (c) 2013 phpbbireland
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbireland\portal\controller;

class portal
{
	/* @var \phpbb\config */
	protected $config;

	/* @var \phpbb\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbbireland\portal */
	protected $portal;

	/* @var string phpBB root path */
	protected $root_path;

	/* @var string phpEx */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\config				$config		Config object
	* @param \phpbb\template			$template	Template object
	* @param \phpbb\user				$user		User object
	* @param \phpbb\controller\helper	$helper		Controller helper object
	* @param \phpbbireland\portal		$portal		Portal object
	* @param string						$root_path	phpBB root path
	* @param string						$php_ext	phpEx
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\controller\helper $helper, \phpbbireland\portal\portal $portal, $root_path, $php_ext)
	{
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->helper = $helper;
		$this->portal = $portal;
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
	}

	/**
	* Portal controller to display multiple bocks dependant of the current page
	*
	* Route must be a sequence of the following substrings,
	* the order is mandatory:
	*	/news							[mandatory]
	*		/page/{page}				[optional]
	*
	* @param int	$page			Page to display
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function portal($page)
	{

		//$this->set_start(($page) * $this->config['portal_number']);

		return $this->base();
	}


	/**
	* Base controller to be accessed with the URL /portal/{id}
	*
	* @param	bool	$display_pagination		Force to hide the pagination
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function base($display_pagination = true)
	{
		$this->portal->generate_archive_list();
		if ($display_pagination)
		{
			$this->portal->generate_pagination();
		}

		$this->portal->base();
		$this->assign_images($this->config['news_user_info'], $this->config['news_post_buttons']);

		return $this->helper->render('portal_body.html', $this->portal->get_page_title());
	}

	public function assign_images($assign_user_buttons, $assign_post_buttons)
	{
		if ($assign_portal_buttons)
		{
			$this->template->assign_vars(array(
				'PORTAL_LOGO_IMG'	=> $this->user->img('icon_portal_logo', 'PORTAL_LOGO'),
			));
		}

		if ($assign_user_buttons)
		{
			$this->template->assign_vars(array(
				'PROFILE_IMG'		=> $this->user->img('icon_user_profile', 'READ_PROFILE'),
				'SEARCH_IMG'		=> $this->user->img('icon_user_search', 'SEARCH_USER_POSTS'),
				'PM_IMG'			=> $this->user->img('icon_contact_pm', 'SEND_PRIVATE_MESSAGE'),
				'EMAIL_IMG'			=> $this->user->img('icon_contact_email', 'SEND_EMAIL'),
				'WWW_IMG'			=> $this->user->img('icon_contact_www', 'VISIT_WEBSITE'),
				'ICQ_IMG'			=> $this->user->img('icon_contact_icq', 'ICQ'),
				'AIM_IMG'			=> $this->user->img('icon_contact_aim', 'AIM'),
				'MSN_IMG'			=> $this->user->img('icon_contact_msnm', 'MSNM'),
				'YIM_IMG'			=> $this->user->img('icon_contact_yahoo', 'YIM'),
				'JABBER_IMG'		=> $this->user->img('icon_contact_jabber', 'JABBER'),
			));
		}

		if ($assign_post_buttons)
		{
			$this->template->assign_vars(array(
				'QUOTE_IMG'			=> $this->user->img('icon_post_quote', 'REPLY_WITH_QUOTE'),
				'EDIT_IMG'			=> $this->user->img('icon_post_edit', 'EDIT_POST'),
				'DELETE_IMG'		=> $this->user->img('icon_post_delete', 'DELETE_POST'),
				'INFO_IMG'			=> $this->user->img('icon_post_info', 'VIEW_INFO'),
				'REPORT_IMG'		=> $this->user->img('icon_post_report', 'REPORT_POST'),
				'WARN_IMG'			=> $this->user->img('icon_user_warn', 'WARN_USER'),
			));
		}
	}
}
