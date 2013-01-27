<?php

require_once WPET_PLUGIN_DIR . 'lib/Table.class.php';

class WPET_Table_TicketOptions extends WPET_Table {

	public function __construct( $args = array() ) {
		$defaults = array( 'post_type' => 'wpet_ticket_options' );
		$args = wp_parse_args( $args, $defaults );
		parent::__construct( $args );
	}

	function get_columns() {
		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'title'     => __( 'Name' ),
			'wpet_type' => __( 'Type' ),
		);

		return $columns;
	}

	function get_sortable_columns() {
		return array(
			'title' => array( 'title', true ),
		);
	}
}

