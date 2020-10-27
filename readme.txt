=== Plugin Name ===
Contributors: zeusmicro, ivanshim
Donate link: 
Tags: comments, spam
Requires at least: 5.0
Tested up to: 5.5
Stable tag: 0.1.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to place the Google Tag Manager code into your web pages. Allowing Google Tag Manager to be triggered when your web pages are loaded.

== Description ==

What is Google Tag Manager?

Google tag manager is a management system that allows you to manage multiple website add-ons for analytics, advertisements, sharing, etc. from a single management platform. All of which can then be triggered to load together by first loading the Google Tag Mangager.

For more information, have a look at:

https://tagmanager.google.com

What does this WordPress plugin do?

This plugin allows you to place the Google Tag Manager code into your web pages. Allowing Google Tag Manager to be triggered when your web pages are loaded.

How is this plugin different from the other Google Tag Manager plugins?

Most plugins work by inserting the code via the regular Wordpress hooks. But unfortunately, template authors are not consistent with including these hooks into their themes.

This has resulted in a couple of problems. Firstly, the Google Tag Manager code may not be inserted. Secondly, the placement may not be exactly where Google recommends them to be, which are:

1/ Install the <script> code as high in the <head> of the page as possible.
2/ Install the <noscript> code immediately after the opening <body> tag.

This plugin address these problems by ensuring that these criteria are met. Firstly, to ensure that both portions of code are inserted into the webpage, and secondly, to ensure that the placement is exactly where Google recommends them to be.


== Frequently Asked Questions ==


== Screenshots ==


== Changelog ==

