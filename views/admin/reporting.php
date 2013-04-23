<?php
/**
 * @todo calculate $sold_count and $available count, move this code
 */
$sold_count = 100;
$available_count = 35;
?>
<script language="javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
	var queryString = '';
	var dataUrl = '';

	function onLoadCallback() {
		if (dataUrl.length > 0) {
			var query = new google.visualization.Query(dataUrl);
			query.setQuery(queryString);
			query.send(handleQueryResponse);
		} else {
			var dataTable = new google.visualization.DataTable();
			dataTable.addRows(2);

			dataTable.addColumn('number');
			dataTable.setValue(0, 0, <?php echo $sold_count; ?>);
			dataTable.setValue(1, 0, <?php echo $available_count; ?>);

			draw(dataTable);
		}
	}

	function draw(dataTable) {
		var vis = new google.visualization.ImageChart(document.getElementById('chart'));
		var options = {
			chs: '300x225',
			cht: 'p',
			chco: 'FF9900',
			chd: 's:Pu',
			chdl: 'Sold|Available',
			chl: '|',
			chtt: 'Ticket Sales'
		};
		vis.draw(dataTable, options);
	}

	function handleQueryResponse(response) {
		if (response.isError()) {
			alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
			return;
		}
		draw(response.getDataTable());
	}

	google.load("visualization", "1", {packages:["imagechart"]});
	google.setOnLoadCallback(onLoadCallback);
</script>
<div class="wrap">
	<h2><?php _e('Reports', 'wpet'); ?></h2>

	<div class="report-column-1">
		<?php
			// if registration is closed, include link to settings page			
			if( get_post_meta( WPET::getInstance()->events->getWorkingEvent()->ID ) && ( get_post_meta( WPET::getInstance()->events->getWorkingEvent()->ID, 'wpet_event_status', TRUE ) == 'closed' ) ) {
				echo '<div id="message" class="updated"><p>Registration is currently closed. <a href="/admin.php?page=wpet_settings">Open registration</a></p></div>';
			}
		?>

		<h2><?php _e('Sales by Package', 'wpet'); ?></h2>
		<table class='widefat'>
			<thead>
				<tr>
					<th><?php _e('Packages', 'wpet'); ?></th>
					<th><?php _e('Sold', 'wpet'); ?></th>
					<th><?php _e('Remaining', 'wpet'); ?></th>
					<th><?php _e('Revenue', 'wpet'); ?></th>
					<th><?php _e('Coupons', 'wpet'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				/**
				 * @todo Replace with proper data values
				 *
				 */
				?>
				<tr>
					<td>General Admission</td>
					<td>229</td>
					<td>31</td>
					<td>$2,220.00</td>
					<td>($50.00)</td>
				</tr>
				<tr>
					<td>Earlybirds</td>
					<td>26</td>
					<td>24</td>
					<td>$260.00</td>
					<td>($0.00)</td>
				</tr>
				<tr>
					<td><strong><?php _e('Totals', 'wpet'); ?></strong></td>
					<td><strong>255</strong></td>
					<td><strong>55</strong></td>
					<td><strong>$2,480.00</strong></td>
					<td><strong>($50.00)</strong></td>
				</tr>
			</tbody>
		</table>
		<h2><?php _e('Sales by Ticket Type', 'wpet'); ?></h2>
		<table class='widefat'>
			<thead>
				<tr>
					<th><?php _e('Ticket', 'wpet'); ?></th>
					<th><?php _e('Sold', 'wpet'); ?></th>
					<th><?php _e('Remaining', 'wpet'); ?></th>
				</tr>
				<?php
				/**
				 * @todo replace these with actual data
				 */
				?>
				<tr>
					<td>General Admission</td>
					<td>255</td>
					<td>145</td>
				</tr>
				<tr>
					<td>Special Admission</td>
					<td>5</td>
					<td>15</td>
				</tr>
				<tr>
					<td><strong><?php _e('Totals', 'wpet'); ?></strong></td>
					<td><strong>260</strong></td>
					<td><strong>160</strong></td>
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
			<h3>Need Support?</h3>
			<p>If you are having problems with the WP Event Ticketing plugin, please post them in the <a href="http://support.9seeds.com/forum/wp-event-ticketing/">support forums</a>.</p>
		</div>
		<div class="seeds-sidebar-box-2">
			<h3>Spread the Love</h3>
			<p>Did WP Event Ticketing help you run a kick ass event? Consider helping us out in one of the following ways:</p>
			<ul>
				<?php
				if (!function_exists('wpet_pro')) {
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