<?php

/**
 * @since 2.0 
 */
class WPET_Packages extends WPET_Module {

	/**
	 * @since 2.0 
	 */
	public function __construct() {
	    $this->mPostType = 'wpet_packages';
	    
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
			'meta' => $_POST['options']
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
		'wpet_cost' => 'Price',
		'wpet_quantity_remaining' => 'Remaining',
		'wpet_quantity' => 'Total Qty',
		'wpet_start-date' => 'Start',
		'wpet_end-date' => 'End'
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
	 * Builds a select menu of packages
	 * 
	 * @since 2.0
	 * @param string $name
	 * @param string $selected_value
	 * @return string 
	 */
	public function selectMenu( $name, $selected_value ) {
	    $s = "<select name='$name' id='$name'>";

	    $s .= '<option value="any">Any Package</option>';
	    
	    foreach( $this->findAllByEvent() AS $pack ) {
		$s .= '<option value="' . $pack->ID . '"';

		$s .= selected( $selected_value, $pack->ID, false ) ;

		$s .= '>';

		$s .= $pack->post_title;

		$s .= '</option>';
	    }

	    $s .= '</select>';
	    return $s;
	}
	
	/**
	 * Returns the max number of packages that can be sold for the specified
	 * event.
	 * 
	 * @param int $event_id
	 * @param int $package_id
	 * @return int
	 */
	public function remaining( $event_id, $package_id ) {	    
	    $max_attendance = (int)get_post_meta( $event_id, 'wpet_max_attendance', true);
	    $packages_total_quantity = (int)get_post_meta( $package_id, 'wpet_quantity', true);
	    $ticket_quantity = (int)get_post_meta( $package_id, 'wpet_ticket-quantity', true );
	    
	    if( 0 == $max_attendance || 0 == $ticket_quantity ) return 0;
	    
	    $max_packages = floor( $max_attendance / $ticket_quantity );
	    
	    if( $max_packages > $packages_total_quantity )
		return $packages_total_quantity;
	    
	    return $max_packages;
	}
	
}// end class