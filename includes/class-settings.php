<?php

namespace Abbeymaniak\PluginLockdownWp;


class Plugin_Lockdown_Settings
{

	public function __construct()
	{
		add_action('admin_init', [$this, 'register_settings']);
	}

	/**
	 *
	 * Register settings.
	 * @return void
	 */
	public function register_settings()
	{

		register_setting(
			'plugin_lockdown_group',
			'plugin_lockdown_options',
			array(
				'type'              => 'array',
				'sanitize_callback' => array($this, 'sanitize_options'),
				'default'           => array(
					'total_lockdown' => 0,
					'block_installs' => 0,
					'hide_plugins_menu' => 0,
					'production_only' => 0,
					'prevent_plugins_activation' => 0,
					'prevent_plugins_deactivation' => 0,
					'prevent_plugins_updates' => 0
				),
			)
		);

		add_settings_section(
			'plugin_lockdown_general_section',
			'General Settings',
			array($this, 'general_section_callback'),
			'plugin_lockdown' //page slug
		);

		add_settings_field(
			'total_lockdown',
			'Total Lockdown',
			array($this, 'total_lockdown_callback'),
			'plugin_lockdown',
			'plugin_lockdown_general_section'
		);

		add_settings_field(
			'block_installs',
			'Block Installs',
			array($this, 'block_installs_callback'),
			'plugin_lockdown',
			'plugin_lockdown_general_section'
		);


		add_settings_field(
			'hide_plugins_menu',
			'Hide Plugins Menu',
			array($this, 'hide_plugins_menu_callback'),
			'plugin_lockdown',
			'plugin_lockdown_general_section'
		);

		add_settings_field(
			'prevent_plugins_activation',
			'Restrict Plugins Activation',
			[$this, 'prevent_plugins_activation_callback'],
			'plugin_lockdown',
			'plugin_lockdown_general_section'
		);

		add_settings_field(
			'prevent_plugins_deactivation',
			'Restrict Plugins Deactivation',
			[$this, 'prevent_plugins_deactivation_callback'],
			'plugin_lockdown',
			'plugin_lockdown_general_section'
		);

		add_settings_field(
			'prevent_plugins_updates',
			'Restrict Plugins Updates',
			[$this, 'prevent_plugins_updates_callback'],
			'plugin_lockdown',
			'plugin_lockdown_general_section'
		);

		add_settings_field(
			'production_only',
			'Production Only',
			array($this, 'production_only_callback'),
			'plugin_lockdown',
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
		} else {
			$new_input['total_lockdown'] = 0;
		}

		if (isset($input['block_installs'])) {
			$new_input['block_installs'] = sanitize_text_field($input['block_installs']);
		} else {
			$new_input['block_installs'] = 0;
		}

		if (isset($input['hide_plugins_menu'])) {
			$new_input['hide_plugins_menu'] = sanitize_text_field($input['hide_plugins_menu']);
		} else {
			$new_input['hide_plugins_menu'] = 0;
		}

		if (isset($input['prevent_plugins_activation'])) {
			$new_input['prevent_plugins_activation'] = sanitize_text_field($input['prevent_plugins_activation']);
		} else {
			$new_input['prevent_plugins_activation'] = 0;
		}

		if (isset($input['prevent_plugins_deactivation'])) {
			$new_input['prevent_plugins_deactivation'] = sanitize_text_field($input['prevent_plugins_deactivation']);
		} else {
			$new_input['prevent_plugins_deactivation'] = 0;
		}

		if (isset($input['prevent_plugins_updates'])) {
			$new_input['prevent_plugins_updates'] = sanitize_text_field($input['prevent_plugins_updates']);
		} else {
			$new_input['prevent_plugins_updates'] = 0;
		}

		if (isset($input['production_only'])) {
			$new_input['production_only'] = sanitize_text_field($input['production_only']);
		} else {
			$new_input['production_only'] = 0;
		}

		return $new_input;
	}

	/**
	 * General section callback
	 */
	public function general_section_callback()
	{
		echo __('<p> Configure plugin lockdown behavior and restrictions.</p>', 'plugin_lockdown_wp');
	}

	/**
	 * Block installs Field callback
	 */

