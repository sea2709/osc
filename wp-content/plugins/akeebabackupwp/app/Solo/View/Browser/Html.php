<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

namespace Solo\View\Browser;


use Solo\Model\Browser;

class Html extends \Solo\View\Html
{
    public $folder;
    public $folder_raw;
    public $parent;
    public $exists;
    public $inRoot;
    public $openbasedirRestricted;
    public $writable;
    public $subfolders;
    public $breadcrumbs;

	/**
	 * Pull the folder browser data from the model
	 *
	 * @return  boolean
	 */
	public function onBeforeMain()
	{
		/** @var Browser $model */
		$model = $this->getModel();

		$this->folder                = $model->getState('folder', '', 'string');
		$this->folder_raw            = $model->getState('folder_raw', '', 'string');
		$this->parent                = $model->getState('parent', '', 'string');
		$this->exists                = $model->getState('exists', 0, 'boolean');
		$this->inRoot                = $model->getState('inRoot', 0, 'boolean');
		$this->openbasedirRestricted = $model->getState('openbasedirRestricted', 0, 'boolean');
		$this->writable              = $model->getState('writable', 0, 'boolean');
		$this->subfolders            = $model->getState('subfolders');
		$this->breadcrumbs           = $model->getState('breadcrumbs');

		return true;
	}
}
