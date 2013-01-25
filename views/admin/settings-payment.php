<form method="post" action="">
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
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'wpet'); ?>"></p>
</form>
