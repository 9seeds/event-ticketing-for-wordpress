<?php

/**
 * 
 * Creates post types:
 * - wpet_attendees
 * 
 * @link https://github.com/9seeds/wp-event-ticketing/wiki/Payment-Flow
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

	register_post_status('coll_att_data', array(
	    'label' => _x('Collect Attendee Data', 'post'),
	    'public' => true,
	    'exclude_from_search' => false,
	    'show_in_admin_all_list' => true,
	    'show_in_admin_status_list' => true,
	    'label_count' => _n_noop('Collect Attendee Data <span class="count">(%s)</span>', 'Collect Attendee Data <span class="count">(%s)</span>'),
	));

	register_post_status('coll_att_data_post', array(
	    'label' => _x('Collect Attendee Data Post', 'post'),
	    'public' => true,
	    'exclude_from_search' => false,
	    'show_in_admin_all_list' => true,
	    'show_in_admin_status_list' => true,
	    'label_count' => _n_noop('Collect Attendee Data <span class="count">(%s)</span>', 'Collect Attendee Data <span class="count">(%s)</span>'),
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
     * @todo under the publish case check for wpet_published = 1 before attempting to publish
     * @see self::registerPostStatus()
     * @link https://github.com/9seeds/wp-event-ticketing/wiki/Payment-Flow
     * @since 2.0
     */
    public function handlePayment() {
	global $post;

	//standard payment page stuffs
	if (is_singular($this->mPostType)) {
	    //don't show adjacent payments
	    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
	    add_filter('previous_post_link', '__return_null');
	    add_filter('next_post_link', '__return_null');

	    //don't show this payment's uniqid
	    add_filter('the_title', array($this, 'filterMyTitle'));
	    add_filter('single_post_title', array($this, 'filterTitle'));
	}

	// Check to see if an order has been submitted. If so create a new payment
	$this->maybeSalesSubmit(); // Note, if there is an order this function stops executing here

	
	/*
	 * At this point we should have access to a payment via $post or $_GET
	 * Lets retrieve it. If we cannot then exit
	 */
	if (!$this->loadPayment())
	    return false;

	// Figure out which step we are on via the post_status and take action accordingly
	switch ($this->mPayment->post_status) {
	    case 'draft':
		/*
		 * Draft mode flow
		 * 
		 * - Create draft attendees for this payment if none are created yet
		 * - Apply coupons if needed
		 * - If the site admin has selected to collect attendee data before
		 *    payment collect it now
		 * - Update the payment status to move to the next stage
		 * - Show the gateway payment collection form
		 * - Redirect to the next step
		 */

		// If attendees need to be reserved for this payment do it now
		$this->maybeCreateAttendees();
		

		// Check to see if the site admin wants to collect attendee data first
		if ('pre' == WPET::getInstance()->settings->collect_attendee_data && !$this->mPayment->wpet_attendees_collected) {
		    // Site admin wants to collect attendee data before payment
		    wp_update_post(array('ID' => $this->mPayment->ID, 'post_status' => 'coll_att_data'));
		    //wp_redirect(get_permalink($this->mPayment->ID));
		    break;
		} 
		    // Update payment status to move to next step
		    //wp_update_post(array('ID' => $this->mPayment->ID, 'post_status' => 'pending'));
		    // Gateway should do this
		    // Show gateway payment collection form
		    remove_filter ('the_content','wpautop');
		    $post->post_content = WPET::getInstance()->getGateway()->getPaymentForm();

		    // Redirect to the next step
		    //wp_redirect(get_permalink($this->mPayment->ID));
		

		break;

	    case 'coll_att_data':
		/*
		 * Collects the attendee data before the payment is collected then
		 * send the payment back to draft status to display the payment 
		 * gateway collection form
		 */
		//wp_update_post(array('ID' => $this->mPayment->ID, 'post_status' => 'draft'));
		$this->maybeCollectAttendeeData();

		// Redirect to the next step
		//wp_redirect(get_permalink($this->mPayment->ID));
		break;

	    case 'pending':
		/*
		 * Add the name of the payee to all the tickets
		 */
		$name = explode( ' ', $this->mPayment->wpet_name );
		foreach( $this->mPayment->wpet_attendees AS $a ) {
		    $args = array(
			'meta' => array(
			    'first_name' => @$name[0],
			    'last_name' => @$name[1],
			    'email' => $this->mPayment->wpet_email,
			    'purchase_date' => time()
			)
		    );
		    WPET::getInstance()->attendees->update($a, $args );
		}
		
		/*
		 * Data has been collected for the payment and will be sent off
		 * to the payment gateway here
		 */
		WPET::getInstance()->getGateway()->processPayment();

		//wp_update_post(array('ID' => $this->mPayment->ID, 'post_status' => 'processing')); 
		// Gateway should do this
		// Redirect to the next step
		//wp_redirect(get_permalink($this->mPayment->ID));

		exit();

	    case 'processing':
		/*
		 * Waiting for the payment gateway to process the payment
		 */
		WPET::getInstance()->getGateway()->processPaymentReturn();
		//wp_update_post(array('ID' => $this->mPayment->ID, 'post_status' => 'publish'));
		// Gateway should do this
		// Redirect to the next step
		//wp_redirect(get_permalink($this->mPayment->ID));
		exit();

	    case 'publish':

		// Check to see if the site admin wants to collect attendee data last
		if ('post' == WPET::getInstance()->settings->collect_attendee_data && !$this->mPayment->wpet_attendees_collected) { 
		    // Site admin wants to collect attendee data before payment
		    wp_update_post(array('ID' => $this->mPayment->ID, 'post_status' => 'coll_att_data'));
		     wp_redirect(get_permalink($this->mPayment->ID));
		}

		
		$this->publishPayment();
		// Payment has completed successfully, show receipt
		//$this->update( $this->mPayment->ID, array( 'post_status' => 'pending' ) );
		add_filter('the_content', array($this, 'showPayment'));
		break;
	}// end switch
	//wp_redirect( get_permalink( $this->mPayment->ID ) );
    }

    function maybeCollectAttendeeData() {
	$this->loadPayment(); 

	// IF THE ATTENDEES HAVE BEEN COLLECTED STOP THIS FUNCTION NOW
	if ($this->mPayment->wpet_attendees_collected)
	    return false; // attendees were previously collected

	$status = $this->mPayment->post_status;

	$ret = false;

	if (isset($_POST['save_attendees'])) {
	    $this->saveAttendeeData();
	    
	    	    
	    switch( WPET::getInstance()->settings->collect_attendee_data ) {
		case 'pre':
		    wp_update_post(array('ID' => $this->mPayment->ID, 'post_status' => 'draft'));
		    break;
		case 'post':
		    wp_update_post(array('ID' => $this->mPayment->ID, 'post_status' => 'publish'));
		    break;
	    }
	  //  die($status);
	    wp_redirect(get_permalink($this->mPayment->ID));
	    return false; // Stop showing the attendee data collection
	}


	// Ensure we are in a valid status
	if ('coll_att_data' == $status ) {
	    add_filter('the_content', array($this, 'collectAttendeeData'));
	}


	return true;
    }

    /**
     * Collects ticket data from attendees
     * 
     * 
     * Process
     * - Get ticket id for package
     * - Loop through attendees
     * 
     *  
     */
    function collectAttendeeData($content) {

	$this->loadPayment();

	// IF THE ATTENDEES HAVE BEEN COLLECTED STOP THIS FUNCTION NOW
	if ($this->mPayment->wpet_attendees_collected)
	    return false; // attendees were previously collected


	$attendees = $this->mPayment->wpet_attendees;

	$content = '<form action="" method="post">';
	$content .= '<table>';

	foreach ($attendees AS $a) {
	    $attendee = get_post($a);
	    $package = get_post($attendee->wpet_package_id);
	    $content .= '<tr><td colspan="2">Package: ' . $package->post_title . '</td></tr>';
	    $content .= '<tr>';
	    $a = WPET::getInstance()->attendees->findByID($a);
	    $ticket_id = $a->wpet_ticket_id;
	    $ticket_options = get_post_meta($ticket_id, 'wpet_options_selected', true);

	    foreach ($ticket_options AS $o) {
		$content .= '<tr>';
		$opt = WPET::getInstance()->ticket_options->findByID($o);
		$content .= '<th>' . $opt->post_title . '</th>';
		$value_name = 'wpet_' . $opt->post_name;
		$content .= '<td>' . WPET::getInstance()->ticket_options->buildHtml($o, 'option[' . $attendee->ID . '][' . $opt->post_name . ']', $a->$value_name) . '</td>';
		$content .= '</tr>';
	    }

	    $content .= '</tr>';
	}
	$content .= '<tr><td colspan="2"><input type="submit" name="save_attendees" value="Save Attendee Info"></td></tr>';
	$content .= '</table>';
	$content .= '</form>';




	return $content;
    }

    public function saveAttendeeData() {

	foreach ($_POST['option'] AS $attendee_id => $data) {
	    //  echo "<p>Attendee: $attendee_id</p>";
	    foreach ($data AS $opt_id => $value) {
		//	echo "<p>Option $opt_id: $value</p>";
		$args = array(
		    'meta' => array(
			$opt_id => $value
		    )
		);
		$this->update($attendee_id, $args);
	    }
	    $this->update( $attendee_id, array( 'meta' => array('purchase_date' => time())));
	}
	$this->update( $this->mPayment->ID, array( 'meta' => array( 'attendees_collected' => true )));
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
     * @TODO maybe double-check coupon stuff here too?
     * @TODO some form validation as well before sending to payment CPT (gateway step)
     * @TODO add attendees (based on package->ticket_quantity) here if attendee info is at beginning
     * @since 2.0
     */
    public function maybeSalesSubmit() {
	if (!empty($_POST['wpet_purchase_nonce']) && wp_verify_nonce($_POST['wpet_purchase_nonce'], 'wpet_purchase_tickets')) {
	    if (!empty($_POST['order_submit'])) {
		
		/*
		 * Set total cost for this order. This is a derived field, meaning
		 * that it is calculated and stored seperate from the actual
		 * price & quantity. If that changes this field will need to
		 * be updated again.
		 */
		$total = 0.00;
		foreach( $_POST['package_purchase'] AS $package => $qty ) {
		    if( $qty < 1 ) continue; // No need to do extra processing!
		    
		    $p = WPET::getInstance()->packages->findByID( $package );
		    
		    $total += $p->wpet_package_cost * $qty;
		}
		
		if( isset( $_POST['coupon_code'] ) && '' != trim( $_POST['coupon_code'] ) ) { 		    
		   $coupon_amount = WPET::getInstance()->coupons->calcDiscount( $total, $package, $_POST['coupon_code'] );
		  
		   $total -= $coupon_amount;
		   
		   if( 0 > $total ) {
		       // Oops, total went past zero dollars. Reset it to zero
		       $total = 0.00;
		   }
			
		}
		
		$_POST['total'] = $total; // Add total to payment details
		
		$data = array(
		    'post_title' => uniqid(),
		    'post_status' => 'draft',
		    'meta' => $_POST/* array(
			  'package_data' => $_POST
			  ) */
		);
		$payment_id = WPET::getInstance()->payment->add($data);
		
		wp_redirect(get_permalink($payment_id));
		exit();
	    }
	}
    }
    

    public function filterMyTitle($title) {
	if ($title == $this->mPayment->post_title)
	    return NULL;
	return $title;
    }

    public function getPermalink( $id = null ) {
	$this->loadPayment();
	
	if( is_null( $id ) ) {
	    $id = $this->mPayment->ID;
	}
	return get_permalink( $id );
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

	foreach ($this->mPayment->wpet_package_purchase as $package_id => $quantity) {
	    if ($quantity) {
		$package = $packages->findByID($package_id);
		$cart['items'][] = array(
		    'package_name' => $package->post_title,
		    'package_cost' => $package->wpet_package_cost,
		    'quantity' => $quantity,
		);
		//$cart['total'] += $package->wpet_package_cost * $quantity;
	    }
	}
	
	$cart['total'] = $this->mPayment->wpet_total;
	return $cart;
    }

    /**
     * Creates a set of draft attendees for the current payment order
     * 
     * @todo make this mor efficient by multiplying packages sold by num tickets per package. I.E. 2 packages with 10 tickets is 20 attendees, or 2x10=20. No need for loops
     * @since 2.0 
     */
    private function maybeCreateAttendees() {
	$this->loadPayment();

	if (empty($this->mPayment->wpet_attendees)) {
	    $attendees = WPET::getInstance()->attendees;
	    
	    /*
	     * Find all unique packages and number of them sold
	     * Look into the package to find the number of tickets in each package
	     * 
	     * num attendees = num packages x num tickets per package
	     */
	    $packages = $this->mPayment->wpet_package_purchase;

	    $total_attendees = 0;
	    $attendee_ids = array();
	    foreach ($packages AS $package => $qty) {
		if (0 == $qty)
		    continue;
		// Get the package
		$p = WPET::getInstance()->packages->findByID($package);
		// Multiply tickets in package by number of packages
		$ticket = $p->wpet_ticket_id;
		for( $x = 0; $x < $qty; $x++ ) {
		    for ($i = 0; $i < $p->wpet_ticket_quantity; $i++) {
			$args = array(
			    'meta' => array(
				'ticket_id' => $ticket,
				'package_id' => $p->ID
			    )
			);
			$attendee_ids[] = $attendees->draftAttendee($args);
		    }
		}
	    }



	    $data = array('meta' => array('attendees' => $attendee_ids));

	    $this->update($this->mPayment->ID, $data);
	}
    }

    /**
     * Once the payment gateway has received payment confirmation, update payment from pending to published
     * 
     * @since 2.0
     */
    private function publishPayment() {
	$this->loadPayment();
	
	// This is a sad shim, which probably could have been avoided with 
	// better architecture :(
	if( 'complete' == $this->mPayment->wpet_complete ) {
	    return;
	} else {
	    $data = array('meta' => array('complete' => 'complete'));

	    $this->update($this->mPayment->ID, $data);
	}
	
	//echo '<pre>'; var_dump($this->mPayment); echo '</pre>';
	//echo '<pre>'; var_dump( $this->mPayment->wpet_attendees); echo '</pre>';
	
	/*
	 * Subtract number of tickets in this package from the available
	 * pool of tickets available
	 */
	$this->reserveTickets();
	
	/*
	 * Subtract number of available coupons from the pool
	 */
	WPET::getInstance()->coupons->subtractFromPool( $this->mPayment->wpet_coupon_code );
	
	
	/*
	 * Publish all the attendees
	 * If the attendee email has been set send the attendee an email to their
	 * attendee page where they can edit their info and see info on their ticket
	 */
	foreach( $this->mPayment->wpet_attendees AS $a_id ) {
	    echo "<p>Working on attendee {$a_id}</p>";
	    WPET::getInstance()->attendees->publishAttendee( $a_id );
	   
	}
	
	/*
	 * Email payment receipt and link to payee
	 * @todo Figure out which email needs to be sent
	 */
	echo "<p>Emailing purchaser {$this->mPayment->wpet_name} ({$this->mPayment->wpet_email})</p>"; 
	
	
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
    public function loadPayment() {
	global $post;
	$ret = false;

	if ($this->mPayment) {
	    // Payment already loaded, send it back
	    $ret = $this->mPayment;
	} else if (isset($post) && $this->mPostType == $post->post_type) {
	    // Load the payment from the existing $post object
	    $ret = $this->mPayment = $post;
	} else if (isset($_REQUEST['post_type']) && $this->mPostType == $_REQUEST['post_type'] && isset($_REQUEST['p'])) {
	    $ret = $this->mPayment = $this->findByID($_REQUEST['p']);
	}

	return $ret;
    }

    private function reserveTickets() {
	$this->loadPayment();
	$packages = get_post_meta($this->mPayment->ID, 'wpet_package_purchase', true);

	foreach ($packages as $package_id => $package_qty) {
	    if ($package_qty)
		WPET::getInstance()->packages->reserve($package_id, $package_qty);
	}
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
	    'show_ui' => false
	);

	register_post_type($this->mPostType, $args);
    }

}

// end class