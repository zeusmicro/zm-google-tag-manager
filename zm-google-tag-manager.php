<?php
/*
Plugin Name:		ZM Installer for Google Tag Manager
Plugin URI:		https://github.com/zeusmicro/zm-google-tag-manager
Description:		This lightweight Wordpress plugin places the Google Tag Manager code at the exactly required places in the webpage.
Version:		0.0.1
Requires at least:	5.0
Requires PHP:		7.0
Author:			Zeus Micro, Ivan Shim
Author URI:		https://zeusmicro.wordpress.com
License:		GPL v2 or later
License URI:		https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:		zm-google-tag-manager

*** Description

What is Google Tag Manager?

Google tag manager is management system that allows you to integrate multiple website add-ons for analytics, advertisements, sharing, etc. into a single management platform.

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

*** History

2020 October 22nd - reboot of this plugin to allow allow the user to input the GTM code at the administrator panel.
2019 February 22nd - 1st written, user needs to manually enter the code into the program.

*** References

https://stackoverflow.com/questions/3581510/wordpress-hook-directly-after-body-tag
https://wordpress.org/plugins/google-tag-manager/

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

	public static $gtm_script = '';
	public static $gtm_noscript = '';

	public static function load_variables($parameter_to_pass_through) {
		if ( ! $gtm_id = get_option( 'zm_google_tag_manager', '' ) ) return $parameter_to_pass_through;
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
		return $parameter_to_pass_through;
	}

	public static function buffer_for_script($parameter_to_pass_through) {
		if ( ! $gtm_id = get_option( 'zm_google_tag_manager', '' ) ) return $parameter_to_pass_through;
		ob_start(); // start filling the buffer
		return $parameter_to_pass_through;
	}

	public static function process_script_buffer($parameter_to_pass_through) {
		if ( ! $gtm_id = get_option( 'zm_google_tag_manager', '' ) ) return $parameter_to_pass_through;
		$the_loaded_buffer = ob_get_clean();
		$pattern ='/<[hH][eE][aA][dD].*>/';
		if (preg_match($pattern, $the_loaded_buffer, $matched_part_of_buffer)) {
			$the_new_body_part = $matched_part_of_buffer[0].self::$gtm_script;
			echo preg_replace($pattern, $the_new_body_part, $the_loaded_buffer);
		}
		ob_flush();
		return $parameter_to_pass_through;
	}

	public static function buffer_for_noscript($parameter_to_pass_through) {
		if ( ! $gtm_id = get_option( 'zm_google_tag_manager', '' ) ) return $parameter_to_pass_through;
		ob_start(); // start filling the buffer
		return $parameter_to_pass_through;
	}

	public static $noscript_buffer_processing_ran_once = false;

	public static function process_noscript_buffer($parameter_to_pass_through) {
		if ( self::$noscript_buffer_processing_ran_once ) return $parameter_to_pass_through; // run this only once
		self::$noscript_buffer_processing_ran_once = true;
		if ( ! $gtm_id = get_option( 'zm_google_tag_manager', '' ) ) return $parameter_to_pass_through;
		$the_loaded_buffer = ob_get_clean();
		$pattern ='/<[bB][oO][dD][yY].*>/';
		if (preg_match($pattern, $the_loaded_buffer, $matched_part_of_buffer)) {
			$the_new_body_part = $matched_part_of_buffer[0].self::$gtm_noscript;
			echo preg_replace($pattern, $the_new_body_part, $the_loaded_buffer);
		}
		ob_flush();
		return $parameter_to_pass_through;
	}

	// above return parameter_to_pass_through are required for add_action of the_title, and the_content
	public static function go() {
		add_action( 'admin_init',	array( __CLASS__, 'register_input' ) );
		add_action( 'init',		array( __CLASS__, 'load_variables' ), 1 ); // run as early as possible
		add_action( 'init',		array( __CLASS__, 'buffer_for_script' ), PHP_INT_MAX ); // run as late as possible
		add_action( 'wp_head',		array( __CLASS__, 'process_script_buffer' ), 1 ); // run as early as possible
		add_action( 'wp_head',		array( __CLASS__, 'buffer_for_noscript' ), PHP_INT_MAX ); // run as late as possible
		// body_class() used by previous hacks does not work, because wordpress does an esc_attr() to the data
		// hook as many places as possible to process & empty the buffer. run as early as possible
		add_action( 'wp_body_open',	array( __CLASS__, 'process_noscript_buffer' ), 1 ); // New core hook
		add_action( 'genesis_before',	array( __CLASS__, 'process_noscript_buffer' ), 1 ); // Genesis
		add_action( 'tha_body_top',	array( __CLASS__, 'process_noscript_buffer' ), 1 ); // Theme Hook Alliance
		add_action( 'body_top',		array( __CLASS__, 'process_noscript_buffer' ), 1 ); // THA Unprefixed
		add_action( 'the_title',	array( __CLASS__, 'process_noscript_buffer' ), 1 ); // Title hook
		add_action( 'the_content',	array( __CLASS__, 'process_noscript_buffer' ), 1 ); // Content hook
		add_action( 'wp_footer',	array( __CLASS__, 'process_noscript_buffer' ), 1 ); // Last resort
	}
}
zm_google_tag_manager::go();
}

?>
