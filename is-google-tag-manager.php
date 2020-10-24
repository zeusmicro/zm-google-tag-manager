<?php
/*
Plugin Name:		Google Tag Manager Lite
Plugin URI:		https://github.com/ivanshim/is-google-tag-manager
Description:		This is a lightweight Wordpress plugin that places the Google Tag Manager code at the appropriate places in the webpage.
Version:		0.0.91
Requires at least:	5.2
Requires PHP:		7.2
Author:			Ivan Shim
Author URI:		https://ivanshim.wordpress.com
License:		GPL v2 or later
License URI:		https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:		is-google-tag-manager

2020 October 22nd - reboot of this plugin to allow allow the user to input the GTM code at the administrator panel.
2019 February 22nd - 1st written, user needs to manually enter the code into the program.

References

https://stackoverflow.com/questions/3581510/wordpress-hook-directly-after-body-tag
https://wordpress.org/plugins/google-tag-manager/

*/

if ( !class_exists('is_google_tag_manager') ) {
class is_google_tag_manager {

	public static function register_input() {
		register_setting( 'general', 'is_google_tag_manager', 'esc_attr' );
		add_settings_field(
			'is_google_tag_manager',
			'<label for="is_google_tag_manager">' . __( 'Google Tag Manager ID' , 'is_google_tag_manager' ) . '</label>' ,
			array( __CLASS__, 'html_input') ,
			'general'
		);
	}
	public static function html_input() {
		?>
<input type="text" id="is_google_tag_manager" name="is_google_tag_manager" placeholder="GTM-nnnnnn" class="regular-text code" value="<?php echo get_option( 'is_google_tag_manager', '' ); ?>" />
<?php
	}

	public static $gtm_script = '';
	public static $gtm_noscript = '';

	public static function load_variables() {
		if ( ! $gtm_id = get_option( 'is_google_tag_manager', '' ) ) return;
		$gtm_id = esc_attr( esc_js( $gtm_id ) );
		self::$gtm_script = "<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','".$gtm_id."');</script>
<!-- End Google Tag Manager -->";
		self::$gtm_noscript = '<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id='.$gtm_id.'"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->';
	}

	public static function buffer_for_script() {
		if ( ! $gtm_id = get_option( 'is_google_tag_manager', '' ) ) return;
		ob_start(); // start filling the buffer
	}

	public static function process_script_buffer() {
		if ( ! $gtm_id = get_option( 'is_google_tag_manager', '' ) ) return;
		$get_me_buffers = ob_get_clean();
		$pattern ='/<[hH][eE][aA][dD].*>/';
		if (preg_match($pattern, $get_me_buffers, $get_me_buffers_return)) {
			$d_new_body_plus =$get_me_buffers_return[0].self::$gtm_script;
			echo preg_replace($pattern, $d_new_body_plus, $get_me_buffers);
		}
		ob_flush();
	}

	public static function buffer_for_noscript() {
		if ( ! $gtm_id = get_option( 'is_google_tag_manager', '' ) ) return;
		ob_start(); // start filling the buffer
	}

	public static $noscript_buffer_processing_ran_once = false;

	public static function process_noscript_buffer() {
		if ( self::$noscript_buffer_processing_ran_once ) { return; } // run this only once
		self::$noscript_buffer_processing_ran_once = true;
		if ( ! $gtm_id = get_option( 'is_google_tag_manager', '' ) ) return;
		$get_me_buffers = ob_get_clean();
		$pattern ='/<[bB][oO][dD][yY].*>/';
		if (preg_match($pattern, $get_me_buffers, $get_me_buffers_return)) {
			$d_new_body_plus =$get_me_buffers_return[0].self::$gtm_noscript;
			echo preg_replace($pattern, $d_new_body_plus, $get_me_buffers);
		}
		ob_flush();
	}

	public static function go() {
		add_filter( 'admin_init',	array( __CLASS__, 'register_input' ) );
		add_filter( 'init',		array( __CLASS__, 'load_variables' ), 1 ); // run as early as possible
		add_action( 'init',		array( __CLASS__, 'buffer_for_script' ), PHP_INT_MAX ); // run as late as possible
		add_action( 'wp_head',		array( __CLASS__, 'process_script_buffer' ), 1 ); // run as early as possible
		add_action( 'wp_head',		array( __CLASS__, 'buffer_for_noscript' ), PHP_INT_MAX ); // run as late as possible
		// hook as many places as possible to process & empty the buffer
		add_action( 'wp_body_open',	array( __CLASS__, 'process_noscript_buffer' ) ); // New core hook
		add_action( 'genesis_before',	array( __CLASS__, 'process_noscript_buffer' ) ); // Genesis
		add_action( 'tha_body_top',	array( __CLASS__, 'process_noscript_buffer' ) ); // Theme Hook Alliance
		add_action( 'body_top',		array( __CLASS__, 'process_noscript_buffer' ) ); // THA Unprefixed
		add_action( 'the_title',	array( __CLASS__, 'process_noscript_buffer' ), 1 ); // priority must be <= 10
		add_action( 'wp_footer',	array( __CLASS__, 'process_noscript_buffer' ) ); // Last resort
	}
}
is_google_tag_manager::go();
}

?>
