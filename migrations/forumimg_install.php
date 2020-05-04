<?php
/**
*
* @package phpBB Extension - Breizh Forum Images
* @copyright (c) 2020 Sylver35  https://breizhcode.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace sylver35\forumimg\migrations;
use phpbb\db\migration\migration;

class forumimg_install extends migration
{
	public function effectively_installed()
	{
		return isset($this->config['forumimg_dir']);
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v32x\v328');
	}

	public function update_data()
	{
		return array(
			// Config
			array('config.add', array('forumimg_dir', 'images/forum_img/')),
			// Add upload directory
			array('custom', array(array($this, 'forum_img_directory'))),
		);
	}

	public function forum_img_directory()
	{
		$dir = 'images/forum_img/';

		if (!is_dir($this->phpbb_root_path . $dir))
		{
			mkdir($this->phpbb_root_path . $dir, 0755);
			chmod($this->phpbb_root_path . $dir, 0755);
		}
	}
}
