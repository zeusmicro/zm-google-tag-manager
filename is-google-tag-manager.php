<?php
/*
Plugin Name:		Google Tag Manager Lite
Plugin URI:		https://github.com/ivanshim/is-google-tag-manager
Description:		This is a lightweight Wordpress plugin that places the Google Tag Manager code at the appropriate places in the webpage.
Version:		0.0.9
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

	public static $gtm_script = '';
	public static $gtm_noscript = '';

	public static function load_variables() {
		if ( ! $gtm_id = get_option( 'is_google_tag_manager', '' ) ) return;
		$gtm_id = esc_attr( esc_js( $gtm_id ) );
		self::$gtm_script = "
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','".$gtm_id."');</script>
<!-- End Google Tag Manager -->
";
		self::$gtm_noscript = '
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id='.$gtm_id.'"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
';
	}

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

	public static function insert_into_header() {
		if ( ! $gtm_id = get_option( 'is_google_tag_manager', '' ) ) return;
		echo self::$gtm_script;
	}

	public static function fill_my_buffer() {
		if ( ! $gtm_id = get_option( 'is_google_tag_manager', '' ) ) return;
		ob_start();
	}

	public static $inserted_into_body = false;

	public static function empty_my_buffer() {
		if ( ! $gtm_id = get_option( 'is_google_tag_manager', '' ) ) return;
		if ( self::$inserted_into_body ) { return; } // insert into body once only
		self::$inserted_into_body = true;
		$get_me_buffers = ob_get_clean();
		$pattern ='/<[bB][oO][dD][yY]\s[A-Za-z]{2,5}[A-Za-z0-9 "_=\-\.]+>|<body>/';
		ob_start();
		if (preg_match($pattern, $get_me_buffers, $get_me_buffers_return)) {
			$d_new_body_plus =$get_me_buffers_return[0].self::$gtm_noscript;
			echo preg_replace($pattern, $d_new_body_plus, $get_me_buffers);
		}
		ob_flush();
	}

	public static function go() {
		add_filter( 'init',		array( __CLASS__, 'load_variables' ) );
		add_filter( 'admin_init',	array( __CLASS__, 'register_input' ) );
		add_action( 'wp_head',		array( __CLASS__, 'insert_into_header' ), PHP_INT_MAX - 1 );

		add_action( 'wp_head',		array( __CLASS__, 'fill_my_buffer' ), PHP_INT_MAX ); // start filling buffer
		add_action( 'wp_body_open',	array( __CLASS__, 'empty_my_buffer' ) ); // process & empty buffer (New core hook)
		add_action( 'wp_footer',	array( __CLASS__, 'empty_my_buffer' ) ); // process & empty buffer
	}
}
is_google_tag_manager::go();
}

?>
