<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

use Awf\Text\Text;

defined('_AKEEBA') or die();

/** @var \Solo\View\Upload\Html $this */

$router = $this->container->router;

?>
<div class="akeeba-panel--failure">
	<h3>
		<?php echo Text::_('COM_AKEEBA_TRANSFER_MSG_FAILED')?>
	</h3>
	<p>
		<?php echo $this->errorMessage; ?>
	</p>
</div>
