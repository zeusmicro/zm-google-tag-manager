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
		

		// https://wordpress.stackexchange.com/a/274139
		// Insert tracking code or other stuff directly after BODY opens
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


	public static function register_input() {
		register_setting( 'general', 'gtm_id', 'esc_attr' );
		add_settings_field( 'gtm_id', '<label for="gtm_id">' . __( 'Google Tag Manager ID' , 'is_google_tag_manager' ) . '</label>' , array( __CLASS__, 'html_input') , 'general' );
	}
	public static function html_input() {
		?>
		<input type="text" id="gtm_id" name="gtm_id" placeholder="GTM-nnnn" class="regular-text code" value="<?php echo get_option( 'gtm_id', '' ); ?>" />
		<p class="description"><?php _e( 'The ID from Google&rsquo;s provided code (as emphasized):', 'google_tag_manager' ); ?><br />
			<code>&lt;noscript&gt;&lt;iframe src="//www.googletagmanager.com/ns.html?id=<strong style="color:#c00;">ABC-DEFG</strong>"</code></p>
		<p class="description"><?php _e( 'You can get yours <a href="https://www.google.com/tagmanager/">here</a>!', 'google_tag_manager' ); ?></p>
		<?php
	}


	public static function insert_into_header() {
		if ( ! $gtm_id = get_option( 'is_google_tag_manager', '' ) ) return;
		$gtm_id = esc_js ($gtm_id);
		?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?=$gtm_id; ?>');</script>
<!-- End Google Tag Manager -->
		<?php
	}

	public static function insert_into_body() {
		if ( ! $gtm_id = get_option( 'is_google_tag_manager', '' ) ) return;
		$gtm_id = esc_attr($gtm_id);
		?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?=$gtm_id; ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
		<?php
	}

	public static function go() {
		add_filter( 'admin_init',	array( __CLASS__, 'register_input' ) );
		add_action( 'wp_head',		array( __CLASS__, 'insert_into_header') );
		add_action( 'wp_body_open',	array( __CLASS__, 'insert_into_body') );
	}
}
is_google_tag_manager::go();
}


?>
