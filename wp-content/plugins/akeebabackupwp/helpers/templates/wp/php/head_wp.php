<?php
/**
 * @package    akeebabackupwp
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

use Awf\Uri\Uri;

?>
<script type="text/javascript">
if (typeof Solo === 'undefined') { var Solo = {}; }
if (typeof akeeba.loadScripts === 'undefined') { akeeba.loadScripts = []; }
</script>
<?php
// This page can be included by Awf\Document\Html or directly from the boot_webapp script in case of error.
// In that case, we do not have a reference to $this, so we have to work around that
if (isset($this))
{
	$scripts = $this->getScripts();
	$scriptDeclarations = $this->getScriptDeclarations();
	$styles = $this->getStyles();
	$styleDeclarations = $this->getStyleDeclarations();
	$darkMode = $this->getContainer()->appConfig->get('darkmode', 0);
}
else
{
    /** @var \Awf\Container\Container $container */
	$scripts = $container->application->getDocument()->getScripts();
	$scriptDeclarations = $container->application->getDocument()->getScriptDeclarations();
	$styles = $container->application->getDocument()->getStyles();
	$styleDeclarations = $container->application->getDocument()->getStyleDeclarations();
	$darkMode = $container->appConfig->get('darkmode', 0);
}


AkeebaBackupWP::enqueueScript(Uri::base() . 'media/js/akjqnamespace.min.js');

// Scripts before the template ones
if(!empty($scripts)) foreach($scripts as $url => $params)
{
	if($params['before'])
	{
		AkeebaBackupWP::enqueueScript($url);
	}
}

$wpVersion = get_bloginfo('version', 'raw');

if (version_compare($wpVersion, '4.0', 'lt'))
{
	// Template scripts
	AkeebaBackupWP::enqueueScript(content_url() . '/js/jquery/jquery-migrate.js');
}
else
{
	AkeebaBackupWP::enqueueScript(includes_url() . '/js/jquery/jquery-migrate.js');
}

AkeebaBackupWP::enqueueScript(Uri::base() . 'media/js/fef/menu.min.js');
AkeebaBackupWP::enqueueScript(Uri::base() . 'media/js/fef/tabs.min.js');

// Scripts after the template ones
if(!empty($scripts)) foreach($scripts as $url => $params)
{
	if(!$params['before'])
	{
		AkeebaBackupWP::enqueueScript($url);
	}
}

// onLoad scripts
AkeebaBackupWP::enqueueScript(Uri::base() . 'media/js/solo/loadscripts.min.js');

// Script declarations
if (!empty($scriptDeclarations))
{
	foreach ($scriptDeclarations as $type => $content)
	{
		echo "\t<script type=\"$type\">\n$content\n</script>";
	}
}
// Hardcoded FEF initialization
?>
    <script type="text/javascript">window.addEventListener('DOMContentLoaded', function(event) { akeeba.fef.menuButton(); akeeba.fef.tabs(); });</script>
<?php


// CSS files before the template CSS
if (!empty($styles))
{
	foreach ($styles as $url => $params)
	{
		if ($params['before'])
		{
			AkeebaBackupWP::enqueueStyle($url);
		}
	}
}

AkeebaBackupWP::enqueueStyle(Uri::base() . 'media/css/fef-wp.min.css');

if ($darkMode)
{
	AkeebaBackupWP::enqueueStyle(Uri::base() . 'media/css/dark.min.css');
}

if (defined('AKEEBADEBUG') && AKEEBADEBUG && @file_exists(dirname(AkeebaBackupWP::$absoluteFileName) . '/app/media/css/theme.css'))
{
	AkeebaBackupWP::enqueueStyle(Uri::base() . 'media/css/theme.css');
}
else
{
	AkeebaBackupWP::enqueueStyle(Uri::base() . 'media/css/theme.min.css');
}

// CSS files before the template CSS
if (!empty($styles))
{
	foreach ($styles as $url => $params)
	{
		if (!$params['before'])
		{
			AkeebaBackupWP::enqueueStyle($url);
		}
	}
}

// Script declarations
if (!empty($styleDeclarations))
{
	foreach ($styleDeclarations as $type => $content)
	{
		echo "\t<style type=\"$type\">\n$content\n</style>";
	}
}
