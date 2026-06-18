<?php

namespace Abbeymaniak\PluginLockdownWp;


class Plugin_Lockdown_Settings
{

	public function __construct()
	{
		add_action('admin_init', [$this, 'register_settings']);
	}

	public function register_settings()
	{

		register_setting(
			'plugin_lockdown_options',
			'plugin_lockdown_options',
			array(
				'type'              => 'array',
				'sanitize_callback' => array($this, 'sanitize_options'),
				'default'           => array(),
			)
		);

		add_settings_section(
			'plugin_lockdown_general_section',
			'General Settings',
			array($this, 'general_section_callback'),
			'plugin_lockdown_options'
		);

		add_settings_field(
			'total_lockdown',
			'Total Lockdown',
			array($this, 'total_lockdown_callback'),
			'plugin_lockdown_options',
			'plugin_lockdown_general_section'
		);
	}

	/**
	 * Sanitize options
	 */
	public function sanitize_options($input)
	{
		$new_input = array();

		if (isset($input['total_lockdown'])) {
			$new_input['total_lockdown'] = sanitize_text_field($input['total_lockdown']);
		}

		if (isset($input['block_installs'])) {
			$new_input['block_installs'] = sanitize_text_field($input['block_installs']);
		}

		if (isset($input['hide_plugins_menu'])) {
			$new_input['hide_plugins_menu'] = sanitize_text_field($input['hide_plugins_menu']);
		}

		return $new_input;
	}

	/**
	 * General section callback
	 */
	public function general_section_callback()
	{
		echo '<p>Configure plugin lockdown behavior and restrictions.</p>';
	}

	/**
	 * Total lockdown field callback
	 */
	public function total_lockdown_callback()
	{
		$options = get_option('plugin_lockdown_options');
		$checked = isset($options['total_lockdown']) ? $options['total_lockdown'] : '';

?>
		<label>
			<input type="checkbox" name="plugin_lockdown_options[total_lockdown]" value="on" <?php checked($checked, 'on'); ?> />
			<span style="color: #d63638; font-weight: bold;">Enable total lockdown</span>
		</label>
		<p class="description">Completely disable all plugin/theme installs, updates, and deletions for all users. Replaces all other settings when enabled.</p>
<?php
	}
}
