<?php

/**
 * @since 2.0 
 */
class WPET_Packages extends WPET_Module {

	/**
	 * @since 2.0 
	 */
	public function __construct() {
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 15 );
		
		add_action( 'init', array( $this, 'registerPostType' ) );
	}

	/**
	 * Add Packages links to the Tickets menu
	 * 
	 * @since 2.0
	 * @param type $menu
	 * @return array 
	 */
	public function adminMenu( $menu ) {
		$menu[] = array( 'Packages', 'Packages', 'add_users', 'wpet_packages', array( $this, 'renderAdminPage' ) );
		return $menu;
	}

	/**
	 * Displays the menu page
	 * 
	 * @since 2.0 
	 */
	public function renderAdminPage() {
	    
	    if( isset( $_GET['add-package'] ) ) {
		WPET::getInstance()->display( 'packages-add.php' );
	    } else {
		//$inst = apply_filters( 'wpet_instructions', $inst = array( 'instructions' => array() ) );
		WPET::getInstance()->display( 'packages.php' );
	    }
	}
	
	
	/**
	 * Add post type for object
	 * 
	 * @since 2.0 
	 */
	public function registerPostType() {
	    $labels = array(
		'name' => 'Packages',
		'singular_name' => 'Package',
		'add_new' => 'Create Package',
		'add_new_item' => 'New Package',
		'edit_item' => 'Edit Package',
		'new_item' => 'New Package',
		'view_item' => 'View Package',
		'search_items' => 'Search Packages',
		'not_found' => 'No Packages found',
		'not_found_in_trash' => 'No Packages found in trash'
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

	    register_post_type( 'wpet_packages', $args );
	}
	
	
	/**
	 * Adds the object data to the database
	 * 
	 * @since 2.0
	 * @param array $data 
	 */
	public function add( $data ) {
	    $defaults = array(
		'post_type' => 'wpet_packages',
		'post_status' => 'publish',
		'post_name' => uniqid()
	    );
	    
	    if( $user_id = get_current_user_id() )
		$defaults['post_author'] = $user_id;
	    
	    $data = wp_parse_args( $data, $defaults );
	    
	    $data = apply_filters( 'wpet_package_add', $data );
	    
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