<?php

/**
 * @since 2.0 
 */
class Reports {

    /**
     * @since 2.0 
     */
    public function __construct() {
        add_filter( 'wpet_admin_menu', array( &$this, 'adminMenu' ) );
    }
    
    /**
     * Add Reports links to the Tickets menu
     * 
     * @since 2.0
     * @param type $menu
     * @return array 
     */
    public function adminMenu( $menu ) {
        $menu[] = array( 'Reports', 'Reports', 'add_users', 'reporting', array( &$this, 'vtReporting' ) );
        return $menu;
    }
} // end class