	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[currency]"><?php _e('Currency', 'wpet'); ?></label></th>
				<td>
				    <?php echo WPET::getInstance()->currency->selectMenu( 'options[currency]', $data['currency']); ?>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="payment_gateway"><?php _e('Payment Gateway', 'wpet'); ?></label></th>
				<td>
				<?php echo WPET::getInstance()->settings->gatewaySelectMenu( 'options[payment_gateway]', 'payment_gateway' ); ?>
				</td>
			</tr>
		</tbody>
	</table>

	<?php
	$gateways = WPET::getInstance()->getGateways();
	foreach ( $gateways as $id => $gateway ):
	?>
		<div id="<?php echo $id ?>" style="display: none;">
			<?php echo $gateway->settingsForm(); ?>
		</div>
	<?php endforeach; ?>