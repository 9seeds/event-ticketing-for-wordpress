<h1><?php _e( 'Manual Payment', 'wpet' ); ?></h1>
<div id="eventTicketing">
<form action="" method="post" id="manual_payment_details">
	<p><?php _e( 'Please enter a name and email address for your confirmation and tickets', 'wpet' ); ?></p>
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
	 <p><?php printf( __( 'Total: %s', 'wpet' ), number_format($data['cart']['total'], 2) ); ?></p>
	 	<p>
			<input type="submit" name="submit" value="Submit" />
	 	</p>
</form>
</div>