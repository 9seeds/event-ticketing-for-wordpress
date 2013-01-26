<?php

require_once WPET_PLUGIN_DIR . 'lib/Table.class.php';

class WPET_TicketOptionsTable extends WPET_Table {

	protected function get_prepare_args( $defaults ) {
		$override = array(
			'post_type' => 'wpet_ticket_options'
		);
		return wp_parse_args( $override, $defaults );
	}

	function get_columns() {
		$columns = array(
			'cb'       => '<input type="checkbox" />',
			'post_title' => __( 'Name' ),
			'post_status'  => __( 'Status' ),
		);

		return $columns;
	}

	function get_sortable_columns() {
		return array(
			'post_title'    => 'post_title',
			'post_status'   => 'post_status',
		);
	}	
}

