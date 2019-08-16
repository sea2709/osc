<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

use Awf\Text\Text;

defined('_AKEEBA') or die();

/** @var \Solo\View\Extradirs\Html $this */

$router = $this->container->router;

echo $this->loadAnyTemplate('Common/error_modal');
echo $this->loadAnyTemplate('Common/folder_browser');
?>

<div class="akeeba-block--info">
	<strong><?php echo Text::_('COM_AKEEBA_CPANEL_PROFILE_TITLE'); ?></strong>
	#<?php echo $this->profileid; ?> <?php echo $this->profilename; ?>
</div>

<div class="akeeba-container--primary">
    <div id="ak_list_container">
        <table id="ak_list_table" class="akeeba-table--striped--dynamic-line-editor">
            <thead>
            <tr>
                <!-- Delete -->
                <th width="50px">&nbsp;</th>
                <!-- Edit -->
                <th width="100px">&nbsp;</th>
                <!-- Directory path -->
                <th>
						<span rel="popover" data-original-title="<?php echo Text::_('COM_AKEEBA_INCLUDEFOLDER_LABEL_DIRECTORY'); ?>"
                              data-content="<?php echo Text::_('COM_AKEEBA_INCLUDEFOLDER_LABEL_DIRECTORY_HELP'); ?>">
							<?php echo Text::_('COM_AKEEBA_INCLUDEFOLDER_LABEL_DIRECTORY'); ?>
						</span>
                </th>
                <!-- Directory path -->
                <th>
						<span rel="popover" data-original-title="<?php echo Text::_('COM_AKEEBA_INCLUDEFOLDER_LABEL_VINCLUDEDIR'); ?>"
                              data-content="<?php echo Text::_('COM_AKEEBA_INCLUDEFOLDER_LABEL_VINCLUDEDIR_HELP'); ?>">
							<?php echo Text::_('COM_AKEEBA_INCLUDEFOLDER_LABEL_VINCLUDEDIR'); ?>
						</span>
                </th>
            </tr>
            </thead>
            <tbody id="ak_list_contents">
            </tbody>
        </table>
    </div>
</div>
