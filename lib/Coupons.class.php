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
	
	public function ajaxGetCoupon() { 
	    if( !isset( $_POST['coupon_code'] ) || '' == $_POST['coupon_code'] ) {
		$c = array( 
		    'amount' => '0.00',
		    'type'	=> 'flat-rate'
		);

		echo json_encode( $c );

		die();
	    }
	    $coupon = $this->findByCode( $_POST['coupon_code'] );
	    
	    
	    
	    $c = array( 
		'amount' => $coupon->wpet_amount,
		'type'	=> $coupon->wpet_type
	    );
	    
	    echo json_encode( $c );
	    
	    die();
	}
	
	public function findByCode( $code ) {
	    $args = array(
		'post_name' => $code
	    );
	    
	    return $this->findOne( $args );
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
				'title'	=> __( 'Overview' ),
				'content'	=> '<p>' . __( 'This screen allows you to add a new coupon for your event.', 'wpet' ) . '</p>',
				)
			);
			$screen->add_help_tab(
				array(
				'id'	=> 'options-explained',
				'title'	=> __( 'Options Explained' ),
				'content'	=> '<p>' . __( 'Here\'s an explanation of the options found on this page:', 'wpet' ) . '</p>'.
					'<ul>'.
						'<li>'. __( '<strong>Coupon Code</strong> is what a visitor would type in to the registration form to receive a discount. This should be a unique value and contain no spaces or special characters.', 'wpet' ) .'</li>'.
						'<li>'. __( '<strong>Package</strong> allows you to choose which package is eligible for purchase with this coupon. Selecting a specific package from the dropdown will tie this coupon to the selected package.', 'wpet' ) .'</li>'.
						'<li>'. __( '<strong>Type</strong> lets you decide if this coupon will give a flat rate discount (e.g. $5.00 off), or a percentage of the package price.', 'wpet' ) .'</li>'.
						'<li>'. __( '<strong>Amount</strong> is the value of the coupon. If using flat rate, 5.00 would equal $5.00 off. If using percentage, 5 would = 5% off.', 'wpet' ) .'</li>'.
						'<li>'. __( '<strong>Quantity</strong> lets you set how many times this coupon can be used.', 'wpet' ) .'</li>'.
					'</ul>',
				)
			);
		} else {
			$screen->add_help_tab(
				array(
				'id'	=> 'overview',
				'title'	=> __( 'Overview' ),
				'content'	=> '<p>' . __( 'This screen provides access to all of your coupons.', 'wpet' ) . '</p>',
				)
			);
			$screen->add_help_tab(
				array(
				'id'	=> 'available-actions',
				'title'	=> __( 'Available Actions' ),
				'content'	=> '<p>' . __( 'Hovering over a row in the coupon list will display action links that allow you to manage each coupon. You can perform the following actions:', 'wpet' ) . '</p>'.
					'<ul>'.
						'<li>'. __( '<strong>Edit</strong> takes you to the editing screen for that coupon. You can also reach that screen by clicking on the coupon code itself.', 'wpet' ) .'</li>'.
						'<li>'. __( '<strong>Trash</strong> removes your coupon from this list and places it in the trash, from which you can permanently delete it.', 'wpet' ) .'</li>'.
					'</ul>',
				)
			);
		}
		$screen->set_help_sidebar(
			'<p><strong>' . __( 'Need help:' ) . '</strong></p>' .
			'<p>' . __( '<a href="http://support.9seeds.com/" target="_blank">Support Forums</a>' ) . '</p>' .
			'<p>' . __( '<a href="https://github.com/9seeds/wp-event-ticketing/wiki/_pages" target="_blank">Developer Docs</a>' ) . '</p>'
		);
	}

	/**
	 * Add Coupons links to the Tickets menu
	 *
	 * @since 2.0
	 * @param type $menu
	 * @return array
	 */
	public function adminMenu($menu) {
		$menu[] = array( 'Coupons', 'Coupons', 'add_users', 'wpet_coupons', array( $this, 'renderAdminPage' ) );
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
			    $p->wpet_pretty_amount = WPET::getInstance()->currency->format( WPET::getInstance()->settings->currency, $p->wpet_amount );
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