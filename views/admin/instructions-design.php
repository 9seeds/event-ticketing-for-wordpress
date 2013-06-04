<h3><strong><?php _e( 'Modify the attendees shortcode display', 'wpet' ); ?></strong></h3>
<p><?php echo sprintf( __( 'After you place the %s shortcode on a page, by default your attendees will be displayed in order based on when they purchased their tickets. However, you can modify the order by adding a parameter to the shortcode. Here are a couple examples:', 'wpet' ), '<em>[wpet_attendees]</em>' ); ?></p>
<p><?php echo sprintf( __( '%s to sort by First Name', 'wpet' ), '<em>[wpet_attendees sort="First Name"]</em>' ); ?></p>
<p><?php echo sprintf( __( '%s to sort by Last Name', 'wpet' ), '<em>[wpet_attendees sort="Last Name"]</em>' ); ?></p>
<p><?php echo sprintf( __( '%s to sort by the attendees\'s Twitter handle', 'wpet' ), '<em>[wpet_attendees sort="Twitter"]</em>' ); ?></p>