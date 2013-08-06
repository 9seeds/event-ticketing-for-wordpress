<?php

//dummy classes from 1.x needed by PHP to complete "__PHP_Incomplete_Class_Name"
class ticketOption {
    
}

class ticket {
    
}

class package {
    
}

class WPET_Installer {

    private $new_ticket_options = NULL;
    private $new_tickets = NULL;
    private $new_packages = NULL;
    private $new_coupons = NULL;
    private $new_settings = NULL;
    private $new_events = NULL;
    private $new_attendees = NULL;
	private $new_payments = NULL;
    private $my_event = NULL;
    private $old_data = NULL;
	private $old_attendees = NULL;
    private $cli = false;
    //old_id => new_id
    private $ticket_option_map = array();
    private $ticket_map = array();
    private $package_map = array();

    public function __construct() {
		if (defined('WP_CLI') && WP_CLI)
			$this->cli = true;

		require_once WPET_PLUGIN_DIR . 'lib/TicketOptions.class.php';
		require_once WPET_PLUGIN_DIR . 'lib/Tickets.class.php';
		require_once WPET_PLUGIN_DIR . 'lib/Packages.class.php';
		require_once WPET_PLUGIN_DIR . 'lib/Coupons.class.php';
		require_once WPET_PLUGIN_DIR . 'lib/Settings.class.php';
		require_once WPET_PLUGIN_DIR . 'lib/Events.class.php';
		require_once WPET_PLUGIN_DIR . 'lib/Attendees.class.php';
		require_once WPET_PLUGIN_DIR . 'lib/Payments.class.php';


		$this->new_ticket_options = new WPET_TicketOptions();
		$this->new_tickets = new WPET_Tickets();
		$this->new_packages = new WPET_Packages();
		$this->new_coupons = new WPET_Coupons();
		$this->new_settings = new WPET_Settings();
		$this->new_events = new WPET_Events();
		$this->new_attendees = new WPET_Attendees();
		$this->new_payments = new WPET_Payments();

		$this->my_event = $this->new_events->getWorkingEvent();
    }

    public function install() {
		$plugin_data = get_plugin_data( WPET_PLUGIN_FILE );
		//do some comparison of version numbers here for upgrades (2.x+ only)
		update_option('wpet_install_data', $plugin_data);

		//decide if we're going to convert, install, or do nothing
		$installed_before = get_option( 'wpet_activate_once' );

		if ($installed_before) {
			$this->out( 'WPET 2.x+ already installed' . PHP_EOL );
		} else {
			$old_ticketing_data = $this->getOldData();

			if (!$this->my_event) {
				if ($old_ticketing_data) {
					$this->runConversion();
				} else {
					$this->installOnce();
				}
			} else {
				$this->out( 'WPET 2.x+ event present, no install will be performed' . PHP_EOL );
			}
		}
		$this->maybeCreateDefaults();
    }

    private function installOnce() {
		$this->out( 'Installing 2.x+ defaults' . PHP_EOL );

		//install an event if there are none	
		if (!$this->my_event) {
			$this->out( 'Adding default 2.x+ event' . PHP_EOL );
			//defaults to closed
			$data = array(
				'meta' => array(
					'event_status' => 'closed' ) );
			$this->new_events->add( $data );
		}

		$settings = WPET::getInstance()->settings;

		// events tab
		$settings->closed_message = 'Tickets for this event will go on sale shortly.';
		$settings->thank_you = 'Thanks for purchasing a ticket to our event!' . "\n" .
			'Your ticket link(s) are below' . "\n" .
			'[ticketlinks]' . "\n\n" .
			'If you have any questions please let us know!';

		// payments tab
		$settings->currency = 'USD';
		$settings->payment_gateway = 'WPET_Gateway_Manual';
		$settings->payment_gateway_status = 'sandbox';

		// email tab
		$settings->subject = 'Thank you for registering for our event';
		$settings->email_body = 'Thanks for purchasing a ticket to our event!' . "\n" .
			'Your ticket link(s) are below' . "\n" .
			'[ticketlinks]' . "\n\n" .
			'If you have any questions please let us know!';

		// form display tab
		$settings->show_package_count = 1;

		// when should attendee data be collected?
		$settings->collect_attendee_data = 'post';

		update_option( 'wpet_activate_once', true );
    }

