<?php

require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

abstract class WPET_Table extends WP_List_Table {

	protected $per_page = 10;

	/**
	 * @since 2.0
	 * @uses wpet_table_prepare
	 */
	public function prepare_items() {
		
		$this->_column_headers = array( $this->get_columns(), //columns
										array(), //hidden
										$this->get_sortable_columns() ); //sortable
		
		$paged = $this->get_pagenum();

		$args = array(
			'posts_per_page' => $this->per_page,
			'offset' => ( $paged - 1 ) * $this->per_page,
		);

		//die( print_r($_REQUEST, true));

		/*
		if ( !empty( $_REQUEST['s'] ) ) {
			$usersearch = $_REQUEST['s'];
			
			//search by last name
			$args['meta_query'][] = array(
				'key' => 'last_name',
				'value' => $usersearch,
				'compare' => 'LIKE'
			);
		}
		*/

		//@TODO meta orderby http://wordpress.stackexchange.com/questions/30241/wp-query-order-results-by-meta-value

		if ( isset( $_REQUEST['orderby'] ) )
			$args['orderby'] = $_REQUEST['orderby'];

		if ( isset( $_REQUEST['order'] ) )
			$args['order'] = $_REQUEST['order'];

		$args = $this->get_prepare_args( $args );

		$args = apply_filters( 'wpet_table_prepare', $args );

		$table_query = new WP_Query( $args );		
		$this->items = $table_query->get_posts();

		$this->set_pagination_args( array(
			'total_items' => $table_query->found_posts,
			'per_page' => $this->per_page,
		) );
	}

	protected function get_prepare_args( $defaults ) {
		$override = array(
			'post_type' => $this->_args['post_type'],
		);
		return wp_parse_args( $override, $defaults );
	}
	//abstract protected function get_prepare_args( $defaults );

	protected function column_default( $data, $column_name ) {
		//if ( isset( $data->{'post_'.$column_name} ) )
		//	return $data->{'post_'.$column_name};
		return $data->{$column_name};
	}

	protected function get_edit_url( $post ) {
		return add_query_arg( array( 'post' => $post->ID ), $this->_args['edit_url'] );
	}
	
	protected function column_title( $post ) {
		$edit_url = $this->get_edit_url( $post );
		$column = "<a href='{$edit_url}'>{$post->post_title}</a>";

		$actions = array();
		$actions = $this->get_row_actions( $actions, $post );
		if ( ! empty( $actions ) ) {
			$column .= "<div class='row-actions'>\n";

			foreach( $actions as $action_info ) {
				$column .= "<span class='{$action_info['class']}'><a href='{$action_info['href']}'>{$action_info['label']}</a></span>\n";
			}
			$column .= "</div>\n";
		}
		return $column;
	}

	protected function get_row_actions( $actions, $post ) {
		$actions['edit'] = array( 'class' => 'edit',
								  'href' => $this->get_edit_url( $post ),
								  'label' => __( 'Edit' )
		);

		return $actions;
	}

	protected function column_cb( $post ) {
		// Set up the checkbox ( because the  is editable, otherwise its empty )
		return "<input type='checkbox' name='posts[]' id='{$post->ID}' value='{$post->ID}' />";
	}

}