<h3><?php _e('PayPal API Signature', 'wpet'); ?></h3>
<p><?php _e('WP Event Ticketing requires a PayPal API Signature in order to accept payments. You will need a business account at PayPal in order to use their API Signature tool. Once you have a business account set up, here is how you set up you API Signature:', 'wpet'); ?></p>
<ol>
	<li><?php _e('Log in to your PayPal account', 'wpet'); ?></li>
	<li><?php sprintf( _e('Hover over the %s subtab on the top navigation area and click the %s link', 'wpet'), '<strong>Profile</strong>', '<strong>My Selling Tools</strong>' ); ?></li>
	<li><?php sprintf( _e('Click %s on the API Access line', 'wpet'), '<strong>update</strong>' ); ?></li>
	<li><?php sprintf( _e('Click the %s link (under Option 2)', 'wpet'), '<strong>request api credentials</strong>' ); ?></li>
	<li><?php sprintf( _e('Submit the form to %s', 'wpet'), '<strong>request api signature</strong>' ); ?></li>
	<li><?php _e('Copy and paste the API username, password and signature into the fields on the Tickets -> Settings -> Payments tab', 'wpet'); ?></li>
</ol>