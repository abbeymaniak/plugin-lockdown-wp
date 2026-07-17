<?php

namespace Abbeymaniak\AdminPluginAccessControl;

use Abbeymaniak\AdminPluginAccessControl\Admin_Plugin_Access_Control;
use Abbeymaniak\AdminPluginAccessControl\Admin_Plugin_Access_Control_Settings;
use Abbeymaniak\AdminPluginAccessControl\Admin_Plugin_Access_Control_Admin_UI;

/**
 *  Admin Plugin Access Control
 *
 * @package         Admin_Plugin_Access_Control
 * @category        Plugin
 * @author          Abiodun Paul Ogunnaike <primastech101@gmail.com>
 * @license         http://www.gnu.org/licenses/gpl-2.0.txt GPLv2 or later
 * @link            https://github.com/abbeymaniak/admin-plugin-access-control
 *
 * Plugin Name:     Admin Plugin Access Control
 * Plugin URI:      https://digital.celebrateme.io/product/admin-plugin-access-control/
 * Description:     Admin Plugin Access Control allows you to control access to plugin features based on user roles.
 * Author:          Abiodun Paul Ogunnaike
 * Author URI:      https://github.com/abbeymaniak
 * Text Domain:     admin-plugin-access-control
 * Donate:          https://www.buymeacoffee.com/abbeymaniak
 * Domain Path:     /languages
 * Version:         1.0.1
 * prefix:          admin_plugin_access_control
 * License:         GPL v2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Requires PHP:      8.1
 * Requires at least: 6.0
 *
 */

// If the file is accessed directly abort script.
defined('ABSPATH') || die('Unauthorized Access');


// define constants
define('ADMIN_PLUGIN_ACCESS_CONTROL_PATH', plugin_dir_path(__FILE__));
define('ADMIN_PLUGIN_ACCESS_CONTROL_URL', plugin_dir_url(__FILE__));
define('ADMIN_PLUGIN_ACCESS_CONTROL_BASENAME', plugin_basename(__FILE__));
define('ADMIN_PLUGIN_ACCESS_CONTROL_CSS', ADMIN_PLUGIN_ACCESS_CONTROL_URL . 'assets/admin.css');
define('ADMIN_PLUGIN_ACCESS_CONTROL_JS', ADMIN_PLUGIN_ACCESS_CONTROL_URL . 'assets/admin.js');

// Include the main plugin class.
require_once ADMIN_PLUGIN_ACCESS_CONTROL_PATH . 'includes/class-admin-plugin-access-control.php';
require_once ADMIN_PLUGIN_ACCESS_CONTROL_PATH . 'includes/class-admin-plugin-access-control-settings.php';
require_once ADMIN_PLUGIN_ACCESS_CONTROL_PATH . 'includes/class-admin-plugin-access-control-admin-ui.php';

// On activation, auto-capture the activating user as exempt.
// Registered at top-level (not inside hooks) per WP lifecycle requirements.
register_activation_hook(__FILE__, function () {
	// Ensure the user has administrative privileges (allow WP-CLI to bypass).
	if (!defined('WP_CLI') || !WP_CLI) {
		if (!current_user_can('activate_plugins') || (!current_user_can('manage_options') && !is_super_admin())) {
			wp_die(
				esc_html__('Sorry, you must be an administrator or super administrator to install and activate this plugin.', 'admin-plugin-access-control'),
				esc_html__('Permission Denied', 'admin-plugin-access-control'),
				['back_link' => true]
			);
		}
	}

	$options         = get_option('admin_plugin_access_control_options', []);
	$current_user_id = get_current_user_id();

	// Only store if we have a real user (not WP-CLI with no user context).
	if ($current_user_id > 0) {
		$exempt_users = isset($options['exempt_users']) ? (array) $options['exempt_users'] : [];

		if (empty($exempt_users)) {
			$options['exempt_users'] = [$current_user_id];
		} elseif (!in_array($current_user_id, $exempt_users, true)) {
			$options['exempt_users'][] = $current_user_id;
		}

		update_option('admin_plugin_access_control_options', $options);
	}
});

//instantiate the main class
add_action('plugins_loaded', function () {
	new Admin_Plugin_Access_Control();
	new Admin_Plugin_Access_Control_Settings();
	new Admin_Plugin_Access_Control_Admin_UI();
});
