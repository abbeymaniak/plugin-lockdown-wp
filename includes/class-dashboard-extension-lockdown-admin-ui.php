<?php

namespace Abbeymaniak\DashboardExtensionLockdown;

class Dashboard_Extension_Lockdown_Admin_UI
{

	public function __construct()
	{

		add_action('admin_menu', [$this, 'menu']);
		add_action('admin_enqueue_scripts', [$this, 'assets']);
	}

	public function menu()
	{
		$options         = get_option('dashboard_extension_lockdown_options', []);
		$exempt_users    = isset($options['exempt_users']) ? array_map('intval', (array) $options['exempt_users']) : [];
		$current_user_id = get_current_user_id();

		// If exempt users are configured, only show menu to exempt users.
		// If no exempt users yet, show to all admins so someone can configure.
		if (!empty($exempt_users) && !in_array($current_user_id, $exempt_users, true)) {
			return;
		}

		add_menu_page(
			'Dashboard Extension Lockdown',
			'Dashboard Extension Lockdown',
			'manage_options',
			'dashboard-extension-lockdown',
			[$this, 'render'],
			'dashicons-shield-alt'
		);
	}

	public function assets()
	{
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_GET['page']) && $_GET['page'] === 'dashboard-extension-lockdown') {

			wp_enqueue_script(
				'dashboard-extension-lockdown-admin',
				DASHBOARD_EXTENSION_LOCKDOWN_JS,
				array('jquery'),
				'1.0.0',
				true
			);
			wp_enqueue_style(
				'dashboard-extension-lockdown-admin',
				DASHBOARD_EXTENSION_LOCKDOWN_CSS,
				array(),
				"1.0.0"
			);
		}
	}

	public function render()
	{
		$options = get_option('dashboard_extension_lockdown_options', []);
		include DASHBOARD_EXTENSION_LOCKDOWN_PATH . 'templates/settings-page.php';
	}
}
