<?php
/*
Plugin Name:		ZM Installer for Google Tag Manager
Plugin URI:		https://github.com/zeusmicro/zm-google-tag-manager
Description:		This lightweight Wordpress plugin places the Google Tag Manager code at the exactly required places in the webpage.
Version:		0.0.3
Requires at least:	5.2
Requires PHP:		7.0
Author:			Zeus Micro, Ivan Shim
Author URI:		https://zeusmicro.wordpress.com
License:		GPL v2 or later
License URI:		https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:		zm-google-tag-manager
GitHub Plugin URI:	zeusmicro/zm-google-tag-manager

*** History

2020 October 22nd - reboot of this plugin to allow the user to input the GTM code at the administrator panel.
2019 February 22nd - 1st written, user needs to manually enter the code into the program.
2026 June 23rd - rewrote to use standard wp_head / wp_body_open hooks instead of output buffering.
  The old ob_start()/ob_get_clean() approach interfered with wp_print_styles(), preventing CSS from
  loading on the homepage. Standard hooks place GTM at priority 1 in wp_head (very early) and
  immediately after <body> via wp_body_open, which satisfies Google's placement requirements.

*/

if ( !class_exists('zm_google_tag_manager') ) {
class zm_google_tag_manager {

	public static function register_input($parameter_to_pass_through) {
		register_setting( 'general', 'zm_google_tag_manager', 'esc_attr' );
		add_settings_field(
			'zm_google_tag_manager',
			'<label for="zm_google_tag_manager">' . __( 'Google Tag Manager ID' , 'zm_google_tag_manager' ) . '</label>' ,
			array( __CLASS__, 'html_input') ,
			'general'
		);
	return $parameter_to_pass_through;
	}
	public static function html_input() {
		?>
<input type="text" id="zm_google_tag_manager" name="zm_google_tag_manager" placeholder="GTM-nnnnnn" class="regular-text code" value="<?php echo get_option( 'zm_google_tag_manager', '' ); ?>" />
<p class="description"><?php _e('Find out more about this plugin from <a href="https://wordpress.org/plugins/zm-google-tag-manager" target="_blank">https://wordpress.org/plugins/zm-google-tag-manager</a>', 'zm_google_tag_manager'); ?></p>
<p class="description"><?php _e('Get your Google Tag Manager ID from <a href="https://tagmanager.google.com" target="_blank">https://tagmanager.google.com</a>', 'zm_google_tag_manager'); ?></p>
<?php
	}

	public static function output_head_script() {
		$gtm_id = get_option( 'zm_google_tag_manager', '' );
		if ( ! $gtm_id ) return;
		$gtm_id = esc_attr( esc_js( $gtm_id ) );
		echo "<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','" . $gtm_id . "');</script>
<!-- End Google Tag Manager -->\n";
	}

	public static function output_body_noscript() {
		$gtm_id = get_option( 'zm_google_tag_manager', '' );
		if ( ! $gtm_id ) return;
		$gtm_id = esc_attr( esc_js( $gtm_id ) );
		echo '<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . $gtm_id . '"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->' . "\n";
	}

	public static function go() {
		add_action( 'admin_init',    array( __CLASS__, 'register_input' ) );
		add_action( 'wp_head',       array( __CLASS__, 'output_head_script' ), 1 ); // as early as possible
		add_action( 'wp_body_open',  array( __CLASS__, 'output_body_noscript' ), 1 ); // immediately after <body>
	}
}
zm_google_tag_manager::go();
}

?>
