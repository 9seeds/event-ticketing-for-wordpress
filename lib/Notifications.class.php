<?php

/**
 * 
 * Creates post types:
 * - wpet_notify_attendees
 * 
 * @since 2.0 
 */
class WPET_Notifications extends WPET_Module {

	/**
	 * @since 2.0 
	 */
	public function __construct() {
	    $this->mPostType = 'wpet_notify_attendees';
	    
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 26 );
		
		add_action( 'init', array( $this, 'registerPostType' ) );

		//do this after post type is set
		parent::__construct();		
	}
	
	/**
	 * Add Notify Attendees links to the Tickets menu
	 * 
	 * @since 2.0
	 * @param type $menu
	 * @return array 
	 */
	public function adminMenu( $menu ) {
		$menu[] = array( 'Notify Attendees', 'Notify Attendees', 'add_users', 'wpet_notify_attendees', array( $this, 'renderAdminPage' ) );
		return $menu;
	}
	
	/**
	 * Renders the attendee notify page in wp-admin
	 * 
	 * @since 2.0 
	 */
	public function renderAdminPage() {
	    if( isset( $_GET['add-notify'] ) ) {
		
		    $post_type = 'wpet_notification';
		    
		    if( isset( $_POST['submit'] ) ) {
			
			$_POST['options']['sent_date'] = date( 'Y-m-d H:m:s', time() );
			$data = array(
				'post_type' => $post_type,
				    'post_title' => $_POST['options']['subject'],
				    'meta' => $_POST['options']
			);

			$this->add( $data );
			
			
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
	 * Retrieves all the notifications from the db
	 * @return array 
	 */
	public function findAllNotificationsByEvent( ) {
	    $args = array(
		'post_type' => 'wpet_notifications',
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
		'query_var' => 'attendee',
		'rewrite' => array( 'slug' => 'attendee', 'with_front' => false ),
		//'menu_icon' => WPET_PLUGIN_URL . 'images/icons/reviews.png',
		//'register_meta_box_cb' => array( &$this, 'registerMetaBox' ),
		'show_ui' => false
	    );

	    register_post_type( 'wpet_attendees', $args );
	}

}