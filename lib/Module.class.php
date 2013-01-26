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
	 * Adds the object data to the database
	 * 
	 * @since 2.0
	 * @param array $data 
	 * @return int|WP_Error The value 0 or WP_Error on failure. The post ID on success.
	 */
	public function add( $data ) {
	    $defaults = array(
		'post_type' => $this->mPostType,
		'post_status' => 'publish'
	    );
	    
	    if( $user_id = get_current_user_id() )
		$defaults['post_author'] = $user_id;
	    
	    $data = wp_parse_args( $data, $defaults );
	    
	    $data = apply_filters( $data['post_type'] . '_add', $data );
	    $data = apply_filters( 'wpet_add', $data );
	    
	    $post_id = wp_insert_post( $data );
	    
	    if( isset( $data['meta'] ) ) {
		foreach( $data['meta'] AS $k => $v ) {
		    update_post_meta( $post_id, "wpet_$k", $v );
		}
	    }
	    return $post_id;
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