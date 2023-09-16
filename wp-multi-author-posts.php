<?php
/**
 * Plugin Name: WP Multi-Author Posts
 * Plugin URL: #
 * Description: WP Multi-Author Posts allows you to create, manage and display authors for your posts.
 * Version: 1.0
 * Author: Ravi Gadhiya
 * Author URI: https://profiles.wordpress.org/ravigadhiyawp
 * Text Domain: wp-multi-author-posts
 * Domain Path: /languages
 *
 * Copyright: © 2009-2019 Ravi Gadhiya.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions
 *
 * @package WP Multi-Author Posts
 * @since 1.0
 */

if ( ! defined( 'WPMAP_VERSION' ) ) {
	define( 'WPMAP_VERSION', '1.0' ); // Version of plugin
}

if ( ! defined( 'WPMAP_FILE' ) ) {
	define( 'WPMAP_FILE', __FILE__ ); // Plugin File
}

if ( ! defined( 'WPMAP_DIR' ) ) {
	define( 'WPMAP_DIR', dirname( __FILE__ ) ); // Plugin dir
}

if ( ! defined( 'WPMAP_URL' ) ) {
	define( 'WPMAP_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}

if ( ! defined( 'WPMAP_PLUGIN_BASENAME' ) ) {
	define( 'WPMAP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // Plugin base name
}

if ( ! defined( 'WPMAP_META_PREFIX' ) ) {
	define( 'WPMAP_META_PREFIX', 'wpmap_' ); // Plugin metabox prefix
}

if ( ! defined( 'WPMAP_PREFIX' ) ) {
	define( 'WPMAP_PREFIX', 'wpmap' ); // Plugin prefix
}

/**
 * Initialize the main class
 */
if ( ! function_exists( 'WPMAP' ) ) {

	if ( is_admin() ) {
		require_once( WPMAP_DIR . '/inc/admin/class.' . WPMAP_PREFIX . '.admin.php' );
	} else {
		require_once( WPMAP_DIR . '/inc/front/class.' . WPMAP_PREFIX . '.front.php' );
	}

	// Initialize all the things.
	require_once( WPMAP_DIR . '/inc/class.' . WPMAP_PREFIX . '.php' );
}
