<div class="wrap">
	<a href="http://9seeds.com/" target="_blank"><div id="seeds-icon"></div></a>
	<h2><?php _e('Event Settings', 'wpet'); ?></h2>

	<h3 class="nav-tab-wrapper">
		<?php
		// @TODO
		// Replace id=2 with event ID
		?>
		<a href="settings-event.php?id=2" class="nav-tab nav-tab-active">Event Settings</a>
		<a href="settings-payment.php?id=2" class="nav-tab">Payment Settings</a>
		<a href="settings-email.php?id=2" class="nav-tab">Email Settings</a>
		<a href="settings-reset.php?id=2" class="nav-tab">Reset Settings</a>
	</h3>
	<?php
// @TODO
// Update form action to actually do something useful
// Deal with nonces, referrer and hidden ID
	?>
	<form method="post" action="site-info.php?action=update-site">
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="27bb2390ea">
		<input type="hidden" name="_wp_http_referer" value="/wp-admin/network/site-info.php?id=2">
		<input type="hidden" name="id" value="2">
		<table class="form-table">
			<tbody>
				<tr class="form-field form-required">
					<th scope="row">Event Name</th>
					<td><input name="" type="text" id="" value=""></td>
				</tr>
<?php
// @TODO
// Add date picker for event date
?>
				<tr class="form-field form-required">
					<th scope="row">Event Date</th>
					<td><input name="" type="text" id="" value=""></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row">Organizer Name</th>
					<td><input name="" type="text" id="" value=""></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row">Organizer Email</th>
					<td><input name="" type="text" id="" value=""></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row">Maximum Attendance</th>
					<td><input name="" type="text" id="" value=""></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row">Event Status</th>
					<td>
						<select name="" id="">
							<option value="">Registration is closed</option>
							<option value="">Registration is open</option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row">Display totals in form</th>
					<td>
						<label><input type="checkbox" name="" value="1" checked="checked"></label>
					</td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row">Coming soon text</th>
					<td><textarea name="" id="" value=""></textarea></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row">Thank you page text</th>
					<td><textarea name="" id="" value=""></textarea></td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
	</form>
</div>