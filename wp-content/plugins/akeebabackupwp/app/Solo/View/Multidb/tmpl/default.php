<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

use Awf\Text\Text;

defined('_AKEEBA') or die();

// Used for type hinting
/** @var \Solo\View\Multidb\Html $this */

$router = $this->container->router;

$token = $this->container->session->getCsrfToken()->getValue();

echo $this->loadAnyTemplate('Common/error_modal');
?>

<div id="akEditorDialog" tabindex="-1" role="dialog" aria-labelledby="akEditorDialogLabel" aria-hidden="true" style="display:none;">
    <div class="akeeba-renderer-fef <?php echo $this->getContainer()->appConfig->get('darkmode', 0) ? 'akeeba-renderer-fef--dark' : '' ?>">
        <div class="akeeba-panel--primary">
            <header class="akeeba-block-header">
                <h3 id="akEditorDialogLabel">
					<?php echo Text::_('COM_AKEEBA_FILEFILTERS_EDITOR_TITLE'); ?>
                </h3>
            </header>

            <div id="akEditorDialogBody">
                <form class="akeeba-form--horizontal" id="ak_editor_table">
                    <div class="akeeba-form-group">
                        <label class="control-label" for="ake_driver"><?php echo Text::_('COM_AKEEBA_MULTIDB_GUI_LBL_DRIVER'); ?></label>
                        <select id="ake_driver">
                            <option value="mysqli">MySQLi</option>
                            <option value="mysql">MySQL (old)</option>
                            <option value="pdomysql">PDO MySQL</option>
                            <option value="sqlsrv">SQL Server</option>
                            <option value="sqlazure">Windows Azure SQL</option>
                            <option value="postgresql">PostgreSQL</option>
                        </select>
                    </div>

                    <div class="akeeba-form-group">
                        <label for="ake_host"><?php echo Text::_('COM_AKEEBA_MULTIDB_GUI_LBL_HOST'); ?></label>
                        <input id="ake_host" type="text" size="40" />
                    </div>

                    <div class="akeeba-form-group">
                        <label for="ake_port"><?php echo Text::_('COM_AKEEBA_MULTIDB_GUI_LBL_PORT'); ?></label>
                        <input id="ake_port" type="text" size="10" />
                    </div>

                    <div class="akeeba-form-group">
                        <label for="ake_username"><?php echo Text::_('COM_AKEEBA_MULTIDB_GUI_LBL_USERNAME'); ?></label>
                        <input id="ake_username" type="text" size="40" />
                    </div>

                    <div class="akeeba-form-group">
                        <label for="ake_password"><?php echo Text::_('COM_AKEEBA_MULTIDB_GUI_LBL_PASSWORD'); ?></label>
                        <input id="ake_password" type="password" size="40" />
                    </div>

                    <div class="akeeba-form-group">
                        <label for="ake_database"><?php echo Text::_('COM_AKEEBA_MULTIDB_GUI_LBL_DATABASE'); ?></label>
                        <input id="ake_database" type="text" size="40" />
                    </div>

                    <div class="akeeba-form-group">
                        <label for="ake_prefix"><?php echo Text::_('COM_AKEEBA_MULTIDB_GUI_LBL_PREFIX'); ?></label>
                        <input id="ake_prefix" type="text" size="10" />
                    </div>

                    <div class="akeeba-form-group--pull-right">
                        <div class="akeeba-form-group--actions">
                            <button type="button" class="akeeba-btn--dark" id="akEditorBtnDefault">
                                <span class="akion-ios-pulse-strong"></span>
								<?php echo Text::_('COM_AKEEBA_MULTIDB_GUI_LBL_TEST'); ?>
                            </button>

                            <button type="button" class="akeeba-btn--primary" id="akEditorBtnSave">
                                <span class="akion-checkmark"></span>
								<?php echo Text::_('COM_AKEEBA_MULTIDB_GUI_LBL_SAVE'); ?>
                            </button>

                            <button type="button" class="akeeba-btn--orange" id="akEditorBtnCancel">
                                <span class="akion-close"></span>
								<?php echo Text::_('COM_AKEEBA_MULTIDB_GUI_LBL_CANCEL'); ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="akeeba-block--info">
	<strong><?php echo Text::_('COM_AKEEBA_CPANEL_PROFILE_TITLE'); ?></strong>
	#<?php echo $this->profileid; ?> <?php echo $this->profilename; ?>
</div>

<div class="akeeba-panel--information">
    <div id="ak_list_container">
        <table id="ak_list_table" class="akeeba-table--striped--dynamic-line-editor">
            <thead>
            <tr>
                <th width="40px">&nbsp;</th>
                <th width="40px">&nbsp;</th>
                <th><?php echo Text::_('COM_AKEEBA_MULTIDB_LABEL_HOST'); ?></th>
                <th><?php echo Text::_('COM_AKEEBA_MULTIDB_LABEL_DATABASE'); ?></th>
            </tr>
            </thead>
            <tbody id="ak_list_contents">
            </tbody>
        </table>
    </div>
</div>
