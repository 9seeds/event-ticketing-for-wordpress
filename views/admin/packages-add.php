<div class="wrap">
	<?php echo $admin_page_icon; ?>
	<h2><?php _e('Add Package', 'wpet'); ?></h2>
<form method="post" action="">
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[package_name]"><?php _e('Package Name', 'wpet'); ?></label></th>
				<td><input name="options[package_name]" type="text" id="options[package_name]" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[description]"><?php _e('Description', 'wpet'); ?></description></th>
				<td><textarea name="options[description]" type="text" id="options[description]" value=""></textarea></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e('Included Tickets', 'wpet'); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[ticket_id]"><?php _e('Ticket Name', 'wpet'); ?></label></th>
<?php
// @TODO
// Pull list of available tickets
?>
				<td>
					<?php echo WPET::getInstance()->tickets->selectMenu( 'options[ticket_id]', 1 ); ?>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[ticket_quantity]"><?php _e('Quantity', 'wpet'); ?></label></th>
				<td><input name="options[ticket_quantity]" type="text" id="options[ticket_quantity]" value=""></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e('On Sale Date', 'wpet'); ?></h2>
	<table class="form-table">
		<tbody>
<?php
// @TODO
// Add date pickers
?>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[start_date]"><?php _e('Start Date', 'wpet'); ?></label></th>
				<td><input name="options[start_date]" type="text" id="options[start_date]" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[end_date]"><?php _e('End Date', 'wpet'); ?></label></th>
				<td><input name="options[end_date]" type="text" id="options[end_date]" value=""></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e('Price', 'wpet'); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[package_cost]"><?php _e('Package Cost', 'wpet'); ?></label></th>
				<td><input name="options[package_cost]" type="text" id="options[package_cost]" value=""></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e('Packages Available', 'wpet'); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[quantity]"><?php _e('Quantity', 'wpet'); ?></label></th>
				<td><input name="options[quantity]" type="text" id="options[quantity]" value=""></td>
			</tr>
		</tbody>
	</table>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Package', 'wpet'); ?>"></p>
</form>
</div>