 	public function runConversion() {
		$this->out( 'Trying conversion from 1.x' . PHP_EOL );
		if ( get_option( 'wpet_convert_1to2' ) ) {
			$this->out( 'Conversion already run' . PHP_EOL );
			return; //conversion already run
		}

		$data = $this->getOldData();

		if ( ! empty( $data['ticketOptions'] ) )
			$this->convertTicketOptions( $data['ticketOptions'] );

		if ( ! empty($data['ticketProtos'] ) )
			$this->convertTickets( $data['ticketProtos'] );

		if ( ! empty($data['packageProtos'] ) )
			$this->convertPackages( $data['packageProtos'] );

		if ( ! empty($data['coupons'] ) )
			$this->convertCoupons( $data['coupons'] );

		$this->convertEvent( $data );

		$this->convertSettings( $data );

		$attendees = $this->getOldAttendees();
		
		if ( ! empty( $attendees ) ) {
			$this->convertAttendeesAndPayments( $attendees );
		}

		update_option( 'wpet_convert_1to2', true );
    }

    public function getOldData() {
		if ( ! $this->old_data ) {
			//for initial testing
			//$this->old_data = unserialize( file_get_contents( WPET_PLUGIN_DIR . 'defaults.ser' ) );
			$this->old_data = get_option( 'eventTicketingSystem' );
		}
		return $this->old_data;
    }

    public function getOldAttendees() {
		if ( ! $this->old_attendees ) {
			global $wpdb;
			$packages = $wpdb->get_results( "select option_value from {$wpdb->options} where option_name like 'package_%'" );
			if ( is_array( $packages ) ) {
				$this->old_attendees = array();
				foreach ( $packages as $option ) {
					$this->old_attendees[] = unserialize( $option->option_value );
				}
			}
		}
		return $this->old_attendees;
    }

    private function convertTicketOptions($ticket_options) {
		$this->out( 'Ticket Options' );
		foreach ( $ticket_options as $ticket_option ) {
			$data = array(
				'post_title' => $ticket_option->displayName,
				'post_name' => sanitize_title_with_dashes( $ticket_option->displayName ),
				'meta' => array(
					'type' => $ticket_option->displayType,
					'values' => $ticket_option->options,
				)
			);
			$new_ticket_option_id = $this->new_ticket_options->add( $data );
			$this->ticket_option_map[$ticket_option->optionId] = $new_ticket_option_id;
			$this->out( '.' );
		}
		$this->out( PHP_EOL );
    }

    private function convertTickets($tickets) {

		$this->out('Tickets');
		foreach ($tickets as $ticket) {

			$options_selected = array();
			foreach ($ticket->ticketOptions as $ticket_option) {
				$options_selected[] = $this->ticket_option_map[$ticket_option->optionId];
			}

			$data = array(
				'post_title' => $ticket->ticketName,
			);

			if ( ! empty( $options_selected ) )
				$data['meta'] = array( 'options_selected' => $options_selected );

			$new_ticket_id = $this->new_tickets->add( $data );
			$this->ticket_map[$ticket->ticketId] = $new_ticket_id;
			$this->out( '.' );
		}
		$this->out( PHP_EOL );
    }

