<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

namespace Solo\View\Sysconfig;

use Awf\Mvc\Model;
use Awf\Utils\Template;
use Solo\Model\Main;

class Html extends \Solo\View\Html
{
	public $profileList;

	public function onBeforeMain()
	{
		/** @var Main $mainModel */
		$mainModel = Model::getTmpInstance($this->container->application_name, 'Main', $this->container);
		$this->profileList = $mainModel->getProfileList();

		$document = $this->container->application->getDocument();

		$buttons = array(
			array(
				'title' 	=> 'SOLO_BTN_SAVECLOSE',
				'class' 	=> 'akeeba-btn--green',
				'onClick'	=> 'akeeba.System.submitForm(\'adminForm\', \'save\')',
				'icon' 		=> 'akion-checkmark-circled'
			),
			array(
				'title' 	=> 'SOLO_BTN_SAVE',
				'class'		=> 'akeeba-btn--grey',
				'onClick' 	=> 'akeeba.System.submitForm(\'adminForm\', \'apply\')',
				'icon' 		=> 'akion-checkmark'
			),
			array(
				'title' 	=> 'SOLO_BTN_PHPINFO',
				'class' 	=> 'akeeba-btn--dark',
				'url' 		=> $this->container->router->route('index.php?view=phpinfo'),
				'icon' 		=> 'akion-information-circled'
			),
			array(
				'title' 	=> 'SOLO_BTN_CANCEL',
				'class' 	=> 'akeeba-btn--orange',
				'url' 		=> $this->container->router->route('index.php'),
				'icon' 		=> 'akion-close-circled'
			),
		);

		$toolbar = $document->getToolbar();

		foreach ($buttons as $button)
		{
			$toolbar->addButtonFromDefinition($button);
		}

		// Load Javascript
		Template::addJs('media://js/solo/setup.js', $this->container->application);

		$js = <<< JS
akeeba.loadScripts.push(function() {
	akeeba.Setup.init();
});

JS;

		$document = $this->container->application->getDocument();
		$document->addScriptDeclaration($js);

		return true;
	}
}
