<?php
$i = 0;
foreach ($data AS $d) {
	$row_shade = 'odd';
	if ($i%2)
		$row_shade = 'even';
	$i++;
	?>
<?php
/**
 * @todo alternate div class with odd/even
 */
?>
	<div class="event-attendee <?php echo $row_shade; ?>">
		<div class="attendee-gravatar">
			<?php echo get_avatar($d->wpet_email, '96'); ?>
		</div>
		<div class="attendee-name"><?php echo $d->wpet_first_name . ' ' . $d->wpet_last_name; ?></div>
		<?php
		if( $d->twitter ) {
		?>
			<div class="attendee-twitter"><a href="http://twitter.com/<?php echo str_replace( '@', '', $d->twitter ); ?>" target="_blank">@<?php echo str_replace( '@', '', $d->twitter ); ?></a></div>
		<?php
		}
		?>
	</div>
	<?php
}
?>