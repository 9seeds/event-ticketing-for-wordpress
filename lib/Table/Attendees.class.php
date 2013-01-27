<?php

require_once WPET_PLUGIN_DIR . 'lib/Table.class.php';

class WPET_Table_Coupons extends WPET_Table {

	public function __construct( $args = array() ) {
		$defaults = array( 'post_type' => 'wpet_attendees' );
		$args = wp_parse_args( $args, $defaults );
		parent::__construct( $args );
	}

	public function get_columns() {
		$columns = array(
			//'cb'        => '<input type="checkbox" />',
			'title' => 'Name',
			'wpet_email' => 'Email',
			'wpet_purchase_date' => 'Purchase Date'
		);

		return $columns;
	}

	public function get_sortable_columns() {
		return array(
			'title' => array( 'title', true ),
		);
	}
}