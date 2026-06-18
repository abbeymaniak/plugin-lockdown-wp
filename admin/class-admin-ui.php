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

		wp_enqueue_script(
			'plugin-lockdown-admin',
			plugin_dir_url(__FILE__) . 'assets/admin.js',
			array('jquery'),
			'1.0.0',
			true
		);
		wp_enqueue_style(
			'plugin-lockdown-admin',
			plugin_dir_url(__FILE__) . 'admin.css'
		);
	}

	public function render()
	{
		$options = get_option('plugin_lockdown_options', []);
		include PLUGIN_LOCKDOWN_PATH . 'templates/settings-page.php';
	}
}
