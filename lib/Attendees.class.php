<?php

/**
 * 
 * Creates post types:
 * - wpet_attendees
 * 
 * @since 2.0 
 */
class WPET_Attendees extends WPET_AddOn {

	/**
	 * @since 2.0 
	 */
	public function __construct() {
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 25 );
		
		add_action( 'init', array( $this, 'registerPostType' ) );
		
		add_action( 'init', array( $this, 'registerShortcodes' ) );
	}
	
	
	/**
	 * Registers shortcodes for pretty reports on the front end
	 * 
	 * @since 2.0 
	 */
	public function registerShortcodes() {
	    add_shortcode( 'wpet_attendees',  array( $this, 'renderAttendeesShortcode' ) );
	}

	/**
	 * Displays the [wpet_attendees] shortcode to visitors
	 * 
	 * Valid attributes:
	 * - event_id
	 * 
	 * @since 2.0
	 * @param array $atts 
	 */
	public function renderAttendeesShortcode( $atts ) {
	    $data = $this->findAllByEventId(1);
	    WPET::getInstance()->display( 'attendees_shortcode.php', $data );
	}
	
	public function findAllByEventId( $event ) {
	    return array( 
			array( 'name' => 'John Hawkins', 'event' => 'WPET Hack' ),
			array( 'name' => 'Justin Foell', 'event' => 'WPET Hack' ),
			array( 'name' => 'Ben Lobaugh', 'event' => "WPET Hack" )
		   );
	}

	/**
	 * Add Attendee links to the Tickets menu
	 * 
	 * @since 2.0
	 * @param type $menu
	 * @return array 
	 */
	public function adminMenu( $menu ) {
		$menu[] = array( 'Attendees', 'Attendees', 'add_users', 'wpet_attendees', array( $this, 'renderAdminPage' ) );
		$menu[] = array( 'Notify Attendees', 'Notify Attendees', 'add_users', 'wpet_notify_attendees', array( $this, 'renderAttendeeNotifyPage' ) );
		return $menu;
	}
	
	public function renderAttendeeNotifyPage() {
	    if( isset( $_GET['add-notify'] ) ) {
		    WPET::getInstance()->display( 'notify-add.php' );
		} else {
		//$inst = apply_filters( 'wpet_instructions', $inst = array( 'instructions' => array() ) );
		    WPET::getInstance()->display( 'notify.php' );
		}
	}

	public function renderAdminPage() {
		WPET::getInstance()->debug( 'Rendering Attendees page', 'Doing it...' );
		
		if( isset( $_GET['add-attendee'] ) ) {
		    WPET::getInstance()->display( 'attendees-add.php' );
		} else {
		//$inst = apply_filters( 'wpet_instructions', $inst = array( 'instructions' => array() ) );
		    WPET::getInstance()->display( 'attendees.php' );
		}
	}
	
	
	/**
	 * Add post type for object
	 * 
	 * @since 2.0 
	 */
	public function registerPostType() {
	    $labels = array(
		'name' => 'Attendees',
		'singular_name' => 'Attendee',
		'add_new' => 'Create Attendee',
		'add_new_item' => 'New Attendee',
		'edit_item' => 'Edit Attendee',
		'new_item' => 'New Attendee',
		'view_item' => 'View Attendee',
		'search_items' => 'Search Attendees',
		'not_found' => 'No Attendees found',
		'not_found_in_trash' => 'No Attendees found in trash'
	    );

	    $args = array(
		'public' => true,
		'supports' => array( 'page-attributes' ),
		'labels' => $labels,
		'hierarchical' => false,
		'has_archive' => true,
		'query_var' => 'shiplog',
		'rewrite' => array( 'slug' => 'review', 'with_front' => false ),
		//'menu_icon' => WPET_PLUGIN_URL . 'images/icons/reviews.png',
		//'register_meta_box_cb' => array( &$this, 'registerMetaBox' ),
		'show_ui' => false
	    );

	    register_post_type( 'wpet_attendees', $args );
	}
	
	
	/**
	 * Adds the object data to the database
	 * 
	 * @since 2.0
	 * @param array $data 
	 * @return int|WP_Error The value 0 or WP_Error on failure. The post ID on success.
	 */
	public function add( $data ) {
	    $defaults = array(
		'post_type' => 'wpet_attendees',
		'post_status' => 'publish',
		'post_name' => uniqid()
	    );
	    
	    if( $user_id = get_current_user_id() )
		$defaults['post_author'] = $user_id;
	    
	    $data = wp_parse_args( $data, $defaults );
	    
	    $data = apply_filters( 'wpet_attendee_add', $data );
	    
	    return wp_insert_post( $data );
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

} // end class