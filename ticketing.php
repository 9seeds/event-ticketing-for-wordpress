<?php
/*
Plugin Name: WP Event Ticketing
Plugin URI: http://9seeds.com/plugins/
Description: The WP Event Ticketing plugin makes it easy to sell and manage tickets for your event.
Author: 9seeds.com
Version: 2.0
Author URI: http://9seeds.com/
Text Domain: wpet
*/

define( 'WPET_PLUGIN_DIR', trailingslashit( dirname( __FILE__) ) );
define( 'WPET_PLUGIN_URL', plugins_url( '/', __FILE__ ) );


require_once( 'lib/WPET.class.php' );

register_activation_hook( __FILE__, array( 'WPET', 'activate' ) );

register_deactivation_hook( __FILE__, array( 'WPET', 'deactivate' ) );

register_uninstall_hook( __FILE__, array( 'WPET', 'uninstall' ) );

$wpet = WPET::getInstance();

