<?php

require_once WPET_PLUGIN_DIR . 'lib/Table.class.php';

class WPET_Table_Attendees extends WPET_Table {

	public function __construct( $args = array() ) {
		add_filter( 'wpet_table_total', array( $this, 'show_only_event' ) );
		$defaults = array( 'post_type' => 'wpet_attendees' );
		$args = wp_parse_args( $args, $defaults );
		parent::__construct( $args );
	}

	protected function get_prepare_args( $defaults ) {
		$override = array(
			'post_type' => $this->_args['post_type'],
			'meta_key' => 'wpet_event_id',
			'meta_value' => WPET::getInstance()->events->getWorkingEvent()->ID,
			'post_status' => 'publish'
		);
		return wp_parse_args( $override, $defaults );
	}
	
	public function get_columns() {
		$columns = array(
			//'cb'        => '<input type="checkbox" />',
			'title' => __( 'Name', 'wpet' ),
			'wpet_email' => __( 'Email', 'wpet' ),
			'wpet_purchase_date' => __( 'Purchase Date', 'wpet' )
		);

		return $columns;
	}
	
	function column_title($item) {
	    $actions = array(
			'edit'      => sprintf( '<a href="?page=%s&action=%s&post=%s">Edit</a>',$_REQUEST['page'], 'edit', $item->ID),
			'delete'    => sprintf( '<a href="?page=%s&action=%s&post=%s">Trash</a>',$_REQUEST['page'], 'trash', $item->ID),
		);

		$name = empty( $item->post_title ) ? $item->wpet_first_name . ' ' . $item->wpet_last_name : $item->post_title;
		
	    $title = sprintf( '<strong><a href="?page=%s&action=%s&post=%s">' . $name . '</a></strong>',$_REQUEST['page'],'edit', $item->ID );
	    return sprintf( '%1$s %2$s', $title, $this->row_actions( $actions ) );
	}
	
	function column_wpet_purchase_date($item) {
		if ( ! $item->wpet_purchase_date )
			return 'Unknown';
	    return date( get_option( 'date_format' ), (int)$item->wpet_purchase_date );
	}

	public function get_sortable_columns() {
		return array(
			'title' => array( 'title', true ),
		);
	}
	
	//don't limit posts for download
	public function filterPrepare( $args ) {
		unset( $args['posts_per_page'] );
		unset( $args['offset'] );
		return $args;
	}

	//@TODO not sure if this is the right way
	public function show_only_event( $total ) {
		$args = array();
		$args = $this->get_prepare_args( $args );
		$args = apply_filters( 'wpet_table_prepare', $args );
		$all_query = new WP_Query( $args );
		$items = $all_query->get_posts();

		if ( $all_query->found_posts < $total )
			$total = $all_query->found_posts;
		if ( $total < 0 )
			$total = 0;

		return $total;		
	}
	
	public function download() {
		add_filter( 'wpet_table_prepare', array( $this, 'filterPrepare' ) );
		$this->prepare_items();
		$columns = $this->get_columns();		

// 		wp_die( '<pre>' . print_r($this) .'</pre');
		//@TODO use post object and/or filters/search
		$filename = "attendees.csv";

		header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );
		
		$outstream = fopen( 'php://output', 'w' );

		
		// wpet_attendees


		
		// Loop process
		// 1. Grab all attendees for this event (ordered by ticket ID)
		// 2. Get ticket options for ticket type (if not already) by looking attendee ticket IDs
		// 3. Output attendees and their ticket option data

		$wpet_event_id = WPET::getInstance()->events->getWorkingEvent()->ID;
		$attendee_args = array(
			'post_type'		=> 'wpet_attendees',
			'showposts' => '-1',
			'posts_per_page' => '-1',
			'meta_key' => 'wpet_ticket_id',
			'orderby' => 'meta_value_num',
			'meta_query' => array(
				array(
					'meta_key' => 'wpet_event_id',
					'meta_value' => $wpet_event_id,			
				),
			)
		);

		$attendees = get_posts( $attendee_args );

		$ticket_ids = array();
		$columns = array();
		$meta_keys = array();

		//loop through attendees once for ticket options (column headers)
		foreach ( $attendees as $attendee ) {
			if ( ! isset( $ticket_ids[$attendee->wpet_ticket_id] ) ) {
				//only get these options once per unique ticket ID
				$ticket_ids[$attendee->wpet_ticket_id] = NULL;
				
			    $options = get_post_meta( $attendee->wpet_ticket_id, 'wpet_options_selected',  true );

				if( is_array( $options ) ) {
					foreach( $options as $o ) {
						$opts = WPET::getInstance()->ticket_options->findByID( $o );
						if ( ! isset( $columns[$opts->ID] ) ) {
							$columns[$opts->ID] = $opts->post_title;
							$meta_keys[$opts->ID] = $opts->post_name;
						}
					}
				}
			}				
		}

		fputcsv( $outstream,  $columns );
		
		//loop through attendees twice for data (rows)
		foreach ( $attendees as $attendee ) {
			$row = array();
			foreach ( $meta_keys as $meta_key ) {
				 $row[] = $attendee->{'wpet_' . $meta_key};
			}
			fputcsv( $outstream,  $row );			
		}		
		fclose( $outstream );
		exit();
	}
}