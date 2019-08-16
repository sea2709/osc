<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

use Awf\Text\Text;
use Solo\Helper\Escape;
use Solo\Helper\FEFSelect;

defined('_AKEEBA') or die();

/** @var \Solo\View\Sysconfig\Html $this */

$config = $this->container->appConfig;
$router = $this->container->router;
$inCMS  = $this->container->segment->get('insideCMS', false);

?>
<div id="sysconfigUpdate" class="tab-pane">
	<?php if (defined('AKEEBABACKUP_PRO') && AKEEBABACKUP_PRO): ?>
		<div class="akeeba-form-group">
			<label for="update_dlid">
				<?php echo Text::_('COM_AKEEBA_CONFIG_DOWNLOADID_LABEL'); ?>
			</label>
			<input type="text" name="options[update_dlid]" id="update_dlid" placeholder="<?php echo Text::_('COM_AKEEBA_CONFIG_DOWNLOADID_LABEL'); ?>" value="<?php echo $config->get('options.update_dlid')?>">
			<p class="akeeba-help-text">
				<?php echo Text::_('COM_AKEEBA_CONFIG_DOWNLOADID_DESC') ?>
			</p>
		</div>
	<?php endif; ?>

	<div class="akeeba-form-group">
		<label for="minstability">
			<?php echo Text::_('SOLO_CONFIG_MINSTABILITY_LABEL'); ?>
		</label>
		<?php echo \Solo\Helper\Setup::minstabilitySelect($config->get('options.minstability', 'stable')); ?>
		<p class="akeeba-help-text">
			<?php echo Text::_('SOLO_CONFIG_MINSTABILITY_DESC') ?>
		</p>
	</div>

	<?php if ($inCMS): ?>
        <div class="akeeba-form-group">
            <label for="options_integratedupdate">
				<?php echo Text::_('SOLO_CONFIG_UPDATE_INTEGRATED_WP'); ?>
            </label>
            <div class="akeeba-toggle">
				<?php echo FEFSelect::booleanList('options[integratedupdate]',
					array('id' => 'options_integratedupdate', 'forToggle' => 1),
					$config->get('options.integratedupdate', 1)) ?>
            </div>
            <p class="akeeba-help-text">
				<?php echo Text::_('SOLO_CONFIG_UPDATE_INTEGRATED_WP_DESC') ?>
            </p>
        </div>
	<?php endif; ?>
</div>
