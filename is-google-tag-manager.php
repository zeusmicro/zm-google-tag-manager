<?php

/**
 * Plugin Name:       Google Tag Manager Only
 * Plugin URI:        https://github.com/ivanshim/is-google-tag-manager
 * Description:       This is a lightweight Wordpress plugin that places the Google Tag Manager code at the appropriate places in the webpage.
 * Version:           0.0.9
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Ivan Shim
 * Author URI:        https://ivanshim.wordpress.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       is-google-tag-manager
 * Domain Path:       /languages
 *
 * 2020 October 22nd - reboot of this plugin to allow allow the user to input the GTM code at the administrator panel.
 * 2019 February 22nd - 1st written, user needs to manually enter the code into the program.
 */


if ( !class_exists('is_google_tag_manager') ) {
	class is_google_tag_manager {
		
		public static $gtm_id = 'GTM-xxxxxxx'; // just change this variable

		public static function add_to_wp_head() {
			global $gtm_id;
?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?=$gtm_id;?>');</script>
<!-- End Google Tag Manager -->
<?php
		}


		/* https://wordpress.stackexchange.com/a/274139 */
		/* Insert tracking code or other stuff directly after BODY opens */
		public static function wps_add_tracking_body($classes) {
			global $gtm_id;

			// close <body> tag, insert stuff, open some other tag with senseless variable      
			$classes[] = '">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id='.$gtm_id.'"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<noscript></noscript novar="';

			return $classes;
		}

		public static function go() {
			add_action('wp_head', array( __CLASS__, 'add_to_wp_head') );
			add_filter('body_class', 'wps_add_tracking_body', PHP_INT_MAX); // make sure, that's the last filter in the queue
			
		}
	}
	is_google_tag_manager::go();
}












?>
