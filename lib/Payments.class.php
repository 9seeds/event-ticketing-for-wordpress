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
			add_action( 'init', array( $this, 'maybeSalesSubmit' ) );
			add_action( 'template_redirect', array( $this, 'maybePaymentSubmit' ), 15 );
			add_filter( 'template_include', array( $this, 'tplInclude' ), 1 );
			add_action( 'the_post', array( $this, 'setPayment' ) );
		}
		
		//do this after post type is set
		parent::__construct();
    }
	
	public function hookDebug( $name ) {
		echo "<!-- {$name} -->\n";
	}

	/**
	 * (possibly) process sales page form front end
	 *
	 * @since 2.0
	 */
	public function maybeSalesSubmit() {
		if ( ! empty( $_POST['wpet_purchase_nonce'] ) && wp_verify_nonce( $_POST['wpet_purchase_nonce'], 'wpet_purchase_tickets' ) ) {
			if ( ! empty( $_POST['couponSubmitButton'] ) ) {
				//@TODO DO COUPON STUFF!!
			} else if ( ! empty( $_POST['submit'] ) ) {
				//@TODO maybe double-check coupon stuff here too?
				//@TODO some form validation as well before sending to payment CPT (gateway step)
				//@TODO add attendees (based on package->ticket_quantity) here if attendee info is at beginning
				$data = array(
					'post_title' => uniqid(),
					'post_status' => 'draft',
					'meta' => array(
						'package_data' => $_POST
					)  
				);
				$payment_id = WPET::getInstance()->payment->add( $data );

				wp_redirect( get_permalink( $payment_id ) );
				exit();
			}
		}
	}

	public function showGateway( $content ) {
		if ( ! $this->mPayment )
			return $content;

		if ( $this->mPayment->post_status == 'draft' ) {
			$gateway = WPET::getInstance()->getGateway();
			return $gateway->getPaymentForm();
		} else if ( $this->mPayment->post_status == 'pending' ) {
			die('your payment must complete before you can add attendee names');
		}

		return $content;
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
			add_filter( 'the_content', array( $this, 'showGateway' ) );
		}
		return $tpl;
    }

	public function filterTitle( $title ) {
		return __( 'Checkout', 'wpet' );
	}

	public function getCart() {
		$this->loadPayment();

		$packages = WPET::getInstance()->packages;
		$cart = array(
			'items' => array(),
			'total' => 0
		);

		foreach ( $this->mPayment->wpet_package_data['packagePurchase'] as $package_id => $quantity ) {
			if ( $quantity ) {
				$package = $packages->findByID( $package_id );
				$cart['items'][] = array(
					'package_name' => $package->post_title,
					'package_cost' => $package->wpet_package_cost,
					'quantity' => $quantity,
				);
				$cart['total'] += $package->wpet_package_cost * $quantity;
			}
		}
		return $cart;
	}

	/**
	 * After payment gateway has validated input save payment as "draft" while processing is done
	 * 
	 * @since 2.0
	 */
	public function pendingPayment() {
		$this->loadPayment();
		
		if ( empty( $this->mPayment->wpet_attendees ) ) {
			$packages = WPET::getInstance()->packages;
			$attendees = WPET::getInstance()->attendees;

			//@TODO this could maybe go somewhere on it's own
			foreach ( $this->mPayment->wpet_package_data['packagePurchase'] as $package_id => $quantity ) {
				if ( $quantity ) {
					$package = $packages->findByID( $package_id );
					$attendee_ids = array();
				   	for ( $i = 0; $i < $package->wpet_ticket_quantity; $i++ ) {
						$attendee_ids[] = $attendees->draftAttendee();
					}
					update_post_meta( $this->mPayment->ID, 'wpet_attendees', $attendee_ids );
				}
			}
			
			//update the payment status
			$this->mPayment->post_status = 'pending';
			$packages->add( $this->mPayment );
		}
	}

	/**
	 * Once the payment gateway has received payment confirmation, update payment from pending to published
	 * 
	 * @since 2.0
	 */
	public function publishPayment() {
		$this->loadPayment();
	}

	public function setPayment( $post ) {
		$this->mPayment = $post;
	}

	protected function loadPayment() {
		if ( $this->mPayment )
			return;
		
		if ( isset( $_REQUEST['p'] ) )
			$this->mPayment = $this->findByID( $_REQUEST['p'] );
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