    private function convertPackages( $packages ) {
		//@TODO $package->orderDetails is not (yet?) converted here
		/*
		  [packageQuantities] => Array
		  (
			  [totalTicketsSold] => 181
			  [] => -28
			  [3] => 11
			  [1] => 198
		  )
	 	*/
		$this->out('Packages');
		foreach ( $packages as $package ) {

			$tickets_selected = array();
			$ticket = reset( $package->tickets );
			$selected = $this->ticket_map[$ticket->ticketId];

			$data = array(
				'post_title' => $package->packageName,
				'post_content' => $package->packageDescription,
				'meta' => array(
					'package_cost' => $package->price,
					'quantity' => $package->packageQuantity,
					'start_date' => $package->expireStart,
					'end_date' => $package->expireEnd,
					'ticket_quantity' => $package->ticketQuantity,
				),
			);

			if ( $selected )
				$data['meta']['ticket_id'] = $selected;

			$new_package_id = $this->new_packages->add( $data );
			$this->package_map[$package->packageId] = $new_package_id;
			$this->out( '.' );
		}
		$this->out( PHP_EOL );
    }

    private function convertCoupons($coupons) {
		$this->out( 'Coupons' );
		foreach ( $coupons as $coupon ) {
			$data = array(
				'post_title' => $coupon['couponCode'],
				'post_name' => sanitize_title_with_dashes( $coupon['couponCode'] ),
				'meta' => array(
					'type' => $coupon['type'] == 'flat' ? 'flat-rate' : 'percentage',
					'amount' => $coupon['amt'],
					'quantity' => $coupon['uses'] + $coupon['used'],
					'quantity_remaining' => $coupon['uses'],
					'package_id' => $this->package_map[$coupon['packageId']],
				),
			);
			$this->new_coupons->add( $data );
			$this->out( '.' );
		}
		$this->out( PHP_EOL );
    }

    private function convertEvent($data) {
		if ( ! $this->my_event ) {
			$this->out( 'Event' );
			$event = array(
				'post_title' => $data['messages']['messageEventName'],
				'meta' => array( 'max_attendance' => $data['eventAttendance'] )
			);
			if ( ! empty( $data['eventTicketingStatus'] ) )
				$event['meta']['event_status'] = $data['eventTicketingStatus'] ? 'open' : 'closed';

			$this->new_events->add( $event );
			$this->out( '.' . PHP_EOL );
		} else {
			$this->out( 'Working Event Found' . PHP_EOL );
		}
    }

    private function convertSettings($data) {
		//@TODO currently not saving $data['messages']['messageEmailBcc']
		$this->out('Settings');

		if (!$this->new_settings->payment_gateway)
			$this->new_settings->payment_gateway = 'WPET_Gateway_PayPalExpress';

		if (!$this->new_settings->paypal_express_currency)
			$this->new_settings->paypal_express_currency = 'USD';

		//use these for the default included PayPalExpress gateway
		$this->new_settings->paypal_express_status = $data['paypalInfo']['paypalEnv'];

		if ($data['paypalInfo']['paypalEnv'] == 'sandbox') {
			$this->new_settings->paypal_sandbox_api_username = $data['paypalInfo']['paypalAPIUser'];
			$this->new_settings->paypal_sandbox_api_password = $data['paypalInfo']['paypalAPIPwd'];
			$this->new_settings->paypal_sandbox_api_signature = $data['paypalInfo']['paypalAPISig'];
		} else {
			$this->new_settings->paypal_live_api_username = $data['paypalInfo']['paypalAPIUser'];
			$this->new_settings->paypal_live_api_password = $data['paypalInfo']['paypalAPIPwd'];
			$this->new_settings->paypal_live_api_signature = $data['paypalInfo']['paypalAPISig'];
		}

		$this->new_settings->organizer_name = $data['messages']['messageEmailFromName'];
		$this->new_settings->organizer_email = $data['messages']['messageEmailFromEmail'];
		$this->new_settings->closed_message = $data['messages']['messageRegistrationComingSoon'];
		$this->new_settings->thank_you = $data['messages']['messageThankYou'];

		$this->new_settings->subject = $data['messages']['messageEmailSubj'];
		$this->new_settings->email_body = $data['messages']['messageEmailBody'];

		$this->new_settings->show_package_count = $data['displayPackageQuantity'];

		$this->out('.' . PHP_EOL);
    }

