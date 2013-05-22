<?php

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
		wp_register_script( 'google-jsapi', 'https://www.google.com/jsapi' );
		wp_register_script( 'wpet-admin-reports', WPET_PLUGIN_URL . 'js/admin_reports.js', array( 'google-jsapi' ) );
		wp_enqueue_script( 'wpet-admin-reports' );
		wp_localize_script(
			'wpet-admin-reports',
			'reportsL10n',
			array(
				'data' => array(
					array( 'Label', 'Value' ),
					array( __( '% Sold' ), 80 ), //@TODO add percent sold of Working Event
				),
		) );
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
		//$inst = apply_filters( 'wpet_instructions', $inst = array( 'instructions' => array() ) );
		WPET::getInstance()->display( 'reporting.php' );
	}

}// end class