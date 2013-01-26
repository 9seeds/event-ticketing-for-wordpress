<?php

/**
 * @since 2.0
 */
class WPET_TicketOptions extends WPET_Module {

	/**
	 * @since 2.0
	 */
	public function __construct() {
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 5 );

		add_action( 'init', array( $this, 'registerPostType' ) );
		
		add_filter( 'wpet_ticket_options_columns', array( $this, 'defaultColumns' ) );
		
	}

	/**
	 * @since 2.0
	 */
	public function enqueueAdminScripts() {
		wp_register_script( 'wpet-admin-ticket-options', WPET_PLUGIN_URL . 'js/admin_ticket_options.js', array( 'jquery' ) );
		wp_enqueue_script( 'wpet-admin-ticket-options' );
	}

	/**
	 * Add Ticket Options links to the Tickets menu
	 *
	 * @since 2.0
	 * @param type $menu
	 * @return array
	 */
	public function adminMenu( $menu ) {
		$menu[] = array( 'Ticket Options', 'Ticket Options', 'add_users', 'wpet_ticket_options', array( $this, 'renderAdminPage' ) );
		return $menu;
	}

	/**
	 * Displays the menu page
	 *
	 * @since 2.0
	 */
	public function renderAdminPage() {

	    if( isset( $_GET['add-ticket-options'] ) ) {
//		echo '<pre>';
//		var_dump( $_POST);
//		echo '</pre>';
		
		if( isset( $_POST['submit'] ) ) {
		    $this->add(
			    array(
				'post_title' => $_POST['options']['display-name'],
				'post_name' => sanitize_title_with_dashes( $_POST['options']['display-name'] ),
				'meta' => array(
				    '_type' => sanitize_title( $_POST['options']['option-type'] ),
				    '_values' => serialize( $_POST['options']['option-value'] )
				)
			    )
		    );
			    
		}
		WPET::getInstance()->display( 'ticket-options-add.php' );
	    } else {
		
		$columns = array(
		    'name' => 'Option Name',
		    'type' => 'Type'
		);
		
		$rows = $this->findAll();
		
		$data['columns'] = apply_filters( 'wpet_ticket_options_columns', $columns );
		$data['rows'] = apply_filters( 'wpet_ticket_options_rows', $rows );
		WPET::getInstance()->display( 'ticket-options.php', $data );
	    }
	}
	
	/**
	 * Creates the ticket options form for the wp-admin area
	 * 
	 * @since 2.0
	 * @return string 
	 */
	public function buildAdminOptionsHtmlForm() {
	    $options = $this->findAll();
	    
	    
	    $s = '';
	    foreach( $options AS $o ) {
		$opts = unserialize( $o['option-value'] );
		$s .= '<tr class="form-field form-required">';
		$s .= '<th scope="row">' . $o['display-name'] . '</th>';
		$s .= '<td>';
		// Figure out the type to build the proper display
		switch( $o['option-type'] ) {
		    
		    case 'multiselect':
			$opts = unserialize( $o['option-value'] );
			$s .= '<select multiple>';
			
			foreach( $opts AS $oi ) { 
			    $s .= '<option value="' . $oi . '">' . $oi . '</option>';
			}
			$s .= '</select>';
			break;
		    case 'dropdown':
			$s .= '<select>';
			
			foreach( $opts AS $oi ) {
			   
			    $s .= '<option value="' . $oi . '">' . $oi . '</option>';
			}
			$s .= '</select>';
			break;
		    
		    case 'text':
		    default:
			$s .= '<input type="text" value="' .  $opts[0] . '" />';
			
		}
		$s .= '</td>';
		$s .= '</tr>';
	    }
	    
	    return $s;
	}
	
	
	/**
	 * Creates the ticket options form for the wp-admin area
	 * 
	 * @since 2.0
	 * @return string 
	 */
	public function buildAdminOptionsCheckboxForm() {
	    $options = $this->findAll();
	    
	    
	    $s = '';
	    foreach( $options AS $o ) {
		$opts = unserialize( $o['option-value'] );
		$s .= '<tr class="form-field form-required">';
		$s .= '<th scope="row"><label for="' . sanitize_title_with_dashes( $o['display-name'] ) . '">' . $o['display-name'] . '</label></th>';
		$s .= '<td>';
		$s .= '<input type="checkbox" id="' . sanitize_title_with_dashes( $o['display-name'] ) . '" name="ticket_options[' . $o['ID'] . ']"/>';
		$s .= '</td>';
		$s .= '</tr>';
	    }
	    
	    return $s;
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
		'display-name' => 'Option Name',
		'option-type' => 'Type'
	    );
	}
	
	/**
	 * Returns an array of all ticket options
	 * 
	 * @since 2.0
	 * @return array 
	 */
	public function findAll() {
	    
	    $args = array(
		'post_type' => 'wpet_ticket_options',
		'showposts' => '-1',
		'posts_per_page' => '-1'
	    );
	    
	    $posts = get_posts( $args );
	    
	    $ret = array();
	    foreach( $posts AS $p ) {
		$data = array(
		    'ID' => $p->ID,
		    'display-name' => $p->post_title,
		    'option-type' => get_post_meta( $p->ID, '_type', true ),
		    'option-value' => get_post_meta( $p->ID, '_values', true )
		);
		$ret[] = $data;
	    }
	    return $ret;
	}

	/**
	 * Add post type for object
	 *
	 * @since 2.0
	 */
	public function registerPostType() {
	    $labels = array(
		'name' => 'Ticket Options',
		'singular_name' => 'Ticket Option',
		'add_new' => 'Create Ticket Option',
		'add_new_item' => 'New Ticket Option',
		'edit_item' => 'Edit Ticket Option',
		'new_item' => 'New Ticket Option',
		'view_item' => 'View Ticket Option',
		'search_items' => 'Search Ticket Options',
		'not_found' => 'No Ticket Options found',
		'not_found_in_trash' => 'No Ticket Options found in trash'
	    );

	    $args = array(
		'public' => false,
		'supports' => array( 'page-attributes' ),
		'labels' => $labels,
		'hierarchical' => false,
		'has_archive' => true,
		'query_var' => 'wpet_ticket_option',
		//'rewrite' => array( 'slug' => 'review', 'with_front' => false ),
		//'menu_icon' => WPET_PLUGIN_URL . 'images/icons/reviews.png',
		//'register_meta_box_cb' => array( &$this, 'registerMetaBox' ),
		'show_ui' => false
	    );

	    register_post_type( 'wpet_ticket_options', $args );
	}


	/**
	 * Adds the object data to the database
	 *
	 * @since 2.0
	 * @param array $data
	 * @uses wpet_ticket_option_add
	 */
	public function add( $data ) {
	    $defaults = array(
		'post_type' => 'wpet_ticket_options',
		'post_status' => 'publish',
		'post_name' => uniqid()
	    );

	    if( $user_id = get_current_user_id() )
		$defaults['post_author'] = $user_id;

	    $data = wp_parse_args( $data, $defaults );

	    $data = apply_filters( 'wpet_ticket_option_add', $data );

	    $post_id = wp_insert_post( $data );
	    
	    if( isset( $data['meta'] ) && is_array( $data['meta'] ) ) {
		foreach( $data['meta'] as $k => $v ) {
		    update_post_meta( $post_id, $k, $v );
		}
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