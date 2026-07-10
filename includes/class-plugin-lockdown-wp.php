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
		$defaults = array(
			'total_lockdown'               => 0,
			'block_installs'               => 0,
			'hide_plugins_menu'            => 0,
			'production_only'              => 0,
			'prevent_plugins_activation'   => 0,
			'prevent_plugins_deactivation' => 0,
			'prevent_plugins_updates'      => 0,
			'exempt_users'                 => [],
		);
		$this->options = wp_parse_args(get_option('plugin_lockdown_options', []), $defaults);

		add_action('init', [$this, 'apply_rules']);
		add_action('admin_menu', [$this, 'hide_plugins_menu']);
		add_action('admin_notices', [$this, 'no_exempt_users_notice']);
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
	 * Check if the current user is in the exempt users whitelist.
	 *
	 * @return bool
	 */
	private function is_exempt_user()
	{
		$current_user_id = get_current_user_id();
		if ($current_user_id === 0) {
			return false;
		}

		$exempt_users = $this->options['exempt_users'] ?? [];
		return in_array($current_user_id, array_map('intval', (array) $exempt_users), true);
	}

	/**
	 * Check if any exempt users have been configured.
	 * When no exempt users exist, lockdown rules are inactive (fail-open safety).
	 *
	 * @return bool
	 */
	private function has_exempt_users()
	{
		$exempt_users = $this->options['exempt_users'] ?? [];
		return !empty($exempt_users);
	}


	/**
	 * Apply lockdown rules based on settings.
	 * Exempt users bypass all rules. If no exempt users are configured,
	 * rules are inactive (fail-open safety).
	 */
	public function apply_rules()
	{
		// Fail-open: if no exempt users are configured, don't apply any rules.
		if (!$this->has_exempt_users()) {
			return;
		}

		// Exempt users bypass all rules.
		if ($this->is_exempt_user()) {
			return;
		}

		// TOTAL LOCKDOWN
		if ((int) $this->options['total_lockdown'] === 1) {
			$this->lock_all();
			$this->block_installs();
		}

		// Block installs only
		if ((int) $this->options['block_installs'] === 1) {
			$this->block_installs();
		}

		//prevent plugin activations
		if ((int) $this->options['prevent_plugins_activation'] === 1) {
			$this->prevent_plugins_activation();
		}

		//prevent plugin deactivations
		if ((int) $this->options['prevent_plugins_deactivation'] === 1) {
			$this->prevent_plugins_deactivation();
		}

		//prevent plugins update
		if ((int) $this->options['prevent_plugins_updates'] === 1) {
			$this->prevent_plugins_updates();
		}
	}

	/**
	 * Block plugin and theme installations.
	 */
	private function block_installs()
	{
		add_filter('map_meta_cap', function ($caps, $cap) {
			if (in_array($cap, ['install_plugins', 'update_plugins', 'delete_plugins', 'install_themes', 'update_themes'])) {
				$caps[] = 'do_not_allow';
			}
			return $caps;
		}, 10, 2);
	}

	/**
	 * Hide plugins menu for non-exempt users.
	 */
	public function hide_plugins_menu()
	{
		// If no exempt users configured or user is exempt, don't hide the menu.
		if (!$this->has_exempt_users() || $this->is_exempt_user()) {
			return;
		}

		if ((int) $this->options['hide_plugins_menu'] === 1 || (int) $this->options['total_lockdown'] === 1) {
			remove_menu_page('plugins.php');
			remove_submenu_page('plugins.php', 'plugin-install.php');
		}
	}


	/**
	 * Total lockdown — block all plugin/theme capabilities via map_meta_cap.
	 * DISALLOW_FILE_MODS removed: it is a blunt constant that cannot be scoped
	 * per-user and has unintended side effects (blocks file editor, etc.).
	 */
	private function lock_all()
	{
		add_filter(
			'map_meta_cap',
			[$this, 'total_lockdown_capabilities'],
			10,
			2
		);
	}

	/**
	 * Total lockdown capabilities filter callback.
	 *
	 * @param array  $caps Required capabilities.
	 * @param string $cap  Capability being checked.
	 * @return array
	 */
	public function total_lockdown_capabilities(
		$caps,
		$cap
	) {

		$blocked = [

			'install_plugins',
			'update_plugins',
			'delete_plugins',

			'activate_plugins',
			'activate_plugin',
			'deactivate_plugins',
			'deactivate_plugin',

			'install_themes',
			'update_themes',
			'delete_themes'

		];

		if (in_array($cap, $blocked)) {
			return ['do_not_allow'];
		}

		return $caps;
	}

	/**
	 * Prevent plugins activation for non-exempt users.
	 *
	 * @return void
	 */
	private function prevent_plugins_activation()
	{

		add_filter(
			'map_meta_cap',
			function ($caps, $cap) {

				if ($cap === 'activate_plugins' || $cap === 'activate_plugin') {
					$caps[] = 'do_not_allow';
				}
				return $caps;
			},
			10,
			2
		);
	}


	/**
	 * Prevent plugins deactivation for non-exempt users.
	 *
	 * @return void
	 */
	private function prevent_plugins_deactivation()
	{

		add_filter('map_meta_cap', function ($caps, $cap) {

			if ($cap === 'deactivate_plugins' || $cap === 'deactivate_plugin') {
				$caps[] = 'do_not_allow';
			}
			return $caps;
		}, 10, 2);
	}

	/**
	 * Prevent plugin updates for non-exempt users.
	 */
	private function prevent_plugins_updates()
	{

		add_filter(
			'map_meta_cap',
			function ($caps, $cap) {

				if ($cap === 'update_plugins') {
					$caps[] = 'do_not_allow';
				}

				return $caps;
			},
			10,
			2
		);
	}

	/**
	 * Display admin notice when no exempt users are configured.
	 * Shows to all administrators so someone can configure the plugin.
	 */
	public function no_exempt_users_notice()
	{
		if (!current_user_can('manage_options')) {
			return;
		}

		if ($this->has_exempt_users()) {
			return;
		}

		$settings_url = esc_url(admin_url('admin.php?page=plugin-lockdown'));

		echo '<div class="notice notice-warning is-dismissible">';
		echo '<p><strong>' . esc_html__('Plugin Lockdown WP:', 'plugin-lockdown-wp') . '</strong> ';
		echo esc_html__('No exempt users configured. Lockdown rules are inactive until at least one administrator is added as exempt.', 'plugin-lockdown-wp');
		echo ' <a href="' . $settings_url . '">' . esc_html__('Configure now →', 'plugin-lockdown-wp') . '</a>';
		echo '</p></div>';
	}
}
