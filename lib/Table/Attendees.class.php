<?php

require_once WPET_PLUGIN_DIR . 'lib/Table.class.php';

class WPET_Table_Attendees extends WPET_Table {

	public function __construct( $args = array() ) {
		$defaults = array( 'post_type' => 'wpet_attendees' );
		$args = wp_parse_args( $args, $defaults );
		parent::__construct( $args );
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
			'edit'      => sprintf('<a href="?page=%s&action=%s&post=%s">Edit</a>',$_REQUEST['page'],'edit',$item->ID),
			'delete'    => sprintf('<a href="?page=%s&action=%s&post=%s">Trash</a>',$_REQUEST['page'],'trash',$item->ID),
		);

	    $name = sprintf('<strong><a href="?page=%s&action=%s&post=%s">' . $item->wpet_first_name . ' ' . $item->wpet_last_name . '</a></strong>',$_REQUEST['page'],'edit',$item->ID);
	    return sprintf('%1$s %2$s', $name, $this->row_actions($actions) );
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
	
	public function download() {
		add_filter( 'wpet_table_prepare', array( $this, 'filterPrepare' ) );
		$this->prepare_items();
		$columns = $this->get_columns();		

		//@TODO use post object and/or filters/search
		$filename = "attendees.csv";

		header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );
		
		$outstream = fopen( 'php://output', 'w' ); 
		fputcsv( $outstream,  $columns );
		foreach ( $this->items as $post ) {
			$data = array();
			foreach ( $columns as $index => $unused ) {
				$data[] = isset( $post->{'post_' . $index} ) ?
					$post->{'post_' . $index} :
					$post->{$index};
			}
			fputcsv( $outstream, $data );
		}		
		fclose( $outstream );
		exit();
	}
}