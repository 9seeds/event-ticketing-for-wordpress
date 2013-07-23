<?php

/**
 * @since 2.0
 */
class WPET_Reports extends WPET_Module {

	private $percent_sold = 0;
	private $packages = array();
	private $tickets = array();

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
		$payment = WPET::getInstance()->payment;
		$packages = WPET::getInstance()->packages;
		$coupons = WPET::getInstance()->coupons;

		$pmt_posts = $payment->findAllByEvent();

		
		$package_rows = array();
		$default_package_row = array(
			'title' => '',
			'sold' => 0,
			'remaining' => 0,
			'revenue' => 0,
			'coupons' => 0 );
		$package_totals = $default_package_row;

		$ticket_rows = array();
		$default_ticket_row = array(
			'title' => '',
			'sold' => 0,
			'remaining' => 0,
		);
		$ticket_totals = $default_ticket_row;

		foreach ( $pmt_posts as $pmt_post ) {
			foreach ( $pmt_post->wpet_package_purchase as $package_id => $qty ) {
				if ( ! isset( $package_rows[$package_id] ) )
					$package_rows[$package_id] = $default_package_row;


				$package_rev = $qty * $packages->cost( $package_id );
				$package_rows[$package_id]['sold'] += $qty;
				$package_rows[$package_id]['revenue'] = $package_rev;

				if ( $pmt_post->wpet_coupon_code ) {
					$discount = $coupons->calcDiscount( $package_rev, $package_id, $pmt_post->wpet_coupon_code );
					$package_rows[$package_id]['coupons'] += $discount;
					$package_rows[$package_id]['revenue'] -= $discount;
				}
			}
		}

		foreach( $package_rows as $package_id => $row ) {
			$pkg_post = $this->getPackage( $package_id );
			$packages_remaining = $packages->remaining( $event->ID, $pkg_post->ID );

			$package_rows[$package_id]['title'] = $pkg_post->post_title;
			$package_rows[$package_id]['remaining'] = $packages_remaining;

			//calc individual ticket info
			$tkt_post = $this->getTicket( $pkg_post->wpet_ticket_id );

			if ( ! isset( $ticket_rows[$pkg_post->wpet_ticket_id] ) ) {
				$ticket_rows[$pkg_post->wpet_ticket_id] = $default_ticket_row;
				$ticket_rows[$pkg_post->wpet_ticket_id]['title'] = $tkt_post->post_title;
			}

			$tickets_sold = $pkg_post->wpet_ticket_quantity;
			$tickets_remaining = $packages_remaining * $pkg_post->wpet_ticket_quantity;

			$ticket_rows[$pkg_post->wpet_ticket_id]['sold'] += $tickets_sold;
			$ticket_rows[$pkg_post->wpet_ticket_id]['remaining'] += $tickets_remaining;

			//add to totals
			$ticket_totals['sold'] += $tickets_sold;
			$ticket_totals['remaining'] += $tickets_remaining;

			$package_totals['sold'] += $package_rows[$package_id]['sold'];
			$package_totals['remaining'] += $packages_remaining;
			$package_totals['revenue'] += $package_rows[$package_id]['revenue'];
			$package_totals['coupons'] += $package_rows[$package_id]['coupons'];

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

	private function getPackage( $package_id ) {
		if ( ! isset( $this->packages[$package_id] ) ) {
			$packages = WPET::getInstance()->packages;
			$this->packages[$package_id] = $packages->findByID( $package_id );
		}
		return $this->packages[$package_id];
	}

	private function getTicket( $ticket_id ) {
		if ( ! isset( $this->tickets[$ticket_id] ) ) {
			$tickets = WPET::getInstance()->tickets;
			$this->tickets[$ticket_id] = $tickets->findByID( $ticket_id );
		}
		return $this->tickets[$ticket_id];
	}

	public function adminFooter() {
		wp_localize_script(
			'wpet-admin-reports',
			'reportsL10n',
			array(
				'data' => array(
					array( 'Label', 'Value' ),
					array( __( '% Sold' ), (float)number_format($this->percent_sold, 2, '.', '' ) ),
				),
		) );
	}

}// end class