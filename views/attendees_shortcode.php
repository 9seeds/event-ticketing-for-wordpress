<?php
foreach ($data AS $d) {
	?>
<?php
/**
 * @todo alternate div class with odd/even
 */
?>
	<div class="event-attendee odd">
		<div class="attendee-gravatar">
			<?php echo get_avatar($d['email'], '96'); ?>
		</div>
		<div class="attendee-name"><?php echo $d['name']; ?></div>
		<?php
		/**
		 * @todo This next div should only display IF the ticket option has twitter
		 * @todo if user included full twitter URL or just the @ symbol, clean that crap up
		 */
		?>
		<div class="attendee-twitter"><a href="http://twitter.com/<?php echo $d['twitter']; ?>" target="_blank">@<?php echo $d['twitter']; ?></a></div>
	</div>
	<?php
}
?>