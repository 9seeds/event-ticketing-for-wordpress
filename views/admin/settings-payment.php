	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="payment_gateway"><?php _e('Payment Gateway', 'wpet'); ?></label></th>
				<td>
				<?php echo WPET::getInstance()->settings->gatewaySelectMenu( 'options[payment_gateway]', 'payment_gateway', $data['payment_gateway'] ); ?>
				</td>
			</tr>
		</tbody>
	</table>

<div id="gateway_container">
	<?php
	$gateways = WPET::getInstance()->getGateways();
	foreach ( $gateways as $id => $gateway ):
		$style = $id == $data['payment_gateway'] ? '' : ' style="display: none;"';
	?>
		<div id="<?php echo $id ?>"<?php echo $style ?>>
			<?php echo $gateway->settingsForm(); ?>
		</div>
	<?php endforeach; ?>
</div>