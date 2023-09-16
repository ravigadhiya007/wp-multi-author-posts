<?php
/**
 * WPMAP_Front Class
 *
 * Handles the Frontend functionality.
 *
 * @package WordPress
 * @subpackage WP Multi-Author Posts
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPMAP_Front' ) ) {

	/**
	 * The WPMAP_Front Class
	 */
	class WPMAP_Front {

		function __construct() {

            // Show contributors on single post page
            add_filter( 'the_content', array( $this, 'filter__display_contributors_on_single_post' ) );
            
            // Modify author archive query
            add_action( 'pre_get_posts', array( $this, 'action__modify_author_archive_query' ) );
        
        }

        /**
		 * Show post contributors on single post page
		 * 
		 * @param string $content
		 * @return string $content
		 */
        public function filter__display_contributors_on_single_post( $content ) {

            if ( is_single() ) {

                $contributors = get_post_meta( get_the_ID(), '_contributors', true );
    
                if ( $contributors ) {

                    $output  = '<div class="contributors-list">';
                    $output .= '<h3>'. __( 'Contributors:', 'wp-multi-author-posts' ) .'</h3>';
    
                    foreach ( $contributors as $contributor_id ) {

                        $user    = get_userdata( $contributor_id );
                        $output .= '<a href="' . esc_url( get_author_posts_url( $contributor_id ) ) . '">';
                        $output .= get_avatar( $contributor_id, 32 ) . ' ';
                        $output .= esc_html( $user->first_name . ' ' . $user->last_name . ' (' . $user->user_login . ')' );
                        $output .= '</a><br>';

                    }
    
                    $output  .= '</div>';
                    $content .= $output;

                }

            }
    
            return $content;
        }

        /**
		 * Modify author archive query to show posts related to contributors
		 * 
		 * @param object $query
		 * @return void
		 */
        public function action__modify_author_archive_query( $query ) {

            if ( is_author() && $query->is_main_query() ) {

                $author_id          = get_queried_object_id(); // Get the current author's ID
                $contributors_posts = get_posts( array(
                    'meta_query' => array(
                        array(
                            'key'     => '_contributors',
                            'value'   => sprintf( ':"%s";', $author_id ), // Ensure an exact match
                            'compare' => 'LIKE',
                        ),
                    ),
                ) );
        
                if ( ! empty( $contributors_posts ) ) {

                    $post_ids = wp_list_pluck( $contributors_posts, 'ID' );
                    unset( $query->query_vars['author_name'] ); 
                    $query->set('post__in', $post_ids );

                }

            }

        }

	}

    add_action( 'plugins_loaded', function() {

		WPMAP()->front = new WPMAP_Front;
        
	} );

}