	public function block_installs_callback()
	{
		$options = get_option('plugin_lockdown_options');
		$block_installs_checked = isset($options['block_installs']) ? $options['block_installs'] : 0;
?>
		<label>
			<input type="checkbox" name="plugin_lockdown_options[block_installs]" value="1" <?php checked($block_installs_checked, 1); ?> />
			<span style=" color: #d63638; font-weight: bold;">Block Installs</span>
		</label>
		<p class="description" style="font-size: .8rem;">Completely disable all plugin installs for users.</p>
	<?php
	}
	/**
	 * Total lockdown field callback
	 */
	public function total_lockdown_callback()
	{
		$options = get_option('plugin_lockdown_options');
		$total_lockdown_checked = isset($options['total_lockdown']) ? $options['total_lockdown'] : 0;

	?>
		<label>
			<input type="checkbox" name="plugin_lockdown_options[total_lockdown]" value="1" <?php checked($total_lockdown_checked, 1); ?> />
			<span style=" color: #d63638; font-weight: bold;">Enable total lockdown</span>
		</label>
		<p class="description" style="font-size: .8rem;">Completely disable all plugin/theme installs, updates, and deletions for all users. Replaces all other settings when enabled.</p>
	<?php
	}

	/**
	 * Hide plugins menu field callback
	 */
	public function hide_plugins_menu_callback()
	{
		$options = get_option('plugin_lockdown_options');
		$hide_plugins_menu_checked = isset($options['hide_plugins_menu']) ? $options['hide_plugins_menu'] : 0;
	?>
		<label>
			<input type="checkbox" name="plugin_lockdown_options[hide_plugins_menu]" value="1" <?php checked($hide_plugins_menu_checked, 1); ?> />
			<span style=" color: #d63638; font-weight: bold;">Hide Plugins Menu</span>
		</label>
		<p class="description" style="font-size: .8rem;">Completely hide the plugins menu for all users.</p>
	<?php
	}

	/**
	 * Prevent plugins activations callback
	 */

	public function prevent_plugins_activation_callback()
	{

		$options = get_option('plugin_lockdown_options');
		$prevent_plugins_activation_checked = isset($options['prevent_plugins_activation']) ? $options['prevent_plugins_activation'] : 0;
	?>
		<label>
			<input type="checkbox" name="plugin_lockdown_options[prevent_plugins_activation]" value="1" <?php checked($prevent_plugins_activation_checked, 1); ?> />
			<span style=" color: #d63638; font-weight: bold;">Restrict Plugins Activation</span>
		</label>
		<p class="description" style="font-size: .8rem;">Completely disable all plugin activations for users.</p>
	<?php
	}

	/**
	 * Prevent plugins deactivations callback
	 */

	public function prevent_plugins_deactivation_callback()
	{

		$options = get_option('plugin_lockdown_options');
		$prevent_plugins_deactivation_checked = isset($options['prevent_plugins_deactivation']) ? $options['prevent_plugins_deactivation'] : 0;
	?>
		<label>
			<input type="checkbox" name="plugin_lockdown_options[prevent_plugins_deactivation]" value="1" <?php checked($prevent_plugins_deactivation_checked, 1); ?> />
			<span style=" color: #d63638; font-weight: bold;">Restrict Plugins Deactivation</span>
		</label>
		<p class="description" style="font-size: .8rem;">Completely disable all plugin deactivations for users.</p>
	<?php
	}

	/**
	 * Prevent plugins updates callback
	 */

	public function prevent_plugins_updates_callback()
	{

		$options = get_option('plugin_lockdown_options');
		$prevent_plugins_updates_checked = isset($options['prevent_plugins_updates']) ? $options['prevent_plugins_updates'] : 0;
	?>
		<label>
			<input type="checkbox" name="plugin_lockdown_options[prevent_plugins_updates]" value="1" <?php checked($prevent_plugins_updates_checked, 1); ?> />
			<span style=" color: #d63638; font-weight: bold;">Restrict Plugins Updates</span>
		</label>
		<p class="description" style="font-size: .8rem;">Completely disable all plugin updates for users.</p>
	<?php
	}

	/**
	 * Production Only Field callback
	 */
	public function production_only_callback()
	{
		$options = get_option('plugin_lockdown_options');
		$production_only_checked = isset($options['production_only']) ? $options['production_only'] : 0;
	?>
		<label>
			<input type="checkbox" name="plugin_lockdown_options[production_only]" value="1" <?php checked($production_only_checked, 1); ?> />
			<span style=" color: #d63638; font-weight: bold;">Production Only</span>
		</label>
		<p class="description" style="font-size: .8rem;">Only apply lockdown settings on production sites.</p>
<?php
	}
}
