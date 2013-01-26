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
    return $tabs;
}
add_filter( 'wpet_settings_tabs', 'my_add_tab' );

add_filter( 'wpet_settings', 'my_add_settings' );

function my_add_settings( $settings ) {
    $settings[] = array(
	'tab' => 'justin',
	'title' => "I'm a hooker",
	'text' => "View my site at http://justinhooker.me"
    );
    return $settings;
}