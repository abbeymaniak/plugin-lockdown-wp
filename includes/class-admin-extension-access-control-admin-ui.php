<?php

namespace Abbeymaniak\AdminExtensionAccessControl;

class Admin_Extension_Access_Control_Admin_UI
{

	public function __construct()
	{

		add_action('admin_menu', [$this, 'menu']);
		add_action('admin_enqueue_scripts', [$this, 'assets']);
	}

	public function menu()
	{
		$options         = get_option('admin_extension_access_control_options', []);
		$exempt_users    = isset($options['exempt_users']) ? array_map('intval', (array) $options['exempt_users']) : [];
		$current_user_id = get_current_user_id();

		// If exempt users are configured, only show menu to exempt users.
		// If no exempt users yet, show to all admins so someone can configure.
		if (!empty($exempt_users) && !in_array($current_user_id, $exempt_users, true)) {
			return;
		}

		add_menu_page(
			'Admin Extension Access Control',
			'Admin Extension Access Control',
			'manage_options',
			'admin-extension-access-control',
			[$this, 'render'],
			'dashicons-shield-alt'
		);
	}

	public function assets()
	{
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_GET['page']) && $_GET['page'] === 'admin-extension-access-control') {

			wp_enqueue_script(
				'admin-extension-access-control-admin',
				ADMIN_EXTENSION_ACCESS_CONTROL_JS,
				array('jquery'),
				'1.0.0',
				true
			);
			wp_enqueue_style(
				'admin-extension-access-control-admin',
				ADMIN_EXTENSION_ACCESS_CONTROL_CSS,
				array(),
				"1.0.0"
			);
		}
	}

	public function render()
	{
		$options = get_option('admin_extension_access_control_options', []);
		include ADMIN_EXTENSION_ACCESS_CONTROL_PATH . 'templates/settings-page.php';
	}
}