    private function convertAttendeesAndPayments( $packages ) {
		$this->out( 'Attendee & Payment' );

		foreach ($packages as $package) {

			//hope this works
			$package_post = $this->new_packages->findByTitle($package->packageName);
			$meta = array('package' => $package_post->ID);

			foreach ($package->tickets as $ticket) {
				$first_name = '';
				$last_name = '';
				
				foreach ($ticket->ticketOptions as $ticket_option) {
					if (!empty($ticket_option->value)) {

						//find the ticket option
						$new_option_id = $this->ticket_option_map[$ticket_option->optionId];
						$ticket_option_post = $this->new_ticket_options->findByID($new_option_id);
						//save meta as 'wpet_<post-name>"
						$meta_key = str_replace( '_', '-', $ticket_option_post->post_name );
						$meta[$meta_key] = $ticket_option->value;
					}

					if ( strtolower( $ticket_option->displayName ) == 'first name' )
						$first_name = $ticket_option->value;

					if ( strtolower( $ticket_option->displayName ) == 'last name' )
						$last_name = $ticket_option->value;
				}
				
				$data = array(
					'post_title' => "{$first_name} {$last_name}",
					'meta' => $meta,
				);

				$this->new_attendees->add($data);			
				$this->out( '.' );

			}

			$pkg_array = array();
			foreach ( $package->orderDetails['items'] as $item ) {
				$pkg_array[$this->package_map[$item['packageid']]] = $item['quantity']; //@TODO Undefined offset: 424242
			}

			$data = array(
				'post_title' => $package->packageId,
				'meta' => array(
					'package_purchase' => $pkg_array,
				),
			);

			if ( is_array( $package->coupon ) ) {
				$data['meta']['coupon_code'] = $package->coupon['couponCode'];	
			}

			$this->new_payments->add($data);			
			$this->out( '.' );
			
		}
		$this->out( PHP_EOL );
    }

    /**
     * Resets the WPET database based on passed in values
     * 
     * @global WPDB $wpdb
     * @param Array $post_types 
    public function reset($post_types) {
	global $wpdb;

	// Remove all ticket options
	if (in_array('ticket_options', $post_types)) {
	    $query = "
		DELETE FROM wp_posts 
		WHERE post_type = '" . WPET::getInstance()->ticket_options->getPostType() . "' 
		
		";

	    $wpdb->query($query);
	}

	// Remove all tickets
	if (in_array('tickets', $post_types)) {
	    $query = "
		DELETE FROM wp_posts 
		WHERE post_type = '" . WPET::getInstance()->tickets->getPostType() . "' 
		
		";

	    $wpdb->query($query);
	}

	// Remove all packages
	if (in_array('packages', $post_types)) {
	    $query = "
		DELETE FROM wp_posts 
		WHERE post_type = '" . WPET::getInstance()->packages->getPostType() . "' 
		
		";

	    $wpdb->query($query);
	}

	// Remove all Coupons
	if (in_array('coupons', $post_types)) {
	    $query = "
		DELETE FROM wp_posts 
		WHERE post_type = '" . WPET::getInstance()->coupons->getPostType() . "' 
		
		";

	    $wpdb->query($query);
	}

	// Remove all attendees
	if (in_array('attendees', $post_types)) {
	    $query = "
		DELETE FROM wp_posts 
		WHERE post_type = '" . WPET::getInstance()->attendees->getPostType() . "' 
		
		";

	    $wpdb->query($query);
	}


	// Remove all notifications
	if (in_array('notifications', $post_types)) {
	    $query = "
		DELETE FROM wp_posts 
		WHERE post_type = '" . WPET::getInstance()->notifications->getPostType() . "' 
		
		";

	    $wpdb->query($query);
	}

	// Remove all events
	if (in_array('events', $post_types)) {
	    $query = "
		DELETE FROM wp_posts 
		WHERE post_type = '" . WPET::getInstance()->events->getPostType() . "' 
		
		";
	    $wpdb->query($query);
	}

	// Remove all payment
	if (in_array('payment', $post_types)) {
	    $query = "
		DELETE FROM wp_posts 
		WHERE post_type = '" . WPET::getInstance()->payment->getPostType() . "' 
		
		";

	    $wpdb->query($query);
	}
	
	
	$this->maybeCreateDefaults();
    }
     */

