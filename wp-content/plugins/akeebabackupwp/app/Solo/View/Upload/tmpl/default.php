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
<form action="<?php echo $router->route('index.php?view=upload&task=upload&tmpl=component&id=' . $this->id) ?>"
      method="POST" name="akeebaform" id="akeebaform">
    <input type="hidden" name="part" value="0"/>
    <input type="hidden" name="frag" value="0"/>
</form>

<div class="akeeba-panel--information">
    <p>
		<?php echo Text::_('COM_AKEEBA_TRANSFER_MSG_START') ?>
    </p>
</div>

<script type="text/javascript" language="javascript">
	akeeba.loadScripts.push(function ()
							{
								document.forms.akeebaform.submit();
							});
</script>
