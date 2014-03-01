=== Plugin Name ===
Contributors: sourcefound
Donate link: http://memberfind.me
Tags: constant contact
Requires at least: 3.0.1
Tested up to: 3.8.1
Stable tag: 1.6
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A really lightweight, clean and simple Constant Contact widget that collects email addresses to a Constant Contact list.

== Description ==

Lightweight and clean Constant Contact plugin that adds a simple widget that collects email addresses to a contact list in your Constant Contact account:

* No advertising or links.
* Lightweight code (~120 LOC).
* Ajax form submission - no page refresh and minimizes spam.
* PageSpeed optimized with minimized HTML and inlined javascript (entire widget adds about 1200 bytes).
* Cross-browser compliant javascript with no dependencies (no jQuery required).
* No CSS so you can apply your own.
* Supports multiple Constant Contact widgets with separate settings (eg. you can specify a different Constant Contact list for each widget).

Note: Constant Contact account required.

== Installation ==

1. Install the Constant Contact Widget plugin via the WordPress.org plugin directory or upload it to your plugins directory.
1. Activate the Constant Contact Widget plugin.
1. Go to Settings > Constant Contact and enter your Constant Contact login and password.
1. Go to Appearance > Widgets and add the Constant Contact widget to the appropriate sidebar.
1. Under the Constant Contact widget settings, you can set the title, description, button text and the name of the Constant Contact list you want the email address to be added to.
1. You can specify either a success message or an URL. Upon successful submission of the address to Constant Contact, if a message is used, that message will replace the form in the Constant Contact widget box. If a URL is specified, the user will be redirected to that URL.
1. Optionally you can also include the first and last name fields in the form.

== Frequently Asked Questions ==

= Can I use this plugin without a Constant Contact account? =

This widget is designed to add a email address to your Constant Contact list, so you will need a Constant Contact account.

= How do I style the Constant Contact widget? =

The Constant Contact widget is container within a widget div element with the class "widget_sf_widget_constantcontact". 

Using your favourite browser developer tool, see what styles your theme is applying to the widget, and add the class ".widget_sf_widget_constantcontact" to increase the priority of your style to target the Constant Contact widget.

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