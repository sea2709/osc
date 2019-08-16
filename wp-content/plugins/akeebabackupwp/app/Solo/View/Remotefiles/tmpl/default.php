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
<h2>
    <?php echo Text::_('COM_AKEEBA_REMOTEFILES') ?>
</h2>

<?php if (empty($this->actions)): ?>
	<div class="akeeba-block--failure">
		<h3>
			<?php echo Text::_('COM_AKEEBA_REMOTEFILES_ERR_NOTSUPPORTED_HEADER') ?>
		</h3>

		<p>
			<?php echo Text::_('COM_AKEEBA_REMOTEFILES_ERR_NOTSUPPORTED'); ?>
		</p>
	</div>
<?php else: ?>

	<div id="cpanel">
		<?php foreach ($this->actions as $action): ?>
			<?php if ($action['type'] == 'button'): ?>
				<button class="akeeba-btn <?php echo $action['class'] ?>"
						onclick="window.location = '<?php echo $router->route($action['link']) ?>'; return false;">
					<span class="<?php echo $action['icon'] ?>"></span>
					<?php echo $action['label']; ?>
				</button>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<div style="clear: both;"></div>

	<h3>
		<?php echo Text::_('COM_AKEEBA_REMOTEFILES_LBL_DOWNLOADLOCALLY') ?>
	</h3>
	<?php $items = 0;
	foreach ($this->actions as $action): ?>
		<?php if ($action['type'] == 'link'): ?>
			<?php $items++ ?>
			<a href="<?php echo $router->route($action['link']) ?>" class="akeeba-btn--small--grey">
				<span class="<?php echo $action['icon'] ?>"></span>
				<?php echo $action['label'] ?>
			</a>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php if (!$items): ?>
		<p class="akeeba-block--info">
			<?php echo Text::_('COM_AKEEBA_REMOTEFILES_LBL_NOTSUPPORTSLOCALDL') ?>
		</p>
	<?php endif; ?>

<?php endif; ?>
