<?php

namespace Abbeymaniak\PluginLockdownWP;

use Abbeymaniak\PluginLockdownWp\Plugin_Lockdown_WP;
use Abbeymaniak\PluginLockdownWp\Plugin_Lockdown_Settings;
use Abbeymaniak\PluginLockdownWp\Plugin_Lockdown_Admin_UI;


/**
 *  Plugin Lockdown Wp
 *
 * @package         Plugin_Lockdown_WP
 * @category        Plugin
 * @author          Abiodun Paul Ogunnaike <primastech101@gmail.com>
 * @license         http://www.gnu.org/licenses/gpl-2.0.txt GPLv2 or later
 * @link            https://github.com/abbeymaniak/plugin-lockdown-wp
 *
 * Plugin Name:     Plugin Lockdown Wp
 * Plugin URI:      https://digital.celebrateme.io/product/plugin-lockdown-wp/
 * Description:     Plugin Lockdown Wp allows you to control access to plugin features based on user roles.
 * Author:          Abiodun Paul Ogunnaike
 * Author URI:      https://github.com/abbeymaniak
 * Text Domain:     plugin-lockdown-wp
 * Donate:          https://www.buymeacoffee.com/abbeymaniak
 * Domain Path:     /languages
 * Version:         1.0.1
 * prefix:          plugin_lockdown_wp
 * License:         GPL v2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Requires PHP:      8.1
 * Requires at least: 6.0
 *
 */

// If the file is accessed directly abort script.
defined('ABSPATH') || die('Unauthorized Access');


// define constants
define('PLUGIN_LOCKDOWN_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_LOCKDOWN_URL', plugin_dir_url(__FILE__));
define('PLUGIN_LOCKDOWN_CSS', PLUGIN_LOCKDOWN_URL . 'assets/admin.css');
define('PLUGIN_LOCKDOWN_JS', PLUGIN_LOCKDOWN_URL . 'assets/admin.js');

// Include the main plugin class.
require_once PLUGIN_LOCKDOWN_PATH . 'includes/class-plugin-lockdown-wp.php';
require_once PLUGIN_LOCKDOWN_PATH . 'includes/class-settings.php';
require_once PLUGIN_LOCKDOWN_PATH . 'includes/class-admin-ui.php';

//instantiate the main class
add_action('plugins_loaded', function () {
	new Plugin_Lockdown_WP();
	new Plugin_Lockdown_Settings();
	new Plugin_Lockdown_Admin_UI();
});
