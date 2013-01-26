<?php

/* test actions & filters here!!! */

//function my_add_tab( $tabs ) {
//	$tabs['justin'] =
//		array( 'label' => __( 'Justin', 'wpet' ),
//			   'tab_content' => "<h2>I'm a hooker</h2>"
//		);
//	//unset($tabs['reset']);
//	return $tabs;
//}

function my_add_tab( $tabs ) {
    $tabs['justin'] = 'Justin';
    unset( $tabs['event']);
    return $tabs;
}
add_filter( 'wpet_settings_tabs', 'my_add_tab' );


function my_add_settings( $settings ) {
    $settings[] = array(
	'tab' => 'justin',
	'title' => "I'm a hooker",
	'text' => "View my site at http://justinhooker.me"
    );
    return $settings;
}
add_filter( 'wpet_settings', 'my_add_settings' );


function my_event( $menu ) {
    $menu[] = array( 'Events', 'Event', 'add_users', 'wpet_ticket_events',  array( WPET::getInstance()->events, 'renderAdminPage' )  );
	return $menu;
}
add_filter( 'wpet_admin_menu', 'my_event', 5 );
