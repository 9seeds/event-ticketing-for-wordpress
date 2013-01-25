<div class="wrap">
	<a href="http://9seeds.com/" target="_blank"><div id="seeds-icon"></div></a>
	<h2><?php _e('Attendees', 'wpet'); ?> <a href="?page=wpet_attendees&add-attendee=1" class="add-new-h2"><?php _e('Add New', 'wpet'); ?></a></h2>

	<form action="" method="get" class="search-form">
		<p class="search-box">
			<label class="screen-reader-text" for="all-package-search-input"><?php _e('Search Attendees', 'wpet'); ?>:</label>
			<input type="search" id="all-user-search-input" name="s" value="">
			<input type="submit" name="" id="search-submit" class="button" value="<?php _e('Search Attendees', 'wpet'); ?>"></p>
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
					<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
						<input type="checkbox">
					</th>
					<th scope="col" id="username" class="manage-column column-username sortable desc" style=""><a href="http://wcphx2012.dev/wp-admin/network/users.php?orderby=login&amp;order=asc"><span>Attendee Name</span><span class="sorting-indicator"></span></a></th>
					<th scope="col" id="name" class="manage-column column-name sortable desc" style=""><a href="http://wcphx2012.dev/wp-admin/network/users.php?orderby=name&amp;order=asc"><span>Status</span><span class="sorting-indicator"></span></a></th>
				</tr>
			</thead>

			<tfoot>
				<tr>
					<th scope="col" class="manage-column column-cb check-column" style=""><input type="checkbox"></th>
					<th scope="col" class="manage-column column-username sortable desc" style=""><a href="http://wcphx2012.dev/wp-admin/network/users.php?orderby=login&amp;order=asc"><span>Attendee Name</span><span class="sorting-indicator"></span></a></th>
					<th scope="col" class="manage-column column-name sortable desc" style=""><a href="http://wcphx2012.dev/wp-admin/network/users.php?orderby=name&amp;order=asc"><span>Status</span><span class="sorting-indicator"></span></a></th>
				</tr>
			</tfoot>

			<tbody id="the-list">
				<tr class="alternate">
					<th scope="row" class="check-column">
						<input type="checkbox" id="blog_1" name="allusers[]" value="1">
					</th>
					<td class="username column-username"><strong>Attendee Name</strong>
						<br>
						<div class="row-actions"><span class="edit"><a href="http://wcphx2012.dev/wp-admin/network/profile.php">Edit</a></span></div>						</td>
					<td class="name column-name"> </td>
					</td>
				</tr>
			</tbody>
		</table>
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