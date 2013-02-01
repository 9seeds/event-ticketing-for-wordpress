<?php

$heading = empty($_REQUEST['post']) ? __('Add Coupon', 'wpet') : __('Edit Coupon', 'wpet');
?>
<h2><?php echo $heading; ?> <?php
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
	echo '<a href="' . $data['edit_url'] . '" class="add-new-h2">' . __('Add New', 'wpet') . '</a>';
}
?></h2>
<form method="post" action="">
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="coupon_code"><?php _e('Coupon Code', 'wpet'); ?></lable></th>
				<td><input name="options[coupon-code]" id="coupon_code" type="text" value="<?php echo $data['coupon']->post_name; ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="package_id"><?php _e('Package', 'wpet'); ?></label></th>
				<td>
					<?php echo WPET::getInstance()->packages->selectMenu('options[package_id]', 'package_id', $data['coupon']->wpet_package_id ); ?>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="type"><?php _e('Type', 'wpet'); ?></label></th>
				<td>
					<select name="options[type]" id="type">
						<option value="flat-rate" <?php selected( 'flat-rate', $data['coupon']->wpet_type, true ); ?>><?php _e('Flat Rate', 'wpet'); ?></option>
						<option value="percentage" <?php selected( 'percentage', $data['coupon']->wpet_type, true ); ?>><?php _e('Percentage', 'wpet'); ?></option>
					</select>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="amount"><?php _e('Amount', 'wpet'); ?></label></th>
				<td><input name="options[amount]" id="amount" type="text" value="<?php echo $data['coupon']->wpet_amount; ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="quantity"><?php _e('Quantity', 'wpet'); ?></label></th>
				<td><input name="options[quantity]" id="quantity" type="text" value="<?php echo $data['coupon']->wpet_quantity; ?>"></td>
			</tr>

		</tbody>
	</table>
	<?php
	echo $data['nonce'];
	$button_label = empty($_REQUEST['post']) ? __('Add Coupon', 'wpet') : __('Save Coupon', 'wpet');
	?>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $button_label; ?>"></p>
</form>