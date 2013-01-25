<div class="wrap">
	<a href="http://9seeds.com/" target="_blank"><div id="seeds-icon"></div></a>
	<h2><?php _e('Add Package', 'wpet'); ?></h2>
<form method="post" action="">
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Package Name', 'wpet'); ?></th>
				<td><input name="" type="text" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Description', 'wpet'); ?></th>
				<td><textarea name="" type="text" id="" value=""></textarea></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e('Included Tickets', 'wpet'); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Ticket Name', 'wpet'); ?></th>
				<td><input name="" type="text" id="" value=""><br />
				<a href=""><?php _e('remove', 'wpet'); ?></a></td>
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
				<th scope="row"><?php _e('Start Date', 'wpet'); ?></th>
				<td><input name="" type="text" id="" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('End Date', 'wpet'); ?></th>
				<td><input name="" type="text" id="" value=""></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e('Price', 'wpet'); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Package Cost', 'wpet'); ?></th>
				<td><input name="" type="text" id="" value=""></td>
			</tr>
		</tbody>
	</table>
	<h2><?php _e('Packages Available', 'wpet'); ?></h2>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><?php _e('Quantity', 'wpet'); ?></th>
				<td><input name="" type="text" id="" value=""></td>
			</tr>
		</tbody>
	</table>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Ticket', 'wpet'); ?>"></p>
</form>

</div>