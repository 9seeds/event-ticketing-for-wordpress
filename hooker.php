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

function my_custom_tab( $tabs ) {
    $tabs['justin'] = 'Justin';
    return $tabs;
}
add_filter( 'wpet_settings_tabs', 'my_custom_tab' );

function my_custom_tab_settings( $settings ) {
    $settings[] = array(
	'tab' => 'justin',
	'title' => "I'm a hooker",
	'text' => "View my site at http://justinhooker.me"
    );
    return $settings;
}
add_filter( 'wpet_settings', 'my_custom_tab_settings' );


add_filter( 'wpet_admin_page_icon', 'my_logo' );

function my_logo() {
    return '<img src="' . WPET_PLUGIN_URL . '/images/wp_toilet_paper.png' . '" style="height: 32px;
	width: 32px;
	float: left;
	margin: 7px 5px 0 0;" />';
}
/*
function my_event_tab_remove( $tabs ) {
    unset( $tabs['event']);
	return $tabs;
}
add_filter( 'wpet_settings_tabs', 'my_event_tab_remove' );

function my_event_menu( $menu ) {
	$menu[] = array( 'Events', 'Events', 'add_users', 'wpet_events', array( WPET::getInstance()->events, 'renderAdminPage' ) );
	return $menu;
}
add_filter( 'wpet_admin_menu', 'my_event_menu' );
*/