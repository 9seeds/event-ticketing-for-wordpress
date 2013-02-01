<?php

/**
 * 
 * Creates post types:
 * - wpet_attendees
 * 
 * @since 2.0 
 */
class WPET_Payments extends WPET_Module {

    /**
     * @since 2.0 
     */
    public function __construct() {
		$this->mPostType = 'wpet_payments';

		add_action( 'init', array( $this, 'registerPostType' ) );
		add_action( 'init', array( $this, 'maybePaymentSubmit' ), 15 );
		//add_action( 'all', array( $this, 'hookDebug' ) );
		//add_filter( 'all', array( $this, 'hookDebug' ) );
		add_filter( 'template_include', array( $this, 'tplInclude' ), 1 );

		//do this after post type is set
		parent::__construct();
    }

	public function hookDebug( $name ) {
		echo "<!-- {$name} -->\n";
	}
	
	public function maybePaymentSubmit() {

	}
	
    /**
     * Hijacks the loading of the payment archive page to create a new payment
     * 
     * @global WP $post
     * @param type $tpl
     * @return boolean 
     */
    public function tplInclude( $tpl ) {
		//die(print_r($tpl, true));
		if ( is_singular( $this->mPostType ) ) {
			//don't show adjacent payments
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
			add_filter( 'previous_post_link', '__return_null' );
			add_filter( 'next_post_link', '__return_null' );

			//insert our gateway form
			add_filter( 'the_content', array( $this, 'showGateway' ) );
		}
		return $tpl;
    }

	public function showGateway( $content ) {
		$gateway = WPET::getInstance()->getGateway();
		return $gateway->getPaymentForm();
	}
	
    /**
     * Add post type for object
     * 
     * @since 2.0 
     */
    public function registerPostType() {
		$labels = array(
			'name' => 'Payments',
			'singular_name' => 'Payment',
			'add_new' => 'Create Payment',
			'add_new_item' => 'New Payment',
			'edit_item' => 'Edit Payment',
			'new_item' => 'New Payment',
			'view_item' => 'View Payment',
			'search_items' => 'Search Payments',
			'not_found' => 'No Payments found',
			'not_found_in_trash' => 'No Payments found in trash'
		);

		$args = array(
			'public' => true,
			'supports' => array('page-attributes'),
			'labels' => $labels,
			'hierarchical' => false,
			'has_archive' => false,
			'query_var' => 'payment',
			'rewrite' => array('slug' => 'payment', 'with_front' => false),
			//'menu_icon' => WPET_PLUGIN_URL . 'images/icons/reviews.png',
			//'register_meta_box_cb' => array( &$this, 'registerMetaBox' ),
			'show_ui' => true
		);

		register_post_type( $this->mPostType, $args);
    }

}

// end class