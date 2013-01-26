<?php

/**
 * @since 2.0
 */
class WPET_Coupons extends WPET_Module {

	/**
	 * @since 2.0
	 */
	public function __construct() {
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 20 );

	    add_action( 'init', array( $this, 'registerPostType' ) );

	    add_action( 'load-tickets_page_wpet_coupons', array( $this, 'contextHelp' ) );
		add_filter( 'wpet_coupons_columns', array( $this, 'defaultColumns' ) );
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
			'id'	=> 'overview',
			'title'	=> __( 'Overview' ),
			'content'	=> '<p>' . __( 'This screen provides access to all of your posts.' ) . '</p>',
		    )
	    );
	    $screen->add_help_tab(
		    array(
			'id'	=> 'available-actions',
			'title'	=> __( 'Available Actions' ),
			'content'	=> '<p>' . __( 'Hovering over a row in the coupon list will display action links that allow you to manage each coupon. You can perform the following actions:' ) . '</p>'.
				'<ul>'.
					'<li>'. __( '<strong>Edit</strong> takes you to the editing screen for that coupon. You can also reach that screen by clicking on the coupon code itself.' ) .'</li>'.
				'</ul>',
		    )
	    );

//		$screen->set_help_sidebar(
//			'<p><strong>' . __( 'For more information:' ) . '</strong></p>' .
//			'<p>' . __( '<a href="http://codex.wordpress.org/Administration_Screens#Comments" target="_blank">Documentation on Comments</a>' ) . '</p>' .
//			'<p>' . __( '<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>' ) . '</p>'
//		);

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
		
		if( isset( $_POST['submit'] ) ) {
		    
		    $data = array(
			'post_title' => $_POST['options']['coupon-code'],
			'post_name' => sanitize_title_with_dashes( $_POST['options']['coupon-code'] ),
			'meta' => array(
			    '_wpet_type' => $_POST['options']['type'],
			    '_wpet_amount' => $_POST['options']['amount'],
			    '_wpet_quantity' => (int)$_POST['options']['uses'],
			    '_wpet_quantity_remaining' => (int)$_POST['options']['uses']
			)
		    );
		    
		    $this->add( $data );
		}
		
		WPET::getInstance()->display( 'coupons-add.php' );
	    } else {
		$columns = array();
		
		$rows = $this->findAll( true );
		
		
		$data['columns'] = apply_filters( 'wpet_coupons_columns', $columns );
		$data['rows'] = apply_filters( 'wpet_coupons_rows', $rows );
		WPET::getInstance()->display( 'coupons.php', $data );
	    }
	}
	
	/**
	 * Adds the default columns to the ticket options list in wp-admin
	 * 
	 * @since 2.0
	 * @param type $columns
	 * @return type 
	 */
	public function defaultColumns( $columns ) {
	    return array(
		'post_title' => 'Name',
		'post_name' => 'Coupon Code',
		'_wpet_pretty_amount' => 'Amount',
		'_wpet_quantity_remaining' => 'Remaining',
		'_wpet_quantity' => 'Total'
	    );
	}
	
	/**
	 * Retrieves all the coupons from the db
	 * @param type $prettyAmount
	 * @return array 
	 */
	public function findAll( $prettyAmount = false ) {
	    $args = array(
		'post_type' => 'wpet_coupons',
		'showposts' => '-1',
		'posts_per_page' => '-1'
	    );
	    
	    $posts = get_posts( $args );
	    
	    if( $prettyAmount ) {
		foreach( $posts AS $p ) {
		    switch( $p->_wpet_type ) {
			case 'percentage':
			    $p->_wpet_pretty_amount = $p->_wpet_amount . '%';
			    break;
			case 'flat-rate':
			    $p->_wpet_pretty_amount = WPET::getInstance()->currency->format( WPET::getInstance()->settings->currency, $p->_wpet_amount );
			    break;
		    }
		}
	    }
	    
	    return $posts;
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