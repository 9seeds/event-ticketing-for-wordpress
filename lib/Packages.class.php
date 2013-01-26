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
		
		add_filter( 'wpet_packages_columns', array( $this, 'defaultColumns' ) );
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
		
		if( isset( $_POST['submit'] ) ) {
		    $data = array(
			'post_title' => $_POST['options']['package-name'],
			'post_name' => sanitize_title_with_dashes( $_POST['options']['package-name'] ),
			'post_content' => stripslashes( $_POST['options']['description'] ),
			'meta' => array(
			    '_wpet_start-date' => $_POST['options']['start-date'],
			    '_wpet_end-date' => $_POST['options']['end-date'],
			    '_wpet_cost' => $_POST['options']['package-cost'],
			    '_wpet_quantity' => $_POST['options']['quantity'],
			    '_wpet_quantity_remaining' => $_POST['options']['quantity']
			)

		    );
		    $this->add( $data );
		}
		
		WPET::getInstance()->display( 'packages-add.php' );
	    } else {
		
		$columns = array();
		
		$rows = $this->findAllByEvent();
		
		
		$data['columns'] = apply_filters( 'wpet_packages_columns', $columns );
		$data['rows'] = apply_filters( 'wpet_packages_rows', $rows );
		WPET::getInstance()->display( 'packages.php', $data );
	    }
	}
	
	/**
	 * Find all packages attached to an event
	 * 
	 * @todo Make it attach to an event
	 * @since 2.0
	 * @return array 
	 */
	public function findAllByEvent() {
	    $args = array(
		'post_type' => 'wpet_packages',
		'showposts' => '-1',
		'posts_per_page' => '-1'
	    );
	    
	    $posts = get_posts( $args );
	    
	    $arr = array();
	    foreach( $posts AS $p ) {
		$meta = get_post_meta( $p->ID );
		 
		foreach( $meta AS $k => $v ) {
		   $p->$k = $v[0];
		}
		
		$arr[] = $p;
	    }
	    
	    return $arr;    
	}
	
	/**
	 * Adds the default columns to the packages list in wp-admin
	 * 
	 * @since 2.0
	 * @param type $columns
	 * @return type 
	 */
	public function defaultColumns( $columns ) {
	    return array(
		'post_title' => 'Package Name',
		'_wpet_cost' => 'Price',
		'_wpet_quantity_remaining' => 'Remaining',
		'_wpet_quantity' => 'Total Qty',
		'_wpet_start-date' => 'Start',
		'_wpet_end-date' => 'End'
	    );
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
	    
	    $post_id = wp_insert_post( $data );
	    
	    foreach( $data['meta'] AS $k => $v ) {
		update_post_meta( $post_id, $k, $v );
	    }
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

}// end class