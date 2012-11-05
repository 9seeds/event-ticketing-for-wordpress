<?php

/*
  Plugin Name: WP Event Ticketing
  Plugin URI: http://9seeds.com/plugins/
  Description: The WP Event Ticketing plugin makes it easy to sell and manage tickets for your event.
  Author: 9seeds.com
  Version: 1.3.3
  Author URI: http://9seeds.com/
 */
define( 'WPEVT_DIR', dirname(__FILE__) );
require_once( WPEVT_DIR  . '/lib/functions.php' );
require_once( WPEVT_DIR  . '/lib/WPEVT.class.php' );
WPEVT::instance();

register_activation_hook(__FILE__, array("eventTicketingSystem", "activate"));
add_action('admin_init', array("eventTicketingSystem", "adminscripts"));
add_action('wp_print_styles', array("eventTicketingSystem", "frontendscripts"));
add_action('admin_menu', array("eventTicketingSystem", "options"));
add_shortcode('wpeventticketing', array("eventTicketingSystem", 'shortcode'));
add_shortcode('wpeventticketingattendee', array("eventTicketingSystem", 'attendeeShortcode'));
//add_action('template_redirect', array("eventTicketingSystem", "paypal"));
add_action('wpmu_new_blog', array("eventTicketingSystem", "activate"), 1);



class eventTicketingSystem {
    

   
    function activate($blog_id = NULL) {
        //If in an MS context and there's a new blog created switch to it before loading defaults
        if (is_numeric($blog_id)) {
            switch_to_blog($blog_id);
        }

        //Set up default options
        if (!get_option("eventTicketingSystem")) {
            $data = unserialize(file_get_contents(WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__)) . '/defaults.ser'));
            add_option("eventTicketingSystem", $data);
        }

        //If in an MS context and there's a new blog created switch back to current blog after defaults are loaded
        if (is_numeric($blog_id)) {
            restore_current_blog();
        }
    }

    function adminscripts() {
        //look for export button being clicked, export attendee list as csv
        if (isset($_POST['exportAttendeeNonce']) && wp_verify_nonce($_POST['exportAttendeeNonce'], plugin_basename(__FILE__)) && strlen($_REQUEST["attendeeCsv"])) {
            header("Content-type: text/csv");
            header("Content-Disposition: attachment;filename=attendee_export.csv");
            echo base64_decode($_REQUEST["attendeeCsv"]);
            exit;
        }

        $pluginurl = WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__));
        wp_enqueue_script('eventticketingscript', $pluginurl . '/js/ticketing.js', array('jquery'));
        wp_enqueue_script('datepicker', $pluginurl . '/js/jquery.ui.datepicker.js', array('eventticketingscript', 'jquery-ui-core'));
        wp_enqueue_style('datepickercss', $pluginurl . '/css/ui.all.css');
    }

    function frontendscripts() {
        $pluginurl = WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__));
        wp_enqueue_style('eventticketingstyle', $pluginurl . '/css/ticketing.css');
    }

    function options() {
        //echo '<pre>'.print_r($menu,true).'</pre>';
        add_object_page('Tickets', 'Tickets', 'activate_plugins', 'eventticketing', array("eventTicketingSystem", "ticketReporting"), WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) . '/images/calendar_full.png');
        add_submenu_page('eventticketing', 'Reporting', 'Reporting', 'activate_plugins', 'eventticketing', array('eventTicketingSystem', 'ticketReporting'));
        add_submenu_page('eventticketing', 'Ticket Options', 'Ticket Options', 'activate_plugins', 'ticketoptions', array('eventTicketingSystem', 'ticketOptionsControl'));
        add_submenu_page('eventticketing', 'Tickets', 'Tickets', 'activate_plugins', 'tickettickets', array('eventTicketingSystem', 'ticketTicketsControl'));
        add_submenu_page('eventticketing', 'Packages', 'Packages', 'activate_plugins', 'ticketpackages', array('eventTicketingSystem', 'ticketPackagesControl'));
        add_submenu_page('eventticketing', 'Coupons', 'Coupons', 'activate_plugins', 'ticketcoupons', array('eventTicketingSystem', 'ticketCouponsControl'));
        add_submenu_page('eventticketing', 'Notify Attendees', 'Notify Attendees', 'activate_plugins', 'ticketnotify', array('eventTicketingSystem', 'ticketNotify'));
        add_submenu_page('eventticketing', 'Attendees', 'Attendees', 'activate_plugins', 'ticketattendeeedit', array('eventTicketingSystem', 'ticketAttendeeEdit'));
        add_submenu_page('eventticketing', 'Instructions', 'Instructions', 'activate_plugins', 'ticketinstructions', array('eventTicketingSystem', 'ticketInstructions'));
        add_submenu_page('eventticketing', 'Settings', 'Settings', 'activate_plugins', 'ticketsettings', array('eventTicketingSystem', 'ticketSettings'));

        if (isset($_REQUEST["page"]) && $_REQUEST["page"] == "wpeventticketingdebug") {
            add_submenu_page('eventticketing', 'Debug', 'Debug', 'activate_plugins', 'wpeventticketingdebug', array('eventTicketingSystem', 'ticketDebug'));
        }
    }

    function ticketInstructions() {
        echo '<div class="wrap">';
        echo '<div id="icon-options-general" class="icon32"></div><h2>' . __('Instructions') . '</h2>';
        echo '<h3>' . __('Step 1') . '</h3>';
        echo '<p>' . __('Before you can use the ticketing system for the first time, you need to <a href="https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_NVPAPIBasics#id084E30I30RO" target="_blank">follow these instructions at Paypal</a> to get your API signature. You\'ll need that information in step 2.') . '</p>';

        echo '<h3>' . __('Step 2') . '</h3>';
        echo '<p>' . __('Update the <a href="admin.php?page=ticketsettings">Settings</a> page with your default event settings.') . '</p>';

        echo '<h3>' . __('Step 3') . '</h3>';
        echo '<p>' . __('The <a href="admin.php?page=ticketoptions">Ticket Options</a> page is where you can set up what types of data you want to collect from your event attendees. Note, not all options have to be used for each ticket type. More on that shortly.') . '</p>';

        echo '<h3>' . __('Step 4') . '</h3>';
        echo '<p>' . __('The <a href="admin.php?page=tickettickets">Tickets</a> page is where you\'ll set up the types of tickets you want to offer. For example, you can set up ticket type "A" to include a shirt and lunch and type "B" which includes just the basics. As you are creating your ticket, you select the ticket options you want included for each ticket type.') . '</p>';

        echo '<h3>' . __('Step 5') . '</h3>';
        echo '<p>' . __('The <a href="admin.php?page=ticketpackages">Packages</a> page is used to create the items that will be listed for sale on your site. For example, if you created ticket types "A" and "B" in the previous step, you could now create a package called "General Admission" and select ticket type "A" to be included. Then create a second package called "Cheapy Ticket" and attach ticket type "B". Another good use of a ticket package would be for offering event sponsorships. For example, create a package called "Gold Sponsor" with a price tag of $1,000 and includes multiple general admission tickets.') . '</p>';

        echo '<h3>' . __('Step 6') . '</h3>';
        echo '<p>' . __('Create a new <a href="post-new.php?post_type=page">Page</a> and add the following shortcode: <strong>[wpeventticketing]</strong>. This will display the ticket packages available for your event.') . '</p>';

        echo '<h3>' . __('Step 7') . '</h3>';
        echo '<p>' . __('On the <a href="admin.php?page=ticketsettings">Settings</a> page, click the Start Registration" button to open ticket sales.') . '</p>';

        echo '<p><hr></p>';

        echo '<h2>' . __('Extras') . '</h2>';
        echo '<h3>' . __('Reporting') . '</h3>';
        echo '<p>' . __('The <a href="admin.php?page=eventticketing">Reporting</a> page gives you a snap shot of the packages and ticket types sold, coupons used and a graph displaying the number of tickets used and still available. The Summary Report makes it easy to get a count of your attendees based on the data provided. Very handy for getting a count of how many t-shirts you need to order in each size.') . '</p>';

        echo '<h3>' . __('Create Coupons') . '</h3>';
        echo '<p>' . __('On the <a href="admin.php?page=ticketcoupons">Coupons</a> page you can create an easy way to give discounts on tickets. Create a ticket code, set a flat-rate or percentage discount and select the number of times it can be used. This is handy for giving your speakers free entry to the event, but having them register so they are included in the attendee list and receive email notifications you might send.') . '</p>';

        echo '<h3>' . __('Send Emails to Attendees') . '</h3>';
        echo '<p>' . __('The <a href="admin.php?page=ticketnotify">Notify Attendees</a> page will let you send an email to everybody on the Attendee list. A copy of the email gets sent to the admin automatically. A history of the messages sent is stored and displayed at the bottom of the page.') . '</p>';

        echo '<h3>' . __('Create Tickets Manually') . '</h3>';
        echo '<p>' . __('You may find it necessary to create a ticket for somebody manually. Use the form on the <a href="admin.php?page=ticketattendeeedit">Attendees</a> page to create a ticket, as an example, for somebody who may have paid cash.') . '</p>';

        echo '<h3>' . __('Export Attendee List') . '</h3>';
        echo '<p>' . __('Also on the Attendee page you have the option to export a list of all the attendees. This creates a CSV of all attendees and the data they\'ve provided.') . '</p>';

        echo '<h3>' . __('Display Attendees Shortcode') . '</h3>';
        echo '<p>' . __('Place a shortcode on a page and your attendees will be displayed. [wpeventticketingattendee] will display the list sorted by sold time. You can sort the list by first name [wpeventticketingattendee sort="First Name"], last name [wpeventticketingattendee sort="Last Name"] or twitter id [wpeventticketingattendee sort="Twitter"] as well.<br /><br />If you use the shortcode you will have to put something similar to the following into your stylesheet.') . '</p><pre>.event-attendee {
    width: 45%;
}
.event-attendee.even {
    float: right;
}
.event-attendee.odd {
    float: left;
}
.event-attendee .attendee-gravatar {
    float: left;
}
.event-attendee .attendee-gravatar img{
    width: 48px;
    height: 48px;
}</pre>';

