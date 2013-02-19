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

		add_action('wp_ajax_get_ticket_options_for_package', array( $this, 'ajaxGetTicketOption' ) );

		//do this after post type is set
		parent::__construct();
	}

	public function ajaxGetTicketOption() {
	    $package_id = (int)$_POST['package_id'];
		$attendee = NULL;
		if ( ! empty( $_POST['attendee_id'] ) ) {
			$attendee = WPET::getInstance()->attendees->findById( $_POST['attendee_id'] );
		}
	   // die(1);
	    //print_r( $_POST );
	    echo $this->buildOptionsHtmlFormForPackage( $package_id, $attendee );
	    exit();
	}

	/**
	 * Displays page specific contextual help through the contextual help API
	 *
	 * @see http://codex.wordpress.org/Function_Reference/add_help_tab
	 * @since 2.0
	 */
	public function contextHelp( $screen ) {
		if ( isset( $_GET['action'] ) && in_array ( $_GET['action'], array( 'edit', 'new' ) ) ) {
			$screen->add_help_tab(
				array(
				'id'	=> 'overview',
				'title'	=> __( 'Overview' ),
				'content'	=> '<p>' . __( 'This screen allows you to add a new ticket type for your event.' ) . '</p>',
				)
			);
			$screen->add_help_tab(
				array(
				'id'	=> 'options-explained',
				'title'	=> __( 'Options Explained' ),
				'content'	=> '<p>' . __( 'Here\'s an explanation of the options found on this page:' ) . '</p>'.
					'<ul>'.
						'<li>'. __( '<strong>Ticket Name</strong> is the name of the type of ticket your attendees purchase. For example, you may have a ticket named "general admission - meal included" and another named "general admission - no meal".' ) .'</li>'.
						'<li>'. __( '<strong>Ticket Options</strong> is a list of the available ticket options you\'ve created. Check the box next to the pieces of data you\'d like to collect for each ticket.' ) .'</li>'.
					'</ul>',
				)
			);
		} else {
			$screen->add_help_tab(
				array(
				'id'	=> 'overview',
				'title'	=> __( 'Overview' ),
				'content'	=> '<p>' . __( 'This screen provides access to all of your ticket types.' ) . '</p>',
				)
			);
			$screen->add_help_tab(
				array(
				'id'	=> 'available-actions',
				'title'	=> __( 'Available Actions' ),
				'content'	=> '<p>' . __( 'Hovering over a row in the ticket list will display action links that allow you to manage each ticket. You can perform the following actions:' ) . '</p>'.
					'<ul>'.
						'<li>'. __( '<strong>Edit</strong> takes you to the editing screen for that ticket. You can also reach that screen by clicking on the ticket name itself.' ) .'</li>'.
						'<li>'. __( '<strong>Trash</strong> removes your ticket from this list and places it in the trash, from which you can permanently delete it.' ) .'</li>'.
					'</ul>',
				)
			);
		}
		$screen->set_help_sidebar(
			'<p><strong>' . __( 'Need help:' ) . '</strong></p>' .
			'<p>' . __( '<a href="http://support.9seeds.com/" target="_blank">Support Forums</a>' ) . '</p>' .
			'<p>' . __( '<a href="https://github.com/9seeds/wp-event-ticketing/wiki/_pages" target="_blank">Developer Docs</a>' ) . '</p>'
		);
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

		if ( isset( $_GET['action'] ) && in_array ( $_GET['action'], array( 'edit', 'new' ) ) ) {
			if ( ! empty( $_GET['post'] ) ) {
				$this->render_data['ticket'] = $this->findByID( $_GET['post'] );
			}
			WPET::getInstance()->display( 'tickets-add.php', $this->render_data );
			return; //don't do anything else
		}

		
		//default view
		WPET::getInstance()->display( 'tickets.php', $this->render_data );
	}

	/**
	 * Prepare the page submit data for save
	 *
	 * @since 2.0
	 */
	public function getPostData() {
		$options = $_POST['options'];
		$post_data = array(
			'post_title' => $options['ticket-name'],
		);
		unset($options['ticket-name']);
		$post_data['meta'] = array(
			'options_selected' => array_keys( $options )
		);

		return $post_data;
	}

	/**
	 * Returns the HTML form with all the ticket options for the ticket
	 * contained within a package
	 *
	 * @param integer $package_id
	 * @param array $data - Array of WP_Post Form field values
	 * @return string
	 */
	public function buildOptionsHtmlFormForPackage( $package_id, $data = null ) {
	    $ticket_id = get_post_meta( $package_id, 'wpet_ticket_id', true );
	    return $this->buildOptionsHtmlForm( $ticket_id, $data );
	}


	/**
	 * Creates the ticket options form for the wp-admin area
	 *
	 * @since 2.0
	 * @param integer $ticket_id
	 * @param array $data - Array of WP_Post form field values
	 * @return string
	 */
	public function buildOptionsHtmlForm( $ticket_id, $data = null ) {
		$options = get_post_meta( $ticket_id, 'wpet_options_selected',  true );

		$s = '';
		if( !is_array( $options ) ) return '';
		foreach( $options AS $o ) {

			$opts = WPET::getInstance()->ticket_options->findByID( $o );
			$field = $opts->post_name;

			$value = '';
			if( !is_null( $data ) )
			    $value = $data->{"wpet_$field"};

			$s .= '<tr class="form-field form-required">';
			$s .= '<th scope="row">' . $opts->post_title . '</th>';
			$s .= '<td>';

			// Figure out the type to build the proper display
			switch( $opts->wpet_type ) {

				case 'multiselect':
					$s .= '<select  name="' . $opts->post_name . '[]" multiple>';

					foreach( ( $opts->wpet_values ) AS $oi ) {
						$s .= '<option value="' . $oi . '"';
						$s .= ( in_array($oi, (array)$value) )? ' selected': 'false';
						$s .= '>' . $oi . '</option>';
					}
					$s .= '</select>';
					break;
				case 'dropdown':
					$s .= '<select name="' . $opts->post_name . '" >';

					foreach( $opts->wpet_values AS $oi ) {
						$s .= '<option value="' . $oi . '"';
						$s .= selected( $value, $oi, false );
						$s .= '>' . $oi . '</option>';
					}
					$s .= '</select>';
					break;

				case 'text':
				default:
					$s .= '<input  name="' . $opts->post_name . '" type="text" value="' . $value . '" />';

			}
			$s .= '</td>';
			$s .= '</tr>';
		}

		return $s;
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
			'has_archive' => false,
			'query_var' => 'wpet_tickets',
			'rewrite' => array( 'slug' => 'tickets', 'with_front' => false ),
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
	 * @param string $id
	 * @param string $selected_value
	 * @return string
	 */
	public function selectMenu( $name, $id, $selected_value ) {
	    $s = "<select name='{$name}' id='{$id}'>";

	    foreach ( $this->findAllByEvent() as $tix ) {
			$s .= "<option value='{$tix->ID}' ";
			$s .= selected( $selected_value, $tix->ID, false ) ;
			$s .= ">{$tix->post_title}</option>\n";
	    }

	    $s .= '</select>';
	    return $s;
	}


}// end class