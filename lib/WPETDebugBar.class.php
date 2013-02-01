<?php

/**
 * Integrate debugging with debug bar plugin.
 *
 * @since 2.0
 */
// Don't load directly
if (!defined('ABSPATH')) {
    die('-1');
}

add_filter('debug_bar_panels', 'wpet_dbb');

//$wpet_debug = new WPET_Test_Bar;


$wpet_debug = array();
;

/**
 * Adds the WP Event Ticketing custom debug bar to the panels
 * 
 * @since 2.0
 * @global array $wpet_debug
 * @param array $panels
 * @return WPET_Debug_Bar 
 */
function wpet_dbb($panels) {
    global $wpet_debug;

    $panels[] = new WPET_Debug_Bar();
    return $panels;
}

if (!class_exists('Debug_Bar_Panel')) {
    require_once( WP_PLUGIN_DIR . '/debug-bar/panels/class-debug-bar-panel.php' );
}

/**
 * Creates a custom debug bar for WP Event Ticketing
 * 
 * Not copied from The Events Calendar cause theirs was hacky and broken :P
 *  
@since 2.0
 */
class WPET_Debug_Bar extends Debug_Bar_Panel {

    private $mLog = array();

    /**
     * @since 2.0 
     */
    function __construct() {
	add_filter('wpet_log', array($this, 'log'), 1, 3);
	parent::__construct();
    }

    /**
     * @since 2.0 
     */
    function init() {
	$this->title(__('WP Event Ticketing', 'wpet'));

	// Custom styling for the debug bar output
	wp_enqueue_style('wpet-debug-bar-css', WPET_PLUGIN_URL . 'css/debug-bar.css');
    }

    /**
     * Required stub
     * 
     * @since 2.0 
     */
    public function prerender() {}

    /**
     * Displays the WPET log
     * 
     * @since 2.0
     * @global array $wpet_debug 
     */
    public function render() {
	global $wpet_debug;

	foreach ($wpet_debug as $v) { //echo '<pre>'; var_dump( $l['data'] ); echo '</pre>'; return;
	    echo "<li class='wpet-debug-{$v['format']}'>";
	    echo "<div class='wpet-debug-entry-title'>{$v['title']}</div>";

	    if ('dump' != $v['format']) {
		echo '<div class="wpet-debug-entry-data">';
		echo $v['data'];
		echo '</div>';
	    } else {
		if (!class_exists('dBug'))
		    require_once( 'dBug.php' );
		dBug($v['data']);
	    }
	    echo '</li>';
	}
    }
} // end class