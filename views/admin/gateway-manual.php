	<table class="form-table">
		 <tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[paypal_currency]"><?php _e('Currency', 'wpet'); ?></label></th>
				<td>
				<?php echo WPET::getInstance()->currency->selectMenu( 'options[manual_currency]', 'manual_currency', $data['currency'], $data['currencies'] ); ?>
				</td>
			</tr>

		</tbody>
	</table>
