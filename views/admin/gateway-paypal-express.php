	<table class="form-table">
		 <tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[paypal_express_currency]"><?php _e('Currency', 'wpet'); ?></label></th>
				<td>
				<?php echo WPET::getInstance()->currency->selectMenu( 'options[paypal_express_currency]', 'paypal_express_currency', $data['paypal_express_currency'], $data['paypal_express_currencies'] ); ?>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[paypal_express_status]"><?php _e('Gateway Status', 'wpet'); ?></label></th>
				<td>
				<?php echo $data['paypal_express_status_menu'] ?>
				</td>
			</tr>

		</tbody>
	</table>
	<h2><?php _e( 'Sandbox Settings', 'wpet' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[paypal_sandbox_api_username]"><?php _e('API Username', 'wpet'); ?></label></th>
				<td><input name="options[paypal_sandbox_api_username]" type="text" id="options[paypal_sandbox_api_username]" value="<?php echo $data['paypal_sandbox_api_username'] ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[paypal_sandbox_api_password]"><?php _e('API Password', 'wpet'); ?></label></th>
				<td><input name="options[paypal_sandbox_api_password]" type="password" id="options[paypal_sandbox_api_password]" value="<?php echo $data['paypal_sandbox_api_password'] ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[paypal_sandbox_api_signature]"><?php _e('API Signature', 'wpet'); ?></label></th>
				<td><input name="options[paypal_sandbox_api_signature]" type="text" id="options[paypal_sandbox_api_signature]" value="<?php echo $data['paypal_sandbox_api_signature'] ?>"></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e( 'Live Settings', 'wpet' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[paypal_live_api_username]"><?php _e('API Username', 'wpet'); ?></label></th>
				<td><input name="options[paypal_live_api_username]" type="text" id="options[paypal_live_api_username]" value="<?php echo $data['paypal_live_api_username'] ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[paypal_live_api_password]"><?php _e('API Password', 'wpet'); ?></label></th>
				<td><input name="options[paypal_live_api_password]" type="password" id="options[paypal_live_api_password]" value="<?php echo $data['paypal_live_api_password'] ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[paypal_live_api_signature]"><?php _e('API Signature', 'wpet'); ?></label></th>
				<td><input name="options[paypal_live_api_signature]" type="text" id="options[paypal_live_api_signature]" value="<?php echo $data['paypal_live_api_signature'] ?>"></td>
			</tr>
		</tbody>
	</table>
