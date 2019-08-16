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
<div class="akeeba-panel--success">
	<?php echo Text::_('COM_AKEEBA_TRANSFER_MSG_DONE');?>
</div>

<script type="text/javascript" language="javascript">
	window.setTimeout(closeme, 3000);

	function closeme()
	{
		parent.akeeba.System.modalDialog.modal('hide');
	}
</script>
