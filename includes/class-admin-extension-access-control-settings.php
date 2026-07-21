<?php

namespace Abbeymaniak\AdminExtensionAccessControl;


class Admin_Extension_Access_Control_Settings
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
			'admin_extension_access_control_group',
			'admin_extension_access_control_options',
			array(
				'type'              => 'array',
				'sanitize_callback' => array($this, 'sanitize_options'),
				'default'           => array(
					'total_lockdown'               => 0,
					'block_installs'               => 0,
					'hide_plugins_menu'            => 0,
					'production_only'              => 0,
					'prevent_plugins_activation'   => 0,
					'prevent_plugins_deactivation' => 0,
					'prevent_plugins_updates'      => 0,
					'exempt_users'                 => [],
				),
			)
		);

		add_settings_section(
			'admin_extension_access_control_general_section',
			'General Settings',
			array($this, 'general_section_callback'),
			'admin_extension_access_control' //page slug
		);

		add_settings_field(
			'total_lockdown',
			'Total Lockdown',
			array($this, 'total_lockdown_callback'),
			'admin_extension_access_control',
			'admin_extension_access_control_general_section'
		);

		add_settings_field(
			'block_installs',
			'Block Installs',
			array($this, 'block_installs_callback'),
			'admin_extension_access_control',
			'admin_extension_access_control_general_section'
		);


		add_settings_field(
			'hide_plugins_menu',
			'Hide Plugins Menu',
			array($this, 'hide_plugins_menu_callback'),
			'admin_extension_access_control',
			'admin_extension_access_control_general_section'
		);

		add_settings_field(
			'prevent_plugins_activation',
			'Restrict Plugins Activation',
			[$this, 'prevent_plugins_activation_callback'],
			'admin_extension_access_control',
			'admin_extension_access_control_general_section'
		);

		add_settings_field(
			'prevent_plugins_deactivation',
			'Restrict Plugins Deactivation',
			[$this, 'prevent_plugins_deactivation_callback'],
			'admin_extension_access_control',
			'admin_extension_access_control_general_section'
		);

		add_settings_field(
			'prevent_plugins_updates',
			'Restrict Plugins Updates',
			[$this, 'prevent_plugins_updates_callback'],
			'admin_extension_access_control',
			'admin_extension_access_control_general_section'
		);

		add_settings_field(
			'production_only',
			'Production Only',
			array($this, 'production_only_callback'),
			'admin_extension_access_control',
			'admin_extension_access_control_general_section'
		);

		add_settings_field(
			'exempt_users',
			'Exempt Users',
			[$this, 'exempt_users_callback'],
			'admin_extension_access_control',
			'admin_extension_access_control_general_section'
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

		// Sanitize exempt users — prevent removing the last exempt user.
		if (isset($input['exempt_users']) && is_array($input['exempt_users'])) {
			$new_exempt = array_map('intval', $input['exempt_users']);
			$new_exempt = array_filter($new_exempt, function ($id) {
				return $id > 0;
			});
			$new_exempt = array_values($new_exempt);

			if (empty($new_exempt)) {
				// Cannot remove the last user — keep current value.
				$existing = get_option('admin_extension_access_control_options', []);
				$new_input['exempt_users'] = isset($existing['exempt_users']) ? $existing['exempt_users'] : [get_current_user_id()];
				add_settings_error(
					'admin_extension_access_control_options',
					'last_exempt_user',
					__('You cannot remove all exempt users. At least one administrator must remain exempt.', 'admin-extension-access-control'),
					'error'
				);
			} else {
				$new_input['exempt_users'] = $new_exempt;
			}
		} else {
			// Checkboxes not submitted (all unchecked) — keep existing.
			$existing = get_option('admin_extension_access_control_options', []);
			$new_input['exempt_users'] = isset($existing['exempt_users']) ? $existing['exempt_users'] : [];
		}

		return $new_input;
	}

	/**
	 * General section callback
	 */
	public function general_section_callback()
	{
		echo '<p>' . esc_html__('Configure dashboard extension lockdown behavior and restrictions.', 'admin-extension-access-control') . '</p>';
	}

	/**
	 * Block installs Field callback
	 */

	public function block_installs_callback()
	{
		$options = get_option('admin_extension_access_control_options');
		$block_installs_checked = isset($options['block_installs']) ? $options['block_installs'] : 0;
?>
		<label>
			<input type="checkbox" name="admin_extension_access_control_options[block_installs]" value="1" <?php checked($block_installs_checked, 1); ?> />
			<span style=" color: #d63638; font-weight: bold;"><?php echo esc_html__('Block Installs', 'admin-extension-access-control'); ?></span>
		</label>
		<p class="description" style="font-size: .8rem;"><?php echo esc_html__('Completely disable all plugin installs for users.', 'admin-extension-access-control'); ?></p>
	<?php
	}
	/**
	 * Total lockdown field callback
	 */
	public function total_lockdown_callback()
	{
		$options = get_option('admin_extension_access_control_options');
		$total_lockdown_checked = isset($options['total_lockdown']) ? $options['total_lockdown'] : 0;

	?>
		<label>
			<input type="checkbox" name="admin_extension_access_control_options[total_lockdown]" value="1" <?php checked($total_lockdown_checked, 1); ?> />
			<span style=" color: #d63638; font-weight: bold;"><?php echo esc_html__('Enable total lockdown', 'admin-extension-access-control'); ?></span>
		</label>
		<p class="description" style="font-size: .8rem;"><?php echo esc_html__('Completely disable all plugin/theme installs, updates, and deletions for all users. Replaces all other settings when enabled.', 'admin-extension-access-control'); ?></p>
	<?php
	}

	/**
	 * Hide plugins menu field callback
	 */
	public function hide_plugins_menu_callback()
	{
		$options = get_option('admin_extension_access_control_options');
		$hide_plugins_menu_checked = isset($options['hide_plugins_menu']) ? $options['hide_plugins_menu'] : 0;
	?>
		<label>
			<input type="checkbox" name="admin_extension_access_control_options[hide_plugins_menu]" value="1" <?php checked($hide_plugins_menu_checked, 1); ?> />
			<span style=" color: #d63638; font-weight: bold;"><?php echo esc_html__('Hide Plugins Menu', 'admin-extension-access-control'); ?></span>
		</label>
		<p class="description" style="font-size: .8rem;"><?php echo esc_html__('Completely hide the plugins menu for all users.', 'admin-extension-access-control'); ?></p>
	<?php
	}

	/**
	 * Prevent plugins activations callback
	 */

	public function prevent_plugins_activation_callback()
	{

		$options = get_option('admin_extension_access_control_options');
		$prevent_plugins_activation_checked = isset($options['prevent_plugins_activation']) ? $options['prevent_plugins_activation'] : 0;
	?>
		<label>
			<input type="checkbox" name="admin_extension_access_control_options[prevent_plugins_activation]" value="1" <?php checked($prevent_plugins_activation_checked, 1); ?> />
			<span style=" color: #d63638; font-weight: bold;"><?php echo esc_html__('Restrict Plugins Activation', 'admin-extension-access-control'); ?></span>
		</label>
		<p class="description" style="font-size: .8rem;"><?php echo esc_html__('Completely disable all plugin activations for users.', 'admin-extension-access-control'); ?></p>
	<?php
	}

	/**
	 * Prevent plugins deactivations callback
	 */

	public function prevent_plugins_deactivation_callback()
	{

		$options = get_option('admin_extension_access_control_options');
		$prevent_plugins_deactivation_checked = isset($options['prevent_plugins_deactivation']) ? $options['prevent_plugins_deactivation'] : 0;
	?>
		<label>
			<input type="checkbox" name="admin_extension_access_control_options[prevent_plugins_deactivation]" value="1" <?php checked($prevent_plugins_deactivation_checked, 1); ?> />
			<span style=" color: #d63638; font-weight: bold;"><?php echo esc_html__('Restrict Plugins Deactivation', 'admin-extension-access-control'); ?></span>
		</label>
		<p class="description" style="font-size: .8rem;"><?php echo esc_html__('Completely disable all plugin deactivations for users.', 'admin-extension-access-control'); ?></p>
	<?php
	}

	/**
	 * Prevent plugins updates callback
	 */

	public function prevent_plugins_updates_callback()
	{

		$options = get_option('admin_extension_access_control_options');
		$prevent_plugins_updates_checked = isset($options['prevent_plugins_updates']) ? $options['prevent_plugins_updates'] : 0;
	?>
		<label>
			<input type="checkbox" name="admin_extension_access_control_options[prevent_plugins_updates]" value="1" <?php checked($prevent_plugins_updates_checked, 1); ?> />
			<span style=" color: #d63638; font-weight: bold;"><?php echo esc_html__('Restrict Plugins Updates', 'admin-extension-access-control'); ?></span>
		</label>
		<p class="description" style="font-size: .8rem;"><?php echo esc_html__('Completely disable all plugin updates for users.', 'admin-extension-access-control'); ?></p>
	<?php
	}

	/**
	 * Production Only Field callback
	 */
	public function production_only_callback()
	{
		$options = get_option('admin_extension_access_control_options');
		$production_only_checked = isset($options['production_only']) ? $options['production_only'] : 0;
	?>
		<label>
			<input type="checkbox" name="admin_extension_access_control_options[production_only]" value="1" <?php checked($production_only_checked, 1); ?> />
			<span style=" color: #d63638; font-weight: bold;"><?php echo esc_html__('Production Only', 'admin-extension-access-control'); ?></span>
		</label>
		<p class="description" style="font-size: .8rem;"><?php echo esc_html__('Only apply lockdown settings on production sites.', 'admin-extension-access-control'); ?></p>
<?php
	}

	/**
	 * Exempt users field callback.
	 * Renders a checkbox list of all administrators on the site.
	 */
	public function exempt_users_callback()
	{
		$options      = get_option('admin_extension_access_control_options', []);
		$exempt_users = isset($options['exempt_users']) ? array_map('intval', (array) $options['exempt_users']) : [];

		// Get all administrators.
		$admins = get_users(['role' => 'administrator']);

		if (empty($admins)) {
			echo '<p>' . esc_html__('No administrators found.', 'admin-extension-access-control') . '</p>';
			return;
		}

		echo '<fieldset class="plw-exempt-users-fieldset">';
		foreach ($admins as $admin) {
			$checked   = in_array($admin->ID, $exempt_users, true) ? 'checked' : '';
			$you_label = ($admin->ID === get_current_user_id()) ? ' <em>(' . esc_html__('you', 'admin-extension-access-control') . ')</em>' : '';
			printf(
				'<label style="display:block; margin-bottom:6px;">
					<input type="checkbox" name="admin_extension_access_control_options[exempt_users][]" value="%d" %s />
					%s (%s)%s
				</label>',
				(int) $admin->ID,
				esc_attr($checked),
				esc_html($admin->display_name),
				esc_html($admin->user_email),
				wp_kses_post($you_label)
			);
		}
		echo '</fieldset>';
		echo '<p class="description" style="font-size: .8rem;">';
		echo esc_html__('Select which administrators are exempt from lockdown rules. At least one must remain selected. Non-exempt admins will not see Admin Extension Access Control settings.', 'admin-extension-access-control');
		echo '</p>';
	}
}
