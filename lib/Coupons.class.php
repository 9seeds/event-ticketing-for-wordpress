<?php

/**
 * @since 2.0 
 */
class Coupons {

    /**
     * @since 2.0 
     */
    public function __construct() {
	add_filter('wpet_admin_menu', array(&$this, 'adminMenu'));
    }

    /**
     * Add Coupons links to the Tickets menu
     * 
     * @since 2.0
     * @param type $menu
     * @return array 
     */
    public function adminMenu($menu) {
	$menu[] = array('Coupons', 'Coupons', 'add_users', 'coupons', array(&$this, 'renderAdminPage'));
	return $menu;
    }

    public function renderAdminPage() {
	// $inst = apply_filters( 'wpet_instructions', $inst = array( 'instructions' => array() ) );
	WPET::getInstance()->display('coupons.php', $inst);
    }

}

// end class