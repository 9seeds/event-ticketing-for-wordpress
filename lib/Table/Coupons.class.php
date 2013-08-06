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
			'title' => __( 'Coupon Code', 'wpet' ),
			'wpet_package_id' => __( 'Package', 'wpet' ),
			'wpet_pretty_amount' => __( 'Coupon Value', 'wpet' ),
			'wpet_quantity_remaining' => __( 'Remaining', 'wpet' ),
			'wpet_quantity' => __( 'Total', 'wpet' )
		);

		return $columns;
	}
	
	public function column_wpet_package_id( $item ) {
	    $package_id = $item->wpet_package_id;
	    
	    if( empty( $package_id ) || 'any' == $package_id ) {
			// If there is not a package id then the coupon works on anything
			return __( 'Any', 'wpet' );
	    } 
	    
	    
	    $package = WPET::getInstance()->packages->findByID( $package_id );
	    return $package->post_title;
	  
	}

	public function column_wpet_pretty_amount( $item ) {
		if ( $item->wpet_type == 'percentage' )
			return $item->wpet_amount . '%';
		else
		    return WPET::getInstance()->currency->format( WPET::getInstance()->getGateway()->getCurrencyCode(), $item->wpet_amount, true );
	}
	
	public function get_sortable_columns() {
		return array(
			'title' => array( 'title', true ),
		);
	}
}