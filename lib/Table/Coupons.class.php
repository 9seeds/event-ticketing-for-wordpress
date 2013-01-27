<?php

require_once WPET_PLUGIN_DIR . 'lib/Table.class.php';

class WPET_Table_Coupons extends WPET_Table {

	public function __construct( $args = array() ) {
		$defaults = array( 'post_type' => 'wpet_coupons' );
		$args = wp_parse_args( $args, $defaults );
		parent::__construct( $args );
	}

	public function get_columns() {
		$columns = array(
			//'cb'        => '<input type="checkbox" />',
			'title' => 'Name',
			'post_name' => 'Coupon Code',
			'wpet_package_title' => 'Package',
			'wpet_pretty_amount' => 'Amount',
			'wpet_quantity_remaining' => 'Remaining',
			'wpet_quantity' => 'Total'
		);

		return $columns;
	}

	public function get_sortable_columns() {
		return array(
			'title' => array( 'title', true ),
		);
	}
}