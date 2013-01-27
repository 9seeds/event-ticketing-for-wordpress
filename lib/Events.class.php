<?php

class WPET_Events extends WPET_Module {

	private static $WORKING_EVENT = NULL;
	
	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->mPostType = 'wpet_event';
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

	    register_post_type( $this->mPostType, $args );
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
			'orderby' => 'date',
			'order' => 'ASC',
			'post_status' => 'publish',
		);

		self::$WORKING_EVENT = $this->findOne( $args );

		return self::$WORKING_EVENT;
	}

	/**
	 * Adds the object data to the database
	 *
	 * @since 2.0
	 * @param array $data
	 * @return int|WP_Error The value 0 or WP_Error on failure. The post ID on success.
	 */
	public function add( $data = array() ) {				
	    $defaults = array(
			'post_title' => __( 'My Event', 'wpet' ),
		);

	    $data = wp_parse_args( $data, $defaults );

		$post_id = parent::add( $data );

	    return $post_id;
	}

}