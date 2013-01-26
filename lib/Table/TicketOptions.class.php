<?php

require_once WPET_PLUGIN_DIR . 'lib/Table.class.php';

class WPET_Table_TicketOptions extends WPET_Table {

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
			'wpet_type'  => __( 'Type' ),
		);

		return $columns;
	}

	function get_sortable_columns() {
		return array(
			'post_title'    => 'post_title',
		);
	}	
}

