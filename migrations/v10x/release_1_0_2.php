<?php
/**
*
* @package migration
* @copyright (c) 2012 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*
*/

namespace phpbbireland\portal\migrations\v10x;

class release_1_0_2 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\phpbbireland\portal\migrations\v10x\release_1_0_1');
	}

	public function update_data()
	{
		return array(
			array('config.update', array('portal_build', '321-001')),
			array('custom', array(array($this, 'seed_db'))),
		);
	}

	public function seed_db()
	{
		$links_sql = array(
			array(
				'link'			=> 'github',
				'url'			=> 'github.com/phpbbireland/portal',
				'image'			=> 'github.gif',
				'active'		=> '1',
				'open_in_tab'	=> '1',
			),
		);
		$this->db->sql_multi_insert($this->table_prefix . 'k_link_images', $links_sql);
	}
}
