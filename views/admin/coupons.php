<div class="wrap">
	<?php echo $admin_page_icon; ?>
	<h2><?php _e('Coupons', 'wpet'); ?> <a href="?page=wpet_coupons&add-coupons=1" class="add-new-h2">Add New</a></h2>

	<form action="" method="get" class="search-form">
		<p class="search-box">
			<label class="screen-reader-text" for="all-package-search-input"><?php _e('Search Coupons', 'wpet'); ?>:</label>
			<input type="search" id="all-user-search-input" name="s" value="">
			<input type="submit" name="" id="search-submit" class="button" value="<?php _e('Search Coupons', 'wpet'); ?>"></p>
	</form>
<?php
// @TODO
// fix nonce
?>
	<form id="form-user-list" action="users.php?action=allusers" method="post">
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="d723817b0c">
		<input type="hidden" name="_wp_http_referer" value="/wp-admin/network/users.php">
		<div class="tablenav top">
<?php
// @TODO
// will we have bulk editing capabilities?
?>
			<div class="alignleft actions">
				<select name="action">
					<option value="-1" selected="selected">Bulk Actions</option>
				</select>
				<input type="submit" name="" id="doaction" class="button-secondary action" value="Apply">
			</div>
<?php
// @TODO
// update # to show the amount of records
?>
			<div class="tablenav-pages one-page"><span class="displaying-num"># items</span>
				<span class="pagination-links"><a class="first-page disabled" title="Go to the first page" href="http://wcphx2012.dev/wp-admin/network/users.php">«</a>
					<a class="prev-page disabled" title="Go to the previous page" href="http://wcphx2012.dev/wp-admin/network/users.php?paged=1">‹</a>
					<span class="paging-input"><input class="current-page" title="Current page" type="text" name="paged" value="1" size="1"> of <span class="total-pages">1</span></span>
					<a class="next-page disabled" title="Go to the next page" href="http://wcphx2012.dev/wp-admin/network/users.php?paged=1">›</a>
					<a class="last-page disabled" title="Go to the last page" href="http://wcphx2012.dev/wp-admin/network/users.php?paged=1">»</a>
				</span>
			</div>
			<input type="hidden" name="mode" value="list">
			<br class="clear">
		</div>
		<table class="wp-list-table widefat fixed users-network" cellspacing="0">
			<thead>
				<tr>
					<?php 
					    foreach( $data['columns'] AS $k => $v ) {
					?>
					<th scope="col" class="manage-column column-<?php echo $k; ?> sortable">
					    <a href="http://wcphx2012.dev/wp-admin/network/users.php?orderby=login&amp;order=asc"><span>
						<?php echo $v;?>
						</span><span class="sorting-indicator"></span></a>
					</th>
					<?php } ?>
				</tr>
			</thead>

			<tfoot>
				<tr>
					<?php 
					    foreach( $data['columns'] AS $k => $v ) {
					?>
					<th scope="col" class="manage-column column-<?php echo $k; ?> sortable">
					    <a href="http://wcphx2012.dev/wp-admin/network/users.php?orderby=login&amp;order=asc"><span>
						<?php echo $v;?>
						</span><span class="sorting-indicator"></span></a>
					</th>
					<?php } ?>
				</tr>
			</tfoot>

			<tbody id="the-list">
				<?php
			    $i = 1;
			    foreach( $data['rows'] AS $row ) {
				$ci = 1; // Only show action in first column
				$class = '';
				if( $i % 2 ) 
				    $class = 'class="alternate"';
				
				$i++;
				?>
			    
			    <tr <?php echo $class; ?>>
					<!--<th scope="row" class="check-column">
						<input type="checkbox" id="blog_1" name="allusers[]" value="1">
					</th>-->
					<?php 
					//echo '<pre>'; var_dump($row); echo '</pre>';
					
					foreach( $data['columns'] AS $k => $v ) { ?>
					<td class="username column-username">
					    <strong><?php echo $row->$k; ?></strong>
					     <?php if( 1 == $ci ) { ?>
						<div class="row-actions">
						    <span class="edit"><a href="http://wcphx2012.dev/wp-admin/network/profile.php">Edit</a></span>
						    | <span class="trash"><a href="http://wcphx2012.dev/wp-admin/network/profile.php">Trash</a></span>
						</div>
						<?php $ci = 2; } ?>
					</td>
					
					<?php } ?>
					</td>
				</tr>
				
				
			    <?php } ?>
			</tbody>
		</table>
		<?php
		    echo WPET::getInstance()->currency->selectMenu( 'bob' );
		?>
		<div class="tablenav bottom">

			<div class="alignleft actions">
				<select name="action2">
					<option value="-1" selected="selected">Bulk Actions</option>
				</select>
				<input type="submit" name="" id="doaction2" class="button-secondary action" value="Apply">
			</div>
			<div class="tablenav-pages one-page"><span class="displaying-num">3 items</span>
				<span class="pagination-links"><a class="first-page disabled" title="Go to the first page" href="http://wcphx2012.dev/wp-admin/network/users.php">«</a>
					<a class="prev-page disabled" title="Go to the previous page" href="http://wcphx2012.dev/wp-admin/network/users.php?paged=1">‹</a>
					<span class="paging-input">1 of <span class="total-pages">1</span></span>
					<a class="next-page disabled" title="Go to the next page" href="http://wcphx2012.dev/wp-admin/network/users.php?paged=1">›</a>
					<a class="last-page disabled" title="Go to the last page" href="http://wcphx2012.dev/wp-admin/network/users.php?paged=1">»</a></span></div>
			<br class="clear">
		</div>
	</form>

</div>