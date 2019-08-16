<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

use Awf\Text\Text;

defined('_AKEEBA') or die();

/** @var \Solo\View\S3import\Html $this */

$router = $this->container->router;

?>
<style type="text/css">
	dl { display: none; }
</style>

<div id="backup-percentage" class="akeeba-progress">
    <div id="progressbar-inner" class="akeeba-progress-fill" style="width: <?php echo (int) $this->percent; ?>%"></div>
    <div class="akeeba-progress-status">
		<?php echo (int) $this->percent; ?>%
    </div>
</div>

<div class="akeeba-panel--information">
    <p>
        <?php echo Text::sprintf('COM_AKEEBA_REMOTEFILES_LBL_DOWNLOADEDSOFAR', $this->done, $this->total, $this->percent); ?>
    </p>
</div>
