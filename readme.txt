=== WP Event Ticketing ===
Contributors: toddhuish, vegasgeek, stastic, jrfoell
Donate Link: http://9seeds.com/donate/
Tags: event, events, ticket, tickets, ticketing, attend, attendee, attending, attendance, conference, wordcamp, admission, entry
Requires at least: 2.8
Tested up to: 3.4.1
Stable tag: 1.3.3
License: GPLv2 or later

Manage and sell ticket for an event.

== Description ==

The WPEventTicketing plugin makes it easy to sell tickets to an event directly from your WordPress website.

Contains the following features:

* Collect payments via paypal.
* Set total attendance limit.
* Multiple ticket types. For example, ticket type A includes a t-shirt while ticket type B does not.
* Custom ticket options. This allows you to decide what information you want ticket purchasers to provide. For example, name, address, shirt size, twitter handle, etc...
* Create ticket packages. For example, early bird specials. Ticket packages can be used to give a discount to people who place their order during a certain time.
* Create coupons to give discounts to individuals.
* Send email to purchaser upon order completion.
* Reporting page shows total sales and income broken down by package, coupons used and tickets sold.
* Export attendee data to a CSV file.
* Display a list of event attendees using a shortcode

View a <a href="http://vimeo.com/18491170">walk-through video</a> that explains how to setup WP Event Ticketing.


== Installation ==

1. Upload `wpeventticketing` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in your WordPress dashboard
3. Get a Paypal API Signature (<a href="https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_NVPAPIBasics#id084E30I30RO">Instructions can be found here</a>)
4. Set up your Ticket Options
5. Create a new Ticket and select which ticket options to include
6. Create a Ticket Package
7. Set your max attendance
8. Enter your Paypal credentials
9. Set up your email messaging
10. Create a blank page and add the shortcode `[wpeventticketing]`

(*optionally*)
11. Create a blank page and add the shortcode `[wpeventticketingattendee]`
12. Add something like the following to your stylesheet
`.event-attendee {
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
}`

== Frequently Asked Questions ==

= How come my tickets aren't showing up for sale on the site? =

The issue is almost always one of 3 things. <a href="https://vimeo.com/29543852">Watch this video</a> and it will explain all 3 scenarios.

= Can I remove the coupon field from the registration page? =

The easiest way to remove the coupon field is to hide it with the following CSS:
`.coupon {
display: none;
}`

= The data entry fields are showing white text in the form fields. How do I fix that? =
This typically happens when you are using a theme with a dark background and white text. The theme designer didn't add a style to force the input fields to use a dark font. You can add the following code to your style.css file:
`#eventTicketing ul.ticketPurchaseInfo li input, 
#eventTicketing tr.coupon input[name="couponCode"] {
color: #000!important;
}`

= Does the plugin create printable tickets? =

Not at this time. But, that's a feature we plan to add in the future.

= Can I run multiple events at one time? =

Not at this time. But, that's a feature we plan to add in the future.

== Screenshots ==

1. Reporting page shows total earnings, coupons and ticket sales. Graph shows breakdown of tickets available and sold.
2. Ticket Options are used to collect information from event attendees.
3. Select which options to include for each ticket type.
4. Set all the options for a package.
5. Create coupons to give buyers a flat rate or percentage discount on their purchase.
6. Manage the messaging that gets displayed after a ticket purchase and the email to the purchaser.


== Changelog ==
= 1.3.3 =
* Update link for Paypal instructions
* Add new FAQs
* Removed extra closing div that was breaking instructions page

= 1.3.2 =
* Fix date calculation bug for ticket sold times

= 1.3.1 =
* Fix reporting bug with multiselect options on tickets

= 1.3 =
* Cleanup HTML on settings page
* Change attendee notification behavior
* Add exclusion rules to attendee page shortcode

= 1.2.4 =
* Duplicate calls to getAttendees() was causing excessive memory usage.

= 1.2.3 =
* Bugfix display currency was previously consistently off everywhere, now fixed

= 1.2.2 =
* Changed Paypal parameters such that customers without a Paypal account can still purchase tickets
* Display Currency throughout the entire program is consistent

= 1.2.1 =
* Fixed Syntax error

= 1.2 =
* Add attendee page shortcode
* Change thank you page display to be links
* Change thank you page links and purchaser email and admin summary email to contain ticket names of what was purchased
* Add package names and coupon names to attendee list and export pages

= 1.1.7 =
* Bugfix don't encode & to &amp; in emails...bad llama.

= 1.1.6 =
* Bugfix more clearly display revenue and discounted coupon revenue in report
* Bugfix multi ticket packages can now have their attendees deleted properly
* Bugfix Setups with multiple ticket types can now edit and delete attendees properly
* Add super secret debug functionality 

= 1.1.5 =
* Bugfix don't format prices and reformat before sending to paypal. Causes errors when sending 1,000 instead of 1000
* Bugfix fix totals not showing up in emails
* Bugfix When shortcode hook is called twice on single WP request redirect from paypal don't let it run twice
* Bugfix Force SSL v3 for Curl calls to paypal re: http://curl.haxx.se/mail/lib-2010-06/0169.html (thanks @wombatcombat)
* Add check so if all the tickets for a package are deleted the package is de-activated 

= 1.1.4 =
* Bugfix Default permalink sites (?page_id=<number>) no longer cause illegal URLs to be generated
* Bugfix Clicking on delete then cancel doesn't cause ticket/package to still be deleted

= 1.1.3 =
* Bugfix all prices on front end went to $0.00

= 1.1.2 =
* Bugfix for coupons not working if permalink is default style (?page_id=<number>)
* Spelling fixes
* Bugfix where menu wouldn't show up if thesis was installed
* Make currency output match chosen currency

= 1.1.1 =
* Bugfix for missing </div> in form

= 1.1 =
* Fix MS compatibility bug where defaults wouldn't load on new blog creation
* Allow selection of currency type
* Added more notifications to UI as things are added/edited/deleted
* Editing a coupon code doesn't create a new coupon and leave the old one there as well
* Edits for extra styling on purchase form (thanks @RyanKelln)
* Cleanup of new ticket creation
* Add multi select type to ticket options
* Pre populate ticket with name/email entered in at time of ticket purchase
* Fix bug if no coupon use quantity entered
* Add explicit on/off switch for packages to display or not

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.1.7 =

This upgrade fixes a small issue with email formatting. See changelog for detailed list.
