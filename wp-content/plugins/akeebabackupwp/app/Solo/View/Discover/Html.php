<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

namespace Solo\View\Discover;


use Akeeba\Engine\Factory;
use Awf\Text\Text;
use Awf\Utils\Template;
use Solo\Helper\Escape;
use Solo\Model\Discover;

class Html extends \Solo\View\Html
{
	/** @var	string	Output directory for importing archives */
	public $directory;

	/** @var 	string	List of files ready to be imported */
	public $files;

	/**
	 * Push state variables before showing the main page
	 *
	 * @return  boolean
	 */
	public function onBeforeMain()
	{
		// Load the necessary Javascript
		Template::addJs('media://js/solo/configuration.js', $this->container->application);

		/** @var Discover $model */
		$model = $this->getModel();

		$directory = $model->getState('directory', '', 'path');

		if (empty($directory))
		{
			$config          = Factory::getConfiguration();
			$this->directory = $config->get('akeeba.basic.output_directory', '[DEFAULT_OUTPUT]');
		}
		else
		{
			$this->directory = $directory;
		}

		$this->loadCommonJavascript();

		return true;
	}

	/**
	 * Push state variables before showing the discovery page
	 *
	 * @return  boolean
	 */
	public function onBeforeDiscover()
	{
		/** @var Discover $model */
		$model = $this->getModel();

		$directory = $model->getState('directory', '');
		$this->setLayout('discover');

		$files = $model->getFiles();

		$this->files     = $files;
		$this->directory = $directory;

		return true;
	}

	/**
	 * Load the common Javascript for this feature: language strings, image locations
	 */
	protected function loadCommonJavascript()
	{
		$strings                                   = array();
		$strings['COM_AKEEBA_CONFIG_UI_BROWSE']    = Escape::escapeJS(Text::_('SOLO_COMMON_LBL_ROOT'));

		$browserURL = Escape::escapeJS($this->getContainer()->router->route('index.php?view=browser&tmpl=component&processfolder=1&folder='));

		$js = <<< JS
		
var akeeba_browser_callback = null;

akeeba.loadScripts.push(function() {
		akeeba.Configuration.translations['COM_AKEEBA_CONFIG_UI_BROWSE'] = '{$strings['COM_AKEEBA_CONFIG_UI_BROWSE']}';

		akeeba.Configuration.URLs['browser'] = '$browserURL';
		
		akeeba.System.addEventListener('btnBrowse', 'click', function(e){
			var element = document.getElementById('directory');
			var folder = element.value;
			akeeba.Configuration.onBrowser(folder, element);
			e.preventDefault();
			return false;
		});
});

JS;

		$this->getContainer()->application->getDocument()->addScriptDeclaration($js);
	}

} 
