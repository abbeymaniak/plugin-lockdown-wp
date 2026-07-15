=== Dashboard Extension Lockdown ===
Contributors: abbeymaniak
Donate link: https://www.buymeacoffee.com/abbeymaniak
Tags: security, plugins, user roles, access control, lockdown
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Control access to plugin features and menus based on user roles, keeping your WordPress dashboard secure.

== Description ==

**Dashboard Extension Lockdown** provides essential security and access control for your WordPress site by allowing you to lock down the plugins area. Easily restrict access to plugin activation, deactivation, deletion, and settings pages based on user roles.

Keep your WordPress dashboard clean and prevent unauthorized users or clients from accidentally breaking your site by modifying critical plugins.

= Key Features =

* **Global Lockdown**: Restrict access to the plugins page for specific user roles.
* **Exempt Users Whitelist**: Designate trusted administrators who bypass all lockdown rules. Only exempt users can see and configure Dashboard Extension Lockdown settings.
* **Restrict Access**: Remove the plugins page for specific user roles.
* **Restrict Add Plugins**: Remove the ability to add plugins for specific user roles.
* **Restrict Delete Plugins**: Remove the ability to delete plugins for specific user roles.
* **Restrict Activate Plugins**: Remove the ability to activate plugins for specific user roles.
* **Restrict Deactivate Plugins**: Remove the ability to deactivate plugins for specific user roles.
* **Restrict Install Plugins**: Remove the ability to install plugins for specific user roles.
* **Role-Based Access Control**: Choose exactly which roles can see and manage plugins.
* **Dashboard Cleanup**: Hide the plugins menu item from unauthorized users.
* **Simple Configuration**: Easy-to-use settings panel.

= Upcoming Advanced Features =

*   **Individual Plugin Selection**: Choose exactly which plugins are hidden from specific user roles.
*   **Multisite Compatibility**: Manage lockdown rules across all sites from a single Network Admin panel.
*   **Admin Permissions Control**: Fine-grained permissions for administrators.
*   **Notifications & Alerts**: Get real-time alerts when a restricted user attempts to access locked plugins.
*   **Slack Notifications**: Instant Slack messages whenever a lockdown event is triggered.
*   **Activity & Audit Log**: Full timestamped log of access attempts and plugin visibility events.
*   **Plugin-Based Access Control**: Choose exactly which plugins are hidden from specific user roles.

== Installation ==

1. Upload the `dashboard-extension-lockdown` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to **Dashboard Extension Lockdown** in the WordPress admin menu to configure your settings.

== Frequently Asked Questions ==

= What happens to the plugins page for restricted users? =

Restricted users will not see the "Plugins" menu in the admin sidebar. If they try to access the URL directly, they will be redirected and denied access.

= Can I prevent users from adding or installing new plugins? =

Yes, you can easily restrict the "Add Plugins" and "Install Plugins" functionality for specific user roles.

= Is it possible to stop certain roles from deleting plugins? =

Yes, you can configure the plugin to remove the ability to delete plugins for specific user roles, keeping your site's core functionalities safe.

= Can I restrict users from activating or deactivating plugins? =

Absolutely. You have full control over who can activate or deactivate plugins by using the role-based access control.

= What are exempt users? =

Exempt users are administrators who bypass all lockdown rules. When the plugin is first activated, the activating user is automatically added to the exempt list. Only exempt users can see and modify Dashboard Extension Lockdown settings. Non-exempt administrators are fully restricted by all enabled lockdown rules.

= What happens if no exempt users are configured? =

If no exempt users are configured (e.g., the plugin was activated via WP-CLI without a user context), all lockdown rules remain inactive and an admin notice is shown prompting any administrator to configure the exempt list.

= How do I recover access if all exempt users are removed or unavailable? =

If all exempt administrators lose access, you can recover using one of these methods:

1. WP-CLI: `wp plugin deactivate dashboard-extension-lockdown`
2. FTP/SFTP: Rename or delete the `dashboard-extension-lockdown` folder in `/wp-content/plugins/`
3. Database: Run `DELETE FROM wp_options WHERE option_name = 'dashboard_extension_lockdown_options';` to reset all settings

== Changelog ==

= 1.1.0 =
* Added Exempt Users Whitelist — designate trusted admins who bypass lockdown rules.
* Settings page now hidden from non-exempt administrators.
* Auto-captures activating user as the first exempt admin.
* Admin notice shown when no exempt users are configured (fail-open safety).
* Removed DISALLOW_FILE_MODS in favour of granular per-user capability filtering.
* Fixed plugin self-exemption bugs (bulk actions, cron, REST API).
* Removed debug output (print_r) from apply_rules.
* Cleaned up unused variables.

= 1.0.1 =
* Initial public release with Global Lockdown features.
* Added "Coming Soon" preview for Advanced Features.

