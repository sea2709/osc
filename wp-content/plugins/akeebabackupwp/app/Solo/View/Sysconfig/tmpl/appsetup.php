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

$timezone = $config->get('timezone', 'GMT');
$timezone = ($timezone == 'UTC') ? 'GMT' : $timezone;

/**
 * Remember to update wpcli/Command/Sysconfig.php in the WordPress application whenever this file changes.
 */
?>
<div class="akeeba-form-group">
	<label for="darkmode">
		<?php echo Text::_('SOLO_CONFIG_DISPLAY_DARKMODE_LABEL'); ?>
	</label>
	<div class="akeeba-toggle">
		<?php echo FEFSelect::booleanList('darkmode', array('forToggle' => 1), $config->get('darkmode', 0)) ?>
	</div>
	<p class="akeeba-help-text">
		<?php echo Text::_('SOLO_CONFIG_DISPLAY_DARKMODE_DESCRIPTION') ?>
	</p>
</div>


<div class="akeeba-form-group">
    <label for="useencryption">
		<?php echo Text::_('COM_AKEEBA_CONFIG_SECURITY_USEENCRYPTION_LABEL'); ?>
    </label>
    <div class="akeeba-toggle">
	    <?php echo FEFSelect::booleanList('useencryption', array('forToggle' => 1), $config->get('useencryption', 1)) ?>
    </div>
    <p class="akeeba-help-text">
		<?php echo Text::_('COM_AKEEBA_CONFIG_SECURITY_USEENCRYPTION_DESCRIPTION') ?>
    </p>
</div>

<?php // WordPress sets its own timezone. We use that value forcibly in our WP-specific Solo\Application\AppConfig (helpers/Solo/Application/AppConfig.php). Therefore we display it locked in WP. ?>
<div class="akeeba-form-group">
    <label for="timezone">
		<?php echo Text::_('SOLO_SETUP_LBL_TIMEZONE'); ?>
    </label>
	<?php echo \Solo\Helper\Setup::timezoneSelect($timezone, 'timezone', true, $inCMS); ?>
    <p class="akeeba-help-text">
		<?php echo Text::_($inCMS ? 'SOLO_SETUP_LBL_TIMEZONE_WORDPRESS' : 'SOLO_SETUP_LBL_TIMEZONE_HELP') ?>
    </p>
</div>

<div class="akeeba-form-group">
    <label for="localtime">
		<?php echo Text::_('COM_AKEEBA_CONFIG_BACKEND_LOCALTIME_LABEL'); ?>
    </label>
    <div class="akeeba-toggle">
	    <?php echo FEFSelect::booleanList('localtime', array('forToggle' => 1), $config->get('localtime', 1)) ?>
    </div>
    <p class="akeeba-help-text">
		<?php echo Text::_('COM_AKEEBA_CONFIG_BACKEND_LOCALTIME_DESC') ?>
    </p>
</div>

<div class="akeeba-form-group">
    <label for="timezonetext">
		<?php echo Text::_('COM_AKEEBA_CONFIG_BACKEND_TIMEZONETEXT_LABEL'); ?>
    </label>
	<?php echo \Solo\Helper\Setup::timezoneFormatSelect($config->get('timezonetext', 'T')); ?>
    <p class="akeeba-help-text">
		<?php echo Text::_('COM_AKEEBA_CONFIG_BACKEND_TIMEZONETEXT_DESC') ?>
    </p>
</div>

<div class="akeeba-form-group">
    <label for="forced_backup_timezone">
		<?php echo Text::_('COM_AKEEBA_CONFIG_FORCEDBACKUPTZ_LABEL'); ?>
    </label>
	<?php echo \Solo\Helper\Setup::timezoneSelect($config->get('forced_backup_timezone', 'AKEEBA/DEFAULT'), 'forced_backup_timezone', true); ?>
    <p class="akeeba-help-text">
		<?php echo Text::_('COM_AKEEBA_CONFIG_FORCEDBACKUPTZ_DESC') ?>
    </p>
</div>

<div class="akeeba-form-group">
    <label for="showDeleteOnRestore">
		<?php echo Text::_('COM_AKEEBA_CONFIG_BACKEND_SHOWDELETEONRESTORE_LABEL'); ?>
    </label>
    <div class="akeeba-toggle">
		<?php echo FEFSelect::booleanList('showDeleteOnRestore', array('forToggle' => 1), $config->get('showDeleteOnRestore', 0)) ?>
    </div>
    <p class="akeeba-help-text">
		<?php echo Text::_('COM_AKEEBA_CONFIG_BACKEND_SHOWDELETEONRESTORE_DESC') ?>
    </p>
</div>

