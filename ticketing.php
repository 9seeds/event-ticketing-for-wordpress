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
define( 'WPET_BASE', plugin_basename( __FILE__ ) );
define( 'WPET_PLUGIN_FILE', __FILE__ );
define( 'WPET_JQUERY_VERSION', '1.9.2' );

require_once( 'lib/WPET.class.php' );

$wpet = WPET::getInstance();

register_activation_hook( __FILE__, array( $wpet, 'activate' ) );
register_deactivation_hook( __FILE__, array( $wpet, 'deactivate' ) );
// register_uninstall_hook( __FILE__, array( $wpet, 'uninstall' ) );

if ( defined('WP_CLI') && WP_CLI ) {
	require_once WPET_PLUGIN_DIR . 'lib/Installer.class.php';
}
