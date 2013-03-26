<?php

//dummy classes from 1.x needed by PHP to complete "__PHP_Incomplete_Class_Name"
class ticketOption {}
class ticket {}
class package {}

class WPET_Convert {

	private $new_ticket_options = NULL;
	private $new_tickets = NULL;
	private $new_packages = NULL;
	
	public function __construct() {
		require_once WPET_PLUGIN_DIR . 'lib/TicketOptions.class.php';
		require_once WPET_PLUGIN_DIR . 'lib/Tickets.class.php';
		require_once WPET_PLUGIN_DIR . 'lib/Packages.class.php';
		require_once WPET_PLUGIN_DIR . 'lib/Settings.class.php';

		$this->new_ticket_options = new WPET_TicketOptions();
		$this->new_tickets = new WPET_Tickets();
		$this->new_packages = new WPET_Packages();
		$this->new_settings = new WPET_Settings();

	}
	
	public function runConversion() {
		if ( get_option( 'wpet_convert_1to2' ) ) {
			echo 'Conversion already run' . PHP_EOL;
			return; //conversion already run
		}
		
		$data = $this->getData();
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

	public function getData() {
		//for initial testing
		return unserialize( file_get_contents( WPET_PLUGIN_DIR . 'defaults.ser' ) );
	}

   	private function convertTicketOptions( $ticket_options ) {
		
		echo 'Ticket Options';
		foreach ( $ticket_options as $ticket_option ) {
			$data = array(
				'post_title' => $ticket_option->displayName,
				'meta' => array(
					'type' => $ticket_option->displayType,
					'values' => $ticket_option->options,
				)
			);
			$this->new_ticket_options->add( $data );
			echo '.';			
		}
		echo PHP_EOL;
	}

   	private function convertTickets( $tickets ) {
		
		echo 'Tickets';
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
			echo '.';
		}
		echo PHP_EOL;
	}

   	private function convertPackages( $packages ) {
		//@TODO $package->orderDetails and $package->coupon are not (yet?) converted here

		echo 'Packages';
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
			echo '.';
		}
		echo PHP_EOL;
	}

	private function convertEvent( $data ) {

		echo 'Event';

		//these go with the active event
		/*
		$event = (array)WPET::getInstance()->events->getWorkingEvent();
		$event['meta']['max_attendance'] = $data['eventAttendance'];
		WPET::getInstance()->events->add( $event );
		
		$data['messages']['messageEventName'];		
		*/
		
		echo '.' . PHP_EOL;
	}

	private function convertSettings( $data ) {
		//@TODO currently not saving $data['messages']['messageEmailBcc']
		echo 'Settings';

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
		
		echo '.' . PHP_EOL;
	}
}

if ( defined('WP_CLI') && WP_CLI ):

class WPET_Convert_Command extends WP_CLI_Command {

	/**
	 * Converts data from WP Event Ticketing 1.x to 2.0
	 *
	 * @synopsis [force]
	 */
	function convert( $args, $assoc_args ) {
		if ( isset( $args[0] ) && $args == 'force' )
			delete_option( 'wpet_convert_1to2' );

		$convert = new WPET_Convert();
		$convert->runConversion();
		//WP_CLI::line( print_r( $convert->getData(), true ) );
		
		// Print a success message
		WP_CLI::success( 'Done.' );
	}
}

WP_CLI::add_command( 'ticketing', 'WPET_Convert_Command' );
	
endif;
