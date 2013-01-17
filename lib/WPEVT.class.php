<?php

class WPEVT {
    
    
    public function __construct() {
       add_action('admin_menu', array( &$this, 'viewsTest' ) );
    }
    
    public function viewsTest() {
        add_menu_page('Tickets', 'Tickets', 'add_users', 'tickets', array( &$this, 'vtReporting' ) );
        
        //add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
        
        add_submenu_page( 'tickets', 'Reporting', 'Reporting', 'add_users', 'reporting', array( &$this, 'vtReporting' ) );
        add_submenu_page( 'tickets', 'Tickets', 'Tickets', 'add_users', 'tickets', array( &$this, 'vtTickets' ) );
        add_submenu_page( 'tickets', 'Packages', 'Packages', 'add_users', 'packages', array( &$this, 'vtPackages' ) );
        add_submenu_page( 'tickets', 'Coupons', 'Coupons', 'add_users', 'coupons', array( &$this, 'vtCoupons' ) );
        add_submenu_page( 'tickets', 'Notify Attendees', 'Notify Attendees', 'add_users', 'notify-attendees', array( &$this, 'vtNotify' ) );
        add_submenu_page( 'tickets', 'Attendees', 'Attendees', 'add_users', 'attendees', array( &$this, 'vtAttendees' ) );
        add_submenu_page( 'tickets', 'Instructions', 'Instructions', 'add_users', 'instructions', array( &$this, 'vtInstructions' ) );
        add_submenu_page( 'tickets', 'Settings', 'Settings', 'add_users', 'settings', array( &$this, 'vtSettings' ) );
        
    }
    
    public function vtReporting() {
        require_once( WPEVT_PLUGIN_DIR . '/views/admin/reporting.php' );
    }
    public function vtTickets() {
        require_once( WPEVT_PLUGIN_DIR . '/views/admin/tickets.php' );
    }
    public function vtPackages() {
        require_once( WPEVT_PLUGIN_DIR . '/views/admin/packages.php' );
    }
    public function vtCoupons() {
        require_once( WPEVT_PLUGIN_DIR . '/views/admin/coupons.php' );
    }
    public function vtNotify() {
        require_once( WPEVT_PLUGIN_DIR . '/views/admin/notify.php' );
    }
    public function vtAttendees() {
        require_once( WPEVT_PLUGIN_DIR . '/views/admin/attendees.php' );
    }
    public function vtInstructions() {
        require_once( WPEVT_PLUGIN_DIR . '/views/admin/instructions.php' );
    }
    public function vtSettings() {
        require_once( WPEVT_PLUGIN_DIR . '/views/admin/settings.php' );
    }
    
    /**
     * Method called on plugin activation 
     */
    public static function activate() {
        $plugin_data = get_plugin_data( WPEVT_PLUGIN_DIR . '/ticketing.php' );
        
        update_option( 'wpevt_install_data', $plugin_data);
    }
    
    /**
     * Method called on plugin deactivation 
     */
    public static function deactivate() {
        delete_option( 'wpevt_install_data' );
    }
    /**
     * Method called when plugin is uninstalled ( deleted )
     */
    public static function uninstall() {
        delete_option( 'wpevt_install_data' );
    }
    
    public function __toString() {
        return 'WPEVT::__toString';
    }
    
} // end class