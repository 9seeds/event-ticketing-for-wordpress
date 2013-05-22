<!-- @TODO: don't translate shortcodes -->
<div id="message" class="updated below-h2">
	<h3><?php _e( 'Did you know?', 'wpet' ); ?></h2>
	<p><?php _e( 'While you are working inside WP Event Ticketing you can click the Help button in the top-right corner of the screen to receive page-specific help.', 'wpet' ); ?></p>
</div>

<h3><?php _e( '<strong>Step 1</strong>', 'wpet' ); ?></h3>
<p><?php echo sprintf( __( 'On the %s page, click the %s tab to configure your payment gateway', 'wpet' ), '<a href="admin.php?page=wpet_settings">Settings</a>', '<strong>Payments</strong>' ); ?></p>

<h3><?php _e( '<strong>Step 2</strong>', 'wpet' ); ?></h3>
<p><?php echo sprintf( __( 'On the %s page, click the %s tab and enter the default settings for your event', 'wpet' ), '<a href="admin.php?page=wpet_settings">Settings</a>', '<strong>Event</strong>' ); ?></p>

<h3><?php _e( '<strong>Step 3</strong>', 'wpet' ); ?></h3>
<p><?php echo sprintf( __( 'On the %s page, click the %s tab and enter the default information that will be sent via email to your attendees after a sucessfull purchase.', 'wpet' ), '<a href="admin.php?page=wpet_settings">Settings</a>', '<strong>Email</strong>' ); ?></p>

<h3><?php _e( '<strong>Step 4</strong>', 'wpet' ); ?></h3>
<p><?php echo sprintf( __( 'The %s page is where you will set up what types of data you want to collect from your event attendees.%s', 'wpet' ), '<a href="admin.php?page=wpet_ticket_options">Ticket Options</a>', '<br /><em>Note, not all options have to be used for each ticket type. More on that shortly.</em>' ); ?></p>

<h3><?php _e( '<strong>Step 5</strong>', 'wpet' ); ?></h3>
<p><?php echo sprintf( __( 'The %s page is where you\'ll set up the types of tickets you want to offer. For example, you can set up ticket type "A" to include a shirt and lunch and type "B" which includes just the basics. As you are creating your ticket, you select the ticket options you want included for each ticket type.', 'wpet' ), '<a href="admin.php?page=wpet_tickets">Tickets</a>' ); ?></p>

<h3><?php _e( '<strong>Step 6</strong>', 'wpet' ); ?></h3>
<p><?php echo sprintf( __( 'The %s page is used to create the items that will be listed for sale on your site. For example, if you created ticket types "A" and "B" in the previous step, you could now create a package called "General Admission" and select ticket type "A" to be included. Then create a second package called "Cheapy Ticket" and attach ticket type "B". Another good use of a ticket package would be for offering event sponsorships. For example, create a package called "Gold Sponsor" with a price tag of $1,000 and includes multiple general admission tickets.', 'wpet' ), '<a href="admin.php?page=wpet_packages">Packages</a>' ); ?></p>

<h3><?php _e( '<strong>Step 7</strong>', 'wpet' ); ?></h3>
<p><?php echo sprintf( __( 'Create a new %s and add the following shortcode: %s. This will display the ticket packages available for your event.', 'wpet' ), '<a href="post-new.php?post_type=page">Page</a>', '<strong>[wpeventticketing]</strong>' ); ?></p>

<h3><?php _e( '<strong>Step 8</strong>', 'wpet' ); ?></h3>
<p><?php echo sprintf( __( 'Once you are ready to start selling tickets, return to the %s page and switch %s to %s', 'wpet' ), '<a href="admin.php?page=wpet_settings">Settings</a>', '<strong>Event Status</strong>', '<em>Registration is Open</em>' ); ?></p>

<h3><?php _e( '<strong>Step 9</strong> <em>(Optional)</em>', 'wpet' ); ?></h3>
<p><?php echo sprintf( __( 'Create a new %s and add the following shortcode: %s to display a list of all the attendees for your event', 'wpet' ), '<a href="post-new.php?post_type=page">Page</a>', '<strong>[wpet_attendee]</strong>' ); ?></p>