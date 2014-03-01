=== Plugin Name ===
Contributors: sourcefound
Donate link: http://memberfind.me
Tags: contant contact
Requires at least: 3.0.1
Tested up to: 3.8.1
Stable tag: 1.6
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A really lightweight, clean and simple Constant Contant widget

== Description ==

Lightweight and clean Constant Contact plugin that adds a simple widget that collects email addresses to a contact list in your Constant Contact account:

* No advertising or links.
* Lightweight code (~120 LOC).
* Ajax form submission - no page refresh and minimizes spam.
* PageSpeed optimized with minimized HTML and inlined javascript (entire widget adds about 1200 bytes).
* Cross-browser compliant javascript with no dependencies (no jQuery required).
* No CSS so you can apply your own.
* Supports multiple widgets with separate settings.

Note: Constant Contact account required.

== Installation ==

1. Install the plugin via the WordPress.org plugin directory or upload it to your plugins directory.
1. Activate the plugin.
1. Go to Settings > Constant Contact and enter your Constant Contact login and password.

== Changelog ==

= 1.0 =
* Initial release

= 1.1 =
* Improved error handling

= 1.2 =
* Replaced WP deprecated function attribute_escape

= 1.3 =
* Option to collect names

= 1.4 =
* Javascript rewritten to be wptexturize friendly when using the_widget() call

= 1.5 =
* Allows redirection to url on success

= 1.6 =
* Wrapped in form element, allows enter key to submit form