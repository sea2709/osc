<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

defined('_AKEEBA') or die();

/** @var \Solo\View\Main\Html $this */
/** @var \Solo\Model\Main $model */

$container = $this->getContainer();
$filePath = isset($filePath['changelogPath']) ? $filePath['changelogPath'] : null;

if (empty($filePath))
{
	$filePath = APATH_BASE . '/CHANGELOG.php';
}

$model = $this->getModel();
echo $model->coloriseChangelog($filePath, true);
