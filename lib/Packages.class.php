<?php

/**
 * @since 2.0 
 */
class Packages {

    /**
     * @since 2.0 
     */
    public function __construct() {
        add_filter( 'wpet_admin_menu', array( &$this, 'adminMenu' ) );
    }
    
    /**
     * Add Packages links to the Tickets menu
     * 
     * @since 2.0
     * @param type $menu
     * @return array 
     */
    public function adminMenu( $menu ) {
        $menu[] = array( 'Packages', 'Packages', 'add_users', 'packages', array( &$this, 'vtPackages' ) );
        return $menu;
    }
} // end class