<?php

/**
 * @since 2.0 
 */
class WPET_Coupons extends WPET_AddOn {

	/**
	 * @since 2.0 
	 */
	public function __construct() {
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 20 );
	
	    add_action( 'init', array( $this, 'registerPostType' ) );
	    
	    add_action( 'load-tickets_page_wpet_coupons', array( $this, 'contextHelp' ) );
	}
	
	/**
	 * Displays page specific contextual help through the contextual help API
	 * 
	 * @see http://codex.wordpress.org/Function_Reference/add_help_tab
	 * @since 2.0
	 */
	public function contextHelp() {
	    $screen = get_current_screen();
	    $screen->add_help_tab( 
		    array(
			'id'	=> 'my_help_tab',
			'title'	=> __( 'My Help Tab' ),
			'content'	=> '<p>' . __( 'Descriptive content that will show in My Help Tab-body goes here.' ) . '</p>',
		    ) 
	    );
	}

	/**
	 * Add Coupons links to the Tickets menu
	 * 
	 * @since 2.0
	 * @param type $menu
	 * @return array 
	 */
	public function adminMenu($menu) { 
		$menu[] = array( 'Coupons', 'Coupons', 'add_users', 'wpet_coupons', array( $this, 'renderAdminPage' ) );
		return $menu;
	}

	public function renderAdminPage() {
	    
	    if( isset( $_GET['add-coupons'] ) ) {
		WPET::getInstance()->display( 'coupons-add.php' );
	    } else {
		// $inst = apply_filters( 'wpet_instructions', $inst = array( 'instructions' => array() ) );
		WPET::getInstance()->display( 'coupons.php' );
	    }
	}
	
	/**
	 * Add post type for object
	 * 
	 * @since 2.0 
	 */
	public function registerPostType() {
	    $labels = array(
			'name' => 'Coupons',
			'singular_name' => 'Coupon',
			'add_new' => 'Create Coupon',
			'add_new_item' => 'New Coupon',
			'edit_item' => 'Edit Coupon',
			'new_item' => 'New Coupon',
			'view_item' => 'View Coupon',
			'search_items' => 'Search Coupons',
			'not_found' => 'No Coupons found',
			'not_found_in_trash' => 'No Coupons found in trash'
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

	    register_post_type( 'wpet_coupons', $args );
	}

	
	/**
	 * Adds the object data to the database
	 * 
	 * @since 2.0
	 * @param array $data 
	 */
	public function add( $data ) {
	    $defaults = array(
		'post_type' => 'wpet_coupons',
		'post_status' => 'publish',
		'post_name' => uniqid()
	    );
	    
	    if( $user_id = get_current_user_id() )
		$defaults['post_author'] = $user_id;
	    
	    $data = wp_parse_args( $data, $defaults );
	    
	    $data = apply_filters( 'wpet_coupon_add', $data );
	    
	    wp_insert_post( $data );
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