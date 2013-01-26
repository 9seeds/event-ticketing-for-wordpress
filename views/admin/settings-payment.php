	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[currency]"><?php _e('Currency', 'wpet'); ?></label></th>
				<td>
				<?php //@TODO currency ?>
					<select name="options[currency]" id="options[currency]">
						<option value="">$ <?php _e('US Dollars', 'wpet'); ?></option>
<?php
// @TODO
// Add dropdown items for all available currencies
?>
					</select>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[payment-gateway]"><?php _e('Payment Gateway', 'wpet'); ?></label></th>
				<td>
				<?php //@TODO payment-gateway ?>
					<select name="options[payment-gateway]" id="options[payment-gateway]">
						<option value=""><?php _e('PayPal Standard', 'wpet'); ?></option>
					</select>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[payment-gateway-status]"><?php _e('Gateway Status', 'wpet'); ?></label></th>
				<td>
				<?php //@TODO payment-gateway-status ?>
					<select name="options[payment-gateway-status]" id="options[payment-gateway-status]">
						<option value=""><?php _e('Sandbox', 'wpet'); ?></option>
						<option value=""><?php _e('Live', 'wpet'); ?></option>
					</select>
				</td>
			</tr>

		</tbody>
	</table>
	<h2><?php _e( 'Sandbox Settings', 'wpet' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[sandbox-api-username]"><?php _e('API Username', 'wpet'); ?></label></th>
				<td><input name="options[sandbox-api-username]" type="text" id="options[sandbox-api-username]" value="<?php echo $data['sandbox-api-username'] ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[sandbox-api-password]"><?php _e('API Password', 'wpet'); ?></label></th>
				<td><input name="options[sandbox-api-password]" type="password" id="options[sandbox-api-password]" value="<?php echo $data['sandbox-api-password'] ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[sandbox-api-signature]"><?php _e('API Signature', 'wpet'); ?></label></th>
				<td><input name="options[sandbox-api-signature]" type="text" id="options[sandbox-api-signature]" value="<?php echo $data['sandbox-api-signature'] ?>"></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e( 'Live Settings', 'wpet' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[live-api-username]"><?php _e('API Username', 'wpet'); ?></label></th>
				<td><input name="options[live-api-username]" type="text" id="options[live-api-username]" value="<?php echo $data['live-api-username'] ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[live-api-password]"><?php _e('API Password', 'wpet'); ?></label></th>
				<td><input name="options[live-api-password]" type="password" id="options[live-api-password]" value="<?php echo $data['live-api-password'] ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[live-api-signature]"><?php _e('API Signature', 'wpet'); ?></label></th>
				<td><input name="options[live-api-signature]" type="text" id="options[live-api-signature]" value="<?php echo $data['live-api-signature'] ?>"></td>
			</tr>
		</tbody>
	</table>
