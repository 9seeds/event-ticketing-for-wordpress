<h2><?php _e( 'Instructions', 'wpet' ); ?></h2>
<div id="message" class="updated below-h2">
	<h3><?php _e( 'Did you know?', 'wpet' ); ?></h2>
	<p><?php _e( 'While you are working inside WP Event Ticketing you can click the Help button in the top-right corner of the screen to receive page-specific help.', 'wpet' ); ?></p>
</div>

<?php
/**
 * @todo This section will go to the 'payment gateway' tab
 */
?>
<h3><?php _e( 'PayPal API Signature', 'wpet' ); ?></h3>
<p><?php _e( 'WP Event Ticketing requires a PayPal API Signature in order to accept payments. You will need a business account at PayPal in order to use their API Signature tool. Once you have a business account set up, here is how you set up you API Signature:', 'wpet' ); ?></p>
<ol>
<li><?php _e( 'Log in to your PayPal account', 'wpet' ); ?></li>
<li><?php _e( 'Hover over the <strong>Profile</strong> subtab on the top navigation area and click the <strong>My Selling Tools</strong> link', 'wpet' ); ?></li>
<li><?php _e( 'Click <strong>update</strong> on the API Access line', 'wpet' ); ?></li>
<li><?php _e( 'Click the <strong>request api credentials</strong> link (under Option 2)', 'wpet' ); ?></li>
<li><?php _e( 'Submit the form to <strong>request api signature</strong>', 'wpet' ); ?></li>
<li><?php _e( 'Copy and paste the API username, password and signature into the fields on the Tickets -> Settings -> Payments tab', 'wpet' ); ?></li>
</ol>

<?php
/**
 * @todo This will go on the 'getting started' tab
 */
?>
<h3><?php _e( '<strong>Step 1</strong>', 'wpet' ); ?></h3>
<p><?php _e( 'On the <a href="admin.php?page=wpet_settings">Settings</a> page, click the <strong>Payments</strong> tab to configure your payment gateway', 'wpet' ); ?></p>

<h3><?php _e( '<strong>Step 2</strong>', 'wpet' ); ?></h3>
<p><?php _e( 'On the <a href="admin.php?page=wpet_settings">Settings</a> page, click the <strong>Event</strong> tab and enter the default settings for your event', 'wpet' ); ?></p>

<h3><?php _e( '<strong>Step 3</strong>', 'wpet' ); ?></h3>
<p><?php _e( 'On the <a href="admin.php?page=wpet_settings">Settings</a> page, click the <strong>Email</strong> tab and enter the default information that will be sent via email to your attendees after a sucessfull purchase.', 'wpet' ); ?></p>

<h3><?php _e( '<strong>Step 4</strong>', 'wpet' ); ?></h3>
<p><?php _e( 'The <a href="admin.php?page=wpet_ticket_options">Ticket Options</a> page is where you will set up what types of data you want to collect from your event attendees.<br /><em>Note, not all options have to be used for each ticket type. More on that shortly.</em>', 'wpet' ); ?></p>

<h3><?php _e( '<strong>Step 5</strong>', 'wpet' ); ?></h3>
<p><?php _e( 'The <a href="admin.php?page=wpet_tickets">Tickets</a> page is where you\'ll set up the types of tickets you want to offer. For example, you can set up ticket type "A" to include a shirt and lunch and type "B" which includes just the basics. As you are creating your ticket, you select the ticket options you want included for each ticket type.', 'wpet' ); ?></p>

<h3><?php _e( '<strong>Step 6</strong>', 'wpet' ); ?></h3>
<p><?php _e( 'The <a href="admin.php?page=wpet_packages">Packages</a> page is used to create the items that will be listed for sale on your site. For example, if you created ticket types "A" and "B" in the previous step, you could now create a package called "General Admission" and select ticket type "A" to be included. Then create a second package called "Cheapy Ticket" and attach ticket type "B". Another good use of a ticket package would be for offering event sponsorships. For example, create a package called "Gold Sponsor" with a price tag of $1,000 and includes multiple general admission tickets.', 'wpet' ); ?></p>

