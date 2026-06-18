<div class="wrap">
	<h1>Plugin Lockdown</h1>

	<form method="post" action="options.php">
		<?php settings_fields('plugin_lockdown_group'); ?>

		<?php $options = $options ?? []; ?>

		<table class="form-table">

			<tr>
				<th>Total Lockdown</th>
				<td>
					<input type="checkbox"
						name="plugin_lockdown_options[total_lockdown]"
						value="1"
						<?php checked($options['total_lockdown'] ?? 0, 1); ?>>
				</td>
			</tr>

			<tr>
				<th>Block New Plugin Installs</th>
				<td>
					<input type="checkbox"
						name="plugin_lockdown_options[block_installs]"
						value="1"
						<?php checked($options['block_installs'] ?? 0, 1); ?>>
				</td>
			</tr>

			<tr>
				<th>Hide Plugins Menu</th>
				<td>
					<input type="checkbox"
						name="plugin_lockdown_options[hide_plugins_menu]"
						value="1"
						<?php checked($options['hide_plugins_menu'] ?? 0, 1); ?>>
				</td>
			</tr>

			<tr>
				<th>Apply Only on Production</th>
				<td>
					<input type="checkbox"
						name="plugin_lockdown_options[production_only]"
						value="1"
						<?php checked($options['production_only'] ?? 1, 1); ?>>
				</td>
			</tr>

		</table>

		<?php submit_button('Save Settings'); ?>
	</form>
</div>
