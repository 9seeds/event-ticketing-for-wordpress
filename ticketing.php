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
define( 'WPET_JQUERY_VERSION', '1.9.2' );
define( 'WPET_DEBUG', false );

require_once( 'lib/WPET.class.php' );

$wpet = WPET::getInstance();

register_activation_hook( __FILE__, array( $wpet, 'activate' ) );
register_deactivation_hook( __FILE__, array( $wpet, 'deactivate' ) );
// register_uninstall_hook( __FILE__, array( $wpet, 'uninstall' ) );

/**
 * @todo Move the following code where you'd like it to live
 *
 * Notes: This adds Settings & Instructions links to the plugins page.
 *
 */

if( !defined( 'WPET_BASE' ) )
	DEFINE('WPET_BASE', plugin_basename(__FILE__) );

// add_filter( 'plugin_action_links', 'wpet_plugin_links', 10, 2	);
 add_filter( 'plugin_row_meta', 'wpet_plugin_links', 10, 2	);


/**
 * show settings link on plugins page
 *
 * @since 2.0
 * @return WPET
 *
 */
function wpet_plugin_links( $links, $file ) {

	static $this_plugin;

	if (!$this_plugin) {
		$this_plugin = WPET_BASE;
	}

	// check to make sure we are on the correct plugin
	if ($file == $this_plugin) {
		$links[] = '<a href="'.menu_page_url( 'wpet_settings', 0 ).'">'.__('Settings', 'wpet').'</a>';
		$links[] = '<a href="'.menu_page_url( 'wpet_instructions', 0 ).'">'.__('Instructions', 'wpet').'</a>';
		$links[] = '<a href="http://support.9seeds.com/forums/wp-event-ticketing/">' . __('Support Forum','wpet') . '</a>';
	}

	return $links;
}

if ( defined('WP_CLI') && WP_CLI ) {
	require_once WPET_PLUGIN_DIR . 'lib/Convert.class.php';
}
