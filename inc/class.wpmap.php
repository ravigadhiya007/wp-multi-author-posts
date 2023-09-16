<?php
/**
 * WPMAP Class
 *
 * Handles the plugin functionality.
 *
 * @package WordPress
 * @subpackage WP Multi-Author Posts
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPMAP' ) ) {

	/**
	 * The main WPMAP class
	 */
	class WPMAP {

		private static $_instance = null;

		var $admin = null,
		    $front = null;

		public static function instance() {

			if ( is_null( self::$_instance ) )
				self::$_instance = new self();

			return self::$_instance;

		}

		function __construct() {

			// Load text domian after plugin loaded
			add_action( 'plugins_loaded', array( $this, 'action__plugins_loaded' ), 1 );

		}

		/**
		 * Load Text Domain
		 * This gets the plugin ready for translation
		 *
		 * @return void
		 */

		function action__plugins_loaded() {

			global $wp_version;

			// Set filter for plugin's languages directory
			$wpmap_lang_dir = dirname( WPMAP_PLUGIN_BASENAME ) . '/languages/';
			$wpmap_lang_dir = apply_filters( 'wpmap_languages_directory', $wpmap_lang_dir );

			// Traditional WordPress plugin locale filter.
			$get_locale = get_locale();

			if ( $wp_version >= 4.7 ) {
				$get_locale = get_user_locale();
			}

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale',  $get_locale, 'wp-multi-author-posts' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'wp-multi-author-posts' , $locale );

			// Setup paths to current locale file
			$mofile_global = WP_LANG_DIR . '/plugins/' . basename( WPMAP_DIR ) . '/' . $mofile;
			
			if ( file_exists( $mofile_global ) ) {

				// Look in global /wp-content/languages/plugin-name folder
				load_textdomain( 'wp-multi-author-posts', $mofile_global );

			} else {

				// Load the default language files
				load_plugin_textdomain( 'wp-multi-author-posts', false, $wpmap_lang_dir );
			
			}

		}

	}
}

function WPMAP() {

	return WPMAP::instance();
	
}

WPMAP();
