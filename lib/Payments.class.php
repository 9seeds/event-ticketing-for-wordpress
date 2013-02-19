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
	remove_filter('pre_post_guid', 'esc_url_raw');
	add_action('init', array($this, 'registerPostType'));
	add_action('init', array($this, 'registerPostStatus'));
	//add_action( 'all', array( $this, 'hookDebug' ) );
	//add_filter( 'all', array( $this, 'hookDebug' ) );

	if (!is_admin()) {
	    global $post;
	    //echo '<pre>'; var_dump( $post ); echo '</pre>';
	    //add_action( 'init', array( $this, 'maybeSalesSubmit' ) );
	    //add_action( 'template_redirect', array( $this, 'maybePaymentSubmit' ), 15 );
	    add_action('template_redirect', array($this, 'handlePayment'), 15);
	    //add_filter( 'template_include', array( $this, 'tplInclude' ), 1 );
	    //add_action( 'the_post', array( $this, 'setPayment' ) );
	}

	//do this after post type is set
	parent::__construct();
    }

    public function registerPostStatus() {
	register_post_status('pending', array(
	    'label' => _x('Pending', 'post'),
	    'public' => true,
	    'exclude_from_search' => false,
	    'show_in_admin_all_list' => true,
	    'show_in_admin_status_list' => true,
	    'label_count' => _n_noop('Unread <span class="count">(%s)</span>', 'Unread <span class="count">(%s)</span>'),
	));

	register_post_status('processing', array(
	    'label' => _x('Processing', 'post'),
	    'public' => true,
	    'exclude_from_search' => false,
	    'show_in_admin_all_list' => true,
	    'show_in_admin_status_list' => true,
	    'label_count' => _n_noop('Unread <span class="count">(%s)</span>', 'Unread <span class="count">(%s)</span>'),
	));
    }

    public function hookDebug($name) {
	echo "<!-- {$name} -->\n";
    }

    /**
     * Process
     * - Check to see if an order has been submitted. If so create a new payment
     * - Get the current payment ( Will be in the URL as a post ID )
     */
    public function handlePayment() {
	global $post;

	// Check to see if an order has been submitted. If so create a new payment
	$this->maybeSalesSubmit(); // Note, if there is an order this function stops executing here
	//var_dump($post);
	//  var_dump( $_POST );
	if (!isset($_POST['submit']) && ( is_null($post) || $this->mPostType != $post->post_type ))
	    return;

	// At this point there should be a payment in the system. Grab it
	$this->loadPayment();

	//echo '<pre>'; var_dump( $this->mPayment->post_status ); echo '</pre>';
	// Figure out which step we are on via the post_status and take action accordingly
	//echo "Current: " . $this->mPayment->post_status . "<br>";
	switch ($this->mPayment->post_status) {
	    case 'draft':
		/*
		 * Need to fill out form to send to payment gateway
		 * - Check to see if the payment form has been submitted
		 * --- Add details to the database
		 * --- Change status to pending
		 * --- Refresh page
		 * Else
		 * - Call pendingPayment() to create draft attendees for payment
		 * - Show payment gateway form
		 */

		if (isset($_POST['submit'])) {

		    //echo 'processing';
		    //var_dump($_POST);
		    // Payment submitted to gateway
		    WPET::getInstance()->getGateway()->processPayment();
		    //$this->update( $this->mPayment->ID, array( 'post_status' => 'pending' ) );

		    wp_update_post(array('ID' => $this->mPayment->ID, 'post_status' => 'pending'));

		    wp_redirect((get_permalink($this->mPayment->ID)));
		} else {
		    // Create draft attendees
		    $this->createAttendees();
		    add_filter('the_content', array($this, 'showPaymentForm'));
		    //echo WPET::getInstance()->getGateway()->getPaymentForm();
		}


		break;
	    case 'pending':
		// Waiting for payment to be processed
		WPET::getInstance()->getGateway()->processPayment();
		//$this->update( $this->mPayment->ID, array( 'post_status' => 'processing' ) );

		wp_redirect(get_permalink($this->mPayment->ID));
		wp_update_post(array('ID' => $this->mPayment->ID, 'post_status' => 'processing'));
		break;
	    case 'processing': // IS THIS NEEDED?
		WPET::getInstance()->getGateway()->processPaymentReturn();
		//$this->update( $this->mPayment->ID, array( 'post_status' => 'published' ) );
		wp_update_post(array('ID' => $this->mPayment->ID, 'post_status' => 'publish'));
		wp_redirect( get_permalink( $this->mPayment->ID ) );
		break;
	    case 'publish':
		// Payment has completed successfully, show receipt
		//$this->update( $this->mPayment->ID, array( 'post_status' => 'pending' ) );
		add_filter('the_content', array($this, 'showPayment'));
		break;
	}// end switch
	//wp_redirect( get_permalink( $this->mPayment->ID ) );
    }

    public function showPayment($content) {
	return 'Payment successful';
    }

    public function pendingPayment($content) {
	die('klajdf');
	return 'alff'; //WPET::getInstance()->getGateway()->processPayment();
    }

    public function showPaymentForm($content) {
	return WPET::getInstance()->getGateway()->getPaymentForm();
    }

    /**
     * (possibly) process sales page form front end
     *
     * @since 2.0
     */
    public function maybeSalesSubmit() {
	if (!empty($_POST['wpet_purchase_nonce']) && wp_verify_nonce($_POST['wpet_purchase_nonce'], 'wpet_purchase_tickets')) {
	    if (!empty($_POST['couponSubmitButton'])) {
		//@TODO DO COUPON STUFF!!
	    } else if (!empty($_POST['order_submit'])) {
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
		$payment_id = WPET::getInstance()->payment->add($data);

		wp_redirect(get_permalink($payment_id));
		exit();
	    }
	}
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
    public function tplInclude($tpl) {
	//die(print_r($tpl, true));
	if (is_singular($this->mPostType)) {
	    //don't show adjacent payments
	    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
	    add_filter('previous_post_link', '__return_null');
	    add_filter('next_post_link', '__return_null');
	    add_filter('the_title', '__return_null');
	    add_filter('single_post_title', array($this, 'filterTitle'));

	    //insert our gateway form
	    //add_filter( 'the_content', array( $this, 'showGateway' ) );
	}
	return $tpl;
    }

    public function filterTitle($title) {
	return __('Checkout', 'wpet');
    }

    public function getCart() {
	$this->loadPayment();

	$packages = WPET::getInstance()->packages;
	$cart = array(
	    'items' => array(),
	    'total' => 0
	);

	foreach ($this->mPayment->wpet_package_data['packagePurchase'] as $package_id => $quantity) {
	    if ($quantity) {
		$package = $packages->findByID($package_id);
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
//	public function pendingPayment() {
//		$this->loadPayment();
//		$this->mPayment->post_status = 'pending';
//		
//		$this->update( $this->mPayment->ID, array( 'post_status' => 'pending' ) );
//		
//		if ( empty( $this->mPayment->wpet_attendees ) ) {
//			$packages = WPET::getInstance()->packages;
//			$attendees = WPET::getInstance()->attendees;
//
//			//@TODO this could maybe go somewhere on it's own
//			foreach ( $this->mPayment->wpet_package_data['packagePurchase'] as $package_id => $quantity ) {
//				if ( $quantity ) {
//					$package = $packages->findByID( $package_id );
//					$attendee_ids = array();
//				   	for ( $i = 0; $i < $package->wpet_ticket_quantity; $i++ ) {
//						$attendee_ids[] = $attendees->draftAttendee();
//					}
//					update_post_meta( $this->mPayment->ID, 'wpet_attendees', $attendee_ids );
//				}
//			}
//			
//			//update the payment status
//			$this->mPayment->post_status = 'pending';
//			$packages->add( $this->mPayment );
//		}
//	}

    private function createAttendees() {
	$this->loadPayment();

	if (empty($this->mPayment->wpet_attendees)) {
	    $packages = WPET::getInstance()->packages;
	    $attendees = WPET::getInstance()->attendees;

	    //@TODO this could maybe go somewhere on it's own
	    foreach ($this->mPayment->wpet_package_data['packagePurchase'] as $package_id => $quantity) {
		if ($quantity) {
		    $package = $packages->findByID($package_id);
		    $attendee_ids = array();
		    for ($i = 0; $i < $package->wpet_ticket_quantity; $i++) {
			$attendee_ids[] = $attendees->draftAttendee();
		    }
		    update_post_meta($this->mPayment->ID, 'wpet_attendees', $attendee_ids);
		}
	    }

	    //update the payment status
	    //$this->mPayment->post_status = 'pending';
	    //unset($this->mPayment->guid);
	    //echo '<pre>'; var_dump($this->mPayment);die();
	    //$packages->add( $this->mPayment );
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

    public function setPayment($post) {
	$this->mPayment = $post;
    }

    protected function loadPayment() {
	global $post;
	if ($this->mPayment)
	    return;

	if (isset($post) && $this->mPostType == $post->post_type)
	    $this->mPayment = $post;
	if (isset($_REQUEST['p']))
	    $this->mPayment = $this->findByID($_REQUEST['p']);

	//echo '<pre>'; var_dump( $this->mPayment ); echo '</pre>';		
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

	register_post_type($this->mPostType, $args);
    }

}

// end class