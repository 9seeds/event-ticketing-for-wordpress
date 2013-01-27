<form method="post" action="">
	<table>
		<tr>
			<td><label for=""><?php echo _e('First Name', 'wpet'); ?></td>
			<td><input type="text" name="first_name" id="first_name" value="<?php echo $post->wpet_first_name; ?>"></td>
		</tr>
		<tr>
			<td><label for=""><?php echo _e('Last Name', 'wpet'); ?></td>
			<td><input type="text" name="last_name" id="last_name" value="<?php echo $post->wpet_last_name; ?>"></td>
		</tr>
		<tr>
			<td><label for=""><?php echo _e('Email', 'wpet'); ?></td>
			<td><input type="text" name="email" id="email" value="<?php echo $post->wpet_email; ?>"></td>
		</tr>
		<?php
		/**
		 * @todo display all other ticket options
		 */
		
		$ticket_id = get_post_meta( $post->wpet_package, 'wpet_ticket-id', true );
		echo WPET::getInstance()->tickets->buildOptionsHtmlForm( $ticket_id );
			
			// ticket-id
		?>
		<tr>
			<td>
			    <input type="hidden" value="<?php echo $ticket_id; ?>" name="ticket_id" />
			    <input type="submit" value="<?php _e('Save Changes', 'wpet'); ?>">
			</td>
			<td></td>
		</tr>
	</table>
</form>