    private function maybeCreateDefaults() {
		$default_options = array();

		if ( ! $this->new_ticket_options->anyExist() ) {
			$default_options[] = $this->new_ticket_options->createTicketOption(__('First Name', 'wpet'));
			$default_options[] = $this->new_ticket_options->createTicketOption(__('Last Name', 'wpet'));
			$default_options[] = $this->new_ticket_options->createTicketOption(__('Email', 'wpet'));
			$default_options[] = $this->new_ticket_options->createTicketOption(__('Twitter', 'wpet'), false, false, false);
		}



		if ( ! $this->new_tickets->anyExist() ) {
			$title = __('General Admission', 'wpet');
			$ticket = array(
				'post_title' => $title,
				'post_name' => sanitize_title_with_dashes($title),
			);
			if (!empty($default_options))
				$ticket['meta'] = array('options_selected' => $default_options);

			$ticket_id = $this->new_tickets->add($ticket);
		}


		if ( ! $this->new_packages->anyExist() ) {
			$title = __('General Admission Package', 'wpet');
			$package = array(
				'post_title' => $title,
				'post_name' => sanitize_title_with_dashes($title),
				'post_content' => $title,
				'meta' => array(
					'package_cost' => '20',
					'quantity' => '100',
					'ticket_quantity' => '1',
					'start_date' => date('m/d/Y'),
					'end_date' => date('m/d/Y', strtotime('+60 days'))
				),
			);

			if ( ! empty( $ticket_id ) )
				$package['meta']['ticket_id'] = $ticket_id;

			$this->new_packages->add($package);
		}
    }


    private function out($message) {
		if ($this->cli)
			WP_CLI::out($message);
    }

}

if (defined('WP_CLI') && WP_CLI):

class WPET_Installer_Command extends WP_CLI_Command {

	/**
	 * Runs the installer (tries to install, convert, or does nothing)
	 *
	 * @synopsis [force]
	 */
	function install($args, $assoc_args) {
	    if (isset($args[0]) && $args == 'force') {
		delete_option('wpet_convert_1to2');
		delete_option('wpet_activate_once');
	    }

	    $installer = new WPET_Installer();
	    $installer->install();

	    // Print a success message
	    WP_CLI::success('Done.');
	}

	/**
	 * Converts data from WP Event Ticketing 1.x to 2.0
	 *
	 * @synopsis [force]
	 */
	function convert($args, $assoc_args) {
	    if (isset($args[0]) && $args == 'force')
		delete_option('wpet_convert_1to2');

	    $installer = new WPET_Installer();
	    $installer->runConversion();

	    // Print a success message
	    WP_CLI::success('Done.');
	}

	/**
	 * Displays WPET 1.x serialized data
	 */
	function show_data( $args, $assoc_args ) {
	    $installer = new WPET_Installer();
		$data = $installer->getOldData();
		print_r( $data );
	}

	/**
	 * Displays WPET 1.x Attendees & Package Purchases
	 */
	function show_attendees( $args, $assoc_args ) {
	    $installer = new WPET_Installer();
		$attendees = $installer->getOldAttendees();
		print_r($attendees);
	}
	
}


WP_CLI::add_command('ticketing', 'WPET_Installer_Command');
	
endif;
