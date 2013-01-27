<?php
$heading = empty($_REQUEST['post']) ? __('Add Coupon', 'wpet') : __('Edit Coupon', 'wpet');
?>
<h2><?php echo $heading; ?> <?php
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
	echo '<a href="' . $data['edit_url'] . '" class="add-new-h2">' . __('Add New', 'wpet') . '</a>';
}
?></h2>
<table class="form-table">
	<tbody>
		<tr class="form-field form-required">
			<th scope="row"><?php _e('Coupon Code', 'wpet'); ?></th>
			<td><input name="options[coupon-code]" type="text" id="" value=""></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><?php _e('Package', 'wpet'); ?></th>
			<td>
				<?php echo WPET::getInstance()->packages->selectMenu('options[package_id]', 'package_id', 1); ?>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><?php _e('Type', 'wpet'); ?></th>
			<td>
				<select name="options[type]" id="">
					<option value="flat-rate"><?php _e('Flat Rate', 'wpet'); ?></option>
					<option value="percentage"><?php _e('Percentage', 'wpet'); ?></option>
				</select>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><?php _e('Amount', 'wpet'); ?></th>
			<td><input name="options[amount]" type="text" id="" value=""></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><?php _e('Quantity', 'wpet'); ?></th>
			<td><input name="options[quantity]" type="text" id="" value=""></td>
		</tr>

	</tbody>
</table>
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Coupon', 'wpet'); ?>"></p>
</form>