<h3><?php _e( '<strong>Step 7</strong>', 'wpet' ); ?></h3>
<p><?php _e( 'Create a new <a href="post-new.php?post_type=page">Page</a> and add the following shortcode: <strong>[wpeventticketing]</strong>. This will display the ticket packages available for your event.', 'wpet' ); ?></p>

<h3><?php _e( '<strong>Step 8</strong>', 'wpet' ); ?></h3>
<p><?php _e( 'Once you are ready to start selling tickets, return to the <a href="admin.php?page=wpet_settings">Settings</a> page and switch <strong>Event Status</strong> to <em>Registration is Open</em>', 'wpet' ); ?></p>

<h3><?php _e( '<strong>Step 9</strong> <em>(Optional)</em>', 'wpet' ); ?></h3>
<p><?php _e( 'Create a new <a href="post-new.php?post_type=page">Page</a> and add the following shortcode: <strong>[wpet_attendee]</strong> to display a list of all the attendees for your event', 'wpet' ); ?></p>

<?php
/**
 * @todo This will go on the 'extras' tab
 */
?>
<h3><?php _e( '<strong>Reporting</strong>', 'wpet' ); ?></h3>
<p><?php _e( 'The <a href="admin.php?page=wpet_reports">Reporting</a> page gives you a snap shot of the packages and ticket types sold, coupons used and a graph displaying the number of tickets used and still available. The Summary Report makes it easy to get a count of your attendees based on the data provided. Very handy for getting a count of how many t-shirts you need to order in each size.', 'wpet' ); ?></p>

<h3><?php _e( '<strong>Create Coupons</strong>', 'wpet' ); ?></h3>
<p><?php _e( 'On the <a href="admin.php?page=wpet_coupons">Coupons</a> page you can create an easy way to give discounts on tickets. Create a ticket code, set a flat-rate or percentage discount and select the number of times it can be used. This is handy for giving your speakers free entry to the event, but having them register so they are included in the attendee list and receive email notifications you might send.', 'wpet' ); ?></p>

<h3><?php _e( '<strong>Send Emails to Attendees</strong>', 'wpet' ); ?></h3>
<p><?php _e( 'The <a href="admin.php?page=wpet_notifications">Notify Attendees</a> page will let you send an email to everybody on the Attendee list. A copy of the email gets sent to the admin automatically. A history of the messages sent is stored and displayed at the bottom of the page.', 'wpet' ); ?></p>

<h3><?php _e( '<strong>Create Tickets Manually</strong>', 'wpet' ); ?></h3>
<p><?php _e( 'You may find it necessary to create a ticket for somebody manually. Use the <a href="admin.php?page=wpet_attendees&action=new">Add Attendee</a> page to create a ticket, as an example, for somebody who may have paid cash.', 'wpet' ); ?></p>

<h3><?php _e( '<strong>Export Attendee List</strong>', 'wpet' ); ?></h3>
<p><?php _e( 'Also on the Attendee page you have the option to export a list of all the attendees. This creates a CSV of all attendees and the data they\'ve provided.', 'wpet' ); ?></p>

<?php
/**
 * @todo This will go on the 'design' tab
 */
?>
<h3><?php _e( '<strong>Modify the attendees shortcode display</strong>', 'wpet' ); ?></h3>
<p><?php _e( 'After you place the <em>[wpet_attendees]</em> shortcode on a page, by default your attendees will be displayed in order based on when they purchased their tickets. However, you can modify the order by adding a parameter to the shortcode. Here are a couple examples:', 'wpet' ); ?></p>
<p><?php _e( '<em>[wpet_attendees sort="First Name"]</em> to sort by First Name', 'wpet' ); ?></p>
<p><?php _e( '<em>[wpet_attendees sort="Last Name"]</em> to sort by Last Name', 'wpet' ); ?></p>
<p><?php _e( '<em>[wpet_attendees sort="Twitter"]</em> to sort by the attendees\'s Twitter handle', 'wpet' ); ?></p>