<?php

class WPET_Convert {
	
	public function runConversion() {
		$data = $this->getData();

		if ( ! empty( $data['ticketOptions'] ) )			
			$this->convertTicketOptions( $data['ticketOptions'] );

		if ( ! empty( $data['ticketProtos'] ) )			
			$this->convertTickets( $data['ticketProtos'] );

		if ( ! empty( $data['packageProtos'] ) )			
			$this->convertPackages( $data['packageProtos'] );

		if ( ! empty( $data['packageProtos'] ) )			
			$this->convertPackages( $data['packageProtos'] );

		
		//print_r($data);
	}

	public function getData() {
		//for initial testing
		return unserialize( file_get_contents( WPET_PLUGIN_DIR . 'defaults.ser' ) );
	}

   	private function convertTicketOptions( $ticket_options ) {
		require_once WPET_PLUGIN_DIR .'lib/TicketOptions.class.php';
		$new_ticket_options = new WPET_TicketOptions();
		
		echo 'Ticket Options';
		foreach ( $ticket_options as $ticket_option ) {
			/*
			$data = array(

			);
			$new_ticket_options->add( $data );
			*/
			echo '.';			
		}
		echo PHP_EOL;
	}

   	private function convertTickets( $tickets ) {
		require_once WPET_PLUGIN_DIR .'lib/Tickets.class.php';
		$new_tickets = new WPET_Tickets();
		
		echo 'Tickets';
		foreach ( $tickets as $ticket ) {
			/*
			$data = array(

			);
			$new_tickets->add( $data );
			*/
			echo '.';
		}
	}
	
   	private function convertPackages( $packages ) {
		require_once WPET_PLUGIN_DIR .'lib/Packages.class.php';
		$new_packages = new WPET_Packages();
		
		echo 'Packages';
		foreach ( $packages as $package ) {
			/*
			$data = array(

			);
			$new_packages->add( $data );
			*/
			echo '.';
		}
		echo PHP_EOL;
	}

}


if ( defined('WP_CLI') && WP_CLI ):

class WPET_Convert_Command extends WP_CLI_Command {

	/**
	 * Converts data from WP Event Ticketing 1.x to 2.0
	 *
	 * @synopsis
	 */
	function convert( $args, $assoc_args ) {
		$convert = new WPET_Convert();
		$convert->runConversion();
		//WP_CLI::line( print_r( $convert->getData(), true ) );
		
		// Print a success message
		WP_CLI::success( 'Done.' );
	}
}

WP_CLI::add_command( 'ticketing', 'WPET_Convert_Command' );
	
endif;
