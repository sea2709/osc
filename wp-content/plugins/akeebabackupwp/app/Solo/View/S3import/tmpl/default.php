<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

use Awf\Text\Text;

defined('_AKEEBA') or die();

/** @var \Solo\View\S3import\Html $this */

$router = $this->container->router;

?>
<form name="adminForm" id="adminForm" action="<?php echo $router->route('index.php?view=s3import') ?>" method="POST"
      role="form">

    <div class="akeeba-hidden-fields-container">
        <input type="hidden" id="ak_s3import_folder" name="folder" value="<?php echo $this->root ?>" />
    </div>

    <div class="akeeba-panel--information">
        <div class="akeeba-form--inline">

            <div class="akeeba-form-group">
                <input type="text" size="40" name="s3access" id="s3access"
                       value="<?php echo $this->s3access ?>"
                       placeholder="<?php echo Text::_('COM_AKEEBA_CONFIG_S3ACCESSKEY_TITLE') ?>" />
            </div>
            <div class="akeeba-form-group">
                <input type="password" size="40" name="s3secret" id="s3secret"
                       value="<?php echo $this->s3secret ?>"
                       placeholder="<?php echo Text::_('COM_AKEEBA_CONFIG_S3SECRETKEY_TITLE') ?>" />
            </div>

	        <?php if(empty($this->buckets)): ?>
            <div class="akeeba-form-group">
                <button class="akeeba-btn--primary" type="submit" onclick="this.form.submit(); return false;">
                    <span class="akion-wifi"></span>
			        <?php echo Text::_('COM_AKEEBA_S3IMPORT_LABEL_CONNECT') ?>
                </button>
            </div>
	        <?php else: ?>
            <div class="akeeba-form-group">
		        <?php echo $this->bucketSelect ?>
            </div>
            <div class="akeeba-form-group">
                <button class="akeeba-btn--primary" type="submit" onclick="this.form.submit(); return false;">
                    <span class="akion-folder"></span>
			        <?php echo Text::_('COM_AKEEBA_S3IMPORT_LABEL_CHANGEBUCKET') ?>
                </button>
            </div>
	        <?php endif;?>
        </div>
	</div>

    <div class="akeeba-panel--information">
		<div id="ak_crumbs_container">
			<ul class="akeeba-breadcrumb">
				<li>
					<a href="<?php echo $router->route('index.php?view=s3import&task=main&folder=') ?>">
						<?php echo Text::_('SOLO_COMMON_LBL_ROOT'); ?>
					</a>
                    <span class="divider">/</span>
				</li>

				<?php if (!empty($this->crumbs)): ?>
					<?php $runningCrumb = '';
					$i                  = 0; ?>
					<?php if(!empty($this->crumbs)) foreach($this->crumbs as $crumb):?>
						<?php $runningCrumb .= $crumb . '/';
						$i++; ?>
                        <li>
                            <a href="<?php echo $router->route('index.php?view=s3import&task=main&folder=' . urlencode($runningCrumb)) ?>">
								<?php echo htmlentities($crumb) ?>
                            </a>
	                        <?php if ($i < count($this->crumbs)): ?>
                                <span class="divider">/</span>
	                        <?php endif; ?>
                        </li>
					<?php endforeach; ?>
                <?php endif; ?>
			</ul>
		</div>
	</div>

    <div class="akeeba-container--50-50">
        <div id="ak_folder_container" class="akeeba-panel--primary">
            <header class="akeeba-block-header">
                <h3>
                    <?php echo Text::_('COM_AKEEBA_FILEFILTERS_LABEL_DIRS'); ?>
                </h3>
            </header>

            <div id="folders">
                <?php if(!empty($this->contents['folders'])) foreach($this->contents['folders'] as $name => $record): ?>
                    <div class="folder-container">
                        <a href="<?php echo $router->route('index.php?view=s3import&task=main&folder=' . $record['prefix']) ?>">
                            <span class="akion-ios-folder"></span>
                            <?php echo basename($name); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="ak_files_container" class="akeeba-panel--primary">
            <header class="akeeba-block-header">
                <h3>
                    <?php echo Text::_('COM_AKEEBA_FILEFILTERS_LABEL_FILES'); ?>
                </h3>
            </header>
            <div id="files">
                <?php if(!empty($this->contents['files'])) foreach($this->contents['files'] as $name => $record): ?>
                    <div class="file-container">
                        <a href="<?php echo $router->route('index.php?view=s3import&task=downloadToServer&part=-1&frag=-1&layout=downloading&file=' . $name) ?>">
                            <span class="akion-document"></span>
                            <?php echo basename($record['name']); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</form>
