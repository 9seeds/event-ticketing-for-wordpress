=== Event Ticketing for WordPress ===
Contributors: 9seeds, vegasgeek, jrfoell, blobaugh
Donate Link: http://9seeds.com/donate/
Tags: event, events, ticket, tickets, ticketing, attend, attendee, attending, attendance, conference, wordcamp, admission, entry, concert
Requires at least: 3.7
Tested up to: 3.8
Stable tag: 2.0.1
License: GPLv2 or later

Easily manage and sell tickets for an event on your WordPress site.

== Description ==

The Event Ticketing for WordPress plugin makes it easy for anybody with a WordPress site to sell tickets for an event.

You may notice that this plugin is very similar to our other plugin, <a href="http://wordpress.org/plugins/wpeventticketing/">WP Event Ticketing</a>. Well, you are correct. This is what we are calling version 2 of the earlier plugin. We chose to release it as a separate plugin so that we wouldn't accidentally break anybody's site who is using the current version and decided to upgrade while they had an ongoing event. We felt this was the safest way for our users.

This plugin is a complete rewrite of the previous version. It contains largely the same functionality as the previous version. The difference being, it's been updated to meet current WordPress coding standards. Also, the new version will make updates and new features easier to add.

The plugin contains the following features:

* Collect payments via PayPal
* Limit the total number of tickets to sell
* Set up multiple ticket packages
* Customize the ticket options
* Create coupons for individual packages

If you run in to any issues and need support, please visit <a href="http://support.9seeds.com/">support.9seeds.com</a> and be sure to mention you are using version 2.x.

== Installation ==

1. Upload the `event-ticketing-for-wordpress` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in your WordPress dashboard
3. Get a Paypal API Signature (<a href="https://vimeo.com/71790459">Instructions can be found here</a>)
4. Set up your Ticket Options
5. Create a new Ticket and select which ticket options to collect
6. Create a Ticket Package
7. Set your max attendance
8. Enter your Paypal credentials
9. Set up your email messaging
10. Create a blank page and add the shortcode `[wpet]`

(*optionally*)
11. Create a blank page and add the shortcode `[wpetattendees]`

== Frequently Asked Questions ==

= How come my tickets aren't showing up for sale on the site? =

The issue is almost always one of 3 things. <a href="https://vimeo.com/29543852">Watch this video</a> and it will explain all 3 scenarios.

= Does the plugin create printable tickets? =

Not at this time. But, that's a feature we plan to add in the future.

= Can I run multiple events at one time? =

Not at this time. But, that's a feature we plan to add in the future.

== Screenshots ==

1. Report showing the number of tickets sold, coupons used, tickets remaining and revenue
2. Create an unlimited number of ticket options to collect after tickets are sold
3. Create an unlimited number of ticket types
4. Create an unlimited number of ticket packages to sell on your site
5. Create an unlimeted number of coupons, each of which can be associated with specific packages and can be used for percentage or flat rate discounts
6. Attendees are now stored as a custom post type for easier management
7. Store the information about your event on the settings page
8. Store your sandbox and live PayPal credentials and easily switch back and forth as needed for testing
9. Customize the email sent to your buyers
10. Customize how the order form is displayed to your visitors
11. The order form shown to your visitors

== Changelog ==

= 2.0.1 =
* Bug fix: order from not displaying proper currency
* Bug fix: wpet_attendees shortcode not limiting to current event
* Bug fix: paypal api credentials forcing use of sandbox creds


= 2.0 =
* Iniital release after a complete rewrite
