<div class="wrap">
	<h1><i class="dashicons dashicons-shield"></i> Plugin Lockdown</h1>
</div>
<?php
$options = get_option('plugin_lockdown_options');
print_r($options);
// print_r(PLUGIN_LOCKDOWN_BASENAME);
?>
<div class="container">
	<input type="radio" name="option" id="1" checked>
	<label for="1">
		<div class="tab-name">
			<i class="dashicons dashicons-dashboard"></i>
			<span><?php echo __('Global Lockdown', 'plugin_lockdown_wp'); ?></span>
		</div>
		<div class="tab-content">

			<form method="POST" action="options.php">
				<?php
				settings_fields('plugin_lockdown_group');
				do_settings_sections('plugin_lockdown');
				submit_button('Save Settings');
				?>
			</form>

		</div>
	</label>

	<input type="radio" name="option" id="2">
	<label for="2">
		<div class="tab-name">
			<i class="dashicons dashicons-dashboard"></i>
			<span><?php echo __('Advanced Settings', 'plugin_lockdown_wp'); ?></span>
		</div>
		<div class="tab-content">
			<h1>Settings</h1>
		</div>
	</label>
</div>
