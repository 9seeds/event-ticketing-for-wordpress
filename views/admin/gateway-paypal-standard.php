	<table class="form-table">				
			<tr class="form-field form-required">
				<th scope="row"><label for="options[payment_gateway_status]"><?php _e('Gateway Status', 'wpet'); ?></label></th>
				<td>
				<?php //@TODO payment_gateway_status ?>
					<select name="options[payment_gateway_status]" id="options[payment_gateway_status]">
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
				<th scope="row"><label for="options[sandbox_api_username]"><?php _e('API Username', 'wpet'); ?></label></th>
				<td><input name="options[sandbox_api_username]" type="text" id="options[sandbox_api_username]" value="<?php echo $data['sandbox_api_username'] ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[sandbox_api_password]"><?php _e('API Password', 'wpet'); ?></label></th>
				<td><input name="options[sandbox_api_password]" type="password" id="options[sandbox_api_password]" value="<?php echo $data['sandbox_api_password'] ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[sandbox_api_signature]"><?php _e('API Signature', 'wpet'); ?></label></th>
				<td><input name="options[sandbox_api_signature]" type="text" id="options[sandbox_api_signature]" value="<?php echo $data['sandbox_api_signature'] ?>"></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e( 'Live Settings', 'wpet' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[live_api_username]"><?php _e('API Username', 'wpet'); ?></label></th>
				<td><input name="options[live_api_username]" type="text" id="options[live_api_username]" value="<?php echo $data['live_api_username'] ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[live_api_password]"><?php _e('API Password', 'wpet'); ?></label></th>
				<td><input name="options[live_api_password]" type="password" id="options[live_api_password]" value="<?php echo $data['live_api_password'] ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[live_api_signature]"><?php _e('API Signature', 'wpet'); ?></label></th>
				<td><input name="options[live_api_signature]" type="text" id="options[live_api_signature]" value="<?php echo $data['live_api_signature'] ?>"></td>
			</tr>
		</tbody>
	</table>
