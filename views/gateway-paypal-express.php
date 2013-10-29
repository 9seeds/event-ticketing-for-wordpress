<h1><?php _e( 'PayPal Payment', 'wpet' ); ?></h1>
<div id="eventTicketing">
<form action="" method="post">
	<p><?php _e( 'Please enter your name and PayPal email address to pay for your tickets', 'wpet' ); ?></p>
	<ul class="ticketPurchaseInfo">
		<li>
			<label for="name"><?php _e( 'Name', 'wpet' ); ?>:</label>
			<input name="name" id="name" size="35" value="">
		</li>
		<li>
			<label for="email"><?php _e( 'Email', 'wpet' ); ?>:</label>
			<input name="email" id="email" size="35" value="">
		</li>
	</ul>
	 <p><?php printf( __( 'Total: %s', 'wpet' ), WPET::getInstance()->currency->format( WPET::getInstance()->settings->currency, $data['cart']['total'] ) ); ?></p>
	 	<p>
			<input type="submit" name="submit" value="Submit" />
	 	</p>
</form>
</div>