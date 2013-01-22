<?php

class Attendees {

    public function __construct() {
        add_filter( 'wpet_admin_menu', array( &$this, 'adminMenu' ) );
    }
    
    public function adminMenu( $menu ) {
        $menu[] = array( 'Attendees', 'Attendees', 'add_users', 'attendees', array( &$this, 'vtAttendees' ) );
        $menu[] = array( 'Notify Attendees', 'Notify Attendees', 'add_users', 'notify-attendees', array( &$this, 'vtNotify' ) );
        return $menu;
    }
} // end class