<?php if (!$inCMS): ?>

    <div class="akeeba-form-group">
        <label for="live_site">
			<?php echo Text::_('SOLO_SETUP_LBL_LIVESITE'); ?>
        </label>
        <input type="text" name="live_site" id="live_site"
               value="<?php echo $config->get('live_site') ?>">
        <p class="akeeba-help-text">
			<?php echo Text::_('SOLO_SETUP_LBL_LIVESITE_HELP') ?>
        </p>
    </div>

    <div class="akeeba-form-group">
        <label for="session_timeout">
			<?php echo Text::_('SOLO_SETUP_LBL_SESSIONTIMEOUT'); ?>
        </label>
        <input type="text" name="session_timeout" id="session_timeout"
               value="<?php echo $config->get('session_timeout') ?>">
        <p class="akeeba-help-text">
			<?php echo Text::_('SOLO_SETUP_LBL_SESSIONTIMEOUT_HELP') ?>
        </p>
    </div>
<?php endif; ?>

<div class="akeeba-form-group">
    <label for="dateformat">
		<?php echo Text::_('COM_AKEEBA_CONFIG_DATEFORMAT_LABEL'); ?>
    </label>
    <input type="text" name="dateformat" id="dateformat"
           value="<?php echo $config->get('dateformat') ?>">
    <p class="akeeba-help-text">
		<?php echo Text::_('COM_AKEEBA_CONFIG_DATEFORMAT_DESC') ?>
    </p>
</div>

<div class="akeeba-form-group">
    <label for="stats_enabled">
		<?php echo Text::_('COM_AKEEBA_CONFIG_USAGESTATS_SOLO_LABEL'); ?>
    </label>
    <div class="akeeba-toggle">
	    <?php echo FEFSelect::booleanList('stats_enabled', array('forToggle' => 1), $config->get('stats_enabled', 1)) ?>
    </div>
    <p class="akeeba-help-text">
		<?php echo Text::_('COM_AKEEBA_CONFIG_USAGESTATS_SOLO_DESC') ?>
    </p>
</div>

<div class="akeeba-form-group">
    <label for="fs_driver">
		<?php echo Text::_('SOLO_SETUP_LBL_FS_DRIVER'); ?>
    </label>
	<?php echo \Solo\Helper\Setup::fsDriverSelect($config->get('fs.driver')); ?>
    <p class="akeeba-help-text">
		<?php echo Text::_('SOLO_SETUP_LBL_FS_DRIVER_HELP') ?>
    </p>
</div>

<div id="ftp_options">
    <div class="akeeba-form-group">
        <label for="fs_host">
			<?php echo Text::_('SOLO_SETUP_LBL_FS_FTP_HOST'); ?>
        </label>
        <input type="text" name="fs_host" id="fs_host"
               value="<?php echo $config->get('fs.host') ?>">
        <p class="akeeba-help-text">
			<?php echo Text::_('SOLO_SETUP_LBL_FS_FTP_HOST_HELP') ?>
        </p>
    </div>

    <div class="akeeba-form-group">
        <label for="fs_port">
			<?php echo Text::_('SOLO_SETUP_LBL_FS_FTP_PORT'); ?>
        </label>
        <input type="text" name="fs_port" id="fs_port"
               value="<?php echo $config->get('fs.port') ?>">
        <p class="akeeba-help-text">
			<?php echo Text::_('SOLO_SETUP_LBL_FS_FTP_PORT_HELP') ?>
        </p>
    </div>

    <div class="akeeba-form-group">
        <label for="fs_username">
			<?php echo Text::_('SOLO_SETUP_LBL_FS_FTP_USERNAME'); ?>
        </label>
        <input type="text" name="fs_username" id="fs_username"
               value="<?php echo $config->get('fs.username') ?>">
        <p class="akeeba-help-text">
			<?php echo Text::_('SOLO_SETUP_LBL_FS_FTP_USERNAME_HELP') ?>
        </p>
    </div>

    <div class="akeeba-form-group">
        <label for="fs_password">
			<?php echo Text::_('SOLO_SETUP_LBL_FS_FTP_PASSWORD'); ?>
        </label>
        <input type="password" name="fs_password" id="fs_password"
               value="<?php echo $config->get('fs.password') ?>">
        <p class="akeeba-help-text">
			<?php echo Text::_('SOLO_SETUP_LBL_FS_FTP_PASSWORD_HELP') ?>
        </p>
    </div>

    <div class="akeeba-form-group">
        <label for="fs_directory">
			<?php echo Text::_('SOLO_SETUP_LBL_FS_FTP_DIRECTORY'); ?>
        </label>
        <div class="akeeba-input-group">
            <input type="text" name="fs_directory" id="fs_directory"
                   value="<?php echo $config->get('fs.directory') ?>">
            <span class="akeeba-input-group-btn">
                <button title="<?php echo Text::_('COM_AKEEBA_CONFIG_UI_BROWSE') ?>"
                        class="akeeba-btn--dark" type="button" id="btnBrowse"
                        onclick="akeeba.Setup.initFtpSftpBrowser(); return false;">
                    <span class="akion-android-folder-open"></span>
                </button>
            </span>
        </div>
        <p class="akeeba-help-text">
			<?php echo Text::_('SOLO_SETUP_LBL_FS_FTP_DIRECTORY_HELP') ?>
        </p>
    </div>
</div>
