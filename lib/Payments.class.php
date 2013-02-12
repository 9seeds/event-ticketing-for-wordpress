<?php

/**
 * 
 * Creates post types:
 * - wpet_attendees
 * 
 * @since 2.0 
 */
class WPET_Payments extends WPET_Module {

	protected $mPayment;
	
    /**
     * @since 2.0 
     */
    public function __construct() {
		$this->mPostType = 'wpet_payments';

		add_action( 'init', array( $this, 'registerPostType' ) );
		//add_action( 'all', array( $this, 'hookDebug' ) );
		//add_filter( 'all', array( $this, 'hookDebug' ) );

		if ( ! is_admin() ) {
			add_action( 'init', array( $this, 'maybePaymentSubmit' ), 15 );
			add_filter( 'template_include', array( $this, 'tplInclude' ), 1 );
		}
		
		//do this after post type is set
		parent::__construct();
    }

	public function hookDebug( $name ) {
		echo "<!-- {$name} -->\n";
	}
	
	public function maybePaymentSubmit() {
		$gateway = WPET::getInstance()->getGateway();
		$gateway->processPayment();
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
			add_filter( 'the_title', '__return_null' );
			add_filter( 'single_post_title', array( $this, 'filterTitle' ) );

			//insert our gateway form
			add_action( 'the_post', array( $this, 'setPost' ) );
			add_filter( 'the_content', array( $this, 'showGateway' ) );
		}
		return $tpl;
    }

	public function filterTitle( $title ) {
		return __( 'Checkout', 'wpet' );
	}

	public function setPost( $post ) {
		$this->mPayment = $post;
	}

	public function getCart() {
		if ( ! $this->mPayment )
			return NULL;

		$packages = WPET::getInstance()->packages;
		$cart = array(
			'items' => array(),
			'total' => 0
		);

		foreach ( $this->mPayment->wpet_package_data['packagePurchase'] as $package_id => $quantity ) {
			if ( $quantity ) {
				$post = $packages->findOne( $package_id );
				$cart['items'][] = array(
					'post_title' => $post->post_title,
					'package_cost' => $post->wpet_package_cost,
					'quantity' => $quantity,
				);
				$cart['total'] += $post->wpet_package_cost * $quantity;
			}
		}
		return $cart;
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