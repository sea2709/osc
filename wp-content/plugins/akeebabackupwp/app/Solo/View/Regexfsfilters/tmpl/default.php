<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

use Awf\Text\Text;

defined('_AKEEBA') or die();

/** @var \Solo\View\Regexfsfilters\Html $this */

$router = $this->container->router;

echo $this->loadAnyTemplate('Common/error_modal');
?>

<div class="akeeba-block--info">
	<strong><?php echo Text::_('COM_AKEEBA_CPANEL_PROFILE_TITLE'); ?></strong>
	#<?php echo $this->profileid; ?> <?php echo $this->profilename; ?>
</div>

<div class="akeeba-panel--information">
    <div class="akeeba-form-section">
        <div class="akeeba-form--inline">
            <label><?php echo Text::_('COM_AKEEBA_FILEFILTERS_LABEL_ROOTDIR'); ?></label>
            <span id="ak_roots_container_tab">
		<?php echo $this->root_select; ?>
	    </span>
        </div>
    </div>
</div>

<div class="akeeba-container--primary">
    <div id="ak_list_container">
        <table id="table-container" class="akeeba-table--striped--dynamic-line-editor">
            <thead>
            <tr>
                <th width="120px">&nbsp;</th>
                <th width="250px"><?php echo Text::_('COM_AKEEBA_FILEFILTERS_LABEL_TYPE'); ?></th>
                <th><?php echo Text::_('COM_AKEEBA_FILEFILTERS_LABEL_FILTERITEM'); ?></th>
            </tr>
            </thead>
            <tbody id="ak_list_contents" class="table-container">
            </tbody>
        </table>
    </div>
</div>

<p></p>
