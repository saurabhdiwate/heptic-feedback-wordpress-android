# Haptic Feedback for WordPress

A lightweight WordPress plugin that enables haptic feedback on buttons and hyperlinks for Android devices.

## Description

This plugin adds subtle vibration feedback when users interact with buttons and links on your WordPress website. The haptic feedback is only activated on Android devices that support vibration.

## Features

- Adds haptic feedback to buttons, links, and form inputs
- Customizable vibration duration
- Configurable CSS selectors to target specific elements
- Simple settings page in the WordPress admin
- Lightweight with minimal impact on performance
- Only activates on supported Android devices

## Installation

1. Upload the `haptic-feedback` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the settings under 'Settings > Haptic Feedback'

## Configuration

You can customize the following settings:

- **Enable/Disable**: Turn haptic feedback on or off
- **Vibration Duration**: Set the duration of vibration in milliseconds (1-1000ms)
- **CSS Selectors**: Define which elements should trigger haptic feedback (default: `a, button, .btn, input[type="button"], input[type="submit"]`)

## Requirements

- WordPress 5.0 or higher
- Android device with vibration support

## Browser Compatibility

Haptic feedback works on Android devices using browsers that support the Vibration API:
- Chrome for Android
- Firefox for Android
- Samsung Internet
- Most other modern mobile browsers

Note: This feature is not available on iOS devices as iOS does not expose the Vibration API to web applications.

## Technical Details

The plugin uses the Web Vibration API (`navigator.vibrate()`) which is supported on most Android browsers. It selectively targets Android devices and only initializes on devices that support the vibration API.

## Frequently Asked Questions

### Does this work on iPhones?
No, Apple does not allow web applications to trigger haptic feedback through browsers on iOS.

### Will this affect desktop users?
No, the haptic feedback code only runs on Android devices that support vibration.

### Can I target specific buttons or links?
Yes, you can customize which elements trigger haptic feedback by modifying the CSS selectors in the plugin settings.

## License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
```
