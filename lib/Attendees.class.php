<?php

/**
 *
 * Creates post types:
 * - wpet_attendees
 *
 * @since 2.0
 */
class WPET_Attendees extends WPET_Module {

	/**
	 * @since 2.0
	 */
	public function __construct() {
	    $this->mPostType = 'wpet_attendees';

		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 25 );

		add_action( 'init', array( $this, 'registerPostType' ) );

		add_action( 'init', array( $this, 'registerShortcodes' ) );

		add_action( 'the_post', array( $this, 'saveAttendeeFront' ) );

		add_filter( 'the_content', array( $this, 'viewSingleAttendee' ) );

		//do this after post type is set
		parent::__construct();
	}

	/**
	 * Overridden from parent to do download action if needed
	 */
	public function maybeSubmit() {
		if ( isset( $_POST['download'] ) ) {
			require_once WPET_PLUGIN_DIR . 'lib/Table/Attendees.class.php';

			$wp_list_table = new WPET_Table_Attendees();
			$wp_list_table->download();
		}
		parent::maybeSubmit();
	}

	
	/**
	 * @todo hawkins to rewrite these instructions
	 */
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
				'content'	=> '<p>' . __( 'This screen allows you to add a new attendee for your event.', 'wpet' ) . '</p>',
				)
			);
			$screen->add_help_tab(
				array(
				'id'	=> 'options-explained',
				'title'	=> __( 'Options Explained' ),
				'content'	=> '<p>' . __( 'Here\'s an explanation of the options found on this page:', 'wpet' ) . '</p>'.
					'<ul>'.
						'<li>'. __( '<strong>Package</strong> is blah blah blah', 'wpet' ) .'</li>'.
					'</ul>',
				)
			);
		} else {
			$screen->add_help_tab(
				array(
				'id'	=> 'overview',
				'title'	=> __( 'Overview' ),
				'content'	=> '<p>' . __( 'This screen provides access to all of your attendees.', 'wpet' ) . '</p>',
				)
			);
			$screen->add_help_tab(
				array(
				'id'	=> 'available-actions',
				'title'	=> __( 'Available Actions' ),
				'content'	=> '<p>' . __( 'Hovering over a row in the attendee list will display action links that allow you to manage each attendee. You can perform the following actions:', 'wpet' ) . '</p>'.
					'<ul>'.
						'<li>'. __( '<strong>Edit</strong> takes you to the editing screen for that attendee. You can also reach that screen by clicking on the attendee\'s name itself.', 'wpet' ) .'</li>'.
						'<li>'. __( '<strong>Trash</strong> removes the attendee from this list and places it in the trash, from which you can permanently delete it.', 'wpet' ) .'</li>'.
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
	 * Will set the attendee status to published, showing their ticket has
	 * been paid for as well as perform other tasks related to an attendee 
	 * being official
	 * 
	 * @todo Figure out which email needs to be sent
	 * @since 2.0
	 * @param type $id 
	 */
	public function publishAttendee( $id ) {
	    
	    do_action( 'wpet_publish_attendee', $id );
	    
	    // Update to publish status last
	    wp_update_post(array('ID' => $id, 'post_status' => 'publish'));
	}

	/**
	 * @since 2.0
	 * @returns int $attendee_id
	 */
	public function draftAttendee( $args = array() ) {

//		$defaults = array('uniqid' => uniqid() );
//		
//		$data = wp_parse_args( $args, $defaults );
//
//		$data = array(
//			'post_status' => 'draft',
//			'meta' => $data,
//		);
		$defaults = array( 'post_status' => 'draft' );
		
		$data = wp_parse_args( $args, $defaults );

		return $this->add( $data );
	}

	/**
	 * @TODO maybe rename these ^v
	 */
	public function saveAttendeeFront() {
	    global $post;

	    if( isset( $_POST['submit'] ) && is_single() && $this->mPostType == $post->post_type && !is_admin() ) {
			$data['meta'] = $_POST;
			$data['post_title'] = $_POST['first_name'] . ' ' . $_POST['last_name'];
			$data['ID'] = $post->ID;
			$this->add( $data );
	    }
	}

	/**
	 * @todo Rename this
	 */
	public function viewSingleAttendee( $content ) {
	    global $post;

	    // Make sure we are on the attendee page
	    if( 'wpet_attendees' != $post->post_type || !is_single() ) return $content;

	    return WPET::getInstance()->getDisplay( 'single_attendee.php' );
	}


	/**
	 * Registers shortcodes for pretty reports on the front end
	 *
	 * @since 2.0
	 */
	public function registerShortcodes() {
	    add_shortcode( 'wpeventticketingattendee',  array( $this, 'renderAttendeesShortcode' ) );
	    add_shortcode( 'wpet_attendees',  array( $this, 'renderAttendeesShortcode' ) );
	}

	/**
	 * Displays the [wpet_attendees] shortcode to visitors
	 *
	 * Valid attributes:
	 * - event_id
	 *
	 * @since 2.0
	 * @param array $atts
	 */
	public function renderAttendeesShortcode( $atts ) {
	    $data = $this->findAllByEvent(1);
	    WPET::getInstance()->display( 'attendees_shortcode.php', $data );
	}



	/**
	 * Add Attendee links to the Tickets menu
	 *
	 * @since 2.0
	 * @param type $menu
	 * @return array
	 */
	public function adminMenu( $menu ) {
		$menu[] = array(
			__( 'Attendees', 'wpet' ),
			__( 'Attendees', 'wpet' ),
			'add_users',
			'wpet_attendees',
			array( $this, 'renderAdminPage' ) );
		return $menu;
	}


	public function enqueueAdminScripts() {
	    wp_register_script( 'wpet-admin-attendee-add', WPET_PLUGIN_URL . '/js/admin_attendee_add.js', array( 'jquery' ) );
	    wp_enqueue_script( 'wpet-admin-attendee-add' );
	}

	/**
	 * Renders the attendee page in wp-admin
	 *
	 * @since 2.0
	 */
	public function renderAdminPage() {
		WPET::getInstance()->debug( 'Rendering Attendees page', 'Doing it...' );

		if ( isset( $_GET['action'] ) && in_array ( $_GET['action'], array( 'edit', 'new' ) ) ) {
			if ( ! empty( $_GET['post'] ) ) {
				$this->render_data['attendee'] = $this->findByID( $_GET['post'] );
			}
		    WPET::getInstance()->display( 'attendees-add.php', $this->render_data );
			return; //don't do anything else
		}
		
		$this->render_data['show_add'] = WPET::getInstance()->packages->anyPackagesExist();

		//default view
	    WPET::getInstance()->display( 'attendees.php', $this->render_data );
	}

	/**
	 * Prepare the page submit data for save
	 *
	 * @since 2.0
	 */
	public function getPostData() {
		$data = array(
			'post_title' => $_POST['first_name'] . ' ' . $_POST['last_name'],
			'meta' => $_POST,
			'post_name' => uniqid()
		);
		return $data;
	}

	/**
	 * Retrieves all the attendees from the db
	 * @return array
	 */
	public function findAllByEvent( ) {
	    $args = array(
		'post_type' => 'wpet_attendees',
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
		'name' => 'Attendees',
		'singular_name' => 'Attendee',
		'add_new' => 'Create Attendee',
		'add_new_item' => 'New Attendee',
		'edit_item' => 'Edit Attendee',
		'new_item' => 'New Attendee',
		'view_item' => 'View Attendee',
		'search_items' => 'Search Attendees',
		'not_found' => 'No Attendees found',
		'not_found_in_trash' => 'No Attendees found in trash'
	    );

	    $args = array(
		'public' => true,
		'supports' => array( 'page-attributes' ),
		'labels' => $labels,
		'hierarchical' => false,
		'has_archive' => false,
		'query_var' => 'attendee',
		'rewrite' => array( 'slug' => 'attendee', 'with_front' => false ),
		//'menu_icon' => WPET_PLUGIN_URL . 'images/icons/reviews.png',
		//'register_meta_box_cb' => array( &$this, 'registerMetaBox' ),
		'show_ui' => false
	    );

	    register_post_type( 'wpet_attendees', $args );
	}


} // end class