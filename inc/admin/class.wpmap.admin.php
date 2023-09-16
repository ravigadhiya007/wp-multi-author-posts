<?php
/**
 * WPMAP_Admin Class
 *
 * Handles the admin functionality.
 *
 * @package WordPress
 * @subpackage WP Multi-Author Posts
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPMAP_Admin' ) ) {

	/**
	 * The WPMAP_Admin Class
	 */
	class WPMAP_Admin {

        function __construct() {

            // Add contributors meta box
            add_action( 'add_meta_boxes', array( $this, 'action__add_contributors_meta_box' ) );
            
            // Save contributors
            add_action( 'save_post', array( $this, 'action__save_contributors' ) );
        
        }

        /**
		 * Register meta box for posts
		 * 
		 * @param void
		 * @return void
		 */

        public function action__add_contributors_meta_box() {

            add_meta_box(
                'contributors_meta_box',
                'Contributors',
                array( $this, 'render_contributors_meta_box' ),
                'post',
                'normal',
                'high'
            );

        }

        /**
		 * Meta box callback function
		 * 
		 * @param object $post
		 * @return void
		 */
        public function render_contributors_meta_box( $post ) {

            // Retrieve the list of users with the capability to write posts
            $authors = get_users( array( 'role__in' => array( 'administrator', 'editor', 'author', 'contributor' ) ) );
    
            // Get the selected contributors for this post
            $selected_contributors = get_post_meta( $post->ID, '_contributors', true );
    
            // Display contributor options
            echo '<label for="contributors"><strong>'. __( 'Select Contributors:', 'wp-multi-author-posts' ) .'</strong></label><br><br>';
            
            foreach ( $authors as $author ) {

                $disabled = '';
                $checked  = ( is_array( $selected_contributors ) && in_array( $author->ID, $selected_contributors ) ) ? 'checked' : '';
                
                if (  $author->ID == get_current_user_id() ) {

                    // The current user (author)
                    $disabled = 'disabled="true"';
                    $checked  = 'checked';
                    echo '<input type="hidden" name="contributors[]" value="' . esc_attr( $author->ID ) . '" />';
                
                }
                
                echo '<label for="'. $author->user_login .'" class="selectit">';
                    echo '<input type="checkbox" id="'. $author->user_login .'" name="contributors[]" value="' . esc_attr( $author->ID ) . '" ' . $checked . ' '. $disabled .'>';
                    echo esc_html( $author->first_name . ' ' . $author->last_name . ' (' . $author->user_login . ')' );
                echo '</label><br>';
            
            }

        }

        /**
		 * Save custom meta value
		 * 
		 * @param int $post_id
		 * @return void
		 */
        public function action__save_contributors( $post_id ) {

            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

            if ( isset( $_POST['contributors'] ) ) {

                update_post_meta( $post_id, '_contributors', array_map( 'intval', $_POST['contributors'] ) );
            
            }

        }


	}

    add_action( 'plugins_loaded', function() {

		WPMAP()->admin = new WPMAP_Admin;

	} );

}
