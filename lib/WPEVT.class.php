<?php

class WPEVT {
    
    
    public function __construct() {
       
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