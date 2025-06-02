=== Haptic Feedback ===
Contributors: yourname
Tags: haptic, feedback, vibration, android, mobile, buttons
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.0.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight plugin that enables haptic feedback on buttons and hyperlinks for Android devices.

== Description ==

Haptic Feedback adds vibration feedback to interactive elements on your WordPress site for Android mobile users. This enhances the user experience by providing tactile feedback when users interact with buttons, links, and other clickable elements.

**Features:**

* Enable haptic feedback for Android devices
* Customize vibration duration
* Define which elements trigger feedback using CSS selectors
* Simple settings page in WordPress admin

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/haptic-feedback` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Settings->Haptic Feedback screen to configure the plugin.

== Frequently Asked Questions ==

= Will this work on iOS devices? =

No, currently the plugin only supports Android devices as iOS restricts vibration API access.

= Can I customize which elements trigger haptic feedback? =

Yes, you can specify any CSS selectors in the plugin settings to control which elements trigger the haptic feedback.

= Does this slow down my website? =

No, the plugin is very lightweight and only activates on Android devices that support the vibration API.

== Screenshots ==

1. The settings page where you can configure haptic feedback options.

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release of the Haptic Feedback plugin.
