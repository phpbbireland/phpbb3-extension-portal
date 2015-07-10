<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbbireland\portal\acp;

class config_module
{
	var $u_action;

	function main($module_id, $mode)
	{
		global $db, $user, $auth, $template, $cache, $request;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$user->add_lang_ext('phpbbireland/portal', 'k_config');
		$this->tpl_name = 'acp_config';
		$this->page_title = $user->lang['ACP_CONFIG'];
		add_form_key('config');

		$action = $request->variable('action', '');
		$mode	= $request->variable('mode', '');
		$generate = $request->variable('generate', '');

		$data = $this->check_version();

		$submit = (isset($_POST['submit'])) ? true : false;

		$forum_id   = $request->variable('f', 0);
		$forum_data = $errors = array();

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('config'))
			{
				trigger_error('FORM_INVALID');
			}
		}

		$blocks_width 	= $config['blocks_width'];
		$blocks_enabled = $config['blocks_enabled'];
		$portal_version	= $config['portal_version'];
		$portal_build	= $config['portal_build'];

		if ($data['version'])
		{
			$template->assign_vars(array(
				'MOD_ANNOUNCEMENT'     => $data['announcement'][0],
				'MOD_CURRENT_VERSION'  => $config['portal_version'],
				'MOD_DOWNLOAD'         => $data['download'][0],
				'MOD_LATEST_VERSION'   => $data['version'],
				'MOD_TITLE'            => $data['title'][0],
				'S_UP_TO_DATE'         => ($data['version'] > $config['portal_version']) ? false : true,
			));

		}

		$template->assign_vars(array(
			'S_BLOCKS_WIDTH'    => $blocks_width,
			'S_BLOCKS_ENABLED'  => $blocks_enabled,
			'S_PORTAL_VERSION'  => $portal_version,
			'S_PORTAL_BUILD'    => $portal_build,
			'U_BACK'            => $this->u_action,
			'S_OPT'             => 'configure',
			'S_MOD_DATA'        => ($data['version']) ? true : false,
		));

		if ($submit)
		{
			$mode = 'save';
		}
		else
		{
			$mode = 'reset';
		}

		switch ($mode)
		{
			case 'save':
			{
				$config->set('blocks_width',	$request->variable('blocks_width', 180));
				$config->set('blocks_enabled',	$request->variable('blocks_enabled', 1));
				$config->set('portal_build',	$request->variable('portal_build', ''));

				$mode='reset';

				$template->assign_var('S_OPT', 'save');

				meta_refresh(0, $this->u_action);
				return;
			}

			case 'default':
			break;
		}
	}
	public function check_version()
	{
		global $db, $template;

		$url = 'phpbbireland.com';
		$sub = 'kiss2/updates';
		$file = 'portal.xml';

		$errstr = '';
		$errno = 0;

		$data = array();

		$data_read = get_remote_file($url, '/' . $sub, $file, $errstr, $errno);

		$mod = @simplexml_load_string(str_replace('&', '&amp;', $data_read));

		if (isset($mod->version_check))
		{
			$row = $mod->version_check;

			$version = $row->version->major[0] . '.' . $row->version->minor[0] . '.' . $row->version->revision[0];

			$data = array(
				'title'			=> $row->title[0],
				'description'	=> $row->description[0],
				'download'		=> $row->download,
				'announcement'	=> $row->announcement,
				'version'       => $version,
			);
			return($data);
		}
		return(null);
	}

}
