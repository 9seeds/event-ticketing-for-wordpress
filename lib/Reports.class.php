<?php

/**
 * @since  2.0
 * @todo Move this to it's proper place
 */
function add_peity_script() {
	wp_enqueue_script( 'jquery-peity', WPET_PLUGIN_URL . '3rd-party/jquery.peity.js' );
}
add_action( 'admin_enqueue_scripts', 'add_peity_script' );

/**
 * @since  2.0
 * @todo Rename and move this to it's proper place
 */
function hawkins_hacky_js() {
	?>
<script>
jQuery(document).ready(function($){
    $("span.pie").peity("pie")
	$(".line").peity("line")
	$(".bar").peity("bar")
});
</script>
<?php
}

add_action( 'admin_head', 'hawkins_hacky_js' );



/**
 * @since 2.0
 */
class WPET_Reports extends WPET_Module {

	/**
	 * @since 2.0
	 */
	public function __construct() {
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 1 );
	}

	/**
	 * @todo this help section isn't showing up like it should.
	 */
	
	/**
	 * Displays page specific contextual help through the contextual help API
	 *
	 * @see http://codex.wordpress.org/Function_Reference/add_help_tab
	 * @since 2.0
	 */
	public function contextHelp( $screen ) {
		$screen->add_help_tab(
			array(
			'id'	=> 'overview',
			'title'	=> __( 'Overview' ),
			'content'	=> '<p>' . __( 'This screen provides a quick overview of your event.' ) . '</p>',
			)
		);
		$screen->add_help_tab(
			array(
			'id'	=> 'report-tab',
			'title'	=> __( 'Reports' ),
			'content'	=> '<p>' . __( 'Here\'s an explanation of the options found on this page:' ) . '</p>'.
				'<ul>'.
					'<li>'. __( '<strong>Display Name</strong> is what will be shown to your visitor when this option is added to a ticket.' ) .'</li>'.
					'<li>'. __( '<strong>Option Type</strong> lets you decide what type of form field will be displayed. The options are Text Input, Dropdown or Multi Select.' ) .'</li>'.
				'</ul>',
			)
		);
	}

	/**
	 * Add Reports links to the Tickets menu
	 *
	 * @since 2.0
	 * @param type $menu
	 * @return array
	 */
	public function adminMenu( $menu ) {
		$menu[] = array( 'Reports', 'Reports', 'add_users', 'wpet_reports', array( $this, 'renderAdminPage' ) );
		return $menu;
	}

	/**
	 * Displays the menu page
	 *
	 * @since 2.0
	 */
	public function renderAdminPage() {
		//$inst = apply_filters( 'wpet_instructions', $inst = array( 'instructions' => array() ) );
		WPET::getInstance()->display( 'reporting.php' );
	}

}// end class