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

		add_filter( 'wpet_attendees_columns', array( $this, 'defaultColumns' ) );
		add_filter( 'wpet_notify_attendees_columns', array( $this, 'defaultNotifyColumns' ) );
	}
	
	
	/**
	 * Registers shortcodes for pretty reports on the front end
	 * 
	 * @since 2.0 
	 */
	public function registerShortcodes() {
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
		$menu[] = array( 'Attendees', 'Attendees', 'add_users', 'wpet_attendees', array( $this, 'renderAdminPage' ) );
		$menu[] = array( 'Notify Attendees', 'Notify Attendees', 'add_users', 'wpet_notify_attendees', array( $this, 'renderAttendeeNotifyPage' ) );
		return $menu;
	}
	
	/**
	 * Renders the attendee notify page in wp-admin
	 * 
	 * @since 2.0 
	 */
	public function renderAttendeeNotifyPage() {
	    if( isset( $_GET['add-notify'] ) ) {
		
		    $post_type = 'wpet_notification';
		    
		    if( isset( $_POST['submit'] ) ) {
			
			$_POST['options']['sent_date'] = date( 'Y-m-d H:m:s', time() );
			$data = array(
				'post_type' => $post_type,
				    'post_title' => $_POST['options']['subject'],
				    'meta' => $_POST['options']
			);

			var_dump($this->add( $data ));
			
			
			// $this->sendNotification( $to, $_POST['subject'], $_POST['email-body'] )
			
		    }
		    
		    WPET::getInstance()->display( 'notify-add.php' );
		} else {
		    $columns = array();

		    $rows = $this->findAllNotificationsByEvent( 1 );


		    $data['columns'] = apply_filters( 'wpet_notify_attendees_columns', $columns );
		    $data['rows'] = apply_filters( 'wpet_notify_attendees_rows', $rows );
		    WPET::getInstance()->display( 'notify.php', $data );
		}
	}

	/**
	 * Renders the attendee page in wp-admin
	 * 
	 * @since 2.0 
	 */
	public function renderAdminPage() {
		WPET::getInstance()->debug( 'Rendering Attendees page', 'Doing it...' );
		
		if( isset( $_GET['add-attendee'] ) ) {
		    
		    if( isset( $_POST['submit'] ) ) {
			$data = array(
				    'post_title' => $_POST['options']['name'],
				    'meta' => $_POST['options']
			);

			$this->add( $data );
		    }
		    
		    WPET::getInstance()->display( 'attendees-add.php' );
		} else {
		    $columns = array();

		    $rows = $this->findAllByEvent( 1 );


		    $data['columns'] = apply_filters( 'wpet_attendees_columns', $columns );
		    $data['rows'] = apply_filters( 'wpet_attendees_rows', $rows );
		    WPET::getInstance()->display( 'attendees.php', $data );
		}
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
	 * Retrieves all the notifications from the db
	 * @return array 
	 */
	public function findAllNotificationsByEvent( ) {
	    $args = array(
		'post_type' => 'wpet_notification',
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
		'has_archive' => true,
		'query_var' => 'shiplog',
		'rewrite' => array( 'slug' => 'review', 'with_front' => false ),
		//'menu_icon' => WPET_PLUGIN_URL . 'images/icons/reviews.png',
		//'register_meta_box_cb' => array( &$this, 'registerMetaBox' ),
		'show_ui' => false
	    );

	    register_post_type( 'wpet_attendees', $args );
	}
	
	/**
	 * Adds the default columns to the attendee list in wp-admin
	 * 
	 * @since 2.0
	 * @param type $columns
	 * @return type 
	 */
	public function defaultColumns( $columns ) {
	    return array(
		'post_title' => 'Name',
		'wpet_email' => 'Email',
		'wpet_purchase_date' => 'Purchase Date'
	    );
	}
	
	/**
	 * Adds the default columns to the notify attendee list in wp-admin
	 * 
	 * @since 2.0
	 * @param type $columns
	 * @return type 
	 */
	public function defaultNotifyColumns( $columns ) {
	    return array(
		'post_title' => 'Subject',
		'wpet_to' => 'To',
		'wpet_sent_date' => 'Sent Date'
	    );
	}

} // end class