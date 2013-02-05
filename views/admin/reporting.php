<div class="wrap">
	<?php echo $admin_page_icon; ?>
	<h2><?php _e( 'Reports', 'wpet' ); ?></h2>

	<div class="report-column-1">
		<div class="report-fill">
			<div><label><?php _e( 'Event', 'wpet' ); ?>:</label> My Event Name</div>
			<div><label><?php _e( 'Date', 'wpet' ); ?>:</label> July 6, 2014</div>
			<div><label><?php _e( 'Total tickets', 'wpet' ); ?>:</label> 250</div>
			<div><label><?php _e( 'Tickets go on sale', 'wpet' ); ?>:</label> April 1, 2014</div>
			<div><label><?php _e( 'Ticket sales end on', 'wpet' ); ?>:</label> July 5, 2014</div>
		</div>
		<div class="report-nofill">
			<img src="//chart.googleapis.com/chart?chs=300x225&cht=p&chd=s:Pu&chdl=Sold|Available&chtt=Ticket+Sales" width="300" height="225" alt="Ticket Sales" />
		</div>
	</div>
	<div class="report-column-2">
		<div class="report-fill">
			<div><label><?php _e( 'Event', 'wpet' ); ?>:</label> My Event Name</div>
			<div><label><?php _e( 'Date', 'wpet' ); ?>:</label> July 6, 2014</div>
			<div><label><?php _e( 'Total tickets', 'wpet' ); ?>:</label> 250</div>
			<div><label><?php _e( 'Tickets go on sale', 'wpet' ); ?>:</label> April 1, 2014</div>
			<div><label><?php _e( 'Ticket sales end on', 'wpet' ); ?>:</label> July 5, 2014</div>
		</div>
		<div class="report-nofill">
			<img src="//chart.googleapis.com/chart?chs=300x225&cht=p&chd=s:Pu&chdl=Sold|Available&chtt=Ticket+Sales" width="300" height="225" alt="Ticket Sales" />
		</div>
	</div>
	<div class="report-column-3">
		<div class="seeds-sidebar-box">
			<h3>Need Support?</h3>
			<p>If you are having problems with the WP Event Ticketing plugin, please post them in the <a href="http://support.9seeds.com/forum/wp-event-ticketing/">support forums</a>.</p>
		</div>
		<div class="seeds-sidebar-box-2">
			<h3>Spread the Love</h3>
			<p>Did WP Event Ticketing help you run a kick ass event? Consider helping us out in one of the following ways:</p>
			<ul>
				<?php
				if( !function_exists( 'wpet_pro' ) ) {
					// display donate link
				?>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="N7ETDY3ZASAS6">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
					<p>or maybe...</p>
				<?php
				}
				?>
					<li><a href="http://wordpress.org/extend/plugins/wpeventticketing/" target="_blank">Give a 5-Star review</a></li>
					<li><a href="https://twitter.com/intent/tweet?text=I%20love%20the%20WP%20Event%20Ticketing%20plugin%20by%20%409seeds%20http%3A%2F%2F9seeds.com%2Fplugins%2F" target="_blank">Tweet about it</a></li>
					<li><a href="http://facebook.com/9seeds/" target="_blank">Like our page on Facebook</a></li>
					<li>
						<form action="http://9seeds.us1.list-manage.com/subscribe/post?u=dee783b6d4761d6fe1a5529f8&amp;id=99eb075029" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
							<label for="mce-EMAIL">Subscribe to our mailing list</label>
							<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required>
							<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
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