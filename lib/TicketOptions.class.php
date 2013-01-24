<?php

/**
 * @since 2.0 
 */
class TicketOptions {

    /**
     * @since 2.0 
     */
    public function __construct() {
		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ) );
    }

    /**
     * Add Ticket Options links to the Tickets menu
     * 
     * @since 2.0
     * @param type $menu
     * @return array 
     */
    public function adminMenu( $menu ) {
		$menu[] = array( 'Ticket Options', 'Ticket Options', 'add_users', 'wpet_ticket_options', array( $this, 'renderAdminPage' ) );
		return $menu;
    }

    public function renderAdminPage() {
		//$inst = apply_filters( 'wpet_instructions', $inst = array( 'instructions' => array() ) );
		WPET::getInstance()->display( 'ticket-options.php' );
    }

}// end class