<div class="wrap">
	<h2><?php _e('Reports', 'wpet' ); ?></h2>

	<div class="report-column-1">
		<?php
			// if registration is closed, include link to settings page			
			if( get_post_meta( WPET::getInstance()->events->getWorkingEvent()->ID ) && ( get_post_meta( WPET::getInstance()->events->getWorkingEvent()->ID, 'wpet_event_status', TRUE ) == 'closed' ) ) {
				echo '<div id="message" class="updated"><p>'. __( 'Registration is currently closed.', 'wpet' ) .' <a href="admin.php?page=wpet_settings">'. __( 'Open registration', 'wpet' ) .'</a></p></div>';
			}
		?>

		<h2><?php _e( 'Sales by Package', 'wpet' ); ?></h2>
		<table class='widefat'>
			<thead>
				<tr>
					<th><?php _e( 'Packages', 'wpet' ); ?></th>
					<th><?php _e( 'Sold', 'wpet' ); ?></th>
					<th><?php _e( 'Remaining', 'wpet' ); ?></th>
					<th><?php _e( 'Revenue', 'wpet' ); ?></th>
					<th><?php _e( 'Coupons', 'wpet' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					end( $data['package_rows'] );
					list( $key, $totals ) = each( $data['package_rows'] );
					unset( $data['package_rows'][$key] );
					foreach( $data['package_rows'] as $row ): ?>
				<tr>
					<td><?php echo $row['title'] ?></td>
					<td><?php echo $row['sold'] ?></td>
					<td><?php echo $row['remaining'] ?></td>
					<td><?php echo $row['revenue'] ?></td>
					<td><?php echo $row['coupons'] ?></td>
				</tr>						
				<?php endforeach; ?>
				<tr>
					<td><strong><?php _e( 'Totals', 'wpet' ); ?></strong></td>
					<td><strong><?php echo $totals['sold'] ?></strong></td>
					<td><strong><?php echo $totals['remaining'] ?></strong></td>
					<td><strong><?php echo $totals['revenue'] ?></strong></td>
					<td><strong><?php echo $totals['coupons'] ?></strong></td>
				</tr>						
			</tbody>
		</table>
		<h2><?php _e( 'Sales by Ticket Type', 'wpet' ); ?></h2>
		<table class='widefat'>
			<thead>
				<tr>
					<th><?php _e( 'Ticket', 'wpet' ); ?></th>
					<th><?php _e( 'Sold', 'wpet' ); ?></th>
					<th><?php _e( 'Remaining', 'wpet' ); ?></th>
				</tr>
				<?php
					end( $data['ticket_rows'] );
					list( $key, $totals ) = each( $data['ticket_rows'] );
					unset( $data['ticket_rows'][$key] );
					foreach( $data['ticket_rows'] as $row ):
				?>
				<tr>
					<td><?php echo $row['title'] ?></td>
					<td><?php echo $row['sold'] ?></td>
					<td><?php echo $row['remaining'] ?></td>
				</tr>						
				<?php endforeach; ?>
				<tr>
					<td><strong><?php _e( 'Totals', 'wpet' ); ?></strong></td>
					<td><strong><?php echo $totals['sold'] ?></strong></td>
					<td><strong><?php echo $totals['remaining'] ?></strong></td>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<div id="pie_chart_div"></div>
		<div id="line_chart_rev"></div>
		<div id="line_chart_sales"></div>
		
	</div>

	<div class="report-column-2">
		<div class="seeds-sidebar-box">
			<h3><?php _e( 'Need Support?', 'wpet' ); ?></h3>
			<p><?php echo sprintf( __( 'If you are having problems with the WP Event Ticketing plugin, please post them in the %ssupport forums%s.', 'wpet' ), '<a href="http://support.9seeds.com/">', '</a>' ); ?></p>
		</div>
		<div class="seeds-sidebar-box-2">
			<h3><?php _e( 'Spread the Love', 'wpet' ); ?></h3>
			<p><?php _e( 'Did WP Event Ticketing help you run a kick ass event? Consider helping us out in one of the following ways:', 'wpet' ); ?></p>
			<ul>
				<?php
				if (!function_exists( 'wpet_pro' )) {
					// display donate link
					?>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="N7ETDY3ZASAS6">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="<?php _e( 'PayPal - The safer, easier way to pay online!', 'wpet' ); ?>">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
					<p><?php _e( 'or maybe...', 'wpet' ); ?></p>
					<?php
				}
				?>
				<li><a href="http://wordpress.org/extend/plugins/wpeventticketing/" target="_blank"><?php _e( 'Give a 5-Star review', 'wpet' ); ?></a></li>
				<li><a href="https://twitter.com/intent/tweet?text=I%20love%20the%20WP%20Event%20Ticketing%20plugin%20by%20%409seeds%20http%3A%2F%2F9seeds.com%2Fplugins%2F" target="_blank"><?php _e( 'Tweet about it', 'wpet' ); ?></a></li>
				<li><a href="http://facebook.com/9seeds/" target="_blank"><?php _e( 'Like our page on Facebook', 'wpet' ); ?></a></li>
				<li>
					<form action="http://9seeds.us1.list-manage.com/subscribe/post?u=dee783b6d4761d6fe1a5529f8&amp;id=99eb075029" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
						<label for="mce-EMAIL"><?php _e( 'Subscribe to our mailing list', 'wpet' ); ?></label>
						<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="<?php _e( 'email address', 'wpet' ); ?>" required>
						<div class="clear"><input type="submit" value="<?php _e( 'Subscribe', 'wpet' ); ?>" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
					</form>
				</li>
			</ul>
		</div>
		<?php
		/**
		 * @todo At some point, add a box to pull in RSS feed from 9seeds.com but just for wpet category
		 */
		?>
	</div>
</div>