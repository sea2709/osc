<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

use Awf\Text\Text;

defined('_AKEEBA') or die();

/** @var \Solo\View\Discover\Html $this */

$router = $this->container->router;

$hasFiles = !empty($this->files);
$task = $hasFiles ? 'import' : 'main';
?>
<form name="adminForm" id="adminForm" action="<?php echo $router->route('index.php?view=discover&task=' . $task) ?>" method="POST" class="akeeba-form--horizontal--with-hidden" role="form">
	<?php if($hasFiles): ?>
	<div class="akeeba-panel--information akeeba-form--horizontal">
        <div class="akeeba-form-group">
            <label for="directory2"><?php echo Text::_('COM_AKEEBA_DISCOVER_LABEL_DIRECTORY') ?></label>
            <input type="text" name="directory2" id="directory2" value="<?php echo $this->directory ?>" disabled="disabled" size="70" />
        </div>
    </div>

	<div class="akeeba-form-group">
		<label for="files">
			<?php echo Text::_('COM_AKEEBA_DISCOVER_LABEL_FILES'); ?>
		</label>
        <select name="files[]" id="files" multiple="multiple" size="10">
			<?php foreach($this->files as $file): ?>
                <option value="<?php echo $this->escape(basename($file)); ?>"><?php echo $this->escape(basename($file)); ?></option>
			<?php endforeach; ?>
        </select>
        <p class="akeeba-help-text">
            <?php echo Text::_('COM_AKEEBA_DISCOVER_LABEL_SELECTFILES'); ?>
        </p>
	</div>

    <div class="akeeba-form-group--pull-right">
        <div class="akeeba-form-group--actions">
            <button class="akeeba-btn--primary" onclick="this.form.submit(); return false;">
                <span class="akion-ios-upload"></span>
                <?php echo Text::_('COM_AKEEBA_DISCOVER_LABEL_IMPORT') ?>
            </button>
        </div>
    </div>

	<?php else: ?>

        <div class="akeeba-panel--warning">
		<?php echo Text::_('COM_AKEEBA_DISCOVER_ERROR_NOFILES'); ?>
	</div>

        <p>
		<button onclick="this.form.submit(); return false;" class="akeeba-btn--orange"><?php echo Text::_('COM_AKEEBA_DISCOVER_LABEL_GOBACK') ?></button>
	</p>
	<?php endif; ?>

    <div class="akeeba-hidden-fields-container">
	    <?php if($hasFiles): ?>
            <input type="hidden" name="directory" value="<?php echo $this->directory ?>" />
	    <?php endif; ?>
        <input type="hidden" name="token" value="<?php echo $this->container->session->getCsrfToken()->getValue(); ?>" />
    </div>
</form>
