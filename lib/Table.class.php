<?php

require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

abstract class WPET_Table extends WP_List_Table {

	const STATUS = 'post_status';
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

		if ( isset( $_REQUEST[self::STATUS] ) )
			$args['post_status'] = $_REQUEST[self::STATUS];

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
			'post_status' => 'publish'
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

	protected function get_trash_url( $post ) {
		return add_query_arg( array( 'post' => $post->ID ), $this->_args['trash_url'] );
	}

	protected function column_title( $post ) {
		if ( $post->wpet_ineditable ) {
			$column = "<strong>{$post->post_title}</strong>";
		} else {
			$edit_url = $this->get_edit_url( $post );
			$column = "<strong><a href='{$edit_url}'>{$post->post_title}</a></strong>";
		}
		$actions = array(); //@TODO add filter?
		$column .= $this->row_actions( $this->get_row_actions( $actions, $post ) );
		return $column;
	}

	protected function get_row_actions( $actions, $post ) {

		if ( empty( $_GET[self::STATUS] ) ) {

			if ( ! $post->wpet_ineditable )
				$actions['edit'] = '<a href="' . $this->get_edit_url( $post ) . '">'. __( 'Edit', 'wpet' ) . '</a>';

			//some tables may not show the trash link (notify attendees)
			if ( isset ( $this->_args['trash_url'] ) && ! $post->wpet_indeleteable ) {			
				$actions['trash'] = '<a href="' . $this->get_trash_url( $post ) . '">' . __( 'Trash', 'wpet' ) . '</a>';
			}
		} else if ( $_GET[self::STATUS] == 'trash' ) {
			$actions['untrash'] = "<a title='" . esc_attr( __( 'Restore this item from the Trash', 'wpet' ) ) . "' href='" . add_query_arg( array( 'action' => 'untrash', 'post' => $post->ID ), $this->_args['base_url'] ) . "'>" . __( 'Restore' ) . "</a>";
			$actions['delete'] = "<a class='submitdelete' title='" . esc_attr( __( 'Delete this item permanently', 'wpet' ) ) . "' href='" . get_delete_post_link( $post->ID, '', true ) . "'>" . __( 'Delete Permanently', 'wpet' ) . "</a>";
		}

		return $actions;
	}

	protected function column_cb( $post ) {
		// Set up the checkbox ( because the  is editable, otherwise its empty )
		return "<input type='checkbox' name='posts[]' id='{$post->ID}' value='{$post->ID}' />";
	}

	public function get_views() {
		$views = array();

		//some tables may not show the trash view (notify attendees)
		if ( isset( $this->_args['trash_url'] ) ) {
			$views['trash'] = __( 'Trash', 'wpet' );
		}

		$num_posts = wp_count_posts( $this->_args['post_type'], 'readable' );
		$total_posts = array_sum( (array) $num_posts );

		// Subtract post types that are not included in the admin all list (trash)
		foreach ( get_post_stati( array( 'show_in_admin_all_list' => false ) ) as $state )
			$total_posts -= $num_posts->$state;
		
		$view_filter = empty( $_GET[self::STATUS] ) ? NULL : $_GET[self::STATUS];
		$url = $this->_args['base_url'];
		$class = empty( $view_filter ) ? ' class="current"' : '';
		$links = array();
		$links['all'] = "<a href='{$url}'{$class}>". sprintf( __( 'All <span class="count">(%s)</span>', 'wpet' ), $total_posts ) .'</a>';
		foreach ( $views as $index => $label ) {
			$class = $view_filter == $index ? ' class="current"' : '';				
			$links[$index] = "<a href='" . esc_url( add_query_arg( self::STATUS, $index, $url ) ) . "'{$class}>" . sprintf( __( '%s <span class="count">(%s)</span>', 'wpet' ), $label, $num_posts->$index ) .'</a>';
		}
		return $links;
	}

}