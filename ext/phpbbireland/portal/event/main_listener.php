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
			'core.page_header'	=> 'add_portal_header',
			'core.page_body'	=> 'add_portal_center',
			'core.page_footer'	=> 'add_portal_footer',
			'core.page_header'	=> 'add_page_header_link',
		);

		/*
		return array(
			'core.user_setup'	=> 'load_language_on_setup',
			'core.page_header'	=> 'add_page_header_link',
			'core.page_header'	=> 'add_portal_header',
			'core.page_footer'	=> 'add_portal_footer',
		);
		*/
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
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user)
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

	public function add_page_header_link($event)
	{
		global $user, $template, $phpbb_container, $phpbb_root_path;

        $this->user->add_lang_ext('phpbbireland/portal', 'kiss_common');

        $this->template->assign_vars(array(
            'U_PORTAL'     => $this->helper->url('portal'),
			'STARGATE'	   => true,
			'HS'           => true,
			'S_HIGHSLIDE'  => true,
			'STARGATE_BUILD'		=> (isset($config['portal_build'])) ? $config['portal_build'] : '',
			'STARGATE_VERSION'		=> (isset($config['portal_version'])) ? $config['portal_version'] : '',



			'S_SHOW_RIGHT_BLOCKS' => true,
			'S_SHOW_LEFT_BLOCKS'  => true,
			'JS_PATH'                       => $web_path . 'js',
			'JS_JQUERY'                     => $web_path . 'js/jquery/jquery-2.0.3.min.js',
			'DEBUG_QUERIES'					=> (defined('DEBUG_QUERIES')) ? DEBUG_QUERIES : '',

			'T_STYLESHEET_PORTAL_OVERLOAD'	=> ($css) ? "{$phpbb_root_path}styles/" . $user->theme['theme_path'] . '/theme/portal_' . $css . '.css' : "{$phpbb_root_path}styles/" . $user->theme['theme_path'] . '/theme/portal.css',
			'T_STYLESHEET_PORTAL_COMMON'	=> "{$phpbb_root_path}styles/_portal_common/theme/portal_common.css",

			'L_PORTAL'			=> $user->lang['FORUM_PORTAL'],
			'U_PORTAL'			=> append_sid("{$phpbb_root_path}portal.$this->php_ext"),
			'U_PORTAL_ARRANGE'	=> append_sid("{$phpbb_root_path}portal.$this->php_ext", "arrange=1"),
			'U_HOME'			=> append_sid("{$phpbb_root_path}portal.$this->php_ext"),

			'SITE_LOGO_IMG'			=> $logo,
			'SITE_LOGO_IMG_RIGHT'	=> $logo_right,
			'RANDOM_BACK_COLOR'		=> $rand_color,
		));

	}

	public function add_overall_footer_content_after($event)
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
}
