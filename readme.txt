=== Plugin Lockdown WP ===
Contributors: abbeymaniak
Donate link: https://www.buymeacoffee.com/abbeymaniak
Tags: security, plugins, user roles, access control, lockdown
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 8.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin Lockdown WP allows you to control access to plugin features and menus based on user roles, keeping your WordPress dashboard secure and clean.

== Description ==

**Plugin Lockdown WP** provides essential security and access control for your WordPress site by allowing you to lock down the plugins area. Easily restrict access to plugin activation, deactivation, deletion, and settings pages based on user roles.

Keep your WordPress dashboard clean and prevent unauthorized users or clients from accidentally breaking your site by modifying critical plugins.

= Key Features =

*   **Global Lockdown**: Restrict access to the plugins page for specific user roles.
*   **Role-Based Access Control**: Choose exactly which roles can see and manage plugins.
*   **Dashboard Cleanup**: Hide the plugins menu item from unauthorized users.
*   **Simple Configuration**: Easy-to-use settings panel.

= Upcoming Advanced Features =

*   **Individual Plugin Selection**: Choose exactly which plugins are hidden from specific user roles.
*   **Multisite Compatibility**: Manage lockdown rules across all sites from a single Network Admin panel.
*   **Admin Permissions Control**: Fine-grained permissions for administrators.
*   **Notifications & Alerts**: Get real-time alerts when a restricted user attempts to access locked plugins.
*   **Slack Notifications**: Instant Slack messages whenever a lockdown event is triggered.
*   **Activity & Audit Log**: Full timestamped log of access attempts and plugin visibility events.

== Installation ==

1. Upload the `plugin-lockdown-wp` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to **Tools > Plugin Lockdown** in the WordPress admin menu to configure your settings.

== Frequently Asked Questions ==

= Does this plugin work with custom user roles? =

Yes, Plugin Lockdown WP automatically detects and supports all custom user roles created by other plugins.

= What happens to the plugins page for restricted users? =

Restricted users will not see the "Plugins" menu in the admin sidebar. If they try to access the URL directly, they will be redirected and denied access.

== Changelog ==

= 1.0.1 =
* Initial public release with Global Lockdown features.
* Added "Coming Soon" preview for Advanced Features.

== Upgrade Notice ==

= 1.0.1 =
Initial release.
