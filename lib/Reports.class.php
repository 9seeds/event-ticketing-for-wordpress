<?php

/**
 * @since  2.0
 * @todo Move this to it's proper place
 */
function add_reporting_script() {
	wp_enqueue_script( 'google-jsapi', 'https://www.google.com/jsapi' );
}
add_action( 'admin_enqueue_scripts', 'add_reporting_script' );

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

	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Day', 'Revenue'],
				['3/1',  80],
				['3/2',  50],
				['3/3',  20],
				['3/4',  35],
				['3/5',  34],
				['3/6',  0],
				['3/7',  0],
				['3/8',  40],
				['3/9',  20],
				['3/10',  12],
				['3/11',  83],
				['3/12',  111],
				['3/13',  90],
				['3/14',  37],
				['3/15',  54],
				['3/16',  38],
				['3/17',  14],
				['3/18',  62],
				['3/19',  85],
				['3/20',  37],
				['3/21',  87],
				['3/22',  24],
				['3/23',  33],
				['3/24',  60],
				['3/25',  105],
				['3/26',  90],
				['3/27',  40],
				['3/28',  60],
				['3/29',  20],
				['3/30',  50],
			]);

			var options = {
				title: 'Daily Revenue'
			};

			var chart = new google.visualization.LineChart(document.getElementById('line_chart_rev'));
			chart.draw(data, options);
		}
	</script>

	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Day', 'Sales'],
				['3/1',  4],
				['3/2',  3],
				['3/3',  1],
				['3/4',  2],
				['3/5',  2],
				['3/6',  0],
				['3/7',  0],
				['3/8',  2],
				['3/9',  2],
				['3/10',  3],
				['3/11',  7],
				['3/12',  12],
				['3/13',  9],
				['3/14',  7],
				['3/15',  4],
				['3/16',  8],
				['3/17',  4],
				['3/18',  2],
				['3/19',  5],
				['3/20',  7],
				['3/21',  7],
				['3/22',  4],
				['3/23',  3],
				['3/24',  1],
				['3/25',  15],
				['3/26',  9],
				['3/27',  4],
				['3/28',  6],
				['3/29',  2],
				['3/30',  5],
			]);

			var options = {
				title: 'Daily Ticket Sales'
			};

			var chart = new google.visualization.LineChart(document.getElementById('line_chart_sales'));
			chart.draw(data, options);
		}
	</script>



<?php
}

add_action( 'admin_head', 'hawkins_hacky_js' );



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
			'title'	=> __( 'Overview' ),
			'content'	=> '<p>' . __( 'This screen provides a quick overview of your event.' ) . '</p>',
			)
		);
		$screen->add_help_tab(
			array(
			'id'	=> 'report-tab',
			'title'	=> __( 'Reports' ),
			'content'	=> '<p>' . __( 'Here\'s an explanation of the options found on this page:' ) . '</p>'.
				'<ul>'.
					'<li>'. __( '<strong>Display Name</strong> is what will be shown to your visitor when this option is added to a ticket.' ) .'</li>'.
					'<li>'. __( '<strong>Option Type</strong> lets you decide what type of form field will be displayed. The options are Text Input, Dropdown or Multi Select.' ) .'</li>'.
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
		$menu[] = array( 'Reports', 'Reports', 'add_users', 'wpet_reports', array( $this, 'renderAdminPage' ) );
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