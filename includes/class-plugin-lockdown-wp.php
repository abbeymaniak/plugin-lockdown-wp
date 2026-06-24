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
		$this->options = get_option('plugin_lockdown_options', []);
		add_action('admin_init', [$this, 'apply_rules']);
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
	 * Exempt Plugin Lockdown itself
	 */
	private function is_exempt_plugin()
	{

		$plugin = $_REQUEST['plugin'] ?? '';

		return $plugin === PLUGIN_LOCKDOWN_BASENAME;
	}


	/**
	 * Lock plugin and theme modifications
	 */
	public function apply_rules()
	{
		echo "aspply_rules";
		print_r($this->options);
		// TOTAL LOCKDOWN
		if ((int) $this->options['total_lockdown'] === 1) {
			$this->lock_all();
			$this->block_installs();
			$this->hide_plugins_menu();
		}

		// Block installs only
		if ((int) $this->options['block_installs'] === 1) {
			$this->block_installs();
		}

		// Hide plugins menu
		if ((int) $this->options['hide_plugins_menu'] === 1) {
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
			if (in_array($cap, ['install_plugins', 'update_plugins', 'delete_plugins', 'install_themes', 'update_themes']) && !$this->is_exempt_plugin()) {
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

		if ((int) $this->options['hide_plugins_menu'] === 1) {
			remove_menu_page('plugins.php');
			remove_submenu_page('plugins.php', 'plugin-install.php');
		}
	}


	/**
	 * Disable installs and updates
	 */

	private function lock_all()
	{
		// Disable installs/updates
		if (!defined('DISALLOW_FILE_MODS') && !$this->is_exempt_plugin()) {
			define('DISALLOW_FILE_MODS', true);
		}

		add_filter(
			'map_meta_cap',
			[$this, 'total_lockdown_capabilities'],
			10,
			2
		);
	}

	/**
	 * Total lockdown capabilities
	 */
	public function total_lockdown_capabilities(
		$caps,
		$cap
	) {

		if ($this->is_exempt_plugin()) {
			return $caps;
		}

		$blocked = [

			'install_plugins',
			'update_plugins',
			'delete_plugins',

			'activate_plugins',
			'deactivate_plugins',

			'install_themes',
			'update_themes',
			'delete_themes'

		];

		if (in_array($cap, $blocked)) {
			return ['do_not_allow'];
		}

		return $caps;
	}
}
