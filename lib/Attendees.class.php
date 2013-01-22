<?php

/**
 * @since 2.0 
 */
class Attendees {

    /**
     * @since 2.0 
     */
    public function __construct() {
        add_filter( 'wpet_admin_menu', array( &$this, 'adminMenu' ) );
    }
    
    /**
     * Add Attendee links to the Tickets menu
     * 
     * @since 2.0
     * @param type $menu
     * @return array 
     */
    public function adminMenu( $menu ) {
        $menu[] = array( 'Attendees', 'Attendees', 'add_users', 'attendees', array( &$this, 'vtAttendees' ) );
        $menu[] = array( 'Notify Attendees', 'Notify Attendees', 'add_users', 'notify-attendees', array( &$this, 'vtNotify' ) );
        return $menu;
    }
} // end class