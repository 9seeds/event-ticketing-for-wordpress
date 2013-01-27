<div id="eventTicketing">
	<form action="" method="post">
		<?php
		/**
		 * @todo fix nonce
		 */
		wp_nonce_field( 'form-action', 'wpet-purchase' );
		?>
		<p><?php _e( 'Please enter a name and email address for your confirmation and tickets', 'wpet' ); ?></p>
		<ul class="ticketPurchaseInfo">
			<li>
				<label for="packagePurchaseName"><?php _e( 'Name', 'wpet' ); ?>:</label>
				<input name="packagePurchaseName" size="35" value="">
			</li>
			<li>
				<label for="packagePurchaseEmail"><?php _e( 'Email', 'wpet' ); ?>:</label>
				<input name="packagePurchaseEmail" size="35" value="">
			</li>
		</ul>
		<div id="packages">
			<table>
				<tr>
				<!--	<th><?php _e( 'Description', 'wpet' ); ?></th>
					<th><?php _e( 'Price', 'wpet' ); ?></th>
					<th><?php _e( 'Remaining', 'wpet' ); ?></th>
					<th><?php _e( 'Quantity', 'wpet' ); ?></th>-->
				    <?php 
				    $num_columns = 0;
				    
				    foreach( $data['columns'] AS $k => $v ) {
					if( !WPET::getInstance()->settings->show_package_count && 'wpet_quantity' == $k)
					    echo "<th colspan='2'>$v</th>";
					else
					    echo "<th>$v</th>";
					
				    }
				    ?>
				</tr>
				<tr>
					<td>
						<div class="packagename"><strong><?php
							/**
							 * @todo display package title
							 */ ?></strong></div>
						<div class="packagedescription"><?php
							/**
							 * @todo display package description
							 */ ?></div>
					</td>
					<td><?php
							/**
							 * @todo display package price
							 */ ?></td>
					<td><?php
							/**
							 * @todo display package remaining
							 */ ?></td>
					<td>
						<select name="packagePurchase[0]">
							<option>0</option>
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
							<option>6</option>
							<option>7</option>
							<option>8</option>
							<option>9</option>
							<option>10</option>
						</select>
					</td>
				</tr>
				<tr class="coupon">
					<td colspan="2">
						<label for="couponCode"><?php _e( 'Coupon Code', 'wpet'); ?>:</label>
						<input class="input" name="couponCode">
					</td>
					<td colspan="2">
						<input type="submit" name="couponSubmitButton" value="<?php _e( 'Apply Coupon', 'wpet'); ?>">
					</td>
				</tr>
				<tr class="paypalbutton">
					<td colspan="4">
						<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif">
						<div class="purchaseInstructions" ><?php _e( 'Choose your tickets and pay for them at PayPal. You will fill in your ticket information after your purchase is completed.', 'wpet'); ?></div>
					</td>
				</tr>
			</table>
		</div>
	</form>
</div>