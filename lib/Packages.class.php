<?php

/**
 * @since 2.0
 */
class WPET_Packages extends WPET_Module {

	/**
	 * @since 2.0
	 */
	public function __construct() {
	    $this->mPostType = 'wpet_packages';

		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 15 );

		add_action( 'init', array( $this, 'registerPostType' ) );

		add_filter( 'wpet_packages_columns', array( $this, 'defaultColumns' ) );

		//do this after post type is set
		parent::__construct();
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
				'content'	=> '<p>' . __( 'This screen allows you to add a new package for your event.', 'wpet' ) . '</p>',
				)
			);
			$screen->add_help_tab(
				array(
				'id'	=> 'options-explained',
				'title'	=> __( 'Options Explained' ),
				'content'	=> '<p>' . __( 'Here\'s an explanation of the options found on this page:', 'wpet' ) . '</p>'.
					'<ul>'.
						'<li>'. __( '<strong>Package Name</strong> is the name that will be displayed to your visitors on the purchase page.', 'wpet' ) .'</li>'.
						'<li>'. __( '<strong>Description</strong> will be displayed to your visitors under the package name on the purchase page.', 'wpet' ) .'</li>'.
						'<li>'. __( '<strong>Included Tickets</strong> lets you select which ticket type and how many tickets are included with the purchase of this package.', 'wpet' ) .'</li>',
						'<li>'. __( '<strong>Ticket Name</strong> lets you select which ticket type will be included in this ticket package.', 'wpet' ) .'</li>',
						'<li>'. __( '<strong>Quantity</strong> lets you set how many of the selected ticket type will be included when somebody purchases this package.', 'wpet' ) .'</li>',
						'<li>'. __( '<strong>On Sale Date</strong> lets you control when this package is available for purchase.', 'wpet' ) .'</li>',
						'<li>'. __( '<strong>Start Date</strong> is the first day this ticket package will be available for purchase.', 'wpet' ) .'</li>',
						'<li>'. __( '<strong>End Date</strong> is the last day this ticket package will be available for purchase.', 'wpet' ) .'</li>',
						'<li>'. __( '<strong>Package Cost</strong> is the price for the package, no matter how many tickets are included.', 'wpet' ) .'</li>',
						'<li>'. __( '<strong>Quantity</strong> is the total number of this specific package that you have available for sale.', 'wpet' ) .'</li>',
					'</ul>',
				)
			);
		} else {
			$screen->add_help_tab(
				array(
				'id'	=> 'overview',
				'title'	=> __( 'Overview' ),
				'content'	=> '<p>' . __( 'This screen provides access to all of your ticket packages.', 'wpet' ) . '</p>',
				)
			);
			$screen->add_help_tab(
				array(
				'id'	=> 'available-actions',
				'title'	=> __( 'Available Actions' ),
				'content'	=> '<p>' . __( 'Hovering over a row in the package list will display action links that allow you to manage each package. You can perform the following actions:', 'wpet' ) . '</p>'.
					'<ul>'.
						'<li>'. __( '<strong>Edit</strong> takes you to the editing screen for that package. You can also reach that screen by clicking on the package name itself.', 'wpet' ) .'</li>'.
						'<li>'. __( '<strong>Trash</strong> removes your package from this list and places it in the trash, from which you can permanently delete it. Deleting a package does not delete the attached tickets.', 'wpet' ) .'</li>'.
					'</ul>',
				)
			);
		}
	}

	/**
	 * @since 2.0
	 */
	public function enqueueAdminScripts() {
		wp_register_script( 'wpet-admin-packages', WPET_PLUGIN_URL . 'js/admin_packages.js', array( 'jquery-ui-datepicker' ) );
		wp_enqueue_script( 'wpet-admin-packages' );
				wp_localize_script( 'wpet-admin-packages', 'wpet_package_add', array(
								'name_required' => __( 'Package Name is required', 'wpet' ),
								'description_required' => __( 'Description is required', 'wpet' ),
								'ticket_required' => __( 'Ticket Name is required', 'wpet' ),
								'ticket_quantity_required' => __( 'Quantity is required', 'wpet' ),
								'ticket_quantity_not_numeric' => __( 'Quantity must be numeric', 'wpet' ),
								'start_required' => __( 'Start Date is required', 'wpet' ),
								'end_required' => __( 'End Date is required', 'wpet' ),
								'end_after_start' => __( 'End Date must be after Start Date', 'wpet' ),
								'cost_required' => __( 'Package Cost is required', 'wpet' ),
								'cost_not_numeric' => __( 'Package Cost must be numeric', 'wpet' ),
								'quantity_required' => __( 'Packages Available is required', 'wpet' ),
								'ticket_package_quantity_not_numeric' => __( 'Packages Available must be numeric', 'wpet' ),
		) );
	}

	/**
	 * Add Packages links to the Tickets menu
	 *
	 * @since 2.0
	 * @param type $menu
	 * @return array
	 */
	public function adminMenu( $menu ) {
		$menu[] = array(
			__( 'Packages', 'wpet' ),
			__( 'Packages', 'wpet' ),
			'add_users',
			'wpet_packages',
			array( $this, 'renderAdminPage' ) );
		return $menu;
	}

	/**
	 * Displays the menu page
	 *
	 * @since 2.0
	 */
	public function renderAdminPage() {

		if ( isset( $_GET['action'] ) && in_array ( $_GET['action'], array( 'edit', 'new' ) ) ) {
			if ( ! empty( $_GET['post'] ) ) {
				$this->render_data['package'] = $this->findByID( $_GET['post'] );
			}
			WPET::getInstance()->display( 'packages-add.php', $this->render_data );
		   	return; //don't do anything else
		}
		
		$this->render_data['show_add'] = WPET::getInstance()->tickets->anyExist();
		
		//default view
		WPET::getInstance()->display( 'packages.php', $this->render_data );
	}
	
	/**
	 * Prepare the page submit data for save
	 *
	 * @since 2.0
	 */
	public function getPostData() {
		$options = $_POST['options'];
		$data = array(
			'post_title' => $options['package_name'],
			'post_name' => sanitize_title_with_dashes( $options['package_name'] ),
			'post_content' => stripslashes( $options['description'] ),
		);
		unset( $options['package_name'] );
		unset( $options['description'] );

		$data['meta'] = $options;
		return $data;
	}

	/**
	 * Add post type for object
	 *
	 * @since 2.0
	 */
	public function registerPostType() {
	    $labels = array(
			'name' => 'Packages',
			'singular_name' => 'Package',
			'add_new' => 'Create Package',
			'add_new_item' => 'New Package',
			'edit_item' => 'Edit Package',
			'new_item' => 'New Package',
			'view_item' => 'View Package',
			'search_items' => 'Search Packages',
			'not_found' => 'No Packages found',
			'not_found_in_trash' => 'No Packages found in trash'
	    );

	    $args = array(
			'public' => false,
			'supports' => array( 'page-attributes' ),
			'labels' => $labels,
			'hierarchical' => false,
			'has_archive' => false,
			//'query_var' => 'packages',
			//'rewrite' => array( 'slug' => 'review', 'with_front' => false ),
			//'menu_icon' => WPET_PLUGIN_URL . 'images/icons/reviews.png',
			//'register_meta_box_cb' => array( &$this, 'registerMetaBox' ),
			'show_ui' => false
	    );

	    register_post_type( 'wpet_packages', $args );
	}


	/**
	 * Find all packages attached to an event
	 *
	 * @TODO Make it attach to an event - currently just gets all packages
	 * @since 2.0
	 * @return Array of WP_Posts
	 */
	public function findAllByEvent() {
		return $this->find();
	}


	/**
	 * Builds a select menu of packages
	 *
	 * @TODO There's an issue with the $enabled value. It's not setting it to true by default. See line 23 of coupons-add.php. I had to set it to false to enable it, which seems backwards.
	 *
	 * @since 2.0
	 * @param string $name
	 * @param string $id
	 * @param string $selected_value
	 * @return string
	 */
	public function selectMenu( $name, $id, $selected_value, $enabled = true ) {
	    
	    $disable = !$enabled;
		$disabled = disabled( $disable, true, false );
	    $s = "<select name='{$name}' id='{$id}'{$disabled}>";
	    $s .= '<option value="any">' . __( 'Any Package', 'wpet' ) . '</option>';

	    foreach ( $this->find() as $pack ) {
			$s .= "<option value='{$pack->ID}' ";
			$s .= selected( $selected_value, $pack->ID, false ) ;
			$s .= ">{$pack->post_title}</option>\n";
	    }

	    $s .= '</select>';
	    return $s;
	}

	/**
	 * Returns the max number of packages that can be sold for the specified
	 * event.
	 *
	 * @param int $event_id
	 * @param int $package_id
	 * @return int
	 */
	public function remaining( $event_id, $package_id ) {		
	    $max_attendance = (int)get_post_meta( $event_id, 'wpet_max_attendance', true);
	    $packages_total_quantity = (int)get_post_meta( $package_id, 'wpet_quantity', true);
	    $ticket_quantity = (int)get_post_meta( $package_id, 'wpet_ticket_quantity', true );

		//@TODO if max_attendance is 0, should that be the same as unlimited?
	    if( 0 == $max_attendance || 0 == $ticket_quantity ) return 0;

	    $max_packages = floor( $max_attendance / $ticket_quantity );

	    if( $max_packages > $packages_total_quantity )
			return $packages_total_quantity;

	    return $max_packages;
	}

	public function reserve( $package_id, $qty ) {
		$wpet_qty = (int)get_post_meta( $package_id, 'wpet_quantity', true);
		echo "<p>wpet_qty: $wpet_qty - $qty = ";
		$wpet_qty -= $qty;
		
		echo "$wpet_qty</p>";
		
		//don't let this go below zero (even though the possiblity of over-selling an event exists)
		if ( $wpet_qty < 0 )
			$wpet_qty = 0;
		update_post_meta( $package_id, 'wpet_quantity', $wpet_qty );
	}

}// end class