<form method="post" action="">
	<table>
		<tr>
			<td><label for=""><?php echo _e('First Name', 'wpet'); ?></td>
			<td><input type="text" name="first_name" id="first_name" value="<?php echo $post->wpet_first_name; ?>"></td>
		</tr>
		<tr>
			<td><label for=""><?php echo _e('Last Name', 'wpet'); ?></td>
			<td><input type="text" name="last_name" id="last_name" value="<?php echo $post->wpet_first_name; ?>"></td>
		</tr>
		<tr>
			<td><label for=""><?php echo _e('Email', 'wpet'); ?></td>
			<td><input type="text" name="email" id="email" value="<?php echo $post->wpet_first_name; ?>"></td>
		</tr>
		<?php
		/**
		 * @todo display all other ticket options
		 */
		?>
		<tr>
			<td><input type="submit" value="<?php _e('Save Changes', 'wpet'); ?>"></td>
			<td></td>
		</tr>
	</table>
</form>Ï