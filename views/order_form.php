<div id="eventTicketing">
	<form action="" method="post">
		<?php
		/**
		 * @todo fix nonce
		 */
		wp_nonce_field( 'wpet_purchase_tickets', 'wpet_purchase_nonce' );
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
					<th><?php _e( 'Description', 'wpet' ); ?></th>
					<th><?php _e( 'Price', 'wpet' ); ?></th>

					<?php if( WPET::getInstance()->settings->show_package_count ) {
					    echo "<th>" . __( 'Remaining', 'wpet' ) . "</th>";
					    echo "<th>";
					} else {
					    echo "<th colspan='2'>";
					}
					?>
					<?php _e( 'Quantity', 'wpet' ); ?></th>
				</tr>
				<?php foreach( $data['rows'] AS $row ): ?>
				<tr>

					<td>
						<div class="packagename"><strong><?php echo $row->post_title ?></strong></div>
						<div class="packagedescription"><?php echo nl2br( $row->post_content ); ?></div>
					</td>
					<td><?php echo WPET::getInstance()->currency->format( WPET::getInstance()->settings->currency, $row->wpet_package_cost ); ?>
							</td>

					<?php if( WPET::getInstance()->settings->show_package_count ) {
					    echo "<td>" . $row->wpet_quantity_remaining . "</td>";
					    echo "<td>";
					} else {
					    echo "<td colspan='2'>";
					}

					$remaining =  WPET::getInstance()->packages->remaining( WPET::getInstance()->events->getWorkingEvent()->ID, $row->ID );
					?>
					<!--<td>-->
						<select name="packagePurchase[0]" >
							<?php
							for( $i = 1; $i <= $remaining; $i++ ) {
							    echo "<option value='$i'>$i</option>";
							}
							?>
						</select>
					</td>
				</tr>
				<?php endforeach; ?>
				<tr class="coupon">
					<td colspan="2">
						<label for="couponCode"><?php _e( 'Coupon Code', 'wpet'); ?>:</label>
						<input class="input" name="couponCode">
					</td>
					<td colspan="2">
						<input type="submit" name="couponSubmitButton" value="<?php _e( 'Apply Coupon', 'wpet'); ?>">
					</td>
				</tr>
						<tr>
						<td colspan="4">
							<input type="submit" name="submit" value="Submit" />
						</td>
						</tr>
						<!--
				<tr class="paypalbutton">
					<td colspan="4">
						<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif">
						<div class="purchaseInstructions" ><?php _e( 'Choose your tickets and pay for them at PayPal. You will fill in your ticket information after your purchase is completed.', 'wpet'); ?></div>
					</td>
				</tr>
						-->
			</table>
		</div>
	</form>
</div>