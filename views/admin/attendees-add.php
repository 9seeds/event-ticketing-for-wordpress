<?php
$heading = empty($_REQUEST['post']) ? __('Add Attendee', 'wpet') : __('Edit Attendee', 'wpet');
?>
<h2><?php echo $heading; ?> <?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
	echo '<a href="' . $data['edit_url'] . '" class="add-new-h2">' . __('Add New', 'wpet') . '</a>';
} ?></h2>
<form method="post" action="">
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="options[package]"><?php _e('Package', 'wpet'); ?></label></th>
				<td>
<?php
// @TODO select correct package on edit
?>
				    <?php echo WPET::getInstance()->packages->selectMenu( 'package', 'package', '' ); ?>
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
    <?php echo $data['nonce']; ?>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Attendee', 'wpet'); ?>"></p>
</form>

</div>