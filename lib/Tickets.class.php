<?php

/**
 * @since 2.0
 */
class WPET_Tickets extends WPET_Module {

	/**
	 * @since 2.0
	 */
	public function __construct() {
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 10 );

		add_action( 'init', array( $this, 'registerPostType' ) );

		add_filter( 'wpet_tickets_columns', array( $this, 'defaultColumns' ) );

	}

	/**
	 * Add Tickets links to the Tickets menu
	 *
	 * @since 2.0
	 * @param type $menu
	 * @return array
	 */
	public function adminMenu($menu) {
		$menu[] = array( 'Tickets', 'Tickets', 'add_users', 'wpet_tickets', array( $this, 'renderAdminPage' ) );
		return $menu;
	}

	/**
	 * Displays the menu page
	 *
	 * @since 2.0
	 */
	public function renderAdminPage() {

	    if( isset( $_GET['add-ticket'] ) ) {

		if( isset( $_POST['submit'] ) ) {
		    $data = array(
			'post_title' => $_POST['options[ticket-name]'],
			'post_name' => sanitize_title_with_dashes( $_POST['options[ticket-name]'] ),
			'post_content' => serialize( $_POST['options'] )
		    );

		    $this->add( $data );
		}
		WPET::getInstance()->display( 'tickets-add.php', WPET::getInstance()->ticket_options->findAll() );
	    } else {

		$columns = array(
		    'name' => 'Option Name',
		    'type' => 'Type'
		);

		$rows = $this->findAllByEvent( 1 );


		$data['columns'] = apply_filters( 'wpet_tickets_columns', $columns );
		$data['rows'] = apply_filters( 'wpet_tickets_rows', $rows );
		WPET::getInstance()->display( 'tickets.php', $data );
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
		'title' => 'Ticket Name'
	    );
	}

	public function findAllByEvent() {
	    $args = array(
		'post_type' => 'wpet_tickets',
		'showposts' => '-1',
		'posts_per_page' => '-1'
	    );

	    return get_posts( $args );


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

//	    if( !isset( $data['ticket_options'] ) ) {
//		return new WP_Error( 1001, 'ticket_options is a required field' );
//	    }

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