//		echo '<p><hr></p>';
//		echo '<h3>'.__('Looking for more features?').'</h3>';
//		echo '<p>'.__('We are currently hard at work on <strong>Event Ticketing Pro</strong>. Sign up for our <a href="http://eepurl.com/brgsD" target="_blank">newsletter</a> to be notified when the pro version is released.').'</p>';

        echo '<p>' . __('We hope you like the plugin. If you have any questions, feel free to <a href="http://9seeds.com/contact/" target="_blank">contact us</a>.') . '</p>';


        echo '</div>';
    }

    function getCurrencySymbol() {
        $o = get_option("eventTicketingSystem");

        if (!isset($o["paypalInfo"]["paypalCurrency"]) || !strlen($o["paypalInfo"]["paypalCurrency"]))
            $type = 'USD';
        else
            $type = $o["paypalInfo"]["paypalCurrency"];

        switch ($type) {
            case 'USD':
                return '$';
                break;
            case 'AUD':
                return 'A $';
                break;
            case 'CAD':
                return 'C $';
                break;
            case 'EUR':
                return '&euro;';
                break;
            case 'GBP':
                return '&pound;';
                break;
            case 'JYP':
                return '&yen;';
                break;
            default:
                return $type . ' ';
        }
    }

    function currencyFormat($num) {
        $symbol = eventTicketingSystem::getCurrencySymbol();

        return $symbol . number_format($num, 2);
    }

    function ticketDebug() {
        global $wpdb;
        $o = get_option("eventTicketingSystem");
        /*
          if (wp_verify_nonce($_POST['ticketDebugEmailNonce'], plugin_basename(__FILE__)))
          {
          $records = $wpdb->get_results("select * from {$wpdb->options} where option_name like 'package_%'");
          foreach($records as $r)
          {
          //	$p = unserialize(
          }
          }
         */
        if (wp_verify_nonce($_POST['ticketDebugVerifyIntegrityNonce'], plugin_basename(__FILE__))) {
            $packages = eventTicketingSystem::getPackages();
            if (is_array($packages)) {
                foreach ($packages as $k => $p) {
                    foreach ($p->tickets as $t) {
                        //echo '<pre>'.print_r($t,true).'</pre>';	exit;
                        if (!get_option('ticket_' . $t->ticketId)) {
                            $package = get_option('package_' . $k);
                            $package->delTicket($t->ticketId);
                            if (count($package->tickets) == 0) {
                                echo "deleting empty package<br />";
                                delete_option('package_' . $k);
                            } else {
                                echo "deleting orphan ticket<br />";
                                update_option('package_' . $k, $package);
                            }
                        }
                    }
                }
            }
        }

        echo '<div class="wrap">';
        echo '<div id="icon-tools" class="icon32"></div><h2>Integrity Check</h2>';
        echo '<form name="ticketDebugVerify" action="" method="post">';
        echo '<input type="hidden" name="ticketDebugVerifyIntegrityNonce" id="ticketDebugVerifyIntegrityNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
        echo '<div><input type="submit" class="button-primary" value="Check Integrity" /></div><div class="instructional">Check integrity of tickets and packages and search for orphans. <strong>IF THERE ARE ORPHANS THEY WILL BE DELETED. PLEASE GET A BACKUP OF YOUR DATABASE BEFORE CLICKING THIS BUTTON!</strong></div><br /><br />';
        echo '</form>';
        /*
          echo '<div class="clear"></div>';
          echo '<div id="icon-tools" class="icon32"></div><h2>Email Debug Info</h2>';
          echo '<form name="ticketDebugEmail" action="" method="post">';
          echo '<input type="hidden" name="ticketDebugEmailNonce" id="ticketDebugEmailNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
          echo '<div><input type="button" class="button-primary" value="Email Debug Info" onClick="javascript:if(confirm(\'Are you sure you want to send all your WP Event Ticketing settings to the email address specified?\')) { document.ticketDebugEmail.submit(); } else { return false; }" /></div><div class="instructional">Email Settings to Developer. This will send a copy of your WP Event Ticketing setup to the email address specified with all sensitive infomation removed</div><br /><br />';
          echo '</form>';
         */
        echo '</div>';
    }  // end function ticketDebug

    function ticketSettings() {
        $wpevt = WPEVT::instance();
        
        $o = get_option("eventTicketingSystem");
        
        /*
         * If the user changed the payment gateway to use be sure to save it 
         */
        if( isset( $_POST['paymentGateway'] ) ) {
            $o['paymentGateway'] = $_POST['paymentGateway'];
            update_option( 'eventTicketingSystem', $o );
        }
        
        if (!isset($o["registrationPermalink"]) || (isset($o["registrationPermalink"]) && $o["registrationPermalink"] != get_permalink())) {
            $o["registrationPermalink"] = get_permalink();
            update_option("eventTicketingSystem", $o);
        }
        
        if( isset( $_GET['msg'] ) && 'paymentGateway' == $_GET['msg'] ) 
            echo '<div id="message" class="updated"><p>Payment gateway settings have been saved.</p></div>';
        
        
        //echo '<pre>'.print_r($_REQUEST,true).'</pre>';	
        echo '<div class="wrap">';
        if (isset($_REQUEST["submitbutton"]) && $_REQUEST["submitbutton"] == 'Save Settings') {
            echo '<div id="message" class="updated"><p>Settings have been saved.</p></div>';
        }

        if (isset($_REQUEST['eventStatusSwitch']) && $_REQUEST['eventStatusSwitch'] == 1) {
            echo '<div id="message" class="updated"><p>Ticketing has been turned <strong>' . ($o['eventTicketingStatus'] == 1 ? 'OFF' : 'ON') . '</strong></p></div>';
        }

        if (isset($_REQUEST["eventReset"]) && $_REQUEST["eventReset"] == 1) {
            echo '<div id="message" class="updated"><p>Ticketing has been reset</p></div>';
        }

                       
        echo '<div class="settings_page">';
        
        /*
         * Build menu to chose which payment gateway to use
         */
        ?>
        <div id="payment-gateway">
            <div id="icon-users" class="icon32"></div><h2>Payment Gateway</h2>
            <form method="post" action="">
		<input type="hidden" name="ticketPaypalNonce" id="ticketPaypalNonce" value="<?php wp_create_nonce(plugin_basename(__FILE__)); ?>" />
                <table class="form-table" id="paymentGatewayTable">			
			<tr valign="top" id="tags">
				<th scope="row"><label for="paymentGateway">Payment Gateway: </label></th>
				<td>
                                    <select name="paymentGateway" id="paymentGateway" onChange="changed(this);">
                                        <?php
                                        foreach( $wpevt->getPaymentGateways() AS $g ) {
                                            $selected = selected( $g->slug(), $o['paymentGateway'], false );
                                            echo '<option value="' . $g->slug() . '" ' . $selected . '>' . $g->displayName() . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
			</tr>
                </table>
                
                <?php foreach( $wpevt->getPaymentGateways() AS $g ) { ?>
                <div id="payment-gateway-settings-<?php echo $g->slug(); ?>">
                    <div id="icon-users" class="icon32"></div><h2><?php echo $g->displayName(); ?> Settings</h2>
                    <?php echo $g->settingsForm( $o['paymentGatewayData'][ $g->slug() ] ); ?>
                </div>
                    
                <?php } // end foreach( $wpevt->getPaymentGateways() AS $g ) ?>
                
        <?php
        
        
        
        /*
         * PAYPAL SETTINGS
         */
         submit_button( 'Save Payment Settings', 'primary', 'wpevt_save_payment_gateway' );
        echo '</div>';

        //</form>';
        //echo '<div class="instructional">Set your paypal info. None of this is going to work if you cannot get paid. Follow <a href="https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_NVPAPIBasics#id084E30I30RO">these instructions at Paypal</a> to get your API signature</div>';
        //echo '</div>';
        /*
         * End Paypal
         */

        /*
         * Messages
         */
        if (isset($_POST['ticketMessagesNonce']) && wp_verify_nonce($_POST['ticketMessagesNonce'], plugin_basename(__FILE__))) {
            $o["messages"] = array(
                "messageEventName" => trim(stripslashes($_REQUEST["messageEventName"])),
                "messageThankYou" => trim(stripslashes($_REQUEST["messageThankYou"])),
                "messageRegistrationComingSoon" => trim(stripslashes($_REQUEST["messageRegistrationComingSoon"])),
                "messageEmailFromName" => trim(stripslashes($_REQUEST["messageEmailFromName"])),
                "messageEmailFromEmail" => trim(stripslashes($_REQUEST["messageEmailFromEmail"])),
                "messageEmailBody" => trim(stripslashes($_REQUEST["messageEmailBody"])),
                "messageEmailSubj" => trim(stripslashes($_REQUEST["messageEmailSubj"])),
                "messageEmailBcc" => trim(stripslashes($_REQUEST["messageEmailBcc"])),
            );
            update_option("eventTicketingSystem", $o);
        }
        echo '<div class="settings_page">';
        echo '<div id="icon-users" class="icon32"></div><h2>Static Pages</h2>';
        //echo '<form method="post" action="">
        echo '<input type="hidden" name="ticketMessagesNonce" id="ticketMessagesNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />
			<table class="form-table">			
			<tr valign="top">
				<th scope="row"><label for="messageThankYou">Thank You Page:</label><br /><br ><em>Note: You must put the shortcode [ticketlinks] in this thank you page to have the links to the purchased tickets show up</em></th>
				<td><textarea id="messageThankYou" name="messageThankYou" rows="10" cols="80">' . $o["messages"]["messageThankYou"] . '</textarea></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="messageRegistrationComingSoon">Registration Coming Soon Page:</label></th>
				<td><textarea id="messageRegistrationComingSoon" name="messageRegistrationComingSoon" rows="10" cols="80"/>' . $o["messages"]["messageRegistrationComingSoon"] . '</textarea></td>
			</tr>
			<tr valign="top">
				<td><input class="button-primary" type="submit" name="submitbutton" value="Save Settings" id="submitbutton"/></td>
			</tr>
			</table>';
        echo '</div>';
        echo '<div class="settings_page">';
        echo '<div id="icon-users" class="icon32"></div><h2>Email Messages</h2>';
        echo '<table class="form-table">			
			<tr valign="top">
				<th scope="row"><label for="messageEventName">Event Name:</label></th>
				<td><input id="messageEventName" type="text" name="messageEventName" size="80" value="' . $o["messages"]["messageEventName"] . '"></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="messageEmailFromName">Email From Name:</label></th>
				<td><input id="messageEmailFromName" type="text" name="messageEmailFromName" size="40" value="' . $o["messages"]["messageEmailFromName"] . '"></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="messageEmailFromEmail">Email From Email:</label></th>
				<td><input id="messageEmailFromEmail" type="text" name="messageEmailFromEmail" size="40" value="' . $o["messages"]["messageEmailFromEmail"] . '"></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="messageEmailSubj">Email Subject:</label></th>
				<td><input id="messageEmailSubj" type="text" name="messageEmailSubj" size="80" value="' . $o["messages"]["messageEmailSubj"] . '"></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="messageEmailBody">Email Body:</label><br /><br ><em>Note: You must put the shortcode [ticketlinks] in this email body to have the links to the purchased tickets show up</em></th>
				<td><textarea id="messageEmailBody" name="messageEmailBody" rows="10" cols="80" >' . $o["messages"]["messageEmailBody"] . '</textarea></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="messageEmailBcc">Organizers email for notifications:</label></th>
				<td><input id="messageEmailBcc" type="text" name="messageEmailBcc" size="40" value="' . $o["messages"]["messageEmailBcc"] . '"></td>
			</tr>
			<tr valign="top">
				<td><input class="button-primary" type="submit" name="submitbutton" value="Save Settings" id="submitbutton"/></td>
			</tr>
			</table>';
        //</form>';
        //echo '<div class="instructional">This section controls the messages that your customers will see in email and on the screen as they purchase tickets to the event</div>';
        echo '</div>';

        /*
         * end messages
         */

        /*
         * Attendance display
         */
        echo '<div class="settings_page">';
        echo '<div id="icon-users" class="icon32"></div><h2>Event Attendance Maximum</h2>';
        //echo '<form method="post" action="" name="eventAttendance">
        echo '<input type="hidden" name="eventAttendanceNonce" id="eventAttendanceNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
        //<input type="hidden" name="edit" value="" />';
        if (isset($_POST['eventAttendanceNonce']) && wp_verify_nonce($_POST['eventAttendanceNonce'], plugin_basename(__FILE__))) {
            if (is_numeric($_REQUEST["eventAttendanceMax"])) {
                $o["eventAttendance"] = $_REQUEST["eventAttendanceMax"];
                $o["displayPackageQuantity"] = $_REQUEST["displayPackageQuantity"];
                update_option("eventTicketingSystem", $o);
            }
        }

        echo '<div id="ticket_events_edit">';
        echo '<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="maximumAttendance">Maximum Attendance</label></th>
				<td><input type="text" value="' . $o["eventAttendance"] . '" name="eventAttendanceMax" size="4" /></td></tr>';
        echo '<tr valign="top">
				<th scope="row"><label for="displayTotals">Display Totals in Form</th>
				<td><input type="checkbox" value="1" name="displayPackageQuantity" ' . ($o["displayPackageQuantity"] == 1 ? "checked" : "") . '></td></tr>';
        echo '<tr><td colspan="2"><input type="submit" class="button-primary" name="submitbutton" value="Save Settings" /></td></tr></table>';
        echo '</div>';

        //echo '</form>';

        echo "</div>";

        //echo '<div class="instructional">Set your maximum event attendance and whether or not you want to display remaining tickets on the ticket form. This number supercedes all the package quantites if they are set. At no point will you sell more than this many tickets to the event.</div>';

        echo '</form>';

        /*
         * end attendance
         */

        /*
         * Event Control
         */
        //Start/Stop/Reset Event Screen
        echo '<div class="settings_page">';
        if (isset($_POST['eventManipulationNonce']) && wp_verify_nonce($_POST['eventManipulationNonce'], plugin_basename(__FILE__))) {
            if ($_REQUEST['eventStatusSwitch'] == 1) {
                $o['eventTicketingStatus'] == 1 ? $o['eventTicketingStatus'] = 0 : $o['eventTicketingStatus'] = 1;
                update_option('eventTicketingSystem', $o);
            }
            if ($_REQUEST["eventReset"] == 1) {
                global $wpdb;
                $wpdb->query("delete from {$wpdb->options} where option_name like 'package_%' OR option_name like 'ticket_%' OR option_name like 'coupon_%' OR option_name like 'paypal_%'");
                //echo '<pre>'.print_r($o,true).'</pre>';
                unset($o["packageQuantities"]);
                unset($o["messages"]["sentMessages"]);

                if (is_numeric($_REQUEST["eventResetOptions"])) {
                    if ($_REQUEST["eventResetOptions"] == 1) {
                        unset($o["packageProtos"]);
                    }
                    if ($_REQUEST["eventResetOptions"] == 2) {
                        unset($o["packageProtos"]);
                        unset($o["ticketProtos"]);
                    }
                    if ($_REQUEST["eventResetOptions"] == 3) {
                        unset($o["packageProtos"]);
                        unset($o["ticketProtos"]);
                        unset($o["ticketOptions"]);
                    }
                    if ($_REQUEST["eventResetOptions"] == 4) {
                        $o = unserialize(file_get_contents(WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__)) . '/defaults.ser'));
                        update_option("eventTicketingSystem", $o);
                    }
                }
                if (isset($_REQUEST["eventResetCoupons"]) && $_REQUEST["eventResetCoupons"] == 1) {
                    unset($o["coupons"]);
                }

                update_option("eventTicketingSystem", $o);
            }
        }
        echo '<div id="icon-tools" class="icon32"></div><h2>Event Control</h2>';
        echo '<div id="event_control">';
        echo '<form name="eventManipulationForm" action="" method="post">';
        echo '<input type="hidden" name="eventReset" value="">';
        echo '<input type="hidden" name="eventStatusSwitch" value="">';
        echo '<input type="hidden" name="eventManipulationNonce" id="eventManipulationNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
        echo '<table class="widefat">';
        echo '<tbody>';
        echo '<tr><td>Turn registration on and off with this button. Registration is currently <strong>' . ($o['eventTicketingStatus'] ? 'On' : 'Off') . '</strong><br /><br /><input class="button-primary" type="button" value="Start/Stop Registration" onClick="javascript:document.eventManipulationForm.eventStatusSwitch.value=\'1\';document.eventManipulationForm.submit(); return false;"></td></tr>';
        echo '<tr><td>Reset event. <em>Warning</em>: <strong>Clicking the reset button will wipe all your sold tickets, coupons, attendee list and reporting!</strong> This resets the plugin so you can create a new event. Uncheck the boxes to save ticket, package and ticket option definitions<br /><br /><input class="button-primary" type="button" value="Reset Event" onClick="javascript:if(confirm(\'Are you sure you want to reset the event? All event data will be wiped and reset and this cannot be undone\')) { document.eventManipulationForm.eventReset.value=\'1\'; document.eventManipulationForm.submit(); } else { return false; }">
			<table>
			<tr><th colspan="2">Also Delete</th></tr>
			<tr><td>Package definitions</td><td><input type="radio" value="1" name="eventResetOptions"></td></tr>
			<tr><td>Ticket & package definitions</td><td><input type="radio" value="2" name="eventResetOptions"></td></tr>
			<tr><td>Ticket, ticket options and package definitions</td><td><input type="radio" value="3" name="eventResetOptions" checked></td></tr>
			<tr><td>FULL RESET TO DEFAULT INSTALL</td><td><input type="radio" value="4" name="eventResetOptions"></td></tr>
			<tr><td>Coupon Definitions</td><td><input type="checkbox" value="1" name="eventResetCoupons" checked></td></tr>
			</table>
			</td></tr>';
        echo '</tbody>';
        echo '</table>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        /*
         * end event control
         */

        echo '</div>';
        echo '</div>';
        
        
    } // end function ticketSettings

    function ticketAttendeeEdit() {
        $o = get_option("eventTicketingSystem");

        echo '<div id="ticket_editattendee" class="wrap">';

        //manualy create a ticket
        if (isset($_POST['manualCreatePackageNonce']) && wp_verify_nonce($_POST['manualCreatePackageNonce'], plugin_basename(__FILE__)) && is_numeric($_REQUEST["manualCreatePackageId"]) && isset($o["packageProtos"][$_REQUEST["manualCreatePackageId"]])) {
            if (isset($_POST["manualCreateEmail"]) && !check_email_address($_POST["manualCreateEmail"])) {
                echo '<div id="message" class="error"><p>Email address is incorrect or blank</p></div>';
                unset($_REQUEST["manualCreatePackageId"]);
            } else {
                $withRevenue = false;
                if (isset($_REQUEST["manualCreateWithRevenue"]))
                    $withRevenue = true;

                $hashes = eventTicketingSystem::ticketSellPackage($_REQUEST["manualCreatePackageId"], $withRevenue, $_REQUEST["manualCreateEmail"]);
                $_REQUEST["tickethash"] = $hashes["ticketHash"][0]["hash"];

                echo '<div id="icon-users" class="icon32"></div><h2>Create Attendee</h2>';
                echo '<div class="instructional">The ticket has been created and an email has been seent to the recipient instructing them to fill out their details. If you would prefer to fill them out yourself you may do so below. Otherwise go <a href="' . admin_url("admin.php?page=ticketattendeeedit") . '">back to attendees</a></div>';
                echo '<a href="' . admin_url("admin.php?page=ticketattendeeedit") . '">Back to Attendees</a>';
                eventTicketingSystem::ticketEditScreen();
            }
        }

        //save edited ticket info
        if (isset($_REQUEST["tickethash"]) && strlen($_REQUEST["tickethash"]) == 32 && wp_verify_nonce($_POST['ticketInformationNonce'], plugin_basename(__FILE__))) {
            eventTicketingSystem::ticketEditScreen();
        }

        //edit or delete an existing ticket
        if (isset($_REQUEST["tickethash"]) && strlen($_REQUEST["tickethash"]) == 32 && wp_verify_nonce($_POST['attendeeEditNonce'], plugin_basename(__FILE__))) {
            if (isset($_REQUEST["edit"]) && is_numeric($_REQUEST["edit"]) && $_REQUEST["edit"] == 1) {
                echo '<div id="icon-profile" class="icon32"></div><h2>Edit Attendee</h2>';
                eventTicketingSystem::ticketEditScreen();
            } elseif (isset($_REQUEST["del"]) && is_numeric($_REQUEST["del"]) && $_REQUEST["del"] == 1) {
                //echo '<pre>'.print_r($_REQUEST,true).'</pre>';

                $ticketHash = $_REQUEST["tickethash"];
                $packageHash = get_option('ticket_' . $ticketHash);
                $package = get_option('package_' . $packageHash);
                unset($package->tickets[$ticketHash]);

                //remove ticket
                delete_option("ticket_" . $ticketHash);
                $o["packageQuantities"]["totalTicketsSold"]--;

                //if this was the last ticket in the package, get rid of the whole package too
                if (count($package->tickets) == 0) {
                    delete_option("package_" . $packageHash);
                    $o["packageQuantities"][$package->orderDetails["items"][0]["packageId"]]--;
                } else {
                    update_option("package_" . $packageHash, $package);
                }

                update_option("eventTicketingSystem", $o);
            }
        }

        if (!((isset($_REQUEST["edit"]) && is_numeric($_REQUEST["edit"])) || (isset($_REQUEST["manualCreatePackageId"]) && is_numeric($_REQUEST["manualCreatePackageId"])))) {
            if (is_array($o["packageProtos"])) {
                echo '<div id="icon-users" class="icon32"></div><h2>Create Manual Ticket</h2>';
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="manualCreatePackageNonce" id="manualCreatePackageNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
                echo '<table>';
                echo '<tr><td>Package Type:</td><td><select name="manualCreatePackageId" id="manualCreatePackageId">';
                foreach ($o["packageProtos"] as $p) {
                    echo '<option value="' . $p->packageId . '">' . $p->displayName() . '</option>';
                }
                echo '</select></td></tr>';
                echo '<tr><td>Add ticket value to revenue report</td><td><input type="checkbox" name="manualCreateWithRevenue" /></td></tr>';
                echo '<tr><td>Email Address of receipient</td><td><input name="manualCreateEmail" /></td></tr>';
                echo '<tr><td colspan="2"><input type="submit" class="button-primary" name="submitbutt" value="Create Ticket" /></td></tr>';
                echo '</table>';
                echo '</form>';
            }

            echo '<div id="icon-profile" class="icon32"></div><h2>Attendee List</h2>';

            if (!isset($_REQUEST["attendeesort"]))
                $_REQUEST["attendeesort"] = 'Sold Time';
            eventTicketingSystem::generateAttendeeTable(urldecode($_REQUEST["attendeesort"]));
        }
        echo '</div>';
        //echo '</div>';
    }

    function ticketSellPackage($packageId, $withRevenue = false, $emailTo = '') {
        //this doesn't replace the regular paypal method of selling a ticket
        //(without modification)
        //notably the $item array and $order array are different


        $o = get_option("eventTicketingSystem");
        $price = $withRevenue ? $o["packageProtos"][$packageId]->price : 0;
        $total = $withRevenue ? $o["packageProtos"][$packageId]->price : 0;

        $item[] = array("quantity" => 1,
            "name" => $o["packageProtos"][$packageId]->displayName(),
            "desc" => $o["packageProtos"][$packageId]->packageDescription,
            "price" => $price,
            "packageid" => $packageId
        );

        $order = array("items" => $item, "email" => $emailTo, "name" => "Admin Generated", "total" => $total);
        $packageHash = md5(microtime() . $packageId);
        $package = clone $o["packageProtos"][$packageId];
        $package->setPackageId($packageHash);
        $package->setOrderDetails($order);
        $package->setPackagePrice($price);

        //get ticket proto from package and wipe proto from package
        $t = array_shift($package->tickets);

        for ($y = 1; $y <= $package->ticketQuantity; $y++) {
            //create tickets and attach them to real package
            $ticketHash = md5(microtime() . $t->ticketId);
            $ticket = clone $o["ticketProtos"][$t->ticketId];
            $ticket->setTicketid($ticketHash);
            $ticket->setSoldTime(current_time('timestamp'));

            //echo '<pre>'.print_r($ticket,true).'</pre>';
            foreach ($ticket->ticketOptions as $tk => $tv) {
                if ($tv->displayName == 'Email') {
                    $ticket->ticketOptions[$tk]->value = $emailTo;
                }
                if ($tv->displayName == 'First Name') {
                    $ticket->ticketOptions[$tk]->value = '';
                }
                if ($tv->displayName == 'Last Name') {
                    $ticket->ticketOptions[$tk]->value = '';
                }
            }

            $package->addTicket($ticket);
            add_option("ticket_" . $ticketHash, $packageHash);
            $o["packageQuantities"]["totalTicketsSold"]++;
            $tickethashes[] = array("hash" => $ticketHash, "name" => $package->packageName);
        }
        $o["packageQuantities"][$packageId]++;
        add_option("package_" . $packageHash, $package);

        //store packagequenitites and tickets sold
        //should probably be in a different option
        update_option("eventTicketingSystem", $o);

        if (check_email_address($emailTo)) {
            $c = 0;
            $emaillinks = "\r\n";
            foreach ($tickethashes as $hash) {
                $c++;
                $url = $o["registrationPermalink"] . (strstr($o["registrationPermalink"], '?') ? '&' : '?') . 'tickethash=' . $hash["hash"];
                $emaillinks .= 'Ticket ' . $c . ': ' . $hash["name"] . ' - ' . $url . "\r\n";
            }

            //$tohead = 'To: ' . $order["name"] . ' <' . $order["email"] . '>' . "\r\n";
            $headers = 'From: ' . $o["messages"]["messageEmailFromName"] . ' <' . $o["messages"]["messageEmailFromEmail"] . '>' . "\r\n";
            $headers .= 'Bcc: ' . $o["messages"]["messageEmailBcc"] . "\r\n";
            wp_mail($order["email"], $o["messages"]["messageEmailSubj"], str_replace('[ticketlinks]', $emaillinks, $o["messages"]["messageEmailBody"]), $tohead . $headers);

            $ordersummarymsg = "Order Placed\r\n";

            $ordersummarymsg .= $order["name"] . ' <' . $order["email"] . '> ordered ' . $c . ' tickets for ' . eventTicketingSystem::currencyFormat($order["total"]) . "\r\n\r\n";
            foreach ($order["items"] as $ord) {
                $ordersummarymsg .= $ord["quantity"] . ' X ' . $ord["name"] . ' for ' . eventTicketingSystem::currencyFormat($ord["price"]) . " each\r\n";
            }
            $ordersummarymsg .= "\r\n";
            wp_mail($o["messages"]["messageEmailBcc"], "Event Order Placed", $ordersummarymsg, $headers);
        }

        return(array("packageHash" => $packageHash, "ticketHash" => $tickethashes));
    }

    function ticketNotify() {
        $o = get_option("eventTicketingSystem");

        if (isset($_POST['attendeeNotificationNonce']) && wp_verify_nonce($_POST['attendeeNotificationNonce'], plugin_basename(__FILE__))) {
            global $wpdb;
            $packages = $wpdb->get_results("select option_value from {$wpdb->options} where option_name like 'package_%'");
            //echo '<pre>'.print_r($packages,true).'</pre>';exit;
            $bccList = array();
            if (is_array($packages)) {
                foreach ($packages as $k => $v) {
                    $v = unserialize($v->option_value);
                    //echo '<pre>'.print_r($v,true).'</pre>';
                    /*
                      if(strlen($v->orderDetails["email"]))
                      {
                      if(
                      (isset($_REQUEST["notification_all"]) && $_REQUEST["notification_all"] == 'on') ||
                      (isset($_REQUEST["notification_package"][$v->orderDetails["items"][0]["packageid"]]))
                      )
                      {
                      $bccList[$v->orderDetails["email"]] = $v->orderDetails["email"];
                      }
                      }
                     */
                    foreach ($v->tickets as $t) {
                        if (
                                (isset($_REQUEST["notification_all"]) && $_REQUEST["notification_all"] == 'on') ||
                                (isset($_REQUEST["notification_final"]) && $_REQUEST["notification_final"] == 'on' && $t->final == 1) ||
                                (isset($_REQUEST["notification_notfinal"]) && $_REQUEST["notification_notfinal"] == 'on' && $t->final != 1) ||
                                (isset($_REQUEST["notification_package"][$v->orderDetails["items"][0]["packageid"]]))
                        ) {
                            if (strlen($v->orderDetails["email"]))
                                $bccList[$v->orderDetails["email"]] = $v->orderDetails["email"];

                            foreach ($t->ticketOptions as $option) {
                                if ($option->displayName == 'Email' && strlen($option->value)) {

                                    $bccList[$option->value] = $option->value;
                                }
                            }
                        }
                    }
                }
            }

            $headers = 'To: ' . $o["messages"]["messageEmailFromName"] . ' <' . $o["messages"]["messageEmailFromEmail"] . '>' . "\r\n";
            $headers = 'From: ' . $o["messages"]["messageEmailFromName"] . ' <' . $o["messages"]["messageEmailFromEmail"] . '>' . "\r\n";
            $headers .= 'Bcc: ' . implode(',', $bccList) . "\r\n";

            //WP -always- quotes so we have to -always- stripslashes
            $note = stripslashes($_POST["attendeeNotificationBody"]);
            $subj = stripslashes($_POST["attendeeNotificationSubj"]);

            wp_mail($o["messages"]["messageEmailFromEmail"], $subj, $note, $headers);

            $msg = array("date" => date_i18n("m/d/Y H:i:s"), "subj" => $subj, "body" => $note);

            $o["messages"]["sentMessages"][] = $msg;

            update_option("eventTicketingSystem", $o);

            echo '<div id="message" class="updated">';
            echo '<p>Notification Sent</p>';
            echo '</div>';
        }
        echo '<div id="ticket_help" class="wrap">';
        echo '<div id="ticket_sales_left">';
        echo '<div id="icon-users" class="icon32"></div><h2>Send Notification to All Attendees</h2>';
        echo '<table class="form-table">';
        echo '<form action="" method="post">';
        echo '<input type="hidden" name="attendeeNotificationNonce" id="attendeeNotificationNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

        echo '<tr valign="top">';
        echo '<th scope="row"><label for="attendeeSubject">To:</label></th>';
        echo '<td>';
        echo '<table>';
        echo '<tr><td>All attendees</td><td><input type="checkbox" name="notification_all"></td>';
        echo '<tr><td>Have filled out info</td><td><input type="checkbox" name="notification_final"></td>';
        echo '<tr><td>Have not filled out info</td><td><input type="checkbox" name="notification_notfinal"></td>';
        foreach ($o['packageProtos'] as $p) {
            echo '<tr><td>' . $p->displayName() . '</td><td><input type="checkbox" name="notification_package[' . $p->packageId . ']"></td>';
        }
        echo '</table>';
        echo '</td>';
        echo '</tr>';

        echo '<tr valign="top">';
        echo '<th scope="row"><label for="attendeeSubject">Subject:</label></th>';
        echo '<td><input name="attendeeNotificationSubj" size="80" type="text" value="Message about ' . $o["messages"]["messageEventName"] . '"></td></tr>';
        echo '<tr valign="top">';
        echo '<th scope="row"><label for="attendeeMessageBody">Message:</th>';
        echo '<td><textarea rows="10" cols="80" name="attendeeNotificationBody"></textarea></td></tr>';
        echo '<tr><td></td><td><input class="button-primary" type="submit" name="submitbutt" value="Send Notification"></td></tr>';
        echo '</form>';
        echo '</table>';

        if (isset($_REQUEST["messageId"]) && is_numeric($_REQUEST["messageId"])) {
            echo '<div class="instructional">';
            echo '<strong>Date: </strong>' . $o["messages"]["sentMessages"][$_REQUEST["messageId"]]["date"] . '<br >';
            echo '<strong>Subject: </strong>' . $o["messages"]["sentMessages"][$_REQUEST["messageId"]]["subj"] . '<br />';
            echo '<strong>Body: </strong>' . $o["messages"]["sentMessages"][$_REQUEST["messageId"]]["body"] . '<br />';
            echo '</div>';
        }

        if (isset($o["messages"]["sentMessages"]) && is_array($o["messages"]["sentMessages"])) {
            echo '<div id="icon-users" class="icon32"></div><h2>Notification History</h2>';
            echo '<table class="widefat">';
            echo '<thead><tr><th>Date Sent</th><th>Subject</th><th>Body</th></tr></thead>';
            echo '<tbody>';
            foreach ($o["messages"]["sentMessages"] as $id => $m) {
                echo '<tr><td><a href="' . admin_url("admin.php?page=ticketnotify&amp;messageId=" . $id) . '">' . $m["date"] . '</a></td><td>' . substr($m["subj"], 0, 40) . '</td><td>' . substr($m["body"], 0, 80) . '</td></tr>';
            }
            echo'</tbody>';
            echo '</table>';
        }

        echo '</div>';
    }

    function getPackages() {
        global $wpdb;
        //echo '<pre>'.print_r($v,true).'</pre>';exit;
        $packages = $wpdb->get_results("select option_value from {$wpdb->options} where option_name like 'package_%'");
        if (is_array($packages)) {
            foreach ($packages as $k => $v) {
                $v = unserialize($v->option_value);
                $p[$v->packageId] = $v;
            }
            return $p;
        }
        return array();
    }

    function getAttendees() {
        global $wpdb;
        $packages = $wpdb->get_results("select option_value from {$wpdb->options} where option_name like 'package_%'");
        if (is_array($packages)) {
            $attendee = array();
            foreach ($packages as $k => $v) {
                $v = unserialize($v->option_value);
                foreach ($v->tickets as $t) {
                    //echo '<pre>'.print_r($v,true).'</pre>';exit;
                    $t->orderDetails = $v->orderDetails;
                    $t->orderDetails["coupon"] = $v->coupon;
                    $attendee[$t->displayName()][] = $t;
                }
            }
            return $attendee;
        }
        return array();
    }

    function getAttendeeArr() {
        $o = get_option("eventTicketingSystem");
        $attendee = eventTicketingSystem::getAttendees();
        if (is_array($attendee)) {
            $tr = $th = array();
            foreach ($attendee as $ticketType => $v) {
                foreach ($v as $ticket) {
                    $trtmp = array();
                    //populate the soldtime stuff in the display array
                    $th[$ticketType]['Sold Time'] = 'Sold Time';
                    $th[$ticketType]['Package Type'] = 'Package Type';
                    $th[$ticketType]['Coupon'] = 'Coupon';

                    $trtmp['Sold Time'] = date_i18n("m/d/Y H:i:s", $ticket->soldTime);
                    if (is_array($ticket->orderDetails["coupon"])) {
                        $trtmp['Package Type'] = $o["packageProtos"][$ticket->orderDetails["coupon"]["packageId"]]->packageName;
                        $trtmp['Coupon'] = $ticket->orderDetails["coupon"]["couponCode"];
                    } else {
                        $trtmp['Package Type'] = $ticket->orderDetails["items"][0]["name"];
                        $trtmp['Coupon'] = '';
                    }

                    foreach ($ticket->ticketOptions as $op) {
                        $th[$ticketType][$op->displayName] = $op->displayName;
                        $trtmp[$op->displayName] = $op->value;
                    }
                    $trtmp["final"] = $ticket->final;
                    $trtmp["orderdetails"] = $ticket->orderDetails;
                    $trtmp["hash"] = $ticket->ticketId;
                    $tr[$ticketType][] = $trtmp;
                }
            }

            return array("tr" => $tr, "th" => $th);
        } else {
            return false;
        }
    }

    function attendeeShortcode($atts) {

        extract(shortcode_atts(array(
                    'sort' => 'Sold Time',
                    'exclude' => ''
                        ), $atts));

        $exclude = explode(',', $exclude);

        // Get attendee data
        if ($tmp = eventTicketingSystem::getAttendeeArr()) {
            ob_start();

            $tr = $tmp["tr"];
            $th = $tmp["th"];

            $cmp = new arbitrarySort($sort);
            foreach ($tr as $k => $v) {
                usort($tr[$k], array($cmp, 'cmp'));
            }

            foreach ($th as $k => $v) {
                $c = 0;
                foreach ($tr[$k] as $data) {
                    if ($data["final"] && !in_array($k, $exclude) && !in_array($data['Package Type'], $exclude)) {
                        $c++;
                        echo '<div class="event-attendee ' . ($c % 2 == 0 ? "even" : "odd") . '">';
                        echo '<div class="attendee-gravatar">' . get_avatar($data['Email'], $size = '96') . '</div>';
                        echo '<div class="attendee-name">' . $data["First Name"] . ' ' . $data["Last Name"] . '</div>';
                        echo strlen($data["Twitter"]) ? '<div class="attendee-twitter"><a href="http://twitter.com/' . str_replace('@', '', $data["Twitter"]) . '">' . (strstr($data["Twitter"], '@') ? '' : '@') . $data["Twitter"] . '</a></div>' : '';
                        echo '</div>';
                    }
                }
            }

            return ob_get_clean();

            // No results
        } else {
            return '';
        }
    }

    function generateAttendeeTable($sort = 'Sold Time') {
        $o = get_option("eventTicketingSystem");
        if (!strlen($sort)) {
            $sort = 'Sold Time';
        }

        // Get attendee data
        if ($tmp = eventTicketingSystem::getAttendeeArr()) {
            $tr = $tmp["tr"];
            $th = $tmp["th"];

            /*
              foreach ($attendee as $ticketType => $v)
              {
              foreach ($v as $ticket)
              {
              $trtmp = array();
              //populate the soldtime stuff in the display array
              $th[$ticketType]['Sold Time'] = 'Sold Time';
              $th[$ticketType]['Package Type'] = 'Package Type';
              $th[$ticketType]['Coupon'] = 'Coupon';

              $trtmp['Sold Time'] = date("m/d/Y H:i:s",$ticket->soldTime);
              if(is_array($ticket->orderDetails["coupon"]))
              {
              $trtmp['Package Type'] = $o["packageProtos"][$ticket->orderDetails["coupon"]["packageId"]]->packageName;
              $trtmp['Coupon'] = $ticket->orderDetails["coupon"]["couponCode"];
              }
              else
              {
              $trtmp['Package Type'] = $ticket->orderDetails["items"][0]["name"];
              $trtmp['Coupon'] = '';
              }

              foreach ($ticket->ticketOptions as $op)
              {
              $th[$ticketType][$op->displayName] = $op->displayName;
              $trtmp[$op->displayName] = $op->value;
              }
              $trtmp["final"] = $ticket->final;
              $trtmp["orderdetails"] = $ticket->orderDetails;
              $trtmp["hash"] = $ticket->ticketId;
              $tr[$ticketType][] = $trtmp;
              }
              }
             */

            $cmp = new arbitrarySort($sort);
            foreach ($tr as $k => $v) {
                usort($tr[$k], array($cmp, 'cmp'));
            }

            echo '<form method="post" action="' . admin_url("admin.php?page=ticketattendeeedit") . '" name="attendeeEdit">
			<input type="hidden" name="attendeeEditNonce" id="attendeeEditNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />
			<input type="hidden" name="del" value="" />
			<input type="hidden" name="edit" value="" />
			<input type="hidden" name="tickethash" value="" />
			</form>';
            foreach ($th as $k => $v) {
                $headerkey = array();

                echo "<table class='widefat'>";
                echo "<thead>";
                echo '<tr><th style="text-align: center;" colspan="' . (count($v) + 1) . '">' . $k . '</th></tr>';
                echo "<tr>";
                echo '<th>&nbsp;</th>';
                foreach ($v as $header) {
                    $headerkey[] = $header;
                    echo '<th><a href="' . admin_url("admin.php?page=ticketattendeeedit&amp;attendeesort=" . urlencode($header)) . '">' . $header . '</a></th>';
                }
                echo "</tr>";
                echo "</thead>";
                echo '<tbody>';
                $c = 0;
                $csv = implode(',', $headerkey) . "\n";
                foreach ($tr[$k] as $data) {
                    $c++;
                    if (!$data["final"]) {
                        echo '<tr style="background-color:LightPink;">';
                        echo '<td><a href="' . ($o["registrationPermalink"] . (strstr($o["registrationPermalink"], '?') ? '&amp;' : '?') . 'tickethash=' . $data["hash"]) . '">Link</a>&nbsp;|&nbsp;<a href="#" onclick="javascript:document.attendeeEdit.edit.value=\'1\';document.attendeeEdit.tickethash.value=\'' . $data["hash"] . '\';document.attendeeEdit.submit();return false;">Edit</a>&nbsp;|&nbsp;<a href="#" onclick="javascript:if (confirm(\'Are you sure you want to delete this ticket?\')) {document.attendeeEdit.del.value=\'1\';document.attendeeEdit.tickethash.value=\'' . $data["hash"] . '\';document.attendeeEdit.submit();return false;}">Delete</a></td>';
                        echo '<td>' . $data["Sold Time"] . '</td>';
                        echo '<td>' . $data["Package Type"] . '</td>';
                        echo '<td>' . $data["Coupon"] . '</td>';
                        echo '<td colspan="' . count($headerkey) . '">' . (is_array($data["orderdetails"]) ? $data["orderdetails"]["name"] . ': ' . $data["orderdetails"]["email"] : "") . '</td>';
                        //echo '<pre>'.print_r($data,true).'</pre>';exit;
                        if (!strlen($data["Email"])) {
                            $tcsv = array($data["Sold Time"], $data["orderdetails"]["name"], $data["orderdetails"]["email"]);
                            $csv .= implode(',', $tcsv) . "\n";
                        } else {
                            $tcsv = '';
                            foreach ($headerkey as $key) {
                                if (is_array($data[$key])) {
                                    $tcsv .= '"' . implode($data[$key], ',') . '",';
                                } else {
                                    $tcsv .= '"' . $data[$key] . '",';
                                }
                            }
                            $csv .= substr($tcsv, 0, -1) . "\n";
                        }
                    } else {
                        echo '<tr>';
                        echo '<td><a href="' . ($o["registrationPermalink"] . (strstr($o["registrationPermalink"], '?') ? '&amp;' : '?') . 'tickethash=' . $data["hash"]) . '">Link</a>&nbsp;|&nbsp;<a href="#" onclick="javascript:document.attendeeEdit.edit.value=\'1\';document.attendeeEdit.tickethash.value=\'' . $data["hash"] . '\';document.attendeeEdit.submit();return false;">Edit</a>&nbsp;|&nbsp;<a href="#" onclick="javascript:if (confirm(\'Are you sure you want to delete this ticket?\')) {document.attendeeEdit.del.value=\'1\';document.attendeeEdit.tickethash.value=\'' . $data["hash"] . '\';document.attendeeEdit.submit();return false;}">Delete</a></td>';
                        //echo '</td>';
                        $tcsv = '';
                        foreach ($headerkey as $key) {
                            if (is_array($data[$key])) {
                                echo '<td>' . implode($data[$key], ',') . '</td>';
                                $tcsv .= '"' . implode($data[$key], ',') . '",';
                            } else {
                                echo '<td>' . (strlen($data[$key]) ? $data[$key] : "&nbsp;") . '</td>';
                                $tcsv .= '"' . $data[$key] . '",';
                            }
                        }
                        $csv .= substr($tcsv, 0, -1) . "\n";
                    }
                    echo '</tr>';
                }
                echo '<tr><td colspan="' . count($headerkey) . '">';
                echo '<form action="" method="post">
            	<input type="hidden" name="exportAttendeeNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />
				<input type="hidden" name="attendeeCsv" value="' . base64_encode($csv) . '" />
				<input type="submit" class="button-primary" name="submit" value="Export Attendee List" />
				</form>';
                echo '</td></tr>';
                echo '</tbody>';
                echo '</table><br /><br />';
            }
        }
    }

    function ticketReporting() {
        global $wpdb;
        $o = get_option("eventTicketingSystem");

        $packages = $wpdb->get_results("select option_value from {$wpdb->options} where option_name like 'package_%'");
        if (is_array($packages)) {
            $package = array();
            foreach ($packages as $k => $v) {
                $v = unserialize($v->option_value);
                $package[$v->displayName()]['count']++;
                $package[$v->displayName()]['money'] += $v->price;

                if (is_array($v->coupon)) {
                    switch ($v->coupon["type"]) {
                        case 'percent':
                            $discounted += $o["packageProtos"][$v->coupon["packageId"]]->price - ($o["packageProtos"][$v->coupon["packageId"]]->price * (1 - $v->coupon["amt"] / 100));
                            break;
                        case 'flat':
                            $discounted += $o["packageProtos"][$v->coupon["packageId"]]->price - $v->coupon["amt"];
                            break;
                    }
                }
                foreach ($v->tickets as $t) {
                    $t->orderDetails = $v->orderDetails;
                    $ticket[$t->displayName()]['count']++;
                    $attendee[$t->displayName()][] = $t;
                    //echo '<pre>'.print_r($t,true).'</pre>';exit;
                }
            }
        }

        $pTotal = $cTotal = 0;


        echo '<div id="ticket_help" class="wrap">';
        echo '<div id="ticket_sales_left">';
        echo '<div id="icon-users" class="icon32"></div><h2>Event Ticketing</h2>';
        //counts table
        echo "<table class='widefat'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Package</th>";
        echo "<th>Sold</th>";
        echo "<th>Revenue</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        if (is_array($package)) {
            $total = 0;
            foreach ($package as $k => $v) {
                $total += $v["money"];
                echo "<tr>";
                echo '<td>' . $k . '</td>';
                echo '<td>' . $v["count"] . '</td>';
                echo '<td>' . eventTicketingSystem::currencyFormat($v["money"]) . '</td>';
                echo "</tr>";
            }
            $pTotal = $total;
            echo '<tr><td><strong>Total Package Revenue</strong><br />(minus coupon discounts)</td><td>&nbsp;</td><td><strong>' . eventTicketingSystem::currencyFormat($pTotal) . '</strong></td></tr>';
        }
        echo "</tbody>";

        if ($discounted) {
            echo "<thead>";
            echo "<tr>";
            echo "<th>Coupon</th>";
            echo "<th>&nbsp;</th>";
            echo "<th>Discounted</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            echo '<tr><td><strong>Total Coupon Discounts</strong></td><td>&nbsp;</td><td><strong>' . eventTicketingSystem::currencyFormat($discounted) . '</strong></td></tr>';
            echo "</tbody>";
        }
        echo "<thead>";
        echo "<tr>";
        echo "<th>Ticket</th>";
        echo "<th>Sold</th>";
        echo "<th>Remaining</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        if (is_array($ticket)) {
            $total = 0;
            foreach ($ticket as $k => $v) {
                $total += $v["count"];
                echo "<tr>";
                echo '<td>' . $k . '</td>';
                echo '<td>' . $v["count"] . '</td>';
                echo '<td>&nbsp;</td>';
                echo "</tr>";
            }
            echo "<tr>";
            echo '<td>Total</td>';
            echo '<td>' . $total . '</td>';
            echo '<td>' . ($o["eventAttendance"] - $total) . '</td>';
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        //end counts table
        echo '</div>';
        echo '<div id="attendeeGraph">';
        echo '<img src="http://chart.apis.google.com/chart?chs=300x150&cht=p3&chd=t:' . number_format($total / 1000, 3) . ',' . number_format(($o["eventAttendance"] - $total) / 1000, 3) . '&chdl=Sold|Left&chp=0.628&chl=' . $total . '|' . ($o["eventAttendance"] - $total) . '&chtt=Attendance">';
        echo '</div>';

        if (is_array($attendee)) {
            foreach ($attendee as $ticketType => $v) {
                //filthy hack to display ticket info quickly
                //should be moved into ticket and ticketOption objects

                usort($v, array("eventTicketingSystem", "ticketCmp"));

                foreach ($v as $ticket) {
                    $trtmp = array();
                    //populate the soldtime stuff in the display array
                    $th[$ticketType]['Sold Time'] = 'Sold Time';
                    $trtmp['Sold Time'] = date_i18n("m/d/Y H:i:s", $ticket->soldTime);

                    foreach ($ticket->ticketOptions as $to) {
                        $th[$ticketType][$to->displayName] = $to->displayName;
                        $trtmp[$to->displayName] = $to->value;
                    }
                    $trtmp["final"] = $ticket->final;
                    $trtmp["orderdetails"] = $ticket->orderDetails;
                    $trtmp["hash"] = $ticket->ticketId;
                    $tr[] = $trtmp;
                }
            }
            echo '<div id="summary_reports_left">';
            echo '<div id="icon-profile" class="icon32"></div><h2>Summary Reports</h2>';
            foreach ($th as $hk => $hv) {
                foreach ($hv as $kk => $vv) {
                    if ($kk == 'Sold Time')
                        continue;
                    $summaryType[$kk] = 1;
                }
            }
            if (strlen($_GET["summary"])) {
                $s = urldecode($_GET["summary"]);
                echo '<table class="widefat">';
                echo '<thead>';
                echo '<tr><th>' . $s . '</th><th>Count</th></tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($tr as $to) {
                    if (is_array($to[$s])) {
                        foreach ($to[$s] as $index)
                            $summary[$index]++;
                    } else {
                        $summary[$to[$s]]++;
                    }
                }
                ksort($summary);
                foreach ($summary as $op => $count) {
                    echo '<tr><td>' . (strlen($op) ? $op : "No reponse") . '</td><td>' . $count . '</td></tr>';
                }
                echo '</tbody>';
                echo '</table>';
            }
            echo '<table class="widefat">';
            echo '<thead>';
            echo '<tr><th>Summarize by...</th></tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($summaryType as $kk => $vv) {
                echo '<tr><td><a href="' . admin_url("admin.php?page=eventticketing&amp;summary=" . urlencode($kk)) . '">' . $kk . '</a></td></tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        }


        echo '</div>';
    }

    function ticketOptionsControl() {
        //echo "<pre>";print_r($_REQUEST); echo "</pre>";
        $o = get_option("eventTicketingSystem");
        if (isset($_POST['ticketOptionAddNonce']) && wp_verify_nonce($_POST['ticketOptionAddNonce'], plugin_basename(__FILE__))) {
            $_REQUEST = array_map('stripslashes_deep', $_REQUEST);

            if (is_numeric($_REQUEST["edit"])) {
                $ticketOption = $o["ticketOptions"][$_REQUEST["edit"]];
            } elseif (is_numeric($_REQUEST["del"])) {
                unset($o["ticketOptions"][$_REQUEST["del"]]);
                update_option("eventTicketingSystem", $o);
                $ticketOption = new ticketOption();
                echo '<div id="message" class="updated"><p>Option deleted</p></div>';
            } else {
                if (is_numeric($_REQUEST["update"])) {
                    unset($o["ticketOptions"][$_REQUEST["update"]]);
                    $nextId = $_REQUEST["update"];
                } else {
                    $nextId = ((int) max(array_keys($o["ticketOptions"]))) + 1;
                }
                if ($_REQUEST["ticketOptionDisplayType"] != "dropdown" && $_REQUEST["ticketOptionDisplayType"] != "multidropdown") {
                    $_REQUEST["ticketOptionDrop"] = NULL;
                }

                $o["ticketOptions"][$nextId] = new ticketOption($_REQUEST["ticketOptionDisplay"], $_REQUEST["ticketOptionDisplayType"], $_REQUEST["ticketOptionDrop"]);
                $o["ticketOptions"][$nextId]->setOptionId($nextId);
                update_option("eventTicketingSystem", $o);
                echo '<div id="message" class="updated"><p>Option saved</p></div>';

                $ticketOption = new ticketOption();
            }
        } else {
            $ticketOption = new ticketOption();
        }
        echo "<div id='ticket_wrapper_1'>";
        echo "<div id='ticket_options'>";
        echo "<div class='wrap'>";
        echo "<div id='icon-users' class='icon32'></div><h2>Options</h2>";
        if (is_array($o["ticketOptions"])) {

            echo "<table class='widefat'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Ticket Options</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($o["ticketOptions"] as $k => $v) {
                echo "<tr>";
                echo '<td>' . $v->displayName . '</td>';
                echo '<td><a href="#" onclick="javascript:document.ticketOptionAdd.update.value=\'\';document.ticketOptionAdd.del.value=\'\';document.ticketOptionAdd.edit.value=\'' . $v->optionId . '\';document.ticketOptionAdd.submit();return false;">Edit</a>&nbsp;|&nbsp;<a href="#" onclick="javascript:if (confirm(\'Are you sure you want to delete this option?\')) {document.ticketOptionAdd.update.value=\'\';document.ticketOptionAdd.edit.value=\'\';document.ticketOptionAdd.del.value=\'' . $v->optionId . '\';document.ticketOptionAdd.submit();return false;}">Delete</a></td>';
            }

            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
            echo '</div><div class="instructional">Create all the options you want for each ticket. This is the information you would ask of each person attending your event such as personal info (name/address/phone) or shirt size or meal preference.</div></div>';
        }

        echo "<div id='ticket_new_options'>";
        echo '<form method="post" action="" name="ticketOptionAdd">
            <input type="hidden" name="ticketOptionAddNonce" id="ticketOptionAddNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />
			<input type="hidden" name="del" value="" />
			<input type="hidden" name="edit" value="" />
			<input type="hidden" name="update" value="' . $ticketOption->optionId . '" />
			
			<div class="wrap">
			<div id="icon-users" class="icon32"></div><h2>Create New Option</h2>
			</div>
			<div id="inputTicketOptionDisplay">
				Display Name: <input type="text" name="ticketOptionDisplay" value="' . $ticketOption->displayName . '">
			</div>
			<div id="inputTicketOptionDisplayType">
				Option Type: <select name="ticketOptionDisplayType" id="ticketoptionselect">
				<option value="text" ' . ($ticketOption->displayType == "text" ? "SELECTED" : "") . '>Text Input</option>
				<option value="dropdown" ' . ($ticketOption->displayType == "dropdown" ? "SELECTED" : "") . '>Dropdown</option>
				<option value="multidropdown" ' . ($ticketOption->displayType == "multidropdown" ? "SELECTED" : "") . '>Multi Select</option>
				</select>
			</div>';
        echo '<div id="optionvalsdiv">';
        if (is_array($ticketOption->options) && !empty($ticketOption->options)) {
            $c = 0;
            foreach ($ticketOption->options as $option) {
                $c++;
                echo '<div id="input' . $c . '" style="margin-bottom:4px;" class="clonedInput">';
                echo 'Value: <input type="text" name="ticketOptionDrop[' . $c . ']" id="ticketOptionDrop' . $c . '" value="' . $option . '"/>';
                echo '</div>';
            }
        } else {
            echo '<div id="input1" style="margin-bottom:4px;" class="clonedInput">';
            echo 'Value: <input type="text" name="ticketOptionDrop[1]" id="ticketOptionDrop1" />';
            echo '</div>';
        }

        echo '<div>
			<p class="submit"><input type="button" id="btnAdd" value="add another option value" />
			<input type="button" id="btnDel" value="remove last option value" /></p>
		</div></div>';
        if (isset($_REQUEST["edit"]) && is_numeric($_REQUEST["edit"]) && is_numeric($ticketOption->optionId)) {
            echo '<div>
				<p class="submit"><input type="submit" class="button-primary" name="submitbutt" value="Update Ticket Option: ' . $ticketOption->displayName . '"></p>
			</div>';
        } else {
            echo '<div>
				<input type="submit" class="button-primary" name="submitbutt" value="Add Ticket Option">
			</div>';
        }
        echo '</form>';
        echo '</div>';
        echo '</div>';
    }

    function ticketTicketsControl() {
        //echo "<pre>";print_r($_REQUEST); echo "</pre>";
        $o = get_option("eventTicketingSystem");

        if (isset($_POST['ticketOptionAddToTicketNonce']) && wp_verify_nonce($_POST['ticketOptionAddToTicketNonce'], plugin_basename(__FILE__))) {
            if (is_numeric($_REQUEST["ticketId"])) {
                if (is_numeric($_REQUEST["add"])) {
                    $o["ticketProtos"][$_REQUEST["ticketId"]]->addOption($o["ticketOptions"][$_REQUEST["add"]]);
                    update_option("eventTicketingSystem", $o);
                } elseif (is_numeric($_REQUEST["del"])) {
                    $o["ticketProtos"][$_REQUEST["ticketId"]]->delOption($_REQUEST["del"]);
                    update_option("eventTicketingSystem", $o);
                }

                $ticketProto = $o["ticketProtos"][$_REQUEST["ticketId"]];
            }
        }

        if (isset($_POST['ticketEditNonce']) && wp_verify_nonce($_POST['ticketEditNonce'], plugin_basename(__FILE__))) {
            if ($_REQUEST["add"] == 1) {
                if (!strlen($_REQUEST["ticketDisplayName"])) {
                    echo '<div id="message" class="error"><p>Ticket name cannot be blank</p></div>';
                } else {
                    if (is_array($o["ticketProtos"]) && !empty($o["ticketProtos"])) {
                        $nextId = ((int) max(array_keys($o["ticketProtos"]))) + 1;
                    } else {
                        $nextId = 0;
                    }

                    $o["ticketProtos"][$nextId] = new ticket();
                    $o["ticketProtos"][$nextId]->setTicketId($nextId);
                    $o["ticketProtos"][$nextId]->setDisplayName($_REQUEST["ticketDisplayName"]);

                    update_option("eventTicketingSystem", $o);

                    $ticketProto = $o["ticketProtos"][$nextId];

                    echo '<div id="message" class="updated"><p>Ticket added</p></div>';
                }
            } elseif (is_numeric($_REQUEST["del"])) {
                unset($o["ticketProtos"][$_REQUEST["del"]]);
                update_option("eventTicketingSystem", $o);
                echo '<div id="message" class="updated"><p>Ticket deleted</p></div>';
            } elseif (is_numeric($_REQUEST["edit"])) {
                $ticketProto = $o["ticketProtos"][$_REQUEST["edit"]];
            } elseif (is_numeric($_REQUEST["update"])) {
                $o["ticketProtos"][$_REQUEST["update"]]->setDisplayName($_REQUEST["ticketDisplayName"]);
                update_option("eventTicketingSystem", $o);
                echo '<div id="message" class="updated"><p>Ticket updated</p></div>';
            }
        }

        echo "<div id='ticket_wrapper_2'>";
        echo "<div id='ticket_holder_left'>";
        echo "<div class='wrap'>";
        echo "<div id='icon-users' class='icon32'></div><h2>Tickets</h2>";

        if (is_array($o["ticketProtos"])) {

            echo "<table class='widefat'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Existing Tickets</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($o["ticketProtos"] as $k => $v) {
                echo "<tr>";
                echo '<td>' . $v->displayName() . '</td>';
                echo '<td><a href="#" onclick="javascript:document.ticketEdit.edit.value=\'' . $v->ticketId . '\'; document.ticketEdit.submit();return false;">Edit</a>&nbsp;|&nbsp;<a href="#" onclick="javascript:if (confirm(\'Are you sure you want to delete this ticket? THIS CANNOT BE UNDONE\')) {document.ticketEdit.del.value=\'' . $v->ticketId . '\';document.ticketEdit.submit();return false;}">Delete</a>';
            }

            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
        }
        echo "</div></div>";
        echo "<div id='ticket_holder_right'>";
        echo '<form method="post" action="" name="ticketOptionAddToTicket">
		<input type="hidden" name="ticketOptionAddToTicketNonce" id="ticketOptionAddToTicketNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />
		<input type="hidden" name="ticketId" value="' . $ticketProto->ticketId . '" />
		<input type="hidden" name="add" value="" />
		<input type="hidden" name="del" value="" />
		</form>';
        echo '<form method="post" action="" name="ticketEdit">
		<input type="hidden" name="ticketEditNonce" id="ticketEditNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />
		<input type="hidden" name="update" value="' . $ticketProto->ticketId . '">
		<input type="hidden" name="add" value="" />
		<input type="hidden" name="edit" value="" />
		<input type="hidden" name="del" value="" />';
        if (is_array($o["ticketOptions"]) && is_numeric($ticketProto->ticketId)) {

            echo "<div class='wrap'>";
            echo "<div id='icon-users' class='icon32'></div><h2>Ticket Options</h2>";
            echo "</div>";
            echo "<table class='widefat'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Ticket Options</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($o["ticketOptions"] as $k => $v) {
                echo "<tr>";
                echo '<td>' . $v->displayName . '</td>';
                echo '<td><a href="#" onclick="javascript:document.ticketOptionAddToTicket.add.value=\'' . $v->optionId . '\'; document.ticketOptionAddToTicket.submit();return false;">Add To New Ticket Below</a></td>';
            }

            echo "</tr>";
            echo "</tbody>";
            echo "</table>";

            $ticketProto->displayForm();
            echo '<div><input type="submit" class="button-primary" name="submitbutt" value="Save Ticket"></div>';
        } else {
            echo '<div class="wrap"><h2>Create New Ticket</h2></div>';
            echo 'Ticket Name: <input type="text" name="ticketDisplayName" value="">';
            echo '<br /><br /><a href="#" class="button-primary" onclick="javascript:document.ticketEdit.add.value=\'1\'; document.ticketEdit.submit();return false;">Add New Ticket</a>';
        }
        echo "</div>";
        echo '</form>';
        echo '</div><div class="instructional">Create a ticket and attach the options you want for that ticket. <strong>Add the options in the order you want them displayed on the form when someone purchases a ticket</strong><p style="font-style: italic;"><strong>Example:</strong> Create two types of tickets which are the same where one doesn\'t ask for shirt size since a shirt isn\'t included</div></div>';
    }

    function ticketPackagesControl() {
        //echo "<pre>";print_r($_REQUEST); echo "</pre>";
        $o = get_option("eventTicketingSystem");

        if (isset($_POST["ticketAddToPackageNonce"]) && wp_verify_nonce($_POST['ticketAddToPackageNonce'], plugin_basename(__FILE__))) {
            if (is_numeric($_REQUEST["packageId"])) {
                if (is_numeric($_REQUEST["add"])) {
                    $o["packageProtos"][$_REQUEST["packageId"]]->addTicket($o["ticketProtos"][$_REQUEST["add"]]);
                    update_option("eventTicketingSystem", $o);
                } elseif (is_numeric($_REQUEST["del"])) {
                    $o["packageProtos"][$_REQUEST["packageId"]]->delTicket($_REQUEST["del"]);
                    update_option("eventTicketingSystem", $o);
                    echo '<div id="message" class="updated"><p>Package updated</p></div>';
                }

                $packageProto = $o["packageProtos"][$_REQUEST["packageId"]];
            }
        }

        if (isset($_POST["packageEditNonce"]) && wp_verify_nonce($_POST['packageEditNonce'], plugin_basename(__FILE__))) {
            if ($_REQUEST["add"] == 1) {
                if (is_array($o["packageProtos"]) && !empty($o["packageProtos"])) {
                    $nextId = ((int) max(array_keys($o["packageProtos"]))) + 1;
                } else {
                    $nextId = 0;
                }

                $o["packageProtos"][$nextId] = new package();
                $o["packageProtos"][$nextId]->setPackageId($nextId);
                update_option("eventTicketingSystem", $o);
                echo '<div id="message" class="updated"><p>New package has been added</p></div>';

                $packageProto = $o["packageProtos"][$nextId];
            } elseif (is_numeric($_REQUEST["activate"])) {
                if ($o["packageProtos"][$_REQUEST["activate"]]->active === false) {
                    $o["packageProtos"][$_REQUEST["activate"]]->setActive(true);
                } else {
                    $o["packageProtos"][$_REQUEST["activate"]]->setActive(false);
                }
                update_option("eventTicketingSystem", $o);
            } elseif (is_numeric($_REQUEST["del"])) {
                unset($o["packageProtos"][$_REQUEST["del"]]);
                update_option("eventTicketingSystem", $o);
                echo '<div id="message" class="updated"><p>Package deleted</p></div>';
            } elseif (is_numeric($_REQUEST["edit"])) {
                $packageProto = $o["packageProtos"][$_REQUEST["edit"]];
            } elseif (is_numeric($_REQUEST["update"])) {
                $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
                $o["packageProtos"][$_REQUEST["update"]]->setDisplayName($_REQUEST["packageDisplayName"]);
                $o["packageProtos"][$_REQUEST["update"]]->setExpire(array("start" => $_REQUEST["packageExpireStart"], "end" => $_REQUEST["packageExpireEnd"]));
                $o["packageProtos"][$_REQUEST["update"]]->setPackagePrice($_REQUEST["packagePrice"]);
                $o["packageProtos"][$_REQUEST["update"]]->setTicketQuantity($_REQUEST["packageTicketQuantity"] < 1 ? 1 : $_REQUEST["packageTicketQuantity"]);
                $o["packageProtos"][$_REQUEST["update"]]->setPackageQuantity($_REQUEST["packageQuantity"]);
                $o["packageProtos"][$_REQUEST["update"]]->setPackageDescription($_REQUEST["packageDescription"]);

                update_option("eventTicketingSystem", $o);
                echo '<div id="message" class="updated"><p>Package updated</p></div>';
            }
        }
        echo "<div id='ticket_wrapper_2'>";
        echo "<div id='ticket_holder_left'>";
        echo "<div class='wrap'>";
        echo "<div id='icon-users' class='icon32'></div><h2>Packages</h2>";
        if (is_array($o["packageProtos"])) {

            echo "<table class='widefat'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Existing Packages</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($o["packageProtos"] as $k => $v) {
                echo '<tr ' . ($v->active === false ? "style=\"background-color:LightPink;\"" : "") . '>';
                echo '<td>' . $v->displayName() . '</td>';
                echo '<td><a href="#" onclick="javascript:document.packageEdit.edit.value=\'' . $v->packageId . '\'; document.packageEdit.submit();return false;">Edit</a>&nbsp;|&nbsp;<a href="#" onclick="javascript:if (confirm(\'Are you sure you want to delete this package? THIS CANNOT BE UNDONE\')) {document.packageEdit.del.value=\'' . $v->packageId . '\';document.packageEdit.submit();return false;}">Delete</a>';
                echo '&nbsp;|&nbsp;<a href="#" onclick="javascript:document.packageEdit.activate.value=\'' . $v->packageId . '\'; document.packageEdit.submit();return false;">' . ($v->active === false ? 'Activate' : 'Deactivate') . '</a>';
                echo '</td>';
            }

            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
        }
        echo "</div></div>";
        echo "<div id='ticket_holder_right'>";
        echo '<form method="post" action="" name="ticketAddToPackage">
		<input type="hidden" name="ticketAddToPackageNonce" id="ticketAddToPackageNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />
		<input type="hidden" name="packageId" value="' . (isset($packageProto->packageId) ? $packageProto->packageId : "") . '" />
		<input type="hidden" name="add" value="" />
		<input type="hidden" name="del" value="" />
		</form>';
        echo '<form method="post" action="" name="packageEdit">
		<input type="hidden" name="packageEditNonce" id="packageEditNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />
		<input type="hidden" name="update" value="' . (isset($packageProto->packageId) ? $packageProto->packageId : "") . '">
		<input type="hidden" name="add" value="" />
		<input type="hidden" name="edit" value="" />
		<input type="hidden" name="activate" value="" />
		<input type="hidden" name="del" value="" />';
        if (is_array($o["ticketProtos"]) && isset($packageProto->packageId) && is_numeric($packageProto->packageId)) {
            if (empty($packageProto->tickets)) {
                echo '<div class="wrap"><h2>Pick the type of ticket for this package</h2></div>';

                echo "<table class='widefat'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Existing Tickets</th>";
                echo "<th>Actions</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($o["ticketProtos"] as $k => $v) {
                    echo "<tr>";
                    echo '<td>' . $v->displayName() . '</td>';
                    echo '<td><a href="#" onclick="javascript:document.ticketAddToPackage.add.value=\'' . $v->ticketId . '\'; document.ticketAddToPackage.submit();return false;">Add Ticket To Package and set options</a></td>';
                }

                echo "</tr>";
                echo "</tbody>";
                echo "</table>";
            } else {
                $packageProto->displayForm();

                echo '<input type="submit" class="button-primary" name="submitbutt" value="Save Package">';
            }
        } else {
            echo '<div class="wrap"><h2>Create New Package</h2></div><br /><a href="#" class="button-primary" onclick="javascript:document.packageEdit.add.value=\'1\'; document.packageEdit.submit();return false;">Add New Package</a>';
        }

        echo '</form>';
        echo '</div><div class="instructional">Create a package and attach a ticket to it. This is where you determine the price for the event.<p style="font-style: italic;"><strong>Example:</strong> You have defined a single standard ticket called Regular. Attach Regular to the package with a quantity of 1, give it a price which is $10 less than full admission and give it an active date which will end a month before the event and a quantity of 50. With this you have created an early bird ticket which will expire either a month before the event occurs or when 50 of them are sold, whichever comes first<p style="font-style: italic;"><strong>Example:</strong> Create a package and attach Regular to the package with a quantity of 4 and give this package a price of $500. This would be like a sponsorship package where you are bundling some free tickets to go along with a sponsorship</div>';
        echo '</div>';
    }

    function ticketEventsControl() {
        //echo "<pre>";print_r($_REQUEST); echo "</pre>";
        $o = get_option("eventTicketingSystem");
        echo '<div id="ticket_events">';
        echo "<div class='wrap'>";
    }

    function ticketCouponsControl() {
        $o = get_option("eventTicketingSystem");

        if (isset($_POST['couponEditNonce']) && wp_verify_nonce($_POST['couponEditNonce'], plugin_basename(__FILE__))) {
            //echo '<pre>'.print_r($_REQUEST,true).'</pre>';
            if (isset($_REQUEST["coupon"]) && is_array($_REQUEST["coupon"])) {
                $saved = 0;
                foreach ($_REQUEST["coupon"] as $k => $v) {
                    if (is_numeric($v["packageId"]) && strlen($v["couponCode"]) && is_numeric($v["couponAmount"])) {
                        //this happens when a coupon is edited and the coupon code is changed
                        //this way we don't have a new coupon added when a code is changed
                        if (isset($_REQUEST["update"]) && isset($o["coupons"][$_REQUEST["update"]]) && $_REQUEST["update"] != $v["couponCode"]) {
                            unset($o["coupons"][$_REQUEST["update"]]);
                        }

                        $o["coupons"][$v["couponCode"]] = array("couponCode" => $v["couponCode"], "packageId" => $v["packageId"], "uses" => $v["couponUses"], "type" => $v["couponType"], "amt" => $v["couponAmount"], "used" => $v["couponUsed"]);
                        $saved++;
                    }
                }
                if ($saved) {
                    update_option("eventTicketingSystem", $o);
                    echo '<div id="message" class="updated"><p>' . $saved . ' coupons saved.</p></div>';
                }
            }
            if ($_REQUEST["add"] == 1) {
                for ($i = 1; $i < 11; $i++) {
                    $coupon[] = array("couponCode" => '', "packageId" => '', "used" => '', "type" => '', "amt" => '', "uses" => '');
                }
            }
            if (strlen($_REQUEST["edit"])) {
                $coupon[] = $o["coupons"][$_REQUEST["edit"]];
            }
            if (strlen($_REQUEST["del"])) {
                unset($o["coupons"][$_REQUEST["del"]]);
                update_option("eventTicketingSystem", $o);
            }
        }

        echo "<div id='ticket_wrapper_2'>";
        echo "<div id='ticket_holder_left'>";
        echo "<div class='wrap'>";
        echo "<div id='icon-users' class='icon32'></div><h2>Coupons</h2>";
        if (isset($o["coupons"]) && is_array($o["coupons"])) {
            echo "<table class='widefat'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Existing Coupons</th>";
            echo "<th>For Package</th>";
            echo "<th>Type</th>";
            echo "<th>Uses Left</th>";
            echo "<th>Used</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($o["coupons"] as $couponid => $v) {
                echo "<tr>";
                echo '<td>' . $v["couponCode"] . '</td>';
                echo '<td>' . ($o["packageProtos"][$v["packageId"]] ? $o["packageProtos"][$v["packageId"]]->displayName() : "<span style=\"background-color:LightPink;\">Invalid Coupon</span>" ) . '</td>';
                echo '<td>' . ($v["type"] == "flat" ? eventTicketingSystem::getCurrencySymbol() . $v["amt"] : $v["amt"] . "%") . '</td>';
                echo '<td>' . $v["uses"] . '</td>';
                echo '<td>' . $v["used"] . '</td>';
                echo '<td><a href="#" onclick="javascript:document.couponEdit.edit.value=\'' . $v["couponCode"] . '\'; document.couponEdit.submit();return false;">Edit</a>&nbsp;|&nbsp;<a href="#" onclick="javascript:if (confirm(\'Are you sure you want to delete this coupon\')) {document.couponEdit.del.value=\'' . $v["couponCode"] . '\';document.couponEdit.submit();return false;}">Delete</a></td>';
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        }
        echo "</div>";
        echo "</div>";
        echo "<div id='ticket_holder_right'>";
        echo '<form method="post" action="" name="couponEdit">
		<input type="hidden" name="couponEditNonce" id="couponEditNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />
		<input type="hidden" name="add" value="" />
		<input type="hidden" name="edit" value="" />
		<input type="hidden" name="del" value="" />';
        if (isset($_REQUEST["edit"]) && strlen($_REQUEST["edit"])) {
            echo '<input type="hidden" name="update" value="' . $_REQUEST["edit"] . '" />';
        }
        if (isset($coupon)) {
            if (strlen($coupon[0]["couponCode"])) {
                echo '<div class="wrap"><h2>Update Coupon</h2></div>';
            } else {
                echo '<div class="wrap"><h2>Add Coupons</h2></div>';
            }

            echo '<table class="form-table">';
            echo '<thead>';
            echo '<tr><th>Code</th><th>Package</th><th>Type</th><th>Amount</th><th>Uses</th></tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($coupon as $ck => $cv) {
                echo '<tr>';
                echo '<td><input name="coupon[' . $ck . '][couponCode]" value="' . $cv["couponCode"] . '"></td>';
                echo '<td>
					<select name="coupon[' . $ck . '][packageId]">';
                foreach ($o["packageProtos"] as $pk => $pv) {
                    echo '<option value="' . $pk . '" ' . ($cv["packageId"] == $pk ? "selected" : "") . '>' . $pv->displayName() . '</option>';
                }
                echo '</select></td>';
                echo '<td><select name="coupon[' . $ck . '][couponType]"><option value="flat" ' . ($cv["type"] == "flat" ? "selected" : "") . '>Flat Rate</option><option value="percent" ' . ($cv["type"] == "percent" ? "selected" : "") . '>Percentage</option></select></td>';
                echo '<td><input name="coupon[' . $ck . '][couponAmount]" value="' . $cv["amt"] . '" size="5"></td>';
                echo '<td><input name="coupon[' . $ck . '][couponUses]" size="2" value="' . ($cv["uses"] ? $cv["uses"] : 1) . '"></td>';
                echo '<input type="hidden" name="coupon[' . $ck . '][couponUsed]" value="' . (int) $cv["used"] . '">';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '<tr><td colspan="5"><input type="submit" class="button" name="submitbutt" value="Save Coupon"></td></tr>';
            echo '</table>';
        } else {
            echo '<div class="wrap"><h2>Create New Coupon</h2></div><br /><a href="#" class="button-primary" onclick="javascript:document.couponEdit.add.value=\'1\'; document.couponEdit.submit();return false;">Add New Coupon</a>';
        }
        echo '</form>';
        echo '</div><div class="instructional">Coupons are codes you will create to give a discounted or free ticket to the event. Owe someone a favor or have them pay you by check? Just generate a coupon and welcome them to the event!</div>';
        echo '</div>';
    }

    function showRegistrationForm( $o ) {
        require_once( WPEVT_DIR . '/views/ticketform.php' );
    }
    
    function showTicket( $ticket_id ) {
        $ticket = get_page_by_title( $ticket_id, OBJECT, 'wpevt_purchase' );
        $ticket = unserialize( $ticket->post_content );
        
        require_once( WPEVT_DIR . '/views/showticket.php' );
    }
    
    
    function shortcode() {
        /*
         * Here is how the flow should happen, though it may not be right now...
         * - If ticket registration not enabled tell user and end function
         * - Check to see if user has submitted request for tickets
         * --- Yes - load payment gateway payment processor
         * - Was payment processed?
         * --- Show form requesting the remaining ticket details from the user
         * - Otherwise show the ticket form 
         * 
         * Ben Lobaugh - 10-17-2012
         */
        
        // WPEVT configuration settings
        $o = get_option("eventTicketingSystem");
        
        $wpevt = WPEVT::instance();

        
        /*
         * Should not this be set already in settings? Pretty sure this is not
         * necessary to have on the front side of things. 
         * 
         * Moving into ticketSettings
         * 
         * Ben Lobaugh - 10-17-2012
         */
//        if (!isset($o["registrationPermalink"]) || (isset($o["registrationPermalink"]) && $o["registrationPermalink"] != get_permalink())) {
//            $o["registrationPermalink"] = get_permalink();
//            update_option("eventTicketingSystem", $o);
//        }
        
        
        
        /*
         * Verify that ticket registration is enabled. If not alert the user
         * and bail out
         */
        if (!isset($o['eventTicketingStatus']) || $o['eventTicketingStatus'] != 1) {
            echo $o["messages"]["messageRegistrationComingSoon"];
            return;
        }
        
       // echo '<pre>'; print_r($_POST); echo '</pre>';
        
        /*
         * Check to see if the user has submitted a ticket request and now
         * needs to pay
         */
        if ( isset( $_POST['packagePurchaseNonce'] ) && verifyPost() ) { 
            /*
             * Build the package information the user chose
             */
            $p = array();
            $packages = $o['packageProtos'];
            $coupons = $o['coupons'];
            $total = 0;
            foreach ( $_POST['packagePurchase'] as $package => $quantity ) {
                $package_id = $package;
                $package = $packages[$package];
                $price = $package->price;
               
                if( isset( $_POST['couponCode'] ) && isset( $coupons[$_POST['couponCode']] )) {
                    $coupon = $coupons[$_POST['couponCode']];
                    
                    switch( $coupon['type'] ) {
                        case 'flat':
                            $price = $price - $coupon['amt'];
                            break;
                        case 'percent':
                            $price = $price - ( ($price/100) * $coupon['amt'] );
                            break;
                    }
                }
                $total += ( $price * $quantity );
                
                $p[] = array(
                    'package_id'    => $package_id,
                    'package'       => $package->packageName,
                    'price'         => $price,
                    'coupon'        => $_POST['couponCode'],
                    'quantity'      => $quantity,
                    'description'   => $package->packageDescription,
                    'start'         => $package->expireStart,
                    'end'           => $package->expireEnd
                );
            }
            
            
            $ticket_url = add_query_arg( "ticket", $_POST['packagePurchaseNonce'], WPEVT::instance()->current_url() );
            
            $args = array(
                'ticket_url'    => $ticket_url,
                'o'             => $o,
                'items'      => $p,
                'event'         => $o["messages"]["messageEventName"] . ": Registration",
                    'total'         => $total,
                'purchase_id'   => $_POST['packagePurchaseNonce'],
                'name'          => $_POST['packagePurchaseName'],
                'email'         => $_POST['packagePurchaseEmail']
                
            );
            
            /*
             * Store ticket to retrieve later
             */
            $post = array(
                'post_title'        => $_POST['packagePurchaseNonce'],
                'post_content'      => serialize( $args ),
                'post_type'         => 'wpevt_purchase'
            );
            $new_post_id = wp_insert_post( $post );
            $args['purchase_post_id'] = $new_post_id;
            WPEVT::instance()->gateway()->processPayment( $args );
            //die('trying to pay');
        }
        
        
        else if( isset( $_REQUEST['paymentReturn'] ) ) {
            WPEVT::instance()->gateway()->processPaymentReturn( $_REQUEST['paymentReturn'], $o );
        }

    //    $_POST['paymentSuccessful'] = false;
        else if( isset( $_REQUEST['paymentSuccessful'] ) ) {
            self::ticketEditScreen();
            
            /*
             * Subtract package quantity availability
             */
        } 
        
        else if (!isset($o['eventTicketingStatus']) || $o['eventTicketingStatus'] != 1) {
            echo $o["messages"]["messageRegistrationComingSoon"];
           
        }
        
        else if( isset( $_GET['ticket'] ) ) {
            /*
             * User wants to see their ticket
             */
            
            self::showTicket( $_GET['ticket'] );
        }
        
        else { 
            self::showRegistrationForm( $o );
           // die();
        }
        return;
        
        // Stop the rest of this function from executing so I can devel the new stuff :) - Ben Lobaugh
       // return;
        
        // Why ob? - Ben Lobaugh 10-17-2012
       // ob_start();

//        if (!isset($o['eventTicketingStatus']) || $o['eventTicketingStatus'] != 1) {
//            echo $o["messages"]["messageRegistrationComingSoon"];
//            return(ob_get_clean());
//        }

        
        //return redirect from paypal
        //token=EC-4DR89227KU882313S&PayerID=5SYRSDFCC4Z56
        if ((isset($_REQUEST["token"]) && isset($_REQUEST["PayerID"]) && strlen($_REQUEST["token"]) == 20 && strlen($_REQUEST["PayerID"]) == 13 && !$_REQUEST["paypalRedirect"]) || (isset($_REQUEST["couponSubmitNonce"]) && wp_verify_nonce($_REQUEST['couponSubmitNonce'], plugin_basename(__FILE__)))) {
            if (!isset($_REQUEST['couponSubmitNonce'])) {
                //get order details to send to paypal...again
                $order = get_option("paypal_" . $_REQUEST["token"]);
                $total = $order["total"];
                $item = $order["items"];

                include(WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__)) . '/lib/nvp.php');
                include(WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__)) . '/lib/paypal.php');
                $p = $o["paypalInfo"];

                $method = "DoExpressCheckoutPayment";
                $cred = array("apiuser" => $p["paypalAPIUser"], "apipwd" => $p["paypalAPIPwd"], "apisig" => $p["paypalAPISig"]);
                $env = $p["paypalEnv"];
                $nvp = array('PAYMENTREQUEST_0_AMT' => $total,
                    'TOKEN' => $_REQUEST["token"],
                    "PAYERID" => $_REQUEST["PayerID"],
                    "PAYMENTREQUEST_0_PAYMENTACTION" => 'Sale',
                    "PAYMENTREQUEST_0_CURRENCYCODE" => $p["paypalCurrency"],
                );
                foreach ($item as $k => $i) {
                    //$nvp['L_PAYMENTREQUEST_0_NAME' . $k] = $i["name"];
                    $nvp['L_PAYMENTREQUEST_0_NAME' . $k] = $o["messages"]["messageEventName"] . ": Registration";
                    $nvp['L_PAYMENTREQUEST_0_DESC' . $k] = substr($i["desc"], 0, 127);
                    $nvp['L_PAYMENTREQUEST_0_AMT' . $k] = $i["price"];
                    $nvp['L_PAYMENTREQUEST_0_QTY' . $k] = $i["quantity"];
                }
                $nvp['PAYMENTREQUEST_0_ITEMAMT'] = $total;
                $nvpStr = nvp($nvp);
                $resp = PPHttpPost($method, $nvpStr, $cred, $env);
                die(' PayPaled it up on line 1782' );
            } else {
                $order = get_option("coupon_" . $_REQUEST["couponSubmitNonce"]);
                if (!$order) {
                    echo '<div class="ticketingerror">That coupon has already been used</div></div>';
                    return(false);
                }
                //remove option so it can't be used again
                delete_option("coupon_" . $_REQUEST["couponSubmitNonce"]);
                $resp["ACK"] = 'Success';
            }

            if (isset($resp["ACK"]) && $resp["ACK"] == 'Success') {
                //Set _REQUEST var so that if this gets hook called again in a single
                //http request it won't get run twice
                //TODO: figure out what can cause this hook to run twice
                $_REQUEST["paypalRedirect"] = 1;

                if (!isset($o["packageQuantities"]["totalTicketsSold"])) {
                    $o["packageQuantities"]["totalTicketsSold"] = 0;
                }
                if (!isset($o["packageQuantities"][$_REQUEST["packageId"]])) {
                    $o["packageQuantities"][$_REQUEST["packageId"]] = 0;
                }

                //check for special packages in the session...err...transient thing
                $transient = get_transient($_COOKIE["event-ticketing-cookie"]);
                if ($transient) {
                    $o["packageProtos"][$transient->packageId] = $transient;
                    delete_transient($_COOKIE["event-ticketing-cookie"]);
                }

                foreach ($order["items"] as $i) {
                    for ($x = 1; $x <= $i["quantity"]; $x++) {
                        $packageHash = md5(microtime() . $i["packageid"]);
                        $package = clone $o["packageProtos"][$i["packageid"]];
                        $package->setPackageId($packageHash);
                        $package->setOrderDetails($order);

                        //register coupon use if there was a coupon used
                        if (isset($package->coupon) && isset($o["coupons"][$package->coupon["couponCode"]])) {
                            if (!isset($o["coupons"][$package->coupon["couponCode"]]["used"])) {
                                $o["coupons"][$package->coupon["couponCode"]]["used"] = 0;
                            }

                            $o["coupons"][$package->coupon["couponCode"]]["used"]++;
                            $o["coupons"][$package->coupon["couponCode"]]["uses"]--;

                            //filthy hack to check for couponed packages and getting them accounted for properly
                            $i["packageid"] = $package->coupon["packageId"];
                        }

                        //get ticket proto from package and wipe proto from package
                        $t = array_shift($package->tickets);

                        for ($y = 1; $y <= $package->ticketQuantity; $y++) {
                            //create tickets and attach them to real package
                            $ticketHash = md5(microtime() . $t->ticketId);
                            $ticket = clone $o["ticketProtos"][$t->ticketId];
                            $ticket->setTicketid($ticketHash);
                            $ticket->setSoldTime(current_time('timestamp'));

                            $n = explode(' ', $order["name"], 2);

                            foreach ($ticket->ticketOptions as $tk => $tv) {
                                if ($tv->displayName == 'Email') {
                                    $ticket->ticketOptions[$tk]->value = $order["email"];
                                }
                                if ($tv->displayName == 'First Name') {
                                    $ticket->ticketOptions[$tk]->value = $n[0];
                                }
                                if ($tv->displayName == 'Last Name') {
                                    $ticket->ticketOptions[$tk]->value = $n[1];
                                }
                            }

                            $package->addTicket($ticket);
                            add_option("ticket_" . $ticketHash, $packageHash);
                            $o["packageQuantities"]["totalTicketsSold"]++;
                            $tickethashes[] = array("hash" => $ticketHash, "name" => $package->packageName);
                        }
                        $o["packageQuantities"][$i["packageid"]]++;
                        add_option("package_" . $packageHash, $package);
                    }

                    //if there's a temporary package lying around don't store it permanently
                    if ($transient) {
                        unset($o["packageProtos"][$transient->packageId]);
                    }

                    //store packagequenitites and tickets sold
                    //should probably be in a different option
                    update_option("eventTicketingSystem", $o);
                }

                $replaceThankYou = '<ul>';
                $c = 0;
                $emaillinks = "\r\n";
                foreach ($tickethashes as $hash) {
                    $c++;
                    $url = $o["registrationPermalink"] . (strstr($o["registrationPermalink"], '?') ? '&' : '?') . 'tickethash=' . $hash["hash"];

                    $href = '<a href="' . $url . '">' . $hash["name"] . '</a>';
                    $emaillinks .= 'Ticket ' . $c . ': ' . $hash["name"] . ' - ' . $url . "\r\n";
                    $replaceThankYou .= '<li>Ticket ' . $c . ': ' . $href . '</li>';
                }
                $replaceThankYou .= '</ul>';

                echo '<div class="info">' . str_replace('[ticketlinks]', $replaceThankYou, $o["messages"]["messageThankYou"]) . '</div>';

                $headers = 'From: ' . $o["messages"]["messageEmailFromName"] . ' <' . $o["messages"]["messageEmailFromEmail"] . '>' . "\r\n";
                $headers .= 'Bcc: ' . $o["messages"]["messageEmailBcc"] . "\r\n";
                wp_mail($order["email"], $o["messages"]["messageEmailSubj"], str_replace('[ticketlinks]', $emaillinks, $o["messages"]["messageEmailBody"]), $tohead . $headers);

                $ordersummarymsg = "Order Placed\r\n";
                $ordersummarymsg .= $order["name"] . ' <' . $order["email"] . '> ordered ' . $c . ' tickets for ' . eventTicketingSystem::currencyFormat($order["total"]) . "\r\n\r\n";
                foreach ($order["items"] as $ord) {
                    $ordersummarymsg .= $ord["quantity"] . ' X ' . $ord["name"] . ' for ' . eventTicketingSystem::currencyFormat($ord["price"]) . " each\r\n";
                }
                $ordersummarymsg .= "\r\n";
                wp_mail($o["messages"]["messageEmailBcc"], "Event Order Placed", $ordersummarymsg, $headers);
            } else {
                echo '<div class="ticketingerror">There was an error from PayPal<br />Error: <strong>' . urldecode($resp["L_LONGMESSAGE0"]) . '</strong></div>';
            }
        } elseif (isset($_REQUEST["tickethash"]) && strlen($_REQUEST["tickethash"]) == 32) {
            eventTicketingSystem::ticketEditScreen();
        } else {
            
            
            // **** SHOW TICKET
            //require_once( WPEVT_DIR . '/views/ticketform.php' );
        }
    } // end function shortcode
    
    function emailTicketInfo( $name, $email, $ticket_url ) {
        $o = get_option("eventTicketingSystem");
        
        $headers = 'From: ' . $o["messages"]["messageEmailFromName"] . ' <' . $o["messages"]["messageEmailFromEmail"] . '>' . "\r\n";
        $headers .= 'Bcc: ' . $o["messages"]["messageEmailBcc"] . "\r\n";
        wp_mail($email, $o["messages"]["messageEmailSubj"], str_replace('[ticketlinks]', $ticket_url, $o["messages"]["messageEmailBody"]), $headers);
        
       // echo 'wp_mail('.$email.', '.$o["messages"]["messageEmailSubj"].', '.str_replace('[ticketlinks]', $ticket_url, $o["messages"]["messageEmailBody"]).', '.$headers.');';

        $ordersummarymsg = "Order Placed\r\n";

        $ordersummarymsg .= $name . ' <' . $email . '> ordered ticket';
//        foreach ($order["items"] as $ord) {
//            $ordersummarymsg .= $ord["quantity"] . ' X ' . $ord["name"] . ' for ' . eventTicketingSystem::currencyFormat($ord["price"]) . " each\r\n";
//        }
        $ordersummarymsg .= "\r\n";
        wp_mail($o["messages"]["messageEmailBcc"], "Event Order Placed", $ordersummarymsg, $headers);
    }

    function ticketEditScreen() {
        
        
        $purchase = get_page_by_title( $_GET['ticket'], OBJECT, 'wpevt_purchase' );
        $post_content = unserialize( $purchase->post_content );
        
        $o = $post_content['o'];
        $packages = $o['packageProtos'];
        $purchased_items = $post_content['items'];
        
        
        
        
        
        
        
        
        // Get purchased pckages
        $purchased_packages = array();
        foreach( $purchased_items AS $p ) {
            if( $p['quantity'] < 1 ) continue;
            
            $packages[$p['package_id']]->quantity = $p['quantity'];
            
            $purchased_packages[] = $packages[$p['package_id']];
        }
        
        // Grab all the tickets
        $tickets = array();
        foreach( $purchased_packages AS $p ) {
            foreach( $p->tickets AS $t ) {
                for( $i = 0; $i < $p->quantity; $i++ ) {
                    $tickets[] = $t;
                }
            }
        }
        
       // echo '<pre>'; print_r($tickets); echo '</pre>';
        // Store saved ticket info
        if( isset( $_POST['ticketInformationNonce'] ) ) {
         //   echo '<pre>'; print_r($_POST); echo '</pre>';
            
            // Get each ticket to update
            $new_tickets = array();
           // echo '<pre>'; print_r($_POST); echo '</pre>';
           // echo '<pre>'; print_r($tickets); echo '</pre>';
            foreach( $tickets AS $k => $ticket ) {
                $new_options = array();
                foreach( $ticket->ticketOptions AS $i => $option ) {
//                    echo '<pre>'; 
//                    print_r($_POST['ticketOption']); 
//                    print_r($_POST['ticketOption'][$i]); 
//                    echo '</pre>';
                    if( isset( $_POST['ticketOption'][$i] ) ) {
                        $option->value = $_POST['ticketOption'][$i];
                //        echo 'set';
                    }
                    $new_options[$i] = $option;
                }
                $ticket->ticketOptions = $new_options;
                $new_tickets[] = $ticket;
            }
            $tickets = $new_tickets;
            
            $purchase->post_content = serialize( $post_content );
            wp_update_post( $purchase );
            
            // Email user about their ticket
            self::emailTicketInfo( $post_content['name'], $post_content['email'], $post_content['ticket_url'] );
            
            require_once( WPEVT_DIR . '/views/ticketthanks.php' );
            return;
        }
       // echo '<pre>'; print_r($tickets); echo '</pre>';
        require_once( WPEVT_DIR . '/views/ticketedit.php' );
        // echo '<pre>'; print_r($tickets); echo '</pre>';
       // echo '<pre>'; print_r($post_content); echo '</pre>';
        
        return;
        //ticket form recieved
//        if (wp_verify_nonce($_POST['ticketInformationNonce'], plugin_basename(__FILE__))) {
//            $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
//            //echo '<pre>'.print_r($_REQUEST,true).'</pre>';
//            $ticketHash = $_REQUEST["tickethash"];
//            $packageHash = $_REQUEST["packagehash"];
//            $package = get_option('package_' . $packageHash);
//            if ($package instanceof package) {
//                foreach ($_REQUEST["ticketOption"] as $oid => $oval) {
//                    $package->tickets[$ticketHash]->ticketOptions[$oid]->value = $oval;
//                }
//                //echo '<pre>'.print_r($package->tickets,true).'</pre>';
//                $package->tickets[$ticketHash]->final = true;
//                update_option('package_' . $packageHash, $package);
//
//                echo '<div id="message" class="updated">Your ticket has been saved</div>';
//            }
//        } else {
//            //pull ticketinfo
//            //display ticket form (filling in if this is already been finished)
//            $ticketHash = $_REQUEST["tickethash"];
//            $packageHash = get_option('ticket_' . $ticketHash);
//            $package = get_option('package_' . $packageHash);
//            if ($package instanceof package) {
//                $ticket = $package->tickets[$ticketHash];
//
//                //echo '<pre>'.print_r($ticket,true).'</pre>';
//                echo '<form name="ticketInformation" method="post" action="">';
//                echo '<table>';
//                echo '<input type="hidden" name="ticketInformationNonce" id="ticketInformationNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
//                echo '<input type="hidden" name ="tickethash" value="' . $ticketHash . '" />';
//                echo '<input type="hidden" name ="packagehash" value="' . $packageHash . '" />';
//                foreach ($ticket->ticketOptions as $option) {
//                    echo '<tr><td>' . $option->displayName . ':</td><td>' . $option->displayForm() . '</tr>';
//                }
//                echo '<tr><td colspan="2"><input type="submit" class="button-primary" name="submitbutt" value="Save Ticket Information"></td></tr>';
//                echo '</table>';
//                echo '</form>';
//            } else {
//                echo '<div class="ticketingerror">Your tickethash appears to be incorrect. Please check your link and try again</div>';
//            }
//        }
    }

    function paypal() { 
        die( 'I am in paypal(). Come and get me poser' );
        $o = get_option("eventTicketingSystem");

        //check order and build for later retrieval
        if (isset($_POST['packagePurchaseNonce']) && wp_verify_nonce($_POST['packagePurchaseNonce'], plugin_basename(__FILE__))) {

            if (!check_email_address($_REQUEST["packagePurchaseEmail"])) {
                $_SESSION["ticketingError"] = 'Please enter a name and email address';
                return (false);
            }
            if ( isset($_POST["couponSubmitButton"])) {
                if ( isset( $o["coupons"] ) && is_array($o["coupons"][$_REQUEST["couponCode"]]) && is_numeric($o["coupons"][$_REQUEST["couponCode"]]["packageId"])) {
                    $coupon = $o["coupons"][$_REQUEST["couponCode"]];
                    if ($coupon["uses"] <= 0) {
                        $_SESSION["ticketingError"] = 'That coupon has already been used the maximum number of times';
                        return(false);
                    }
                    if ($o["packageProtos"][$coupon["packageId"]]->active === false) {
                        $_SESSION["ticketingError"] = 'That coupon is for a package which is inactive';
                        return(false);
                    }

                    //echo "<pre>";print_r($coupon);exit;
                    $package = clone $o["packageProtos"][$coupon["packageId"]];
                    if ($coupon["type"] == 'flat') {
                        $package->price = $package->price - $coupon["amt"];
                    } elseif ($coupon["type"] == 'percent') {
                        $package->price = $package->price * (1 - ($coupon["amt"] / 100));
                    }
                    $package->packageDescription = '**discounted** ' . $package->packageDescription;
                    $package->setExpire(array("start" => date_i18n("m/d/Y", strtotime("-1 days")), "end" => date_i18n("m/d/Y", strtotime("+1 days"))));
                    //set package id to something unlikely to happen in normal operation
                    $package->setPackageId(424242);
                    $package->setPackageQuantity(1);
                    $package->setCoupon($coupon);

                    $cookieVal = md5(microtime() . rand(0, 100));
                    setcookie("event-ticketing-cookie", $cookieVal, time() + 3600);
                    $_COOKIE["event-ticketing-cookie"] = $cookieVal;
                    set_transient($cookieVal, $package, 3600);
                    return(true);
                } else {
                    $_SESSION["ticketingError"] = 'Invalid Coupon Entered';
                    return (false);
                }
            }

            //check for coupon package in the session...err...transient thing
            $transient = get_transient(@$_COOKIE["event-ticketing-cookie"]);
            if ($transient instanceof package) {
                $o["packageProtos"][$transient->packageId] = $transient;
            }

            $somethingpurchased = $total = 0;
            foreach ($_REQUEST["packagePurchase"] as $packageId => $quantity) {
                if ($quantity > 0) {
                    $somethingpurchased = 1;
                    $total += $o["packageProtos"][$packageId]->price * $quantity;
                    $item[] = array("quantity" => $quantity,
                        "name" => $o["packageProtos"][$packageId]->displayName(),
                        "desc" => $o["packageProtos"][$packageId]->packageDescription,
                        "price" => $o["packageProtos"][$packageId]->price,
                        "packageid" => $packageId
                    );
                }
            }


            //was something purchased?
            if ($somethingpurchased == 0) {
                $_SESSION["ticketingError"] = 'You did not choose a quantity on any of the tickets. Please choose how many tickets you want';
            } else {
                //check to see if value is $0 due to a discount code or something
                //do something here
                if ($total <= 0) {
                    $couponSubmitNonce = wp_create_nonce(plugin_basename(__FILE__));

                    add_option('coupon_' . $couponSubmitNonce, array("items" => $item, "email" => $_REQUEST["packagePurchaseEmail"], "name" => $_REQUEST["packagePurchaseName"]));
                    header('Location: ' . get_permalink() . (strstr(get_permalink(), '?') ? '&amp;' : '?') . 'couponSubmitNonce=' . $couponSubmitNonce);
                    exit;
                }

                //looks like we got a submit with some values, let's redirect to paypal
                include(WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__)) . '/lib/nvp.php');
                include(WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__)) . '/lib/paypal.php');
                $returnsite = get_permalink();

                $p = $o["paypalInfo"];

                if (is_array($item)) {
                    $method = "SetExpressCheckout";
                    $cred = array("apiuser" => $p["paypalAPIUser"], "apipwd" => $p["paypalAPIPwd"], "apisig" => $p["paypalAPISig"]);
                    $env = $p["paypalEnv"];
                    $nvp = array('PAYMENTREQUEST_0_AMT' => $total,
                        "RETURNURL" => $returnsite,
                        "CANCELURL" => $returnsite,
                        "PAYMENTREQUEST_0_PAYMENTACTION" => 'Sale',
                        "PAYMENTREQUEST_0_CURRENCYCODE" => $p["paypalCurrency"],
                        "SOLUTIONTYPE" => 'Sole',
                        "LANDINGPAGE" => 'Billing'
                    );
                    foreach ($item as $k => $i) {
                        //$nvp['L_PAYMENTREQUEST_0_NAME' . $k] = $i["name"];
                        $nvp['L_PAYMENTREQUEST_0_NAME' . $k] = $o["messages"]["messageEventName"] . ": Registration";
                        $nvp['L_PAYMENTREQUEST_0_DESC' . $k] = substr($i["desc"], 0, 127);
                        $nvp['L_PAYMENTREQUEST_0_AMT' . $k] = $i["price"];
                        $nvp['L_PAYMENTREQUEST_0_QTY' . $k] = $i["quantity"];
                    }
                    $nvp['PAYMENTREQUEST_0_ITEMAMT'] = $total;


                    $nvpStr = nvp($nvp);

                    //echo '<pre>'.print_r($nvp,true).'</pre>';

                    $resp = PPHttpPost($method, $nvpStr, $cred, $env);
                    //echo '<pre>'.print_r($httpParsedResponseAr,true).'</pre>';
                    if (isset($resp["ACK"]) && $resp["ACK"] == 'Success') {
                        //store some vals and redirect
                        //echo '<pre>'.print_r($resp,true).'</pre>';
                        $token = urldecode($resp["TOKEN"]);
                        add_option('paypal_' . $token, array("total" => $total, "items" => $item, "paid" => false, "email" => $_REQUEST["packagePurchaseEmail"], "name" => $_REQUEST["packagePurchaseName"]));
                        if ("sandbox" === $env || "beta-sandbox" === $env) {
                            $payPalURL = "https://www.$env.paypal.com/webscr&cmd=_express-checkout&token=$token";
                        } else {
                            $payPalURL = "https://www.paypal.com/webscr&cmd=_express-checkout&token=$token";
                        }
                        header("Location: $payPalURL");
                        exit;
                    } else {
                        $_SESSION["ticketingError"] = 'There was an error from PayPal<br />Error: <strong>' . urldecode($resp["L_LONGMESSAGE0"]) . '</strong>';
                    }
                } else {
                    $_SESSION["ticketingError"] = 'No items were found. Please go back and choose how many tickets you want';
                }
            }
        }
    }

    function ticketCmp($a, $b) {
        if ($a->soldTime == $b->soldTime) {
            return 0;
        }
        return ($a->soldTime < $b->soldTime) ? -1 : 1;
    }

}

