<?php

/**
 * override these if you'd like
 * 
 * @since 2.0
 */
abstract class WPET_Module {
    
	protected $mPostType;
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
}