<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

use Awf\Text\Text;

defined('_AKEEBA') or die();

/** @var   \Solo\View\Restore\Html $this */

$router = $this->getContainer()->router;
$token  = $this->getContainer()->session->getCsrfToken()->getValue();

echo $this->loadAnyTemplate('Common/ftp_browser');
echo $this->loadAnyTemplate('Common/ftp_test');
echo $this->loadAnyTemplate('Common/folder_browser');
?>

<form action="<?php echo $router->route('index.php?view=restore&task=start&id=' . $this->id) ?>" method="POST"
      name="adminForm" id="adminForm" class="akeeba-form--horizontal" role="form">
    <input type="hidden" name="token" value="<?php echo $token ?>">

    <h4><?php echo Text::_('COM_AKEEBA_RESTORE_LABEL_EXTRACTIONMETHOD'); ?></h4>

    <div class="akeeba-form-group">
        <label for="procengine">
			<?php echo Text::_('COM_AKEEBA_RESTORE_LABEL_EXTRACTIONMETHOD'); ?>
        </label>
	    <?php echo \Awf\Html\Select::genericList($this->extractionmodes, 'procengine', ['class' => 'form-control'], 'value', 'text', $this->ftpparams['procengine']); ?>
        <p class="akeeba-help-text">
		    <?php echo Text::_('COM_AKEEBA_RESTORE_LABEL_REMOTETIP'); ?>
        </p>
    </div>

	<?php if($this->getContainer()->appConfig->get('showDeleteOnRestore', 0) == 1): ?>
        <div class="akeeba-form-group">
            <label for="zapbefore">
				<?php echo Text::_('COM_AKEEBA_RESTORE_LABEL_ZAPBEFORE'); ?>
            </label>
            <div class="akeeba-toggle">
	            <?php echo \Solo\Helper\FEFSelect::booleanList('zapbefore', array('forToggle' => 1), 0) ?>
            </div>
            <p class="akeeba-help-text"><?php echo Text::_('COM_AKEEBA_RESTORE_LABEL_ZAPBEFORE_HELP'); ?></p>
        </div>
	<?php endif; ?>

    <?php if ($this->extension == 'jps'): ?>
    <h4><?php echo Text::_('COM_AKEEBA_RESTORE_LABEL_JPSOPTIONS'); ?></h4>

    <div class="akeeba-form-group">
        <label >
            <?php echo Text::_('COM_AKEEBA_CONFIG_JPS_KEY_TITLE') ?>
        </label>
        <input value="" type="password" class="form-control" id="jps_key" name="jps_key"
               placeholder="<?php echo Text::_('COM_AKEEBA_CONFIG_JPS_KEY_TITLE') ?>" autocomplete="off">
    </div>
    <?php endif; ?>

    <div id="ftpOptions">
        <h4><?php echo Text::_('COM_AKEEBA_RESTORE_LABEL_FTPOPTIONS'); ?></h4>

        <input id="ftp_passive_mode" type="checkbox" checked autocomplete="off" style="display: none">
        <input id="ftp_ftps" type="checkbox" autocomplete="off" style="display: none">

        <div class="akeeba-form-group">
            <label  for="ftp_host">
				<?php echo Text::_('COM_AKEEBA_CONFIG_DIRECTFTP_HOST_TITLE') ?>
            </label>

            <input id="ftp_host" name="ftp_host" value="<?php echo $this->ftpparams['ftp_host']; ?>"
                   type="text" class="form-control">
        </div>

        <div class="akeeba-form-group">
            <label  for="ftp_port">
				<?php echo Text::_('COM_AKEEBA_CONFIG_DIRECTFTP_PORT_TITLE') ?>
            </label>

            <input id="ftp_port" name="ftp_port" value="<?php echo $this->ftpparams['ftp_port']; ?>"
                   type="text" class="form-control">
        </div>

        <div class="akeeba-form-group">
            <label  for="ftp_user">
				<?php echo Text::_('COM_AKEEBA_CONFIG_DIRECTFTP_USER_TITLE') ?>
            </label>

            <input id="ftp_user" name="ftp_user" value="<?php echo $this->ftpparams['ftp_user']; ?>"
                   type="text" class="form-control">
        </div>

        <div class="akeeba-form-group">
            <label  for="ftp_pass">
				<?php echo Text::_('COM_AKEEBA_CONFIG_DIRECTFTP_PASSWORD_TITLE') ?>
            </label>

            <input id="ftp_pass" name="ftp_pass" value="<?php echo $this->ftpparams['ftp_pass']; ?>"
                   type="password" class="form-control">
        </div>

        <div class="akeeba-form-group">
            <label  for="ftp_root">
				<?php echo Text::_('COM_AKEEBA_CONFIG_DIRECTFTP_INITDIR_TITLE') ?>
            </label>

            <input id="ftp_root" name="ftp_root"
                   value="<?php echo $this->ftpparams['ftp_root']; ?>" type="text" class="form-control">
            <div class="akeXXeba-input-group">
                <div class="akeXXeba-input-group-btn" style="display: none;">
                    <button class="akeeba-btn--dark" id="ftp-browse" onclick="return false;">
                        <span class="akion-folder"></span>
			            <?php echo Text::_('COM_AKEEBA_CONFIG_UI_BROWSE'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

	<h4><?= Text::_('COM_AKEEBA_RESTORE_LABEL_TIME_HEAD') ?></h4>

	<div class="akeeba-form-group">
		<label for="min_exec">
			<?php echo Text::_('COM_AKEEBA_RESTORE_LABEL_MIN_EXEC'); ?>
		</label>
		<input type="number" min="0" max="180" name="min_exec" value="<?= $this->getModel()->getState('min_exec', 0, 'int') ?>"/>
		<p class="akeeba-help-text">
			<?php echo Text::_('COM_AKEEBA_RESTORE_LABEL_MIN_EXEC_TIP'); ?>
		</p>
	</div>
	<div class="akeeba-form-group">
		<label for="max_exec">
			<?php echo Text::_('COM_AKEEBA_RESTORE_LABEL_MAX_EXEC'); ?>
		</label>
		<input type="number" min="0" max="180" name="max_exec" value="<?= $this->getModel()->getState('max_exec', 5, 'int') ?>"/>
		<p class="akeeba-help-text">
			<?php echo Text::_('COM_AKEEBA_RESTORE_LABEL_MAX_EXEC_TIP'); ?>
		</p>
	</div>

	<hr/>

    <div class="akeeba-form-group--pull-right">
        <div class="akeeba-form-group--actions">
            <button class="akeeba-btn--primary" id="backup-start">
                <span class="akion-refresh"></span>
		        <?php echo Text::_('COM_AKEEBA_RESTORE_LABEL_START') ?>
            </button>
            <button class="akeeba-btn--grey" id="testftp" onclick="return false;">
                <span class="akion-ios-pulse-strong"></span>
		        <?php echo Text::_('COM_AKEEBA_CONFIG_DIRECTFTP_TEST_TITLE') ?>
            </button>
        </div>
    </div>

</form>
