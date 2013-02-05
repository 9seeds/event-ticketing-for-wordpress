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
	    $this->mPostType = 'wpet_notifications';
	    
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 26 );
		
		add_action( 'init', array( $this, 'registerPostType' ) );

		//do this after post type is set
		parent::__construct();		
	}
	
	/**
	 * @since 2.0
	 */
	public function enqueueAdminScripts() {
		if ( isset( $_GET['action'] ) ) {
			wp_enqueue_style( 'editor' );
		}
	}

	/**
	 * Add Notify Attendees links to the Tickets menu
	 * 
	 * @since 2.0
	 * @param type $menu
	 * @return array 
	 */
	public function adminMenu( $menu ) {
		$menu[] = array( 'Notify Attendees', 'Notify Attendees', 'add_users', 'wpet_notifications', array( $this, 'renderAdminPage' ) );
		return $menu;
	}
	
	/**
	 * Renders the attendee notify page in wp-admin
	 * 
	 * @since 2.0 
	 */
	public function renderAdminPage() {

		//might have to override maybeSubmit() to do this
		//$this->sendNotification( $to, $_POST['subject'], $_POST['email-body'] )

		if ( isset( $_GET['action'] ) ) {
			if ( ! empty( $_REQUEST['post'] ) ) {
				$this->render_data['notification'] = $this->findByID( $_REQUEST['post'] );
			}
			
			if( isset( $_GET['notify'] ) ) {
			    echo '<pre>'; var_dump( $_POST ); echo '</pre>';
			    
			    $organizer_name = WPET::getInstance()->settings->organizer_name;
			    $organizer_email = WPET::getInstance()->settings->organizer_email;
			    			    
			    $headers[] = "From: $organizer_email <$organizer_email>";
			    $headers[] = 'Bcc: ben@lobaugh.net';

			    echo "wp_mail( 'ben@lobaugh.net', {$_POST['options']['subject']}, {$_POST['options']['email_body']}, $headers );";
			   $mail =  wp_mail( 'ben@lobaugh.net', $_POST['options']['subject'], $_POST['options']['email_body'], $headers );
			    var_dump( $mail );
			}
		    WPET::getInstance()->display( 'notifications-add.php', $this->render_data );
		} else {			
		    WPET::getInstance()->display( 'notifications.php', $this->render_data );
		}
	}
	
	/**
	 * Prepare the page submit data for save
	 *
	 * @since 2.0
	 */
	public function getPostData() {
		$_POST['options']['sent_date'] = date( 'Y-m-d H:m:s', time() );
		$data = array(
			'post_type' => $post_type,
			'post_title' => $_POST['options']['subject'],
			'meta' => $_POST['options'],		
		);
		return $data;
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
		'name' => 'Notifications',
		'singular_name' => 'Notification',
		'add_new' => 'Create Notification',
		'add_new_item' => 'New Notification',
		'edit_item' => 'Edit Notification',
		'new_item' => 'New Notification',
		'view_item' => 'View Notification',
		'search_items' => 'Search Notifications',
		'not_found' => 'No Notifications found',
		'not_found_in_trash' => 'No Notifications found in trash'
	    );

	    $args = array(
		'public' => false,
		'supports' => array( 'page-attributes' ),
		'labels' => $labels,
		'hierarchical' => false,
		'has_archive' => false,
		'query_var' => 'notification',
		//'rewrite' => array( 'slug' => 'notification', 'with_front' => false ),
		//'menu_icon' => WPET_PLUGIN_URL . 'images/icons/reviews.png',
		//'register_meta_box_cb' => array( &$this, 'registerMetaBox' ),
		'show_ui' => false
	    );

	    register_post_type( $this->mPostType, $args );
	}

}