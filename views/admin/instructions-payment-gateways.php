<h3><?php _e('PayPal API Signature', 'wpet'); ?></h3>
<p><?php _e('WP Event Ticketing requires a PayPal API Signature in order to accept payments. You will need a business account at PayPal in order to use their API Signature tool. Once you have a business account set up, here is how you set up your API Signature:', 'wpet'); ?></p>
<ol>
	<li><?php _e('Log in to your PayPal account', 'wpet'); ?></li>
	<li><?php echo sprintf( __('While on the My Account tab, hover over the %sProfile%s subtab on the top navigation area and click the %sMy Selling Tools%s link', 'wpet'), '<strong>', '</strong>', '<strong>', '</strong>' ); ?></li>
	<li><?php echo sprintf( __('Click %supdate%s on the API Access line', 'wpet'), '<strong>', '</strong>' ); ?></li>
	<li><?php echo sprintf( __('Click the %srequest api credentials%s link (under Option 2)', 'wpet'), '<strong>', '</strong>' ); ?></li>
	<li><?php echo sprintf( __('Submit the form to %srequest api signature%s', 'wpet'), '<strong>', '</strong>' ); ?></li>
	<li><?php _e('Copy and paste the API username, password and signature into the fields on the Tickets -> Settings -> Payments tab', 'wpet'); ?></li>
</ol>