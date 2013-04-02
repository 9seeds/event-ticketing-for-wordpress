<?php

//dummy classes from 1.x needed by PHP to complete "__PHP_Incomplete_Class_Name"
class ticketOption {}
class ticket {}
class package {}

class WPET_Installer {

	private $new_ticket_options = NULL;
	private $new_tickets = NULL;
	private $new_packages = NULL;
	private $new_settings = NULL;
	private $new_events = NULL;
	private $my_event = NULL;
	private $old_data = NULL;
	private $cli = false;
	
	public function __construct() {
		if ( defined('WP_CLI') && WP_CLI )
			$this->cli = true;
		
		require_once WPET_PLUGIN_DIR . 'lib/TicketOptions.class.php';
		require_once WPET_PLUGIN_DIR . 'lib/Tickets.class.php';
		require_once WPET_PLUGIN_DIR . 'lib/Packages.class.php';
		require_once WPET_PLUGIN_DIR . 'lib/Settings.class.php';
		require_once WPET_PLUGIN_DIR . 'lib/Events.class.php';

		
		$this->new_ticket_options = new WPET_TicketOptions();
		$this->new_tickets = new WPET_Tickets();
		$this->new_packages = new WPET_Packages();
		$this->new_settings = new WPET_Settings();
		$this->new_events = new WPET_Events();
		//$this->new_events->registerPostType();
		$this->my_event = $this->new_events->getWorkingEvent();
	}

	public function install() {
		$plugin_data = get_plugin_data( WPET_PLUGIN_FILE );
		//do some comparison of version numbers here for upgrades (2.x+ only)
		update_option( 'wpet_install_data', $plugin_data );

		//decide if we're going to convert, install, or do nothing
		$installed_before = get_option( 'wpet_activated_once' );
		
		if( $installed_before ) {
			$this->out( 'WPET 2.x+ already installed' . PHP_EOL );
			return;
		}
		
		$old_ticketing_data = $this->getOldData();

	   	if ( $old_ticketing_data && ! $this->my_event ) {
			$this->runConversion();
		} else {			
			$this->installOnce();
		}

	}

	private function installOnce() {
		$this->out( 'Installing 2.x+ defaults' . PHP_EOL );				

		//install an event if there are none	
		if ( ! $this->my_event ) {
			$this->out( 'Adding default 2.x+ event' . PHP_EOL );				
			$this->new_events->add();
		}
		
		$settings = WPET::getInstance()->settings;

		update_option( 'wpet_activate_once', true );

		//@TODO default TicketOption "Twitter"

		// events tab
		$settings->event_status = 'closed';
		$settings->closed_message = 'Tickets for this event will go on sale shortly.';
		$settings->thank_you = 'Thanks for purchasing a ticket to our event!' . "\n".
			'Your ticket link(s) are below' . "\n".
			'[ticketlinks]' . "\n\n".
			'If you have any questions please let us know!';

		// payments tab
		$settings->currency = 'USD';
		$settings->payment_gateway = 'WPET_Gateway_Manual';
		$settings->payment_gateway_status = 'sandbox';

		// email tab
		$settings->email_body = 'Thanks for purchasing a ticket to our event!' . "\n".
			'Your ticket link(s) are below' . "\n".
			'[ticketlinks]' . "\n\n".
			'If you have any questions please let us know!';

		// form display tab
		$settings->show_package_count = 1;
			
		// when should attendee data be collected?
		$settings->collect_attendee_data = 'post';
	}
	
	public function runConversion() {
		$this->out( 'Trying conversion from 1.x' . PHP_EOL );
		if ( get_option( 'wpet_convert_1to2' ) ) {
			$this->out( 'Conversion already run' . PHP_EOL );
			return; //conversion already run
		}
		
		$data = $this->getOldData();
		//print_r($data);
		//exit();
		
		if ( ! empty( $data['ticketOptions'] ) )			
			$this->convertTicketOptions( $data['ticketOptions'] );

		if ( ! empty( $data['ticketProtos'] ) )			
			$this->convertTickets( $data['ticketProtos'] );

		if ( ! empty( $data['packageProtos'] ) )			
			$this->convertPackages( $data['packageProtos'] );

		$this->convertEvent( $data );
		
		$this->convertSettings( $data );

		update_option( 'wpet_convert_1to2', true );		
	}

