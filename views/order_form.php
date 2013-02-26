<div id="eventTicketing">
    
    <?php if( 'closed' == WPET::getInstance()->settings->event_status ) { 
    
    
    
    echo WPET::getInstance()->settings->closed_message;
    
    
    } else { ?>
	<form action="" method="post" id="order_form"> <?php //echo site_url( '?wpet-action=new-payment' );  ?>
		<?php
		/**
		 * @todo fix nonce
		 */
		wp_nonce_field( 'wpet_purchase_tickets', 'wpet_purchase_nonce' );
		?>
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
				<?php foreach( $data['rows'] as $row ): ?>
				<tr class="package_row">

					<td>
						<div class="packagename"><strong><?php echo $row->post_title ?></strong></div>
						<div class="packagedescription"><?php echo nl2br( $row->post_content ); ?></div>
					</td>
					<td class="price"><?php echo WPET::getInstance()->currency->format( WPET::getInstance()->settings->currency, $row->wpet_package_cost ); ?>
							</td>

					<?php if( WPET::getInstance()->settings->show_package_count ) {
					    echo "<td class='remaining'>" . WPET::getInstance()->packages->remaining( WPET::getInstance()->events->getWorkingEvent()->ID, $row->ID ) . "</td>";
					    echo "<td>";
					} else {
					    echo "<td colspan='2'>";
					}

					$remaining =  WPET::getInstance()->packages->remaining( WPET::getInstance()->events->getWorkingEvent()->ID, $row->ID );
					?>
					<!-- <td> -->
						<select name="package_purchase[<?php echo $row->ID ?>]" class="quantity">
							<?php
							for( $i = 0; $i <= $remaining; $i++ ) {
							    echo "<option value='$i'>$i</option>";
							}
							?>
						</select>
					</td>
				</tr>
				<?php endforeach; ?>
				
				<?php if( !$data['hide_coupons'] ) { ?>
				<tr class="coupon">
					<td colspan="2">
						<label for="coupon_code"><?php _e( 'Coupon Code', 'wpet'); ?>:</label>
						<input class="input" id="couponCode" name="couponCode">
					</td>
					<td colspan="2">
						<input type="button" name="couponSubmitButton" id="couponSubmitButton" value="<?php _e( 'Apply Coupon', 'wpet'); ?>">
					</td>
				</tr>
				<?php } ?>
						<tr>
						<td colspan="3">
							Subtotal: $<span id="subTotal">0.00</span> 
						</td>
						<td>
						    <input type="submit" name="order_submit" value="Submit" />
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
    <?php } ?>
</div>