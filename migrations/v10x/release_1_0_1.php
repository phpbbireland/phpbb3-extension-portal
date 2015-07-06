<?php
/**
*
* @package migration
* @copyright (c) 2012 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*
*/

namespace phpbbireland\portal\migrations\v10x;

class release_1_0_1 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\phpbbireland\portal\migrations\v10x\release_1_0_0');
	}

	public function update_data()
	{
		return array(
			array('config.update', array('portal_version', '1.0.1')),
			array('config.update', array('portal_build', '310-002')),
			array('custom', array(array($this, 'seed_db'))),
		);
	}

	public function update_schema()
	{
		return array(

			'add_tables' => array(
				$this->table_prefix . 'k_link_images' => array(
					'COLUMNS' => array(
						'link'			=> array('VCHAR', ''),
						'url'			=> array('VCHAR', ''),
						'image'			=> array('VCHAR', ''),
						'active'		=> array('BOOL', '1'),
						'open_in_tab'	=> array('BOOL', '1'),
					),
					'PRIMARY_KEY'	=> 'link',
				),
			),
		);
	}


	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'k_link_images',
			),
		);
	}

	public function seed_db()
	{
		$links_sql = array(
			array(
				'link'			=> 'Kiss Portal',
				'url'			=> 'www.phpbbireland.com',
				'image'			=> 'www.phpbbireland.gif',
				'active'		=> '1',
				'open_in_tab'	=> '1',
			),
			array(
				'link'			=> 'phpBB',
				'url'			=> 'www.phpbb.com',
				'image'			=> 'www.phpbb.gif',
				'active'		=> '1',
				'open_in_tab'	=> '1',
			),
			array(
				'link'			=> 'sourceforge',
				'url'			=> 'sourceforge.net',
				'image'			=> 'sourceforge.gif',
				'active'		=> '1',
				'open_in_tab'	=> '1',
			),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'k_link_images', $links_sql);
	}
}
