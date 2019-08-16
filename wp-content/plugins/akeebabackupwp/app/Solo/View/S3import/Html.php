<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

namespace Solo\View\S3import;


use Solo\Helper\Escape;
use Solo\Model\S3import;

class Html extends \Solo\View\Html
{
	public $s3access;
	public $s3secret;
	public $buckets;
	public $bucketSelect;
	public $contents;
	public $root;
	public $crumbs;

	public function onBeforeMain()
	{
		/** @var S3import $model */
		$model = $this->getModel();
		$model->getS3Credentials();

		// Assign variables
		$this->s3access 	= $model->getState('s3access');
		$this->s3secret 	= $model->getState('s3secret');
		$this->buckets 		= $model->getBuckets();
		$this->bucketSelect = $model->getBucketsDropdown();
		$this->contents 	= $model->getContents();
		$this->root 		= $model->getState('folder', '', 'raw');
		$this->crumbs 		= $model->getCrumbs();

		// Work around Safari which ignores autocomplete=off
		$escapedAccess = Escape::escapeJS($this->s3access);
		$escapedSecret = Escape::escapeJS($this->s3secret);

		$js = <<< JS
akeeba.loadScripts.push(function ()
{
	setTimeout(function(){
		document.getElementById('s3access').value = 'DummyData';
		document.getElementById('s3access').value = '$escapedAccess';

		document.getElementById('s3secret').value = 'DummyData';
		document.getElementById('s3secret').value = '$escapedSecret';
	}, 500);
});

JS;
		$this->getContainer()->application->getDocument()->addScriptDeclaration($js);

		return true;
	}

	public function onBeforeDownloadToServer()
	{
		$this->setLayout('downloading');
		$model = $this->getModel();

		$total  = $model->getState('totalsize', 0, 'int');
		$done   = $model->getState('donesize', 0, 'int');
		$part   = $model->getState('part', 0, 'int') + 1;
		$parts  = $model->getState('totalparts', 0, 'int');

		if ($total <= 0)
		{
			$percent = 0;
		}
		else
		{
			$percent = (int)(100 * ($done / $total));

			if ($percent < 0)
			{
				$percent = 0;
			}

			if ($percent > 100)
			{
				$percent = 100;
			}
		}

		$this->total        = $total;
		$this->done         = $done;
		$this->percent      = $percent;
		$this->total_parts  = $parts;
		$this->current_part = $part;

		$step = $model->getState('step', 1, 'int') + 1;
		$location = Escape::escapeJS($this->getContainer()->router->route('index.php?view=s3import&layout=downloading&task=downloadToServer&step=' . $step));
		$js = <<< JS
akeeba.loadScripts.push(function ()
{
	window.location = '$location';
});

JS;
		$this->container->application->getDocument()->addScriptDeclaration($js);

		return true;
	}
} 
