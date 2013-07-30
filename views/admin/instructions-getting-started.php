<div id="message" class="updated below-h2">
	<h3><?php _e( 'Did you know?', 'wpet' ); ?></h2>
	<p><?php _e( 'While you are working inside WP Event Ticketing you can click the Help button in the top-right corner of the screen to receive page-specific help.', 'wpet' ); ?></p>
</div>

<h3><strong><?php _e( 'Step 1', 'wpet' ); ?></strong></h3>
<p><?php echo sprintf( __( 'On the %sSettings%s page, click the %sPayment Gateways%s tab to configure your payment gateway', 'wpet' ), '<a href="admin.php?page=wpet_settings&tab=payment">', '</a>', '<strong>', '</strong>' ); ?></p>

<h3><strong><?php _e( 'Step 2', 'wpet' ); ?></strong></h3>
<p><?php echo sprintf( __( 'On the %sSettings%s page, click the %sEvent%s tab and enter the settings for your event', 'wpet' ), '<a href="admin.php?page=wpet_settings&tab=event">', '</a>', '<strong>', '</strong>' ); ?></p>

<h3><strong><?php _e( 'Step 3', 'wpet' ); ?></strong></h3>
<p><?php echo sprintf( __( 'On the %sSettings%s page, click the %sEmail%s tab and enter the information that will be sent via email to your attendees after a sucessfull purchase.', 'wpet' ), '<a href="admin.php?page=wpet_settings&tab=email">', '</a>', '<strong>', '</strong>' ); ?></p>

<h3><strong><?php _e( 'Step 4', 'wpet' ); ?></strong></h3>
<p><?php echo sprintf( __( 'The %sTicket Options%s page is where you will set up what types of data you want to collect from your event attendees.%sNote, not all options have to be used for each ticket type. More on that shortly.%s', 'wpet' ), '<a href="admin.php?page=wpet_ticket_options">', '</a>', '<br /><em>', '</em>' ); ?></p>

<h3><strong><?php _e( 'Step 5', 'wpet' ); ?></strong></h3>
<p><?php echo sprintf( __( 'The %sTickets%s page is where you\'ll set up the types of tickets you want to offer. For example, you can set up ticket type "A" to include a shirt and lunch and type "B" which includes just the basics. As you are creating your ticket, you select the ticket options you want included for each ticket type.', 'wpet' ), '<a href="admin.php?page=wpet_tickets">', '</a>' ); ?></p>

<h3><strong><?php _e( 'Step 6', 'wpet' ); ?></strong></h3>
<p><?php echo sprintf( __( 'The %sPackages%s page is used to create the items that will be listed for sale on your site. For example, if you created ticket types "A" and "B" in the previous step, you could now create a package called "General Admission" and select ticket type "A" to be included. Then create a second package called "Cheapy Ticket" and attach ticket type "B". Another good use of a ticket package would be for offering event sponsorships. For example, create a package called "Gold Sponsor" with a price tag of $1,000 and includes multiple general admission tickets.', 'wpet' ), '<a href="admin.php?page=wpet_packages">', '</a>' ); ?></p>

<h3><strong><?php _e( 'Step 7', 'wpet' ); ?></strong></h3>
<p><?php echo sprintf( __( 'Create a new %sPage%s and add the following shortcode: %s. This will display the ticket packages available for your event.', 'wpet' ), '<a href="post-new.php?post_type=page">', '</a>', '<strong>[wpet]</strong>' ); ?></p>

<h3><strong><?php _e( 'Step 8', 'wpet' ); ?></strong></h3>
<p><?php echo sprintf( __( 'Once you are ready to start selling tickets, return to the %sSettings%s page and switch %sEvent Status%s to %sRegistration is Open%s', 'wpet' ), '<a href="admin.php?page=wpet_settings">', '</a>', '<strong>', '</strong>', '<em>', '</em>' ); ?></p>

<h3><strong><?php _e( 'Step 9', 'wpet' ); ?></strong> <em>(<?php _e( 'Optional', 'wpet' ); ?>)</em></h3>
<p><?php echo sprintf( __( 'Create a new %sPage%s and add the following shortcode: %s to display a list of all the attendees for your event', 'wpet' ), '<a href="post-new.php?post_type=page">', '</a>', '<strong>[wpet_attendee]</strong>' ); ?></p>