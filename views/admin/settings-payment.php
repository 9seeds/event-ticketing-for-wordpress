	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Currency', 'wpet'); ?></th>
				<td>
					<select name="" id="">
						<option value="">$ <?php _e('US Dollars', 'wpet'); ?></option>
<?php
// @TODO
// Add dropdown items for all available currencies
?>
					</select>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Payment Gateway', 'wpet'); ?></th>
				<td>
					<select name="" id="">
						<option value=""><?php _e('PayPal Standard', 'wpet'); ?></option>
					</select>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Gateway Status', 'wpet'); ?></th>
				<td>
					<select name="" id="">
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
				<th scope="row"><?php _e('API Username', 'wpet'); ?></th>
				<td><input name="options[sandbox-api-username]" type="text" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('API Password', 'wpet'); ?></th>
				<td><input name="options[sandbox-api-password]" type="password" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('API Signature', 'wpet'); ?></th>
				<td><input name="options[sandbox-api-signature]" type="text" id="" value=""></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e( 'Live Settings', 'wpet' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('API Username', 'wpet'); ?></th>
				<td><input name="options[live-api-username]" type="text" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('API Password', 'wpet'); ?></th>
				<td><input name="options[live-api-password]" type="password" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('API Signature', 'wpet'); ?></th>
				<td><input name="options[live-api-signature]" type="text" id="" value=""></td>
			</tr>
		</tbody>
	</table>
