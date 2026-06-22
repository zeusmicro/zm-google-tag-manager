# ZM Installer for Google Tag Manager

**Contributors:** zeusmicro, ivanshim
**Tags:** google tag manager, gtm, google, tag manager, analytics
**Requires at least:** 5.2
**Requires PHP:** 7.0
**License:** GPLv2 or later — https://www.gnu.org/licenses/gpl-2.0.html

## Description

This WordPress plugin makes it easy for you to add the Google Tag Manager code into your webpage.

You can obtain your Google Tag Manager ID from [tagmanager.google.com](https://tagmanager.google.com).

After getting your Google Tag Manager ID, log in to your WordPress Dashboard > **Settings** > **General** > **Google Tag Manager ID**, enter your ID, and save.

## Installation

1. Upload `zm-google-tag-manager.php` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Go to **Settings** > **General** and set the ID from your Google Tag Manager account.

## Frequently Asked Questions

**What is Google Tag Manager?**

Google Tag Manager is a system that lets you load multiple website add-ons (analytics, ads, etc.) by installing a single code snippet into your webpage. When your page loads, GTM calls those add-ons based on rules you configure.

**What does this plugin do?**

It inserts the GTM `<script>` tag early in `<head>` (via `wp_head` priority 1) and the `<noscript>` fallback immediately after `<body>` (via `wp_body_open` priority 1), matching Google's recommended placement.

**How is this different from other GTM plugins?**

It uses standard WordPress hooks (`wp_head`, `wp_body_open`) with no output buffering, making it lightweight and compatible with all themes and other plugins.

## Changelog

### 0.0.3 — 2026 June 23
- Rewrote to use standard `wp_head` / `wp_body_open` hooks instead of output buffering.
  The old `ob_start()`/`ob_get_clean()` approach interfered with `wp_print_styles()`, preventing CSS from loading on the front page.

### 0.0.2
- Added admin settings field for GTM ID input.

### 0.0.1 — 2021 August 16
- Initial public release.
- Renamed `master` branch to `main`.
