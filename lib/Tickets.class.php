<?php

/**
 * @since 2.0 
 */
class WPET_Tickets extends WPET_AddOn {

	/**
	 * @since 2.0 
	 */
	public function __construct() {
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 10 );
		
		add_action( 'init', array( $this, 'registerPostType' ) );
	}

	/**
	 * Add Tickets links to the Tickets menu
	 * 
	 * @since 2.0
	 * @param type $menu
	 * @return array 
	 */
	public function adminMenu($menu) {
		$menu[] = array('Tickets', 'Tickets', 'add_users', 'wpet_tickets', array(&$this, 'renderAdminPage'));
		return $menu;
	}

	/**
	 * Displays the menu page
	 * 
	 * @since 2.0 
	 */
	public function renderAdminPage() {
	    
	    if( isset( $_GET['add-ticket'] ) ) {
		WPET::getInstance()->display( 'tickets-add.php' );
	    } else {
		//$inst = apply_filters( 'wpet_instructions', $inst = array( 'instructions' => array() ) );
		WPET::getInstance()->display( 'tickets.php' );
	    }
	}
	
	/**
	 * Add post type for object
	 * 
	 * @since 2.0 
	 */
	public function registerPostType() {
	    $labels = array(
		'name' => 'Tickets',
		'singular_name' => 'Ticket',
		'add_new' => 'Create Ticket',
		'add_new_item' => 'New Ticket',
		'edit_item' => 'Edit Ticket',
		'new_item' => 'New Ticket',
		'view_item' => 'View Ticket',
		'search_items' => 'Search Tickets',
		'not_found' => 'No Tickets found',
		'not_found_in_trash' => 'No Tickets found in trash'
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

	    register_post_type( 'wpet_tickets', $args );
	}
	
	
	/**
	 * Adds the object data to the database
	 * 
	 * Must pass in an array of ticket option ids as $data['ticket_options']
	 * 
	 * @since 2.0
	 * @param array $data 
	 */
	public function add( $data ) {
	    $defaults = array(
		'post_type' => 'wpet_tickets',
		'post_status' => 'publish',
		'post_name' => uniqid()
	    );
	    
	    if( !isset( $data['ticket_options'] ) ) {
		return WP_Error( 1001, 'ticket_options is a required field' );
	    }
	    
	    if( $user_id = get_current_user_id() )
		$defaults['post_author'] = $user_id;
	    
	    $data = wp_parse_args( $data, $defaults );
	    
	    $data = apply_filters( 'wpet_ticket_add', $data );
	    
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

}// end class