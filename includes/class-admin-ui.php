<?php

namespace Abbeymaniak\PluginLockdownWP;

class Plugin_Lockdown_Admin_UI
{

	public function __construct()
	{

		add_action('admin_menu', [$this, 'menu']);
		add_action('admin_enqueue_scripts', [$this, 'assets']);
	}

	public function menu()
	{
		$options         = get_option('plugin_lockdown_options', []);
		$exempt_users    = isset($options['exempt_users']) ? array_map('intval', (array) $options['exempt_users']) : [];
		$current_user_id = get_current_user_id();

		// If exempt users are configured, only show menu to exempt users.
		// If no exempt users yet, show to all admins so someone can configure.
		if (!empty($exempt_users) && !in_array($current_user_id, $exempt_users, true)) {
			return;
		}

		add_menu_page(
			'Plugin Lockdown',
			'Plugin Lockdown',
			'manage_options',
			'plugin-lockdown',
			[$this, 'render'],
			'dashicons-shield-alt'
		);
	}

	public function assets()
	{
		if (isset($_GET['page']) && $_GET['page'] === 'plugin-lockdown') {

			wp_enqueue_script(
				'plugin-lockdown-admin',
				PLUGIN_LOCKDOWN_JS,
				array('jquery'),
				'1.0.0',
				true
			);
			wp_enqueue_style(
				'plugin-lockdown-admin',
				PLUGIN_LOCKDOWN_CSS,
				array(),
				"1.0.0"
			);
		}
	}

	public function render()
	{
		$options = get_option('plugin_lockdown_options', []);
		include PLUGIN_LOCKDOWN_PATH . 'templates/settings-page.php';
	}
}
