<?php
/*
Plugin Name: WP Event Ticketing
Plugin URI: http://9seeds.com/plugins/
Description: The WP Event Ticketing plugin makes it easy to sell and manage tickets for your event.
Author: 9seeds.com
Version: 2.0
Author URI: http://9seeds.com/
Text Domain: wpevt
*/

define( 'WPEVT_PLUGIN_DIR', trailingslashit( dirname( __FILE__) ) );
define( 'WPEVT_PLUGIN_URL', trailingslashit( WP_CONTENT_URL . '/' . basename( __DIR__ ))  );


require_once( 'lib/WPEVT.class.php' );

register_activation_hook( __FILE__, array( 'WPEVT', 'activate' ) );

register_deactivation_hook( __FILE__, array( 'WPEVT', 'deactivate' ) );

register_uninstall_hook( __FILE__, array( 'WPEVT', 'uninstall' ) );

$wpevt = new WPEVT();

