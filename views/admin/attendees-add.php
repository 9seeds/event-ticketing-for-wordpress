<div class="wrap">
	<?php echo $admin_page_icon; ?>
	<h2><?php _e('Add Attendee', 'wpet'); ?></h2>
<form method="post" action="">
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[package]"><?php _e('Package', 'wpet'); ?></label></th>
				<td>
					<select name="options[package]" id="options[package]">
						<option value="">List of Ticket Packages</option>
<?php
// @TODO
// add dropdowns for all ticket packages
?>
					</select>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[name]"><?php _e('Name', 'wpet'); ?></label></th>
				<td><input name="options[name]" type="text" id="options[name]" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[email]"><?php _e('Email', 'wpet'); ?></label></th>
				<td><input name="options[email]" type="text" id="options[email]" value=""></td>
			</tr>
<?php
// @TODO
// add rows for all ticket options
?>

		</tbody>
	</table>
    <?php echo $data['nonce']; ?>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Attendee', 'wpet'); ?>"></p>
</form>

</div>