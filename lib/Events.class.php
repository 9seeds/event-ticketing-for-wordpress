<?php

class WPET_Events extends WPET_Module {

	private static $WORKING_EVENT = NULL;
	
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
	 * Returns the current working event
	 * 
	 * @since 2.0
	 * @return array 
	 */
	public function getWorkingEvent() {
		if ( ! empty( self::$WORKING_EVENT ) )
			return self::$WORKING_EVENT;
		
		$args = array(
			'numposts' => 1,
			'post_status' => 'publish',
		);

		$posts = $this->find( $args );

		if ( ! empty( $posts ) )
			 self::$WORKING_EVENT = current( $posts );

		return self::$WORKING_EVENT;
	}

	/**
	 * Finds wpet_event objects
	 *
	 * @since 2.0
	 * @param array $args
	 */
	public function find( $args ) {
		
	    $defaults = array(
			'post_type' => 'wpet_event',
			'numposts' => -1,
			'orderby' => 'date',
			'order' => 'ASC',
			'post_status' => array( 'publish', 'archive' ),
	    );

		$data = wp_parse_args( $args, $defaults );
		
	    $posts = get_posts( $data );

	    $ret = array();
	    foreach ( $posts as $p ) {
			$ret[] = array(
				'ID' => $p->ID,
				'display-name' => $p->post_title,
				'meta' => array(
					'event-date' => get_post_meta( $p->ID, 'wpet-event-date', true ),
					'max-attendance' => get_post_meta( $p->ID, 'wpet-max-attendance', true ),
					'event-status' => get_post_meta( $p->ID, 'wpet-event-status', true ),
				),
			);
	    }
	    return $ret;
	}
	
	
	/**
	 * Adds the object data to the database
	 *
	 * @since 2.0
	 * @param array $data
	 * @return int|WP_Error The value 0 or WP_Error on failure. The post ID on success.
	 * @uses wpet_event_add
	 */
	public function add( $data = array() ) {
	    $defaults = array(
			'post_type' => 'wpet_event',
			'post_status' => 'publish',
			'post_title' => __( 'My Event', 'wpet' ),
		);

	    if( $user_id = get_current_user_id() )
			$defaults['post_author'] = $user_id;

	    $data = wp_parse_args( $data, $defaults );

	    $data = apply_filters( 'wpet_event_add', $data );

	    $post_id = wp_insert_post( $data );
	    
	    if( isset( $data['meta'] ) && is_array( $data['meta'] ) ) {
			foreach( $data['meta'] as $k => $v ) {
				update_post_meta( $post_id, "wpet_{$k}", $v );
			}
	    }

		if ( isset( $data['ID'] ) && isset( self::$WORKING_EVENT['ID'] ) &&
			 $data['ID'] == self::$WORKING_EVENT['ID'] )
			self::$WORKING_EVENT = $data;
	    return $post_id;
	}
	
	/**
	 * Helper function to update the post record in the database
	 *
	 * @param integer $post_id
	 * @param array $data
	 * @return int|WP_Error The value 0 or WP_Error on failure. The post ID on success.
	 */
	public function update( $post_id, $data ) {

	    $data['ID'] = $post_id;
	    return $this->add( $data );
	}

}