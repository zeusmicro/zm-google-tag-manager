=== Plugin Name ===
Contributors: zeusmicro, ivanshim
Donate link: 
Tags: google tag manager, gtm, google, tag manager, analytics, adwords, theme hook alliance
Requires at least: 5.0
Tested up to: 5.5
Stable tag: 0.0.1
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html


== Description ==

This WordPress plugin makes it easy for you to add the Google Tag Manager code into your webpage.

You can obtain your Google Tag Manager ID from: [ https://tagmanager.google.com ] ( https://tagmanager.google.com "Google Tag Manager" )

After getting your Google Tag Manager ID, login to your WordPress Dashboard > Settings > General > Google Tag Manager ID

Enter your Google Tag Manger ID, and Save Changes

Find out more about the Google Tag Manager, and how it can help you by watching this video:

[ youtube http://www.youtube.com/watch?v=KRvbFpeZ11Y ]

== Installation ==

Upload `zm-google-tag-manager.php` to the `/wp-content/plugins/` directory

Activate the plugin through the 'Plugins' menu in WordPress

Go to `Settings` > `General` and set the ID from your Google Tag Manager account.

== Frequently Asked Questions ==

= What is the Google Tag Manager? =

The Google Tag Manager is a system that allows you to load muitiple website add-ons by installing code into your webpage.

When your webpage loads, the installed Google Tag Manager code will then call the Google Tag Manager system, which can then call multiple add-ons for example, for analytics, advertisements, sharing, etc.

The Google tag manager also provides a management system allowing to manage what you would like to load, and how you would like to have them loaded.

For more information, have a look at: [ https://tagmanager.google.com ] ( https://tagmanager.google.com "Google Tag Manager" )

= What does this WordPress plugin do? =

This WordPress plugin allows you to place the Google Tag Manager code into your web pages. Allowing Google Tag Manager to be triggered when your web pages are loaded.

= How is this plugin different from the other Google Tag Manager plugins? =

Most plugins work by inserting the code via the regular Wordpress hooks. But unfortunately, template authors are not consistent with including these hooks into their themes.

This has resulted in a couple of problems. Firstly, the Google Tag Manager code may not be inserted. Secondly, the placement may not be exactly where Google recommends them to be, which are:

1/ Install the <script> code as high in the <head> of the page as possible.
2/ Install the <noscript> code immediately after the opening <body> tag.

This plugin address these problems by ensuring that these criteria are met. Firstly, to ensure that both portions of code are inserted into the webpage, and secondly, to ensure that the placement is exactly where Google recommends them to be.


== Screenshots ==


== Changelog ==

= 1.0.0 =
* Initial Public Release

