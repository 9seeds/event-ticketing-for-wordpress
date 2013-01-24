<?php

/**
 * @since 2.0 
 */
class Attendees {

	/**
	 * @since 2.0 
	 */
	public function __construct() {
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ) );
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
		$menu[] = array( 'Notify Attendees', 'Notify Attendees', 'add_users', 'wpet_notify_attendees', array( $this, 'renderAdminPage' ) );
		return $menu;
	}

	public function renderAdminPage() {
		//$inst = apply_filters( 'wpet_instructions', $inst = array( 'instructions' => array() ) );
		WPET::getInstance()->display( 'attendees.php' );
	}

} // end class