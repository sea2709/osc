<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

use Awf\Text\Text;
use Awf\Html;
use Solo\Helper\FEFSelect;

defined('_AKEEBA') or die();

/** @var \Solo\View\Sysconfig\Html $this */

$config = $this->container->appConfig;
$router = $this->container->router;

?>
<div class="akeeba-form-group">
    <label for="backup-core-update">
		<?php echo Text::_('SOLO_SETUP_LBL_BACKUPONUPDATE_CORE'); ?>
    </label>
    <div class="akeeba-toggle">
		<?php echo FEFSelect::booleanList('options[backuponupdate_core_manual]', array('forToggle' => 1), $config->get('options.backuponupdate_core_manual', 1)) ?>
    </div>
    <p class="akeeba-help-text">
		<?php echo Text::_('SOLO_SETUP_LBL_BACKUPONUPDATE_CORE_DESC') ?>
    </p>
</div>
<div class="akeeba-form-group">
    <label for="backup-core-update">
		<?php echo Text::_('SOLO_SETUP_LBL_BACKUPONUPDATE_CORE_PROFILE'); ?>
    </label>
    <div class="akeeba-toggle">
		<?php echo Html\Select::genericList($this->profileList, 'options[backuponupdate_core_manual_profile]', array(), 'value', 'text',
                                            $config->get('options.backuponupdate_core_manual_profile', 1));
		?>
    </div>
    <p class="akeeba-help-text">
		<?php echo Text::_('SOLO_SETUP_LBL_BACKUPONUPDATE_CORE_PROFILE_DESC') ?>
    </p>
</div>
