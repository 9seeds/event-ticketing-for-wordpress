<?php

/**
 * override these if you'd like
 *
 * @since 2.0
 */
abstract class WPET_Module {

	protected $mPostType;
	protected $render_data;

	public function __construct() {
		add_action( 'admin_init', array( $this, 'setupRenderData' ) );
	}

	public function getPostType() {
		return $this->mPostType;
	}

	public function setupRenderData() {
		$this->render_data = array();
		$this->render_data['nonce'] = wp_nonce_field( 'wpet_submit', 'wpet_submit_nonce', true, false );

		if ( isset( $this->mPostType ) ) {
			$this->render_data['base_url'] = admin_url( "admin.php?page={$this->mPostType}" );
			$this->render_data['edit_url'] = add_query_arg( array( 'action' => 'edit' ), $this->render_data['base_url'] );
			/*
			 * @todo trash_url not correct. Should have this format:
			 * ?post=263&action=trash&_wpnonce=6fc9c28983
			 */
			$this->render_data['trash_url'] = add_query_arg( array( 'action' => 'trash' ), $this->render_data['base_url'] );
			$this->render_data['new_url'] = add_query_arg( array( 'action' => 'new' ), $this->render_data['base_url'] );
		}
	}

	/**
	 * @since 2.0
	 */
	public function adminMenu( $menu ) {
		return $menu;
	}

	/**
	 * @since 2.0
	 */
	public function renderAdminPage() {
	}

	/**
	 * @since 2.0
	 */
	public function enqueueAdminScripts() {
	}

	/**
	 * @since 2.0
	 */
	public function maybeSubmit() {
		if ( ! empty($_POST['wpet_submit_nonce'] ) && wp_verify_nonce( $_POST['wpet_submit_nonce'], 'wpet_submit' ) ) {

			$post_data = $this->getPostData();

			if ( ! empty( $_REQUEST['post'] ) )
				$post_data['ID'] = $_REQUEST['post'];

			$post_id = $this->add( $post_data );

			wp_redirect( add_query_arg( array( 'post' => $post_id ), $this->render_data['edit_url'] ) );
		}
	}

	/**
	 * Prepare the page submit data for save
	 *
	 * @since 2.0
	 */
	public function getPostData() {
		return array();
	}

	/**
	 * @since 2.0
	 */
	public function contextHelp( $screen ) {
	}

	/**
	 * Finds wpet objects
	 *
	 * @since 2.0
	 * @param array $args
	 * @return array of WP_Post objects
	 */
	public function find( $args = array() ) {
	    $defaults = array(
			'post_type' => $this->mPostType,
			'showposts' => -1,
			'posts_per_page' => -1,
			'post_status' => array( 'publish', 'draft' ),
	    );

		$data = wp_parse_args( $args, $defaults );

	    return get_posts( $data );
	}

	/**
	 * Finds one wpet object
	 *
	 * @since 2.0
	 * @param array $args
	 * @return WP_Post|NULL
	 */
	public function findOne( $args = array() ) {
	    $defaults = array(
			'showposts' => 1
		);

		$data = wp_parse_args( $args, $defaults );

		$posts = $this->find( $data );

		if ( ! empty( $posts ) )
			return current( $posts );

		return NULL;
	}

	/**
	 * Finds one wpet object
	 *
	 * @since 2.0
	 * @param int $post_id
	 * @return WP_Post
	 */
	public function findByID( $post_id ) {
		return WP_Post::get_instance( $post_id );
	}

	/**
	 * Adds the object data to the database
	 *
	 * @since 2.0
	 * @param array $data
	 * @return int|WP_Error The value 0 or WP_Error on failure. The post ID on success.
	 */
	public function add( $data = array() ) {
	    $defaults = array(
			'post_type' => $this->mPostType,
			'post_status' => 'publish',
			//'post_title' => uniqid(),
	    );

	    if( $user_id = get_current_user_id() )
		$defaults['post_author'] = $user_id;

	    $data = wp_parse_args( $data, $defaults );

	    $data = apply_filters( $data['post_type'] . '_add', $data );
	    $data = apply_filters( 'wpet_add', $data );

	    $post_id = wp_insert_post( $data );

	    if( isset( $data['meta'] ) ) {
		$this->saveMeta( $post_id, $data['meta'] );
//			foreach( $data['meta'] as $k => $v ) {
//			    echo '<pre>'; print_r( $v ); echo '</pre>';
//			    if( is_array( $v ) ) {
//				foreach( $v AS $x => $y ) {
//				    update_post_meta( $post_id, "wpet_{$k}", $y );
//				}
//			    } else {
//				update_post_meta( $post_id, "wpet_{$k}", $v );
//			    }
//			}
	    }
	    return $post_id;
	}

	public function saveMeta( $post_id, $meta ) {
	    //echo 'saveMeta<br><br><pre>'; print_r( $meta ); echo '</pre>';
	    foreach( $meta as $k => $v ) {

		/*if( is_array( $v ) ) {
		    $this->saveMeta($post_id, $v);
		} else {*/
		    update_post_meta( $post_id, "wpet_{$k}", $v );
		//}
	    }
	}

	/**
	 * Helper function to update the post record in the database
	 *
	 * @param integer $post_id
	 * @param array $data
	 * @return int|WP_Error The value 0 or WP_Error on failure. The post ID on success.
	 */
	public function update( $post_id, $data ) {

	    $data['ID'] = $post_id;
	    return $this->add( $data );
	}

	/**
	 * Helper function to trash the post record in the database
	 *
	 * @param integer $post_id
	 * @param array $data
	 * @return int|WP_Error The value 0 or WP_Error on failure. The post ID on success.
	 */
	public function trash( $post_id, $data = array() ) {
	    $defaults = array(
			'ID' => $post_id,
			'post_status' => 'trash',
	    );

	    $data = wp_parse_args( $data, $defaults );

		return wp_update_post( $data );
	}
	
}