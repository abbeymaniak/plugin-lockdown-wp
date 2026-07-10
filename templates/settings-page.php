<div class="">
	<h1><i class="dashicons dashicons-shield"></i> Plugin Lockdown</h1>
</div>
<?php
$options = get_option('plugin_lockdown_options');
// print_r($options);
// // print_r(PLUGIN_LOCKDOWN_BASENAME);
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
		<div class="tab-content plw-advanced-tab">

			<!-- Coming Soon Header -->
			<div class="plw-coming-soon-header">
				<span class="plw-rocket">🚀</span>
				<h2><?php echo __('Advanced Features', 'plugin_lockdown_wp'); ?></h2>
				<p class="plw-coming-soon-sub"><?php echo __('Powerful features are on the way. Here\'s a sneak peek at what\'s coming next.', 'plugin_lockdown_wp'); ?></p>
			</div>

			<div class="plw-features-grid">

				<!-- Individual Plugin Selection -->
				<div class="plw-feature-card">
					<div class="plw-feature-icon">🔌</div>
					<div class="plw-feature-body">
						<h3><?php echo __('Individual Plugin Selection', 'plugin_lockdown_wp'); ?></h3>
						<p><?php echo __('Choose exactly which plugins are hidden from specific user roles, giving you granular control over your plugin list.', 'plugin_lockdown_wp'); ?></p>
					</div>
					<span class="plw-badge plw-badge--soon"><?php echo __('Coming Soon', 'plugin_lockdown_wp'); ?></span>
				</div>

				<!-- Multisite Compatibility -->
				<div class="plw-feature-card">
					<div class="plw-feature-icon">🌐</div>
					<div class="plw-feature-body">
						<h3><?php echo __('Multisite Compatibility', 'plugin_lockdown_wp'); ?></h3>
						<p><?php echo __('Full WordPress Multisite Network support — manage lockdown rules across all sites from a single Network Admin panel.', 'plugin_lockdown_wp'); ?></p>
					</div>
					<span class="plw-badge plw-badge--soon"><?php echo __('Coming Soon', 'plugin_lockdown_wp'); ?></span>
				</div>

				<!-- Admin Permissions -->
				<div class="plw-feature-card">
					<div class="plw-feature-icon">🛡️</div>
					<div class="plw-feature-body">
						<h3><?php echo __('Admin Permissions Control', 'plugin_lockdown_wp'); ?></h3>
						<p><?php echo __('Define fine-grained permissions for administrators — choose which admins can activate, deactivate, or even see specific plugins.', 'plugin_lockdown_wp'); ?></p>
					</div>
					<span class="plw-badge plw-badge--soon"><?php echo __('Coming Soon', 'plugin_lockdown_wp'); ?></span>
				</div>

				<!-- Notifications & Alerts -->
				<div class="plw-feature-card">
					<div class="plw-feature-icon">🔔</div>
					<div class="plw-feature-body">
						<h3><?php echo __('Notifications & Alerts', 'plugin_lockdown_wp'); ?></h3>
						<p><?php echo __('Get real-time alerts when a restricted user attempts to access or modify locked plugins. Email and in-dashboard notifications included.', 'plugin_lockdown_wp'); ?></p>
					</div>
					<span class="plw-badge plw-badge--soon"><?php echo __('Coming Soon', 'plugin_lockdown_wp'); ?></span>
				</div>

				<!-- Slack Notifications -->
				<div class="plw-feature-card">
					<div class="plw-feature-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5.042 15.165a2.528 2.528 0 0 1-2.52 2.523A2.528 2.528 0 0 1 0 15.165a2.527 2.527 0 0 1 2.522-2.52h2.52v2.52zm1.271 0a2.527 2.527 0 0 1 2.521-2.52 2.527 2.527 0 0 1 2.521 2.52v6.313A2.528 2.528 0 0 1 8.834 24a2.528 2.528 0 0 1-2.521-2.522v-6.313zM8.834 5.042a2.528 2.528 0 0 1-2.521-2.52A2.528 2.528 0 0 1 8.834 0a2.528 2.528 0 0 1 2.521 2.522v2.52H8.834zm0 1.271a2.528 2.528 0 0 1 2.521 2.521 2.528 2.528 0 0 1-2.521 2.521H2.522A2.528 2.528 0 0 1 0 8.834a2.528 2.528 0 0 1 2.522-2.521h6.312zm10.122 2.521a2.528 2.528 0 0 1 2.522-2.521A2.528 2.528 0 0 1 24 8.834a2.528 2.528 0 0 1-2.522 2.521h-2.522V8.834zm-1.268 0a2.528 2.528 0 0 1-2.523 2.521 2.527 2.527 0 0 1-2.52-2.521V2.522A2.527 2.527 0 0 1 15.165 0a2.528 2.528 0 0 1 2.523 2.522v6.312zm-2.523 10.122a2.528 2.528 0 0 1 2.523 2.522A2.528 2.528 0 0 1 15.165 24a2.527 2.527 0 0 1-2.52-2.522v-2.522h2.52zm0-1.268a2.527 2.527 0 0 1-2.52-2.523 2.526 2.526 0 0 1 2.52-2.52h6.313A2.527 2.527 0 0 1 24 15.165a2.528 2.528 0 0 1-2.522 2.523h-6.313z" fill="#4A154B" />
						</svg></div>
					<div class="plw-feature-body">
						<h3><?php echo __('Slack Notifications', 'plugin_lockdown_wp'); ?></h3>
						<p><?php echo __('Send instant Slack messages to your team channel whenever a lockdown event is triggered. Configure webhooks, channels, and message templates.', 'plugin_lockdown_wp'); ?></p>
					</div>
					<span class="plw-badge plw-badge--soon"><?php echo __('Coming Soon', 'plugin_lockdown_wp'); ?></span>
				</div>

				<!-- Audit Log -->
				<div class="plw-feature-card">
					<div class="plw-feature-icon">📋</div>
					<div class="plw-feature-body">
						<h3><?php echo __('Activity & Audit Log', 'plugin_lockdown_wp'); ?></h3>
						<p><?php echo __('A full timestamped log of every access attempt, role change, and plugin visibility event — exportable to CSV for compliance and review.', 'plugin_lockdown_wp'); ?></p>
					</div>
					<span class="plw-badge plw-badge--soon"><?php echo __('Coming Soon', 'plugin_lockdown_wp'); ?></span>
				</div>

			</div><!-- /.plw-features-grid -->

			<p class="plw-coming-soon-footer">
				<?php echo __('⭐ Have a feature suggestion? ', 'plugin_lockdown_wp'); ?>
				<a href="https://wordpress.org/support/plugin/plugin-lockdown-wp" target="_blank" rel="noopener noreferrer"><?php echo __('Let us know on the support forum', 'plugin_lockdown_wp'); ?></a>.
			</p>

		</div><!-- /.plw-advanced-tab -->
	</label>
</div>