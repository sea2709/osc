<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

use Awf\Text\Text;

defined('_AKEEBA') or die();

/** @var \Solo\View\Remotefiles\Html $this */

$router = $this->container->router;

?>
<div id="backup-percentage" class="akeeba-progress">
	<div id="progressbar-inner" class="akeeba-progress-fill" role="progressbar" aria-valuenow="<?php echo $this->percent ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $this->percent ?>%;">
	</div>
    <div class="akeeba-progress-status"><?php echo sprintf('%0.2f', $this->percent) ?>%</div>
</div>

<div class="akeeba-panel--information">
	<?php echo Text::sprintf('COM_AKEEBA_REMOTEFILES_LBL_DOWNLOADEDSOFAR', $this->done, $this->total, $this->percent); ?>
</div>

<form action="<?php echo $router->route('index.php?view=remorefiles&task=downloadToServer&tmpl=component')?>" name="adminForm" id="adminForm">
	<input type="hidden" name="id" value="<?php echo $this->id ?>"/>
	<input type="hidden" name="part" value="<?php echo $this->part ?>"/>
	<input type="hidden" name="frag" value="<?php echo $this->frag ?>"/>
</form>
