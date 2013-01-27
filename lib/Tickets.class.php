<?php

/**
 * @since 2.0
 */
class WPET_Tickets extends WPET_Module {

	/**
	 * @since 2.0
	 */
	public function __construct() {
	    $this->mPostType = 'wpet_tickets';
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

		if ( ! empty($_POST['wpet_tickets_update_nonce'] ) && wp_verify_nonce( $_POST['wpet_tickets_update_nonce'], 'wpet_tickets_update' ) ) {

		    $post_data = array(
				'post_title' => $_POST['options']['ticket-name'],
				'post_content' => serialize( $_POST['options'] ), //I don't like this
		    );
			
			if ( ! empty( $_REQUEST['post'] ) )
				$post_data['ID'] = $_REQUEST['post'];

			//kind of a hack
		    $_REQUEST['post'] = $this->add( $data );
		}

		
		$data = array();
		$data['edit_url'] = admin_url( "admin.php?page={$this->mPostType}&action=edit" );
		
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) {
			if ( ! empty( $_REQUEST['post'] ) ) {
				$data['ticket'] = $this->findByID( $_REQUEST['post'] );
				$data['edit_url'] = add_query_arg( array( 'post' => $_REQUEST['post'] ), $data['edit_url'] );
			}
			$data['nonce'] = wp_nonce_field( 'wpet_tickets_update', 'wpet_tickets_update_nonce', true, false );
			WPET::getInstance()->display( 'tickets-add.php', $data );
		} else {			
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
		//'register_meta_box_cb' => array( $this, 'registerMetaBox' ),
		'show_ui' => false
	    );

	    register_post_type( 'wpet_tickets', $args );
	}

	/**
	 * Builds a select menu of Tickets
	 * 
	 * @since 2.0
	 * @param string $name
	 * @param string $selected_value
	 * @return string 
	 */
	public function selectMenu( $name, $selected_value ) {
	    $s = "<select name='$name' id='$name'>";

	    foreach( $this->findAllByEvent() AS $tix ) {
		$s .= '<option value="' . $tix->ID . '"';

		$s .= selected( $selected_value, $tix->ID, false ) ;

		$s .= '>';

		$s .= $tix->post_title;

		$s .= '</option>';
	    }

	    $s .= '</select>';
	    return $s;
	}

	
}// end class