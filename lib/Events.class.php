<?php

class WPET_Events extends WPET_Module {

	/**
	 * @since 2.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'registerPostType' ) );
	}

	/**
	 * Add post type for object
	 *
	 * @since 2.0
	 */
	public function registerPostType() {
	    $labels = array(
			'name' => __( 'Events', 'wpet' ),
			'singular_name' => __( 'Event', 'wpet' ),
			'add_new' => __( 'Create Event', 'wpet' ),
			'add_new_item' => __( 'New Event', 'wpet' ),
			'edit_item' => __( 'Edit Event', 'wpet' ),
			'new_item' => __( 'New Event', 'wpet' ),
			'view_item' => __( 'View Event', 'wpet' ),
			'search_items' => __( 'Search Events', 'wpet' ),
			'not_found' => __( 'No Events found', 'wpet' ),
			'not_found_in_trash' => __( 'No Events found in trash', 'wpet' ),
	    );

	    $args = array(
			'public' => false,
			'supports' => array( 'title' ),
			'labels' => $labels,
			'hierarchical' => false,
			'has_archive' => true,
			'query_var' => 'wpet_event',
			'show_ui' => false,
	    );

	    register_post_type( 'wpet_event', $args );
	}

	/**
	 * Returns an array of all active events
	 * 
	 * @since 2.0
	 * @return array 
	 */
	public function getWorkingEvent() {
	    
	    $args = array(
			'post_type' => 'wpet_event',
			'numposts' => '1',
			'orderby' => 'date',
			'order' => 'ASC',
			'post_status' => 'publish',
	    );
	    
	    $posts = get_posts( $args );

		if ( ! empty( $posts ) )
			return current( $posts );
		
		return NULL;
	}
	
	
	/**
	 * Adds the object data to the database
	 *
	 * @since 2.0
	 * @param array $data
	 * @uses wpet_event_add
	 */
	public function add( $data = array() ) {
	    $defaults = array(
			'post_type' => 'wpet_event',
			'post_status' => 'publish',
			'post_name' => 'My Event',
		);

	    if( $user_id = get_current_user_id() )
			$defaults['post_author'] = $user_id;

	    $data = wp_parse_args( $data, $defaults );

	    $data = apply_filters( 'wpet_event_add', $data );

	    $post_id = wp_insert_post( $data );
	    
	    if( isset( $data['meta'] ) && is_array( $data['meta'] ) ) {
			foreach( $data['meta'] as $k => $v ) {
				update_post_meta( $post_id, $k, $v );
			}
	    }
	    
	    return $post_id;
	}

}