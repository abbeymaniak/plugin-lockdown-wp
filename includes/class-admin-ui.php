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


	public function plugin_lockdown_settings_page_before_extra_content()
	{
		echo '<p>Plugin Lockdown extra content</p>';
	}
}
