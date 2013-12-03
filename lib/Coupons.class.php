<?php

/**
 * @since 2.0
 */
class WPET_Coupons extends WPET_Module {

	/**
	 * @since 2.0
	 */
	public function __construct() {
	    $this->mPostType = 'wpet_coupons';

		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 20 );

	    add_action( 'init', array( $this, 'registerPostType' ) );

	    add_action('wp_ajax_get_coupon', array( $this, 'ajaxGetCoupon' ) );
	    add_action('wp_ajax_nopriv_get_coupon', array( $this, 'ajaxGetCoupon' ) );
		//do this after post type is set
		parent::__construct();
	}

	public function enqueueAdminScripts() {
		wp_register_script( 'wpet-admin-coupons', WPET_PLUGIN_URL . 'js/admin_coupons.js', array( 'jquery' ) );
		wp_enqueue_script( 'wpet-admin-coupons');
		wp_localize_script( 'wpet-admin-coupons', 'wpet_coupons_add', array(
								'name_required' => __( 'Coupon Code is required', 'wpet' ),
								'percent_too_high' => __( 'Amount must be less than 100 percent', 'wpet' ),
								'amount_not_numeric' => __( 'Amount must be a number', 'wpet' ),
								'uses_not_numeric' => __( 'Uses must be a number', 'wpet' ),
		) );
	}
	
	public function ajaxGetCoupon() {

		//@todo refactor this and Payments.class.php:399
		$discount = 0.00;
		foreach( $_POST['packages'] as $package => $qty ) {
			if( $qty < 1 ) continue; // No need to do extra processing!

			$p = WPET::getInstance()->packages->findByID( $package );

			$total = $p->wpet_package_cost * $qty;

			if( '' != trim( $_POST['coupon_code'] ) ) {
				$coupon_amount = $this->calcDiscount( $total, $package, $_POST['coupon_code'] );
				
				$discount += $coupon_amount;
			}
		}

		$c = array( 'amount' => $discount );
		echo json_encode( $c );
		die();
	}
	
	public function calcDiscount( $amount, $package_id, $code ) {	    
	    $coupon = $this->findByCode( $code );
		
	    $discount = 0.00;

	    if( is_a( $coupon, 'WP_Post' ) ) {
			if ( $code && $code != $coupon->post_title )
				return $discount;

			if ( /* applies to any */ '' == $coupon->wpet_package_id 
				 || $package_id == $coupon->wpet_package_id 
				 || 'any' == $coupon->wpet_package_id ) {

				switch( $coupon->wpet_type ) {
					case 'flat-rate':
						$discount = $coupon->wpet_amount;
						break;
					case 'percentage':
						if( 100 == $coupon->wpet_amount ) {
							$discount = $amount;
							break;
						}
			 
						$discount = $amount * ( $coupon->wpet_amount / 100 );
						break;
				}

			}
		}

	    return $discount;
	}
	
	public function findByCode( $code ) {
		if ( trim( $code ) == '' )
			return NULL;
		
	    $args = array(
			'post_type' => $this->mPostType,
			'name' => $code,
			'post_status' => array( 'publish' ),
	    );

		return $this->findOne( $args );
	}
	
	/**
	 *
	 * @todo Add warning to log if subtracting tickets went below 0 remaining
	 * @todo Add error checking in case wpet_quantity_remaining cannot be found to make function gracefully fail and log
	 * @todo Verify quantity changed and send a return value based on that
	 * @param type $code
	 * @param type $num 
	 */
	public function subtractFromPool( $code, $num = 1 ) {
	    $coupon = $this->findByCode( $code );

		if ( $coupon ) {
			$qty = (int)get_post_meta( $coupon->ID, 'wpet_quantity_remaining', true) - 1;
	    
			if( 0 > $qty ) {
				$qty = 0;
			}
			update_post_meta( $coupon->ID, 'wpet_quantity_remaining', $qty );
		}
	    
	    
	}

	/**
	 * Displays page specific contextual help through the contextual help API
	 *
	 * @see http://codex.wordpress.org/Function_Reference/add_help_tab
	 * @since 2.0
	 */
	public function contextHelp( $screen ) {
		if ( isset( $_GET['action'] ) && in_array ( $_GET['action'], array( 'edit', 'new' ) ) ) {
			$screen->add_help_tab(
				array(
				'id'	=> 'overview',
				'title'	=> __( 'Overview', 'wpet' ),
				'content'	=> '<p>' . __( 'This screen allows you to add a new coupon for your event.', 'wpet' ) . '</p>',
				)
			);
			$screen->add_help_tab(
				array(
				'id'	=> 'options-explained',
				'title'	=> __( 'Options Explained', 'wpet' ),
				'content'	=> '<p>' . __( 'Here\'s an explanation of the options found on this page:', 'wpet' ) . '</p>'.
					'<ul>'.
						'<li>'. sprintf( __( '%sCoupon Code%s is what a visitor would type in to the registration form to receive a discount. This should be a unique value and contain no spaces or special characters.', 'wpet' ), '<strong>', '</strong>' ) .'</li>'.
						'<li>'. sprintf( __( '%sPackage%s allows you to choose which package is eligible for purchase with this coupon. Selecting a specific package from the dropdown will tie this coupon to the selected package.', 'wpet' ), '<strong>', '</strong>' ) .'</li>'.
						'<li>'. sprintf( __( '%sType%s lets you decide if this coupon will give a flat rate discount (e.g. $5.00 off), or a percentage of the package price.', 'wpet' ), '<strong>', '</strong>' ) .'</li>'.
						'<li>'. sprintf( __( '%sAmount%s is the value of the coupon. If using flat rate, 5.00 would equal $5.00 off. If using percentage, 5 would equal 5 percent off.', 'wpet' ), '<strong>', '</strong>' ) .'</li>'.
						'<li>'. sprintf( __( '%sUses%s lets you set how many times this coupon can be used.', 'wpet' ), '<strong>', '</strong>' ) .'</li>'.
					'</ul>',
				)
			);
		} else {
			$screen->add_help_tab(
				array(
				'id'	=> 'overview',
				'title'	=> __( 'Overview', 'wpet' ),
				'content'	=> '<p>' . __( 'This screen provides access to all of your coupons.', 'wpet' ) . '</p>',
				)
			);
			$screen->add_help_tab(
				array(
				'id'	=> 'available-actions',
				'title'	=> __( 'Available Actions', 'wpet' ),
				'content'	=> '<p>' . __( 'Hovering over a row in the coupon list will display action links that allow you to manage each coupon. You can perform the following actions:', 'wpet' ) . '</p>'.
					'<ul>'.
						'<li>'. sprintf( __( '%sEdit%s takes you to the editing screen for that coupon. You can also reach that screen by clicking on the coupon code itself.', 'wpet' ), '<strong>', '</strong>' ) .'</li>'.
						'<li>'. sprintf( __( '%sTrash%s removes your coupon from this list and places it in the trash, from which you can permanently delete it.', 'wpet' ), '<strong>', '</strong>' ) .'</li>'.
					'</ul>',
				)
			);
		}
	}

	/**
	 * Add Coupons links to the Tickets menu
	 *
	 * @since 2.0
	 * @param type $menu
	 * @return array
	 */
	public function adminMenu($menu) {
		$menu[] = array(
			__( 'Coupons', 'wpet' ),
			__( 'Coupons', 'wpet' ),
			'add_users',
			'wpet_coupons',
			array( $this, 'renderAdminPage' ) );
		return $menu;
	}

	/**
	 * Renders the admin page in wp-admin
	 *
	 * @since 2.0
	 */
	public function renderAdminPage() {

		if ( isset( $_GET['action'] ) && in_array ( $_GET['action'], array( 'edit', 'new' ) ) ) {
			if ( ! empty( $_GET['post'] ) ) {
				$this->render_data['coupon'] = $this->findByID( $_GET['post'] );
			}
			WPET::getInstance()->display( 'coupons-add.php', $this->render_data );
			return; //don't do anything else
		}

		//default view
		WPET::getInstance()->display( 'coupons.php', $this->render_data );
	}

	/**
	 * Prepare the page submit data for save
	 *
	 * @since 2.0
	 */
	public function getPostData() {
		$data = array(
			'post_title' => $_POST['options']['coupon-code'],
			'post_name' => sanitize_title_with_dashes( $_POST['options']['coupon-code'] ),
			'meta' => array(
			    'type' => $_POST['options']['type'],
			    'amount' => $_POST['options']['amount'],
			    'quantity' => (int)$_POST['options']['quantity'],
			    'quantity_remaining' => (int)$_POST['options']['quantity'],
			    'package_id' => $_POST['options']['package_id']
			)
		);
		return $data;
	}

	/**
	 * Retrieves all the coupons from the db
	 * @param type $prettyAmount
	 * @return array
	 */
	public function findAll( $prettyAmount = false ) {
	    $args = array(
		'post_type' => 'wpet_coupons',
		'showposts' => '-1',
		'posts_per_page' => '-1'
	    );

	    $posts = get_posts( $args );

	    if( $prettyAmount ) {
		foreach( $posts AS $p ) {
		    switch( $p->wpet_type ) {
			case 'percentage':
			    $p->wpet_pretty_amount = $p->wpet_amount . '%';
			    break;
			case 'flat-rate':
			    $p->wpet_pretty_amount = WPET::getInstance()->currency->format( $p->wpet_amount );
			    break;
		    }

		    if( is_numeric( $p->wpet_package_id ) ) {
			$package = get_post( $p->wpet_package_id );
			$p->wpet_package_title = $package->post_title;
		    } else {
			$p->wpet_package_title = ucfirst( $p->wpet_package_id );
		    }
		}
	    }

	    return $posts;
	}

	/**
	 * Add post type for object
	 *
	 * @since 2.0
	 */
	public function registerPostType() {
	    $labels = array(
			'name' => 'Coupons',
			'singular_name' => 'Coupon',
			'add_new' => 'Create Coupon',
			'add_new_item' => 'New Coupon',
			'edit_item' => 'Edit Coupon',
			'new_item' => 'New Coupon',
			'view_item' => 'View Coupon',
			'search_items' => 'Search Coupons',
			'not_found' => 'No Coupons found',
			'not_found_in_trash' => 'No Coupons found in trash'
	    );

	    $args = array(
			'public' => false,
			'supports' => array( 'page-attributes' ),
			'labels' => $labels,
			'hierarchical' => false,
			'has_archive' => false,
			//'query_var' => 'coupon',
			//'rewrite' => array( 'slug' => 'coupon', 'with_front' => false ),
			//'menu_icon' => WPET_PLUGIN_URL . 'images/icons/reviews.png',
			//'register_meta_box_cb' => array( &$this, 'registerMetaBox' ),
			'show_ui' => false
	    );

	    register_post_type( 'wpet_coupons', $args );
	}
}// end class