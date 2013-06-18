<?php

/**
 * @since 2.0
 */
class WPET_Reports extends WPET_Module {

	private $percent_sold = 0;
	
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
		wp_register_script( 'google-jsapi', 'https://www.google.com/jsapi' );
		//put these in the footer
		wp_register_script( 'wpet-admin-reports', WPET_PLUGIN_URL . 'js/admin_reports.js', array( 'google-jsapi' ), NULL, true );
		wp_enqueue_script( 'wpet-admin-reports' );
		add_action( 'admin_footer', array( $this, 'adminFooter' ) );
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
			'content'	=> '<p>' . __( 'Here\'s an explanation of the reports found on this page:', 'wpet' ) . '</p>'.
				'<h2>'. __( 'Sales by Package', 'wpet' ) .'</h2>'.
				__( 'This is a report of sales broken down by package and contains the following columns.', 'wpet' ).
				'<ul>'.
					'<li>'. sprintf( __( '%sPackages%s is the name of the package that was sold.', 'wpet' ), '<strong>', '</strong>' ) .'</li>'.
					'<li>'. sprintf( __( '%sSold%s is the number of packages sold. Keep in mind that a package may contain multiple tickets, so this number may not reflect the total number of tickets sold.', 'wpet' ), '<strong>', '</strong>' ) .'</li>'.
					'<li>'. sprintf( __( '%sRemaining%s is the quantity still remaining for purchase of this specific package.', 'wpet' ), '<strong>', '</strong>' ) .'</li>'.
					'<li>'. sprintf( __( '%sRevenue%s is the net amount earned from sales of the specific package after taking in to account any coupons used during.', 'wpet' ), '<strong>', '</strong>' ) .'</li>'.
					'<li>'. sprintf( __( '%sCoupons%s is the amount that clients saved using coupons during their orders.', 'wpet' ), '<strong>', '</strong>' ) .'</li>'.
				'</ul>'.
				'<h2>'. __( 'Sales by Ticket Type', 'wpet' ) .'</h2>'.
				__( 'This is a report of sales broken down by ticket type and can be used to calculate the number of attendees to expect. It contais the following columns.', 'wpet' ).
				'<ul>'.
					'<li>'. sprintf( __( '%sTicket%s is the name of the ticket.', 'wpet' ), '<strong>', '</strong>' ) .'</li>'.
					'<li>'. sprintf( __( '%sSold%s is the quantity of this specific ticket that have been sold.', 'wpet' ), '<strong>', '</strong>' ) .'</li>'.
					'<li>'. sprintf( __( '%sRemaining%s is the quantity of this specific ticket that are still remaining to be sold.', 'wpet' ), '<strong>', '</strong>' ) .'</li>'.
				'</ul>'.
				'<h2>'. __( 'Ticket Gauge', 'wpet' ) .'</h2>'.
				__( 'The ticket guage shows you what percentage of tickets have been sold for the event. Once the guage reaches 100%, your event is sold out.', 'wpet' ),
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


		$event = WPET::getInstance()->events->getWorkingEvent();
		$packages = WPET::getInstance()->packages;
		$tickets = WPET::getInstance()->tickets;
		$pkg_posts = $packages->findAllByEvent();

		$package_rows = array();
		$package_totals = array(
			'sold' => 0,
			'remaining' => 0,
			'revenue' => 0,
			'coupons' => 0 );

		$ticket_rows = array();
		$tkt_posts = array();
		$ticket_totals = array(
			'sold' => 0,
			'remaining' => 0,
		);
		
		foreach( $pkg_posts as $pkg_post ){
			$packages_sold = $packages->sold( $pkg_post->ID );
			$packages_remaining = $packages->remaining( $event->ID, $pkg_post->ID );
			$packages_rev = $packages_sold * $packages->cost( $pkg_post->ID );
			
			$package_rows[] = array(
				'title' => $pkg_post->post_title,
				'sold' => $packages_sold,
				'remaining' => $packages_remaining,
				'revenue' => $packages_rev,
				'coupons' => 0, //@TODO
			);

			//calc individual ticket info
			if ( empty( $tkt_posts[$pkg_post->wpet_ticket_id] ) ) {
				$tkt_post = $tickets->findByID( $pkg_post->wpet_ticket_id );
				$tkt_posts[$pkg_post->wpet_ticket_id] = $tkt_post;
				
				$ticket_rows[$pkg_post->wpet_ticket_id] = array(
					'title' => $tkt_post->post_title,
					'sold' => 0,
					'remaining' => 0,
				);
			} else {
				 $tkt_post = $tkt_posts[$pkg_post->wpet_ticket_id];
			}

			$tickets_sold = $pkg_post->wpet_ticket_quantity;
			$tickets_remaining = $packages_remaining * $pkg_post->wpet_ticket_quantity;
			
			$ticket_rows[$pkg_post->wpet_ticket_id]['sold'] += $tickets_sold;
			$ticket_rows[$pkg_post->wpet_ticket_id]['remaining'] += $tickets_remaining;

			
			//add to totals
			$ticket_totals['sold'] += $tickets_sold;
			$ticket_totals['remaining'] += $tickets_remaining;
			
			$package_totals['sold'] += $packages_sold;
			$package_totals['remaining'] += $packages_remaining;
			$package_totals['revenue'] += $packages_rev;

		}
		$package_rows[] = $package_totals;
		$ticket_rows[] = $ticket_totals;

		if( $package_totals['sold'] > 0 ) {
		    $this->percent_sold = ( $package_totals['sold'] / ( $package_totals['sold'] + $package_totals['remaining'] ) ) * 100;
		} else {
		    $this->percent_sold = 0;
		}
		
		$data = array(
			'package_rows' => $package_rows,
			'ticket_rows' => $ticket_rows,
		);
		WPET::getInstance()->display( 'reporting.php', $data );
	}

	public function adminFooter() {
		wp_localize_script(
			'wpet-admin-reports',
			'reportsL10n',
			array(
				'data' => array(
					array( 'Label', 'Value' ),
					array( __( '% Sold' ), $this->percent_sold ), //@TODO add percent sold of Working Event
				),
		) );
	}
	
}// end class