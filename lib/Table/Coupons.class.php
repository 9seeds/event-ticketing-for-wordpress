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
			'title' => 'Coupon Code',
			'wpet_package_title' => 'Package',
			'wpet_pretty_amount' => 'Amount',
			'wpet_quantity_remaining' => 'Remaining',
			'wpet_quantity' => 'Total'
		);

		return $columns;
	}

	public function column_wpet_pretty_amount( $item ) {
		if ( $item->wpet_type == 'percentage' )
			return $item->wpet_amount . '%';
		else
		    return WPET::getInstance()->currency->format( WPET::getInstance()->settings->currency, $item->wpet_amount, true );
	}
	
	public function get_sortable_columns() {
		return array(
			'title' => array( 'title', true ),
		);
	}
}