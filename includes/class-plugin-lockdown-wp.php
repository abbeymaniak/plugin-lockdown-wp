<?php

namespace Abbeymaniak\PluginLockdownWp;


/**
 * This is the Plugin Lockdown WP class.
 *
 * @category Plugin
 * @package  Plugin_Lockdown_WP
 * @author   Abiodun Paul Ogunnaike <primastech101@gmail.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.txt GPLv2 or later
 * @link     https://github.com/abbeymaniak/plugin-lockdown-wp
 */
class Plugin_Lockdown_WP
{


	private $options;
	// Constructor
	public function __construct()
	{
		// add_action('admin_menu', array($this, 'add_plugin_menu_page'));

		$this->options = get_option('plugin_lockdown_options', []);



		// if (get_option('plugin_lockdown_all') === 'on') {

		// 	add_action('init', [$this, 'apply_rules']);
		// }
	}


	/**
	 *
	 * is production
	 */

	private function is_production()
	{
		if (defined('WP_ENVIRONMENT_TYPE')) {
			return WP_ENVIRONMENT_TYPE === 'production';
		}

		return true;
	}

	/**
	 * Detect environment
	 */
	private function get_environment()
	{
		// Priority: WP_ENV constant
		if (defined('WP_ENV')) {
			return WP_ENV;
		}

		// Fallback: WP_ENVIRONMENT_TYPE (WP 5.5+)
		if (function_exists('wp_get_environment_type')) {
			return wp_get_environment_type();
		}

		// Fallback: check domain
		$host = $_SERVER['HTTP_HOST'] ?? '';

		if (strpos($host, 'localhost') !== false || strpos($host, '.test') !== false) {
			return 'local';
		}

		if (strpos($host, 'staging') !== false) {
			return 'staging';
		}

		return 'production';
	}


	/**
	 * Lock plugin and theme modifications
	 */
	private function apply_rules()
	{

		// TOTAL LOCKDOWN
		if (!empty($this->options['total_lockdown'])) {
			$this->lock_all();
			return;
		}

		// Block installs only
		if (!empty($this->options['block_installs'])) {
			$this->block_installs();
		}

		// Hide plugins menu
		if (!empty($this->options['hide_plugins_menu'])) {
			$this->hide_plugins_menu();
		}
	}

	/**
	 * Block install
	 */
	private function block_installs()
	{
		// Extra safety: block install capability
		add_filter('map_meta_cap', function ($caps, $cap) {
			if (in_array($cap, ['install_plugins', 'update_plugins', 'delete_plugins', 'install_themes', 'update_themes'])) {
				$caps[] = 'do_not_allow';
			}
			return $caps;
		}, 10, 2);
	}

	/**
	 * Hide plugins menu
	 */
	private function hide_plugins_menu()
	{
		// Optional: hide plugins menu
		add_action('admin_menu', function () {
			remove_menu_page('plugins.php');
			remove_submenu_page('plugins.php', 'plugin-install.php');
		}, 999);
	}


	/**
	 * Disable installs and updates
	 */

	private function lock_all()
	{
		// Disable installs/updates
		if (!defined('DISALLOW_FILE_MODS')) {
			define('DISALLOW_FILE_MODS', true);
		}
	}
}
