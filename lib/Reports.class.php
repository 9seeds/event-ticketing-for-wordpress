<?php

/**
 * @since  2.0
 * @todo Rename and move this to it's proper place
 */
function hawkins_hacky_js() {
	?>
	<script type='text/javascript'>
		google.load('visualization', '1', {packages:['gauge']});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Label', 'Value'],
				['% Sold', 80]
			]);

			var options = {
				width: 200, height: 200,
				redFrom: 90, redTo: 100,
				yellowFrom:75, yellowTo: 90,
				minorTicks: 5
			};

			var chart = new google.visualization.Gauge(document.getElementById('pie_chart_div'));
			chart.draw(data, options);
		}
	</script>
<?php
}



/**
 * @since 2.0
 */
class WPET_Reports extends WPET_Module {

	/**
	 * @since 2.0
	 */
	public function __construct() {
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 1 );
	}

	/**
	 * @since  2.0
	 * Enqueue Google Charts
	 */
	public function enqueueAdminScripts() {
		wp_enqueue_script( 'google-jsapi', 'https://www.google.com/jsapi' );
		add_action( 'admin_head', 'hawkins_hacky_js' );
	}

	/**
	 * @todo this help section isn't showing up like it should.
	 */
	
	/**
	 * Displays page specific contextual help through the contextual help API
	 *
	 * @see http://codex.wordpress.org/Function_Reference/add_help_tab
	 * @since 2.0
	 */
	public function contextHelp( $screen ) {
		$screen->add_help_tab(
			array(
			'id'	=> 'overview',
			'title'	=> __( 'Overview', 'wpet' ),
			'content'	=> '<p>' . __( 'This screen provides a quick overview of your event.', 'wpet' ) . '</p>',
			)
		);
		$screen->add_help_tab(
			array(
			'id'	=> 'report-tab',
			'title'	=> __( 'Reports', 'wpet' ),
			'content'	=> '<p>' . __( 'Here\'s an explanation of the options found on this page:', 'wpet' ) . '</p>'.
				'<ul>'.
			'<li>'. __( '<strong>Display Name</strong> is what will be shown to your visitor when this option is added to a ticket.', 'wpet' ) .'</li>'.
					'<li>'. __( '<strong>Option Type</strong> lets you decide what type of form field will be displayed. The options are Text Input, Dropdown or Multi Select.', 'wpet' ) .'</li>'.
				'</ul>',
			)
		);
	}

	/**
	 * Add Reports links to the Tickets menu
	 *
	 * @since 2.0
	 * @param type $menu
	 * @return array
	 */
	public function adminMenu( $menu ) {
		$menu[] = array( __( 'Reports', 'wpet' ),
						 __( 'Reports', 'wpet' ),
						 'add_users',
						 'wpet_reports',
						 array( $this, 'renderAdminPage' ) );
		return $menu;
	}

	/**
	 * Displays the menu page
	 *
	 * @since 2.0
	 */
	public function renderAdminPage() {
		//$inst = apply_filters( 'wpet_instructions', $inst = array( 'instructions' => array() ) );
		WPET::getInstance()->display( 'reporting.php' );
	}

}// end class