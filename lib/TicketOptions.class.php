<?php

/**
 * @since 2.0
 */
class WPET_TicketOptions extends WPET_Module {

    /**
     * @since 2.0
     */
    public function __construct() {

	$this->mPostType = 'wpet_ticket_options';

	add_filter('wpet_admin_menu', array($this, 'adminMenu'), 5);

	add_action('init', array($this, 'registerPostType'));

	//do this after post type is set
	parent::__construct();
    }

    /**
     * Displays page specific contextual help through the contextual help API
     *
     * @see http://codex.wordpress.org/Function_Reference/add_help_tab
     * @since 2.0
     */
    public function contextHelp($screen) {

	if (isset($_GET['action']) && in_array($_GET['action'], array('edit', 'new'))) {
	    $screen->add_help_tab(
		    array(
			'id' => 'overview',
			'title' => __('Overview'),
			'content' => '<p>' . __('This screen allows you to add a new ticket option for your tickets.') . '</p>',
		    )
	    );
	    $screen->add_help_tab(
		    array(
			'id' => 'options-explained',
			'title' => __('Options Explained'),
			'content' => '<p>' . __('Here\'s an explanation of the options found on this page:') . '</p>' .
			'<ul>' .
			'<li>' . __('<strong>Display Name</strong> is what will be shown to your visitor when this option is added to a ticket.') . '</li>' .
			'<li>' . __('<strong>Option Type</strong> lets you decide what type of form field will be displayed. The options are Text Input, Dropdown or Multi Select.') . '</li>' .
			'</ul>',
		    )
	    );
	} else {
	    $screen->add_help_tab(
		    array(
			'id' => 'overview',
			'title' => __('Overview'),
			'content' => '<p>' . __('This screen provides access to all of your ticket options.') . '</p>',
		    )
	    );
	    $screen->add_help_tab(
		    array(
			'id' => 'available-actions',
			'title' => __('Available Actions'),
			'content' => '<p>' . __('Hovering over a row in the coupon list will display action links that allow you to manage each ticket option. You can perform the following actions:') . '</p>' .
			'<ul>' .
			'<li>' . __('<strong>Edit</strong> takes you to the editing screen for that ticket option. You can also reach that screen by clicking on the ticket option itself.') . '</li>' .
			'<li>' . __('<strong>Trash</strong> removes your ticket option from this list and places it in the trash, from which you can permanently delete it.') . '</li>' .
			'</ul>',
		    )
	    );
	}
    }

    public function buildHtml($option_id, $name, $value = '' ) {
	$s = '';
	$opts = get_post($option_id);
	// Figure out the type to build the proper display
	switch ($opts->wpet_type) {

	    case 'multiselect':
		$s .= '<select  name="' . $name . '" multiple>';

		foreach (( $opts->wpet_values) AS $oi) {
		    $s .= '<option value="' . $oi . '"';
		    $s .= ( in_array($oi, (array) $value) ) ? ' selected' : 'false';
		    $s .= '>' . $oi . '</option>';
		}
		$s .= '</select>';
		break;
	    case 'dropdown':
		$s .= '<select name="' . $name . '" >';

		foreach ($opts->wpet_values AS $oi) {
		    $s .= '<option value="' . $oi . '"';
		    $s .= selected($value, $oi, false);
		    $s .= '>' . $oi . '</option>';
		}
		$s .= '</select>';
		break;

	    case 'text':
	    default:
		$s .= '<input  name="' . $name . '" type="text" value="' . $value . '" />';
	}

	return $s;
    }

    /**
     * @since 2.0
     */
    public function enqueueAdminScripts() {
	wp_register_script('wpet-admin-ticket-options', WPET_PLUGIN_URL . 'js/admin_ticket_options.js', array('jquery'));
	wp_enqueue_script('wpet-admin-ticket-options');
	wp_localize_script( 'wpet-admin-ticket-options', 'wpet_ticket_options_add', array(
								'name_required' => __( 'Display Name is required', 'wpet' ),
		) );
    }

    /**
     * Add Ticket Options links to the Tickets menu
     *
     * @since 2.0
     * @param type $menu
     * @return array
     */
    public function adminMenu($menu) {
	$menu[] = array('Ticket Options', 'Ticket Options', 'add_users', $this->mPostType, array($this, 'renderAdminPage'));
	return $menu;
    }

    /**
     * Displays the menu page
     *
     * @since 2.0
     */
    public function renderAdminPage() {

	if (isset($_GET['action']) && in_array($_GET['action'], array('edit', 'new'))) {
	    if (!empty($_GET['post'])) {
		$this->render_data['option'] = $this->findByID($_GET['post']);
	    }
	    WPET::getInstance()->display('ticket-options-add.php', $this->render_data);
	    return; //don't do anything else
	}

	//default view
	WPET::getInstance()->display('ticket-options.php', $this->render_data);
    }

    /**
     * Prepare the page submit data for save
     *
     * @since 2.0
     */
    public function getPostData() {
	$post_data = array(
	    'post_title' => $_POST['options']['display_name'],
	    'post_name' => sanitize_title_with_dashes($_POST['options']['display_name']),
	    'meta' => array(
		'type' => sanitize_title($_POST['options']['option_type']),
		'values' => stripslashes_deep($_POST['options']['option_value'])
	    )
	);
	return $post_data;
    }

    /**
     * Creates the ticket options form for the wp-admin area
     *
     * @since 2.0
     * @return string
     */
    public function getAdminOptionsCheckboxes($checked = array()) {
	$options = $this->find();
	$checkboxes = array();
	foreach ($options as $o) {
	    $checked_attr = in_array($o->ID, $checked) ? 'checked="checked" ' : '';
	    $checkboxes[] = array(
		'label' => "<label for='" . sanitize_title_with_dashes($o->post_title) . "'>{$o->post_title}</label>",
		'checkbox' => "<input type='checkbox' id='" . sanitize_title_with_dashes($o->post_title) . "' name='options[{$o->ID}]' {$checked_attr}/>",
	    );
	}

	return $checkboxes;
    }

    /**
     * Returns an array of all ticket options
     *
     * @since 2.0
     * @return array
     */
    public function find($args = array()) {
	$posts = parent::find($args);

	foreach ($posts as $p) {
	    //this one needs to be explicitly fetched (WP_Post::__get() only gets a single value)
	    $p->wpet_values = get_post_meta($p->ID, 'wpet_values');
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
	    'name' => 'Ticket Options',
	    'singular_name' => 'Ticket Option',
	    'add_new' => 'Create Ticket Option',
	    'add_new_item' => 'New Ticket Option',
	    'edit_item' => 'Edit Ticket Option',
	    'new_item' => 'New Ticket Option',
	    'view_item' => 'View Ticket Option',
	    'search_items' => 'Search Ticket Options',
	    'not_found' => 'No Ticket Options found',
	    'not_found_in_trash' => 'No Ticket Options found in trash'
	);

	$args = array(
	    'public' => false,
	    'supports' => array('page-attributes'),
	    'labels' => $labels,
	    'hierarchical' => false,
	    'has_archive' => false,
	    'query_var' => 'wpet_ticket_option',
	    //'rewrite' => array( 'slug' => 'review', 'with_front' => false ),
	    //'menu_icon' => WPET_PLUGIN_URL . 'images/icons/reviews.png',
	    //'register_meta_box_cb' => array( &$this, 'registerMetaBox' ),
	    'show_ui' => false
	);

	register_post_type($this->mPostType, $args);
    }

}

// end class