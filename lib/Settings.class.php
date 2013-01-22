<?php

/**
 * @since 2.0 
 */
class Settings {

    /**
     * @since 2.0 
     */
    public function __construct() {
        add_filter( 'wpet_admin_menu', array( &$this, 'adminMenu' ) );
    }
    
    /**
     * Add Settings links to the Tickets menu
     * 
     * @since 2.0
     * @param type $menu
     * @return array 
     */
    public function adminMenu( $menu ) {
        $menu[] = array( 'Settings', 'Settings', 'add_users', 'settings', array( &$this, 'vtSettings' ) );
        return $menu;
    }
} // end class