class arbitrarySort {

    public $sort;

    public function __construct($sort) {
        $this->sort = $sort;
    }

    public function cmp($a, $b) {
        if (is_numeric($a[$this->sort]) && is_numeric($b[$this->sort])) {
            if ($a[$this->sort] == $b[$this->sort]) {
                return 0;
            }
            return ($a[$this->sort] < $b[$this->sort]) ? -1 : 1;
        } else {
            return strcasecmp($a[$this->sort], $b[$this->sort]);
        }
    }

}

class ticketOption {

    public $displayName;
    public $displayType;
    public $options;
    public $required;
    public $value;
    public $optionId;

    function __construct($display = NULL, $displayType = NULL, $options = NULL, $required = true) {
        $this->displayName = $display;
        $this->displayType = $displayType;
        $this->options = $options;
        $this->required = $required;
    }

    public function displayForm() {
        ob_start();
        switch ($this->displayType) {
            case "text":
                echo '<input class="ticket-option-text" id="text-' . $this->optionId . '" name="ticketOption[' . $this->optionId . ']" value="' . $this->value . '">';
                break;
            case "checkbox":
                echo '<input class="ticket-option-checkbox" id="checkbox-' . $this->optionId . '" name="ticketOption[' . $this->optionId . ']" ' . ($this->value ? "CHECKED" : "") . '>';
                break;
            case "dropdown":
                echo '<select class="ticket-option-dropdown" id="dropdown-' . $this->optionId . '" name="ticketOption[' . $this->optionId . ']">';
                foreach ($this->options as $o) {
                    echo '<option ' . ($o == $this->value ? "selected" : "") . '>' . $o . '</option>';
                }
                echo '</select>';
                break;
            case "multidropdown":
                echo '<select size=5 MULTIPLE class="ticket-option-multidropdown" id="multidropdown-' . $this->optionId . '" name="ticketOption[' . $this->optionId . '][]">';
                foreach ($this->options as $o) {
                    echo '<option ' . (in_array($o, $this->value) ? "selected" : "") . '>' . $o . '</option>';
                }
                echo '</select>';
                break;
        }
        return ob_get_clean();
    }

