<?php
/*
Plugin Name: Akeeba Backup Professional for WordPress
Plugin URI: https://www.akeebabackup.com
Description: The complete backup solution for WordPress
Version: 3.5.1
Author: Akeeba Ltd
Author URI: https://www.akeebabackup.com
Network: true
License: GPLv3
*/

/**
 * @package    akeebabackupwp
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * You can contact Akeeba Ltd through our contact page:
 * https://www.akeebabackup.com/contact-us
 */

/**
 * Make sure we are being called from WordPress itself
 */
defined('WPINC') or die;

/**
 * This should never happen unless your site is broken! It'd mean that you're double loading our plugin which is not how
 * WordPress works. We still defend against this because we've learned to expect the unexpected ;)
 */
if (defined('AKEEBA_SOLOWP_PATH'))
{
	return;
}

// Preload our helper classes
require_once dirname(__FILE__) . '/helpers/AkeebaBackupWP.php';
require_once dirname(__FILE__) . '/helpers/AkeebaBackupWPUpdater.php';

// Initialization of our helper class
AkeebaBackupWP::preboot_initialization(__FILE__);

/**
 * Register public plugin hooks
 */
register_activation_hook(__FILE__, array('AkeebaBackupWP', 'install'));

/**
 * Register the plugin updater hooks (if necessary)
 */
AkeebaBackupWP::loadIntegratedUpdater();

/**
 * Register administrator plugin hooks
 */
if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX))
{
	add_action('admin_menu', array('AkeebaBackupWP', 'adminMenu'));
	add_action('network_admin_menu', array('AkeebaBackupWP', 'networkAdminMenu'));

	if (!AkeebaBackupWP::$wrongPHP)
	{
		add_action('init', array('AkeebaBackupWP', 'startSession'), 1);
		add_action('init', array('AkeebaBackupWP', 'loadJavascript'), 1);
		add_action('plugins_loaded', array('AkeebaBackupWP', 'fakeRequest'), 1);
		add_action('wp_logout', array('AkeebaBackupWP', 'endSession'));
		add_action('wp_login', array('AkeebaBackupWP', 'endSession'));
		add_action('in_admin_footer', array('AkeebaBackupWP', 'clearBuffer'));
		add_action('clear_auth_cookie', array('AkeebaBackupWP', 'onUserLogout'), 1);
	}
}

// Register WP-CLI commands
if (defined('WP_CLI') && WP_CLI)
{
	if (file_exists(__DIR__ . '/wpcli/register_commands.php'))
	{
		require_once __DIR__ . '/wpcli/register_commands.php';
	}
}
