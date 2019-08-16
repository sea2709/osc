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
<form action="<?php echo $router->route('index.php?view=upload&task=upload&tmpl=component&id=' . $this->id) ?>" method="POST" name="akeebaform">
	<input type="hidden" name="part" value="<?php echo $this->part ?>" />
	<input type="hidden" name="frag" value="<?php echo $this->frag ?>" />
</form>

<div class="akeeba-panel--information">
    <p>
	    <?php if($this->frag == 0): ?>
            <?php echo Text::sprintf('COM_AKEEBA_TRANSFER_MSG_UPLOADINGPART',$this->part+1, $this->parts); ?>
	    <?php else: ?>
		    <?php echo Text::sprintf('COM_AKEEBA_TRANSFER_MSG_UPLOADINGFRAG',$this->part+1, $this->parts); ?>
	    <?php endif; ?>
    </p>
</div>


<script type="text/javascript" language="javascript">
akeeba.loadScripts.push(function ()
{
	window.setTimeout(document.forms.akeebaform.submit, 1000);
});
</script>
