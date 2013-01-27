<?php

require_once WPET_PLUGIN_DIR . 'lib/Table.class.php';

class WPET_Table_Packages extends WPET_Table {

	public function __construct( $args = array() ) {
		$defaults = array( 'post_type' => 'wpet_packages' );
		$args = wp_parse_args( $args, $defaults );
		parent::__construct( $args );
	}

	function get_columns() {
		//Package Name 	Price 	Remaining 	Total Qty 	Start 	End
		$columns = array(
			//'cb'        => '<input type="checkbox" />',
			'title' => __( 'Package Name', 'wpet' ),
			'wpet_package_cost' => __( 'Price', 'wpet' ),
			'wpet_remaining' => __( 'Remaining', 'wpet' ),
			'wpet_quantity' => __( 'Quantity', 'wpet' ),
			'wpet_start_date' => __( 'Start', 'wpet' ),
			'wpet_end_date' => __( 'End', 'wpet' ),
		);

		return $columns;
	}

	function get_sortable_columns() {
		return array(
			'title' => array( 'title', true ),
		);
	}
}