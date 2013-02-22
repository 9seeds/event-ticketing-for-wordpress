<?php

/**
 * 
 * Creates post types:
 * - wpet_attendees
 * 
 * @todo clean up register post status
 * @todo fill in metion PHPdoc
 * @since 2.0 
 */
class WPET_Payments extends WPET_Module {

    protected $mPayment;

    /**
     * @since 2.0 
     */
    public function __construct() {
	$this->mPostType = 'wpet_payments';
	
	add_action('init', array($this, 'registerPostType'));
	add_action('init', array($this, 'registerPostStatus'));
	//add_action( 'all', array( $this, 'hookDebug' ) );
	//add_filter( 'all', array( $this, 'hookDebug' ) );

	if (!is_admin()) {
	    add_action('template_redirect', array($this, 'handlePayment'), 15);
	    //add_action( 'the_post', array( $this, 'setPayment' ) );
	}

	//do this after post type is set
	parent::__construct();
    }

    /**
     * Registering the post stati of the steps in the payment process allows it
     * to load each step on the front and does not bork anything. Will also 
     * show pretty to the admin in wp-admin
     * 
     * @since 2.0 
     */
    public function registerPostStatus() {
	register_post_status('pending', array(
	    'label' => _x('Pending', 'post'),
	    'public' => true,
	    'exclude_from_search' => false,
	    'show_in_admin_all_list' => true,
	    'show_in_admin_status_list' => true,
	    'label_count' => _n_noop('Pending <span class="count">(%s)</span>', 'Pending <span class="count">(%s)</span>'),
	));

	register_post_status('processing', array(
	    'label' => _x('Processing', 'post'),
	    'public' => true,
	    'exclude_from_search' => false,
	    'show_in_admin_all_list' => true,
	    'show_in_admin_status_list' => true,
	    'label_count' => _n_noop('Processing <span class="count">(%s)</span>', 'Processing <span class="count">(%s)</span>'),
	));
	
	register_post_status('draft', array(
	    'label' => _x('Draft', 'post'),
	    'public' => true,
	    'exclude_from_search' => false,
	    'show_in_admin_all_list' => true,
	    'show_in_admin_status_list' => true,
	    'label_count' => _n_noop('Draft <span class="count">(%s)</span>', 'Draft <span class="count">(%s)</span>'),
	));
    }

    public function hookDebug($name) {
	echo "<!-- {$name} -->\n";
    }

    /**
     * Manages the payment process. Will load the selected gateway and call
     * the function for whatever step the user is on. Uses post_status to track
     * step in payment
     * 
     * @see self::registerPostStatus()
     * @since 2.0
     */
    public function handlePayment() {
	global $post;

	// Check to see if an order has been submitted. If so create a new payment
	$this->maybeSalesSubmit(); // Note, if there is an order this function stops executing here
	
	
	/*
	 * At this point we should have access to a payment via $post or $_GET
	 * Lets retrieve it. If we cannot then exit
	 */
	if( !$this->loadPayment() ) return false;

	// Figure out which step we are on via the post_status and take action accordingly
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
		    // Payment submitted to gateway
		    WPET::getInstance()->getGateway()->processPayment();

		    wp_update_post(array('ID' => $this->mPayment->ID, 'post_status' => 'pending'));

		    wp_redirect((get_permalink($this->mPayment->ID)));
		} else {
		    // Create draft attendees
		    $this->createAttendees();
		    add_filter('the_content', array($this, 'showPaymentForm'));
		}


		break;
	    case 'pending':
		// Waiting for payment to be processed
		WPET::getInstance()->getGateway()->processPayment();
		wp_redirect(get_permalink($this->mPayment->ID));
		wp_update_post(array('ID' => $this->mPayment->ID, 'post_status' => 'processing'));
		break;
	    case 'processing': // IS THIS NEEDED?
		WPET::getInstance()->getGateway()->processPaymentReturn();
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

    /**
     * Content to show on successful payment
     * 
     * @since 2.0
     * @uses the_content
     * @param string $content
     * @return string 
     */
    public function showPayment($content) {
	return 'Payment successful';
    }

    /**
     * Displays the payment gateway form
     * 
     * @uses the_content
     * @since 2.0
     * @param string $content
     * @return string 
     */
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
     * Creates a set of draft attendees for the current payment order
     * 
     * @since 2.0 
     */
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

    /**
     * Loads the payment info for the current payment. 
     * Note: Must be on a payment page
     * 
     * @since 2.0
     * @global WP_Post $post
     */
    protected function loadPayment() {
	global $post;
	$ret = false;
	
	if ($this->mPayment) {
	    // Payment already loaded, send it back
	    $ret = $this->mPayment;
	} else if (isset($post) && $this->mPostType == $post->post_type) {
	    // Load the payment from the existing $post object
	    $ret = $this->mPayment = $post;
	} else if ( isset($_REQUEST['post_type']) && $this->mPostType == $_REQUEST['post_type'] && isset($_REQUEST['p'])) {
	    $ret = $this->mPayment = $this->findByID($_REQUEST['p']);
	} 
	
	return $ret;	
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