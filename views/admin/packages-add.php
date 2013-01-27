<div class="wrap">
	<?php
	echo $admin_page_icon;
	$heading = empty( $_REQUEST['post'] ) ? __('Add Package', 'wpet') : __('Edit Package', 'wpet');
	?>
	<h2><?php echo $heading; ?></h2>
<form method="post" action="">
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="package_name"><?php _e('Package Name', 'wpet'); ?></label></th>
				<td><input name="options[package_name]" type="text" id="package_name" value="<?php echo empty( $data['package'] ) ? '' : $data['package']->post_title ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="description"><?php _e('Description', 'wpet'); ?></description></th>
				<td><textarea name="options[description]" type="text" id="description"><?php echo empty( $data['package'] ) ? '' : $data['package']->post_content ?></textarea></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e('Included Tickets', 'wpet'); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="ticket_id"><?php _e('Ticket Name', 'wpet'); ?></label></th>
<?php
// @TODO
// select saved ticket
?>
				<td>
					<?php echo WPET::getInstance()->tickets->selectMenu( 'options[ticket_id]', 'ticket_id', 1 ); ?>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="ticket_quantity"><?php _e('Quantity', 'wpet'); ?></label></th>
				<td><input name="options[ticket_quantity]" type="text" id="ticket_quantity" value="<?php echo empty( $data['package'] ) ? '' : $data['package']->wpet_ticket_quantity ?>"></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e('On Sale Date', 'wpet'); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="start_date"><?php _e('Start Date', 'wpet'); ?></label></th>
				<td><input name="options[start_date]" type="text" id="start_date" value="<?php echo empty( $data['package'] ) ? '' : $data['package']->wpet_start_date ?>"></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="end_date"><?php _e('End Date', 'wpet'); ?></label></th>
				<td><input name="options[end_date]" type="text" id="end_date" value="<?php echo empty( $data['package'] ) ? '' : $data['package']->wpet_end_date ?>"></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e('Price', 'wpet'); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="package_cost"><?php _e('Package Cost', 'wpet'); ?></label></th>
				<td><input name="options[package_cost]" type="text" id="package_cost" value="<?php echo empty( $data['package'] ) ? '' : $data['package']->wpet_package_cost ?>"></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e('Packages Available', 'wpet'); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="quantity"><?php _e('Quantity', 'wpet'); ?></label></th>
				<td><input name="options[quantity]" type="text" id="quantity" value="<?php echo empty( $data['package'] ) ? '' : $data['package']->wpet_quantity ?>"></td>
			</tr>
		</tbody>
	</table>
	<?php echo $data['nonce'] ?>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Package', 'wpet'); ?>"></p>
</form>
</div>