    public function displayValue() {
        echo $this->value;
    }

    public function display() {
        echo $this->displayName;
    }

    public function setOptionId($id) {
        $this->optionId = $id;
    }

}

class ticket {

    public $ticketName;
    public $ticketOptions;
    public $ticketId;
    public $final;
    public $soldTime;

    function __construct($ticketOptions = array()) {
        $this->ticketOptions = $ticketOptions;
        $this->final = false;
    }

    function __clone() {
        foreach ($this->ticketOptions as $k => $v) {
            $this->ticketOptions[$k] = clone $v;
        }
    }

    public function displayName() {
        return ($this->ticketName == '' ? 'Unnamed' : $this->ticketName);
    }

    public function displayFull() {
        echo '<div id="eventTicket">';
        echo '<div class="ticketName">' . ($this->ticketName == '' ? 'Unnamed' : $this->ticketName) . '</div>';
        /*
          echo '<div>';
          if(is_array($this->ticketOptions))
          {
          foreach ($this->ticketOptions as $o)
          {
          echo '<div>'.$o->displayName.'</div>';
          }
          }
          echo '</div>';
         */
        echo '</div>';
    }

    public function displayForm() {
        echo '<div id="eventTicket">';
        echo '<div class="ticketName">Ticket Display Name&nbsp;<input name="ticketDisplayName" type="text" value="' . $this->ticketName . '"></div>';
        echo '<div>';
        //echo '<h3>Ticket Options</h3>';
        if (is_array($this->ticketOptions)) {

            echo "<table class='widefat'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>New Ticket Options</th>";
            echo "<th>Actions</th>";
            echo "<tbody>";
            foreach ($this->ticketOptions as $o) {
                echo "<tr>";
                echo '<td>' . $o->displayName . '</td>';
                echo '<td><a href="#" onclick="javascript:document.ticketOptionAddToTicket.del.value=\'' . $o->optionId . '\'; document.ticketOptionAddToTicket.submit();return false;">Delete</a></td>';
            }

            echo "</tr>";
            echo "</tbody>";
            echo "</tr>";
            echo "</thead>";
            echo "</table>";
            echo "<br />";
        }
        echo '</div>';
    }

