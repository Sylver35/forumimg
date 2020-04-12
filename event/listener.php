<?php
/**
*
* @package phpBB Extension - Breizh Forum Images
* @copyright (c) 2020 Sylver35  https://breizhcode.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace sylver35\forumimg\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use phpbb\template\template;
use phpbb\config\config;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string phpEx */
	protected $php_ext;

	/**
	* Constructor
	*/
	public function __construct(template $template, config $config, $root_path, $php_ext)
	{
		$this->template		= $template;
		$this->config		= $config;
		$this->root_path	= $root_path;
		$this->php_ext		= $php_ext;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.acp_manage_forums_display_form'		=> 'add_forum_images',
		);
	}

	public function add_forum_images($event)
	{
		if (!function_exists('filelist'))
		{
			include($this->root_path . 'includes/functions_admin.' . $this->php_ext);
		}

		$list = array_values(filelist($this->root_path, $this->config['forumimg_dir'], 'gif|jpg|jpeg|png|webp|jp2|j2k|jpf|jpm|jpg2|j2c|jpc'));

		foreach ($list as $key => $img)
		{
			natcasesort($img);
			foreach ($img as $image)
			{
				$this->template->assign_block_vars('images', array(
					'IMAGE_SRC'	=> $image,
				));
			}
		}

		$this->template->assign_vars(array(
			'S_FORUM_IMAGE'	=> true,
		));

		$src = $event['template_data']['FORUM_IMAGE_SRC'];
		$event['template_data'] = array_merge($event['template_data'], array(
			'ROOT_PATH'				=> $this->root_path,
			'FORUM_IMAGE_PATH'		=> $this->config['forumimg_dir'],
			'FORUM_IMAGE_SRC'		=> ($src === '') ? $this->root_path . 'images/spacer.gif' : $src,
		));
	}
}
