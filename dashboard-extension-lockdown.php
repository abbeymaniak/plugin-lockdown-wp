<?php

namespace Abbeymaniak\DashboardExtensionLockdown;

use Abbeymaniak\DashboardExtensionLockdown\Dashboard_Extension_Lockdown;
use Abbeymaniak\DashboardExtensionLockdown\Dashboard_Extension_Lockdown_Settings;
use Abbeymaniak\DashboardExtensionLockdown\Dashboard_Extension_Lockdown_Admin_UI;

/**
 *  Dashboard Extension Lockdown
 *
 * @package         Dashboard_Extension_Lockdown
 * @category        Plugin
 * @author          Abiodun Paul Ogunnaike <primastech101@gmail.com>
 * @license         http://www.gnu.org/licenses/gpl-2.0.txt GPLv2 or later
 * @link            https://github.com/abbeymaniak/dashboard-extension-lockdown
 *
 * Plugin Name:     Dashboard Extension Lockdown
 * Plugin URI:      https://digital.celebrateme.io/product/dashboard-extension-lockdown/
 * Description:     Dashboard Extension Lockdown allows you to control access to plugin features based on user roles.
 * Author:          Abiodun Paul Ogunnaike
 * Author URI:      https://github.com/abbeymaniak
 * Text Domain:     dashboard-extension-lockdown
 * Donate:          https://www.buymeacoffee.com/abbeymaniak
 * Domain Path:     /languages
 * Version:         1.0.1
 * prefix:          dashboard_extension_lockdown
 * License:         GPL v2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Requires PHP:      8.1
 * Requires at least: 6.0
 *
 */

// If the file is accessed directly abort script.
defined('ABSPATH') || die('Unauthorized Access');


// define constants
define('DASHBOARD_EXTENSION_LOCKDOWN_PATH', plugin_dir_path(__FILE__));
define('DASHBOARD_EXTENSION_LOCKDOWN_URL', plugin_dir_url(__FILE__));
define('DASHBOARD_EXTENSION_LOCKDOWN_BASENAME', plugin_basename(__FILE__));
define('DASHBOARD_EXTENSION_LOCKDOWN_CSS', DASHBOARD_EXTENSION_LOCKDOWN_URL . 'assets/admin.css');
define('DASHBOARD_EXTENSION_LOCKDOWN_JS', DASHBOARD_EXTENSION_LOCKDOWN_URL . 'assets/admin.js');

// Include the main plugin class.
require_once DASHBOARD_EXTENSION_LOCKDOWN_PATH . 'includes/class-dashboard-extension-lockdown.php';
require_once DASHBOARD_EXTENSION_LOCKDOWN_PATH . 'includes/class-dashboard-extension-lockdown-settings.php';
require_once DASHBOARD_EXTENSION_LOCKDOWN_PATH . 'includes/class-dashboard-extension-lockdown-admin-ui.php';

// On activation, auto-capture the activating user as exempt.
// Registered at top-level (not inside hooks) per WP lifecycle requirements.
register_activation_hook(__FILE__, function () {
	// Ensure the user has administrative privileges (allow WP-CLI to bypass).
	if (!defined('WP_CLI') || !WP_CLI) {
		if (!current_user_can('activate_plugins') || (!current_user_can('manage_options') && !is_super_admin())) {
			wp_die(
				esc_html__('Sorry, you must be an administrator or super administrator to install and activate this plugin.', 'dashboard-extension-lockdown'),
				esc_html__('Permission Denied', 'dashboard-extension-lockdown'),
				['back_link' => true]
			);
		}
	}

	$options         = get_option('dashboard_extension_lockdown_options', []);
	$current_user_id = get_current_user_id();

	// Only store if we have a real user (not WP-CLI with no user context).
	if ($current_user_id > 0) {
		$exempt_users = isset($options['exempt_users']) ? (array) $options['exempt_users'] : [];

		if (empty($exempt_users)) {
			$options['exempt_users'] = [$current_user_id];
		} elseif (!in_array($current_user_id, $exempt_users, true)) {
			$options['exempt_users'][] = $current_user_id;
		}

		update_option('dashboard_extension_lockdown_options', $options);
	}
});

//instantiate the main class
add_action('plugins_loaded', function () {
	new Dashboard_Extension_Lockdown();
	new Dashboard_Extension_Lockdown_Settings();
	new Dashboard_Extension_Lockdown_Admin_UI();
});