    public function addOption($option) {
        $this->ticketOptions[$option->optionId] = $option;
    }

    public function delOption($optionId) {
        unset($this->ticketOptions[$optionId]);
    }

    public function setDisplayName($display) {
        $this->ticketName = $display;
    }

    public function setTicketId($id) {
        $this->ticketId = $id;
    }

    public function setSoldTime($timeStamp) {
        $this->soldTime = $timeStamp;
    }

}

class package {

    public $packageId;
    public $packageName;
    public $tickets;
    public $ticketQuantity;
    public $expireStart;
    public $expireEnd;
    public $price;
    public $packageQuantity;
    public $packageDescription;
    public $orderDetails;
    public $coupon;
    public $active;

    function __construct($tickets = array()) {
        $this->tickets = $tickets;
        $this->active = false;
    }

    public function displayForm() {
        echo '<div id="eventPackage" class="wrap">';
        echo '<h2>' . $this->displayName() . '</h2>';
        echo '<div class="packageName">Package Display Name<input type="text" name="packageDisplayName" value="' . $this->packageName . '"></div>';
        echo '<div class="packageDescription">Package Description<textarea name="packageDescription">' . $this->packageDescription . '</textarea></div>';
        echo '<div>';
        echo '<h2>Included Tickets</h2>';
        if (is_array($this->tickets)) {
            echo '<ul>';
            foreach ($this->tickets as $o) {
                echo '<li>';
                echo $o->displayName() . ' X <input name="packageTicketQuantity" size="2" type="text" value="' . $this->ticketQuantity . '">&nbsp;&nbsp;';
                echo '<a href="#" onclick="javascript:document.ticketAddToPackage.del.value=\'' . $o->ticketId . '\'; document.ticketAddToPackage.submit();return false;">Reset</a>';
                echo '</li>';
            }
            echo '</ul>';
        }
        echo '<h2>Package Expiration Dates</h2>';
        echo '<div>Start: <input type="text" name="packageExpireStart" id="expireStart" value="' . $this->expireStart . '"> End: <input type="text" name="packageExpireEnd" id="expireEnd" value="' . $this->expireEnd . '"></div>';
        echo '</div>';
        echo '<h2>Package Cost</h2>';
        echo '<div>' . eventTicketingSystem::getCurrencySymbol() . '<input type="text" name="packagePrice" value="' . $this->price . '" size="5"></div>';
        //echo '</div>';
        echo '<h2>Package Quantity</h2>';
        echo '<div>Quantity: <input type="text" name="packageQuantity" value="' . $this->packageQuantity . '" size="3"><br /><p style="font-style: italic;">How many of this package to sell? Leave blank for no limit</p></div>';
        echo '</div>';
    }

