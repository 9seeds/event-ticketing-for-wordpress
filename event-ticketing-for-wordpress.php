<?php
/*
Plugin Name: Event Ticketing for WordPress
Plugin URI: http://9seeds.com/plugins/
Description: Event Ticketing for WordPress is the easiest way to sell tickets for your event!
Author: 9seeds.com
Version: 2.0
Author URI: http://9seeds.com/
Text Domain: wpet
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

*/

define( 'WPET_PLUGIN_DIR', trailingslashit( dirname( __FILE__) ) );
define( 'WPET_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
define( 'WPET_BASE', plugin_basename( __FILE__ ) );
define( 'WPET_PLUGIN_FILE', __FILE__ );

load_plugin_textdomain( 'wpet', false, dirname( WPET_BASE ) . '/lang/' );

require_once( 'lib/WPET.class.php' );

$wpet = WPET::getInstance();

register_activation_hook( __FILE__, array( $wpet, 'activate' ) );
register_deactivation_hook( __FILE__, array( $wpet, 'deactivate' ) );
register_uninstall_hook( __FILE__, array( $wpet, 'uninstall' ) );

if ( defined('WP_CLI') && WP_CLI ) {
	require_once WPET_PLUGIN_DIR . 'lib/Installer.class.php';
}
