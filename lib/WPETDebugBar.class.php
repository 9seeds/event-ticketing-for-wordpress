<?php
/**
 * Integrate debugging with debug bar plugin.
 *
 * @since 2.0
 */

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

add_filter( 'debug_bar_panels', 'wpet_load_debug_bar', 1 );
function wpet_load_debug_bar($panels) {
	if (!class_exists('WPETDebugBar') && class_exists('Debug_Bar_Panel')) {
		class WPETDebugBar extends Debug_Bar_Panel {

			private static $debug_log = array();
			
			function init() {
				// Title to display in left column of debug bar
				$this->title( __('WP Event Ticketing', 'wpet') );

				// Custom styling for the debug bar output
				wp_enqueue_style( 'wpet-debug-bar-css', WPET_PLUGIN_URL . 'css/debug-bar.css' );

				// Action hook called when new dbug info is submitted
				add_action( 'wpet_debug', array( &$this, 'logDebug' ), 1, 3 );

			}

			function prerender() {
				$this->set_visible( true );
			}

			function render() {
				echo '<div id="wpet-debug-bar">';
				if (count(self::$debug_log)) {// echo "<pre>";var_dump(self::$debug_log); echo '</pre>';
					echo '<ul>';
					foreach(self::$debug_log as $k => $v) {
						echo "<li class='wpet-debug-{$v['format']}'>";
						echo "<div class='wpet-debug-entry-title'>{$v['title']}</div>";

						if ( 'dump' != $v['format'] ) {
							echo '<div class="wpet-debug-entry-data">';
							echo $v['data'];
							echo '</div>';
						} else {
							if( !class_exists( 'dBug' ) ) require_once( 'dBug.php' );
								dBug( $v['data'] );
						}
						echo '</li>';
					}
					echo '</ul>';
				}
				echo '</div>';
			}

			/**
			 * log debug statements for display in debug bar
			 *
			 * @since 1.0
			 * @param string $title - message to display in log
			 * @param string $data - optional data to display
			 * @param string $format - optional format (log|warning|error|notice|dump)
			 * @return void
			 * @author Ben Lobaugh
			 */
			public function logDebug($title, $data, $format) { 
				self::$debug_log[] = array(
					'title' => $title,
					'data' => $data,
					'format' => $format,
				);
			}
		}
		$panels[] = new WPETDebugBar;
	}
	return $panels;
}