    public function setActive($active) {
        if ($active && $this->validatePackage()) {
            $this->active = true;
        } else {
            $this->active = false;
        }
    }

    public function validatePackage() {
        if (count($this->tickets) == 0)
            return false;

        return true;
    }

    public function setPackageId($id) {
        $this->packageId = $id;
    }

    public function setExpire($dates) {
        $this->expireStart = $dates["start"];
        $this->expireEnd = $dates["end"];
    }

    public function displayName() {
        return ($this->packageName == '' ? 'Unnamed' : $this->packageName);
    }

    public function setDisplayName($display) {
        $this->packageName = $display;
    }

    public function setPackageDescription($desc) {
        $this->packageDescription = $desc;
    }

    public function addTicket($ticket) {
        $this->tickets[$ticket->ticketId] = $ticket;
    }

    public function setTicketQuantity($num) {
        $this->ticketQuantity = $num;
    }

    public function setPackagePrice($price) {
        $this->price = $price;
    }

    public function setPackageQuantity($q) {
        $this->packageQuantity = $q;
    }

    public function delTicket($ticketId) {
        unset($this->tickets[$ticketId]);

        if (count($this->tickets) == 0) {
            $this->setActive(false);
        }
    }

    public function validDates() {
        if (current_time('timestamp') > strtotime($this->expireStart) && current_time('timestamp') < strtotime($this->expireEnd . " +1 day")) {
            return true;
        } else {
            return false;
        }
    }

    public function setOrderDetails($p) {
        $this->orderDetails = $p;
    }

    public function setCoupon($coupon) {
        $this->coupon = $coupon;
    }

}

function check_email_address($email) {
    // First, we check that there's one @ symbol, and that the lengths are right
    if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
        // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
        return false;
    }
    // Split it into sections to make life easier
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    for ($i = 0; $i < sizeof($local_array); $i++) {
        if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
            return false;
        }
    }
    if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
            return false; // Not enough parts to domain
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
            if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
                return false;
            }
        }
    }
    return true;
}

?>