	public function getOldData() {
		if ( ! $this->old_data ) {
			//for initial testing
			$this->old_data = unserialize( file_get_contents( WPET_PLUGIN_DIR . 'defaults.ser' ) );
			//$this->old_data = get_option( 'eventTicketingSystem' );
		}
		return $this->old_data;
	}

   	private function convertTicketOptions( $ticket_options ) {
		
		$this->out( 'Ticket Options' );
		foreach ( $ticket_options as $ticket_option ) {
			$data = array(
				'post_title' => $ticket_option->displayName,
				'meta' => array(
					'type' => $ticket_option->displayType,
					'values' => $ticket_option->options,
				)
			);
			$this->new_ticket_options->add( $data );
			$this->out( '.' );
		}
		$this->out( PHP_EOL );
	}

   	private function convertTickets( $tickets ) {
		
		$this->out( 'Tickets' );
		foreach ( $tickets as $ticket ) {

			$options_selected = array();
			foreach ( $ticket->ticketOptions as $ticket_option ) {
				$selected = $this->new_ticket_options->findByTitle( $ticket_option->displayName );
				if ( $selected )
					$options_selected[] = $selected->ID;
			}
			
			$data = array(
				'post_title' => $ticket->ticketName,
			);

			if ( ! empty( $options_selected ) )
				$data['meta'] = array( 'options_selected' => $options_selected );
				
			$this->new_tickets->add( $data );
			$this->out( '.' );
		}
		$this->out( PHP_EOL );
	}

   	private function convertPackages( $packages ) {
		//@TODO $package->orderDetails and $package->coupon are not (yet?) converted here

		$this->out( 'Packages' );
		foreach ( $packages as $package ) {

			$tickets_selected = array();
			$ticket = reset( $package->tickets );
			$selected = $this->new_tickets->findByTitle( $ticket->ticketName );
						
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
				$data['meta']['ticket_id'] = $selected->ID;

			$this->new_packages->add( $data );
			$this->out( '.' );
		}
		$this->out( PHP_EOL );
	}

	private function convertEvent( $data ) {
		if ( ! $this->my_event ) {
			$this->out( 'Event' );
			$event = array(
				'post_title' => $data['messages']['messageEventName'],
				'meta' => array( 'max_attendance' => $data['eventAttendance'] )
			);
			$this->new_events->add();
			$this->out( '.' . PHP_EOL );			
		} else {
			$this->out( 'Working Event Found' . PHP_EOL );
		}
	}

	private function convertSettings( $data ) {
		//@TODO currently not saving $data['messages']['messageEmailBcc']
		$this->out( 'Settings' );

		if ( ! $this->new_settings->payment_gateway )
			$this->new_settings->payment_gateway = 'WPET_Gateway_PayPalExpress';

		if ( ! $this->new_settings->paypal_express_currency )
			$this->new_settings->paypal_express_currency = 'USD';
		
		//use these for the default included PayPalExpress gateway
		$this->new_settings->paypal_express_status = $data['paypalInfo']['paypalEnv'];

		if ( $data['paypalInfo']['paypalEnv'] == 'sandbox' ) {
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
		
		$this->out( '.' . PHP_EOL );
	}

	private function out( $message ) {
		if ( $this->cli )
			WP_CLI::out( $message );
	}
}

if ( defined('WP_CLI') && WP_CLI ):

class WPET_Installer_Command extends WP_CLI_Command {

	/**
	 * Runs the installer (tries to install, convert, or does nothing)
	 *
	 * @synopsis [force]
	 */
	function install( $args, $assoc_args ) {
		if ( isset( $args[0] ) && $args == 'force' ) {
			delete_option( 'wpet_convert_1to2' );
			delete_option( 'wpet_activate_once' );
		}
		
		$installer = new WPET_Installer();
		$installer->install();
		
		// Print a success message
		WP_CLI::success( 'Done.' );
	}

	/**
	 * Converts data from WP Event Ticketing 1.x to 2.0
	 *
	 * @synopsis [force]
	 */
	function convert( $args, $assoc_args ) {
		if ( isset( $args[0] ) && $args == 'force' )
			delete_option( 'wpet_convert_1to2' );

		$installer = new WPET_Installer();
		$installer->runConversion();
		
		// Print a success message
		WP_CLI::success( 'Done.' );
	}
}

WP_CLI::add_command( 'ticketing', 'WPET_Installer_Command' );
	
endif;
