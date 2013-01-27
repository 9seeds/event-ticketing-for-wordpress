<div class="wrap">
	<?php echo $admin_page_icon; ?>
	<h2><?php _e('Add Attendee', 'wpet'); ?></h2>
<form method="post" action="">
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[package]"><?php _e('Package', 'wpet'); ?></label></th>
				<td>
					<!--<select name="options[package]" id="packages">
						<option value="1">List of Ticket Packages</option>
						<option value="2">List of Ticket Packages2</option>
						<option value="3">List of Ticket Packages3</option>
						<option value="4">List of Ticket Packages4</option>
<?php
// @TODO
// add dropdowns for all ticket packages
?>
					</select>-->
				    <?php echo WPET::getInstance()->packages->selectMenu( 'package', 'packages', '' ); ?>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="first_name"><?php _e('First Name', 'wpet'); ?></label></th>
				<td><input name="first_name" type="text" id="first_name" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="last_name"><?php _e('Last Name', 'wpet'); ?></label></th>
				<td><input name="last_name" type="text" id="last_name" value=""></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[email]"><?php _e('Email', 'wpet'); ?></label></th>
				<td><input name="email" type="text" id="email" value=""></td>
			</tr>
			
		
<?php
// @TODO
// add rows for all ticket options
?>

		</tbody>
	</table>
    <table class="form-table"><tbody id="ticket_options" ></tbody></table>
    <?php //echo $data['nonce']; ?>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Attendee', 'wpet'); ?>"></p>
</form>

</div>