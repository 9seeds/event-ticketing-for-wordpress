<?php

require_once WPET_PLUGIN_DIR . 'lib/Table.class.php';

class WPET_Table_Notifications extends WPET_Table {

	public function __construct( $args = array() ) {
		$defaults = array( 'post_type' => 'wpet_notifications' );
		$args = wp_parse_args( $args, $defaults );
		parent::__construct( $args );
	}

	public function get_columns() {
		$columns = array(
			//'cb'        => '<input type="checkbox" />',
			'title' => __( 'Subject', 'wpet' ),
			'wpet_option_to' => __( 'To', 'wpet' ),
			'post_date' => __( 'Sent Date', 'wpet' )
		);

		return $columns;
	}

	public function column_title( $item ) {
	    return $item->wpet_subject;
	}

	public function column_wpet_option_to( $item ) {
		foreach ( $item->wpet_option_to as $index => $on ) {
			if ( ! $on )
				continue;
			
			switch( $index ) {
				case 'all-attendees':
					_e( 'All attendees', 'wpet' );
					break;
				case 'have-info':
					_e( 'Have filled out info', 'wpet' );
					echo "";
					break;
				case 'no-info':
					_e( 'Have not filled out info', 'wpet' );
					break;
			}
		}
	}

	public function get_sortable_columns() {
		return array(
			'title' => array( 'title', true ),
		);
	}
}