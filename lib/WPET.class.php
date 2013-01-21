<?php

class WPET {
    
    
    public function __construct() {
        
        /*
         * Items that should only run in wp-admin
         * 
         * Reduces overhead on page load
         */
        if( is_admin() ) {
            add_action('admin_menu', array( &$this, 'setupMenu' ) );
        }
       
    }
    
    /**
     * Builds the Ticket menu in wp-admin
     * 
     * @since 2.0
     * @uses wpet_admin_menu_items 
     */
    public function setupMenu() {
        add_object_page('Tickets', 'Tickets', 'add_users', 'tickets', array( &$this, 'vtReporting' ) );
        $menu_items = array(
            array( 'Reporting', 'Reporting', 'add_users', 'reporting', array( &$this, 'vtReporting' ) ),
            array( 'Tickets', 'Tickets', 'add_users', 'tickets', array( &$this, 'vtTickets' ) ),
            array( 'Packages', 'Packages', 'add_users', 'packages', array( &$this, 'vtPackages' ) ),
            array( 'Coupons', 'Coupons', 'add_users', 'coupons', array( &$this, 'vtCoupons' ) ),
            array( 'Notify Attendees', 'Notify Attendees', 'add_users', 'notify-attendees', array( &$this, 'vtNotify' ) ),
            array( 'Attendees', 'Attendees', 'add_users', 'attendees', array( &$this, 'vtAttendees' ) ),
            array( 'Instructions', 'Instructions', 'add_users', 'instructions', array( &$this, 'vtInstructions' ) ),
            array( 'Settings', 'Settings', 'add_users', 'settings', array( &$this, 'vtSettings' ) )
        );
        
        $menu_items = apply_filters( 'wpet_admin_menu_items', $menu_items );
        
        foreach( $menu_items AS $i ) {
            add_submenu_page( 'tickets', $i[0], $i[1], $i[2], $i[3], $i[4] );
        }
    }
        
    public function vtReporting() {
        require_once( WPET_PLUGIN_DIR . '/views/admin/reporting.php' );
    }
    public function vtTickets() {
        require_once( WPET_PLUGIN_DIR . '/views/admin/tickets.php' );
    }
    public function vtPackages() {
        require_once( WPET_PLUGIN_DIR . '/views/admin/packages.php' );
    }
    public function vtCoupons() {
        require_once( WPET_PLUGIN_DIR . '/views/admin/coupons.php' );
    }
    public function vtNotify() {
        require_once( WPET_PLUGIN_DIR . '/views/admin/notify.php' );
    }
    public function vtAttendees() {
        require_once( WPET_PLUGIN_DIR . '/views/admin/attendees.php' );
    }
    public function vtInstructions() {
        require_once( WPET_PLUGIN_DIR . '/views/admin/instructions.php' );
    }
    public function vtSettings() {
        require_once( WPET_PLUGIN_DIR . '/views/admin/settings.php' );
    }
    
    /**
     * Method called on plugin activation 
     */
    public static function activate() {
        $plugin_data = get_plugin_data( WPET_PLUGIN_DIR . '/ticketing.php' );
        
        update_option( 'wpet_install_data', $plugin_data);
    }
    
    /**
     * Method called on plugin deactivation 
     */
    public static function deactivate() {
        delete_option( 'wpet_install_data' );
    }
    /**
     * Method called when plugin is uninstalled ( deleted )
     */
    public static function uninstall() {
        delete_option( 'wpet_install_data' );
    }
    
    public function __toString() {
        return 'WPET::__toString';
    }
    
} // end class