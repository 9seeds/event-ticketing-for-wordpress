<?php

require_once WPET_PLUGIN_DIR . 'lib/Table.class.php';

class WPET_Table_Packages extends WPET_Table {

	private $packages = NULL;
	private $event_id = NULL;
	
	public function __construct( $args = array() ) {
		$this->packages = WPET::getInstance()->packages;
		$this->event_id = WPET::getInstance()->events->getWorkingEvent()->ID;
		$defaults = array( 'post_type' => 'wpet_packages' );
		$args = wp_parse_args( $args, $defaults );
		parent::__construct( $args );
	}

	public function get_columns() {
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

	public function get_sortable_columns() {
		return array(
			'title' => array( 'title', true ),
		);
	}	
	
	protected function column_wpet_remaining( $post ) {
		return $this->packages->remaining( $this->event_id, $post->ID );
	}

	protected function column_wpet_quantity( $post ) {
		$qty = $post->wpet_quantity;
		if ( $qty == '' )
			return __( 'Unlimited', 'wpet' );
		return $qty;
	}
	
}