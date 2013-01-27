<?php

/**
 * @since 2.0
 */
class WPET_TicketOptions extends WPET_Module {

	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->mPostType = 'wpet_ticket_options';

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
		$menu[] = array( 'Ticket Options', 'Ticket Options', 'add_users', $this->mPostType, array( $this, 'renderAdminPage' ) );
		return $menu;
	}

	/**
	 * Displays the menu page
	 *
	 * @since 2.0
	 */
	public function renderAdminPage() {

		if ( ! empty($_POST['wpet_ticket_options_update_nonce'] ) && wp_verify_nonce( $_POST['wpet_ticket_options_update_nonce'], 'wpet_ticket_options_update' ) ) {

			$post_data = array(
				'post_title' => $_POST['options']['display-name'],
				'post_name' => sanitize_title_with_dashes( $_POST['options']['display-name'] ),
				'meta' => array(
					'type' => sanitize_title( $_POST['options']['option-type'] ),
					'values' => stripslashes_deep( $_POST['options']['option-value'] )
				)
			);
			if ( ! empty( $_REQUEST['post'] ) )
				$post_data['ID'] = $_REQUEST['post'];

			//kind of a hack
		    $_REQUEST['post'] = $this->add( $post_data );
		}
		
		$data = array();
		$data['edit_url'] = admin_url( "admin.php?page={$this->mPostType}&action=edit" );
		
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) {
			if ( ! empty( $_REQUEST['post'] ) ) {
				$data['option'] = $this->findByID( $_REQUEST['post'] );
				$data['edit_url'] = add_query_arg( array( 'post' => $_REQUEST['post'] ), $data['edit_url'] );
			}
			$data['nonce'] = wp_nonce_field( 'wpet_ticket_options_update', 'wpet_ticket_options_update_nonce', true, false );
			WPET::getInstance()->display( 'ticket-options-add.php', $data );
		} else {			
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
		$options = $this->find();

		$s = '';
		foreach( $options AS $o ) {
			$opts = $o->wpet_values;
			$s .= '<tr class="form-field form-required">';
			$s .= '<th scope="row">' . $o->post_title . '</th>';
			$s .= '<td>';
			// Figure out the type to build the proper display
			switch( $o->wpet_type ) {

				case 'multiselect':
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
					$s .= '<input type="text" value="' . $opts[0] . '" />';

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
	public function getAdminOptionsCheckboxes( $checked = array() ) {		
		$options = $this->find();
		$checkboxes = array();
		foreach ( $options as $o ) {
			$checked_attr = in_array( $o->ID, $checked ) ? 'checked="checked" ' : '';
			$checkboxes[] = array(
				'label' => "<label for='" . sanitize_title_with_dashes( $o->post_title ) . "'>{$o->post_title}</label>",
				'checkbox' => "<input type='checkbox' id='" . sanitize_title_with_dashes( $o->post_title ) . "' name='options[{$o->ID}]' {$checked_attr}/>",
			);
		}

		return $checkboxes;
	}
		
	/**
	 * Returns an array of all ticket options
	 *
	 * @since 2.0
	 * @return array
	 */
	public function find( $args = array() ) {
	    $posts = parent::find( $args );

	    foreach( $posts as $p ) {
			//this one needs to be explicitly fetched (WP_Post::__get() only gets a single value)
			$p->wpet_values = get_post_meta( $p->ID, 'wpet_values' );
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

	    register_post_type( $this->mPostType, $args );
	}
}// end class