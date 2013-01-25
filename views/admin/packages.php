<div class="wrap">
	<a href="http://9seeds.com/" target="_blank"><div id="seeds-icon"></div></a>
	<h2><?php _e('Ticket Packages', 'wpet'); ?> <a href="" class="add-new-h2">Add New</a></h2>

	<form action="" method="get" class="search-form">
		<p class="search-box">
			<label class="screen-reader-text" for="all-package-search-input">Search Packages:</label>
			<input type="search" id="all-user-search-input" name="s" value="">
			<input type="submit" name="" id="search-submit" class="button" value="Search Packages"></p>
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
					<a class="last-page disabled" title="Go to the last page" href="http://wcphx2012.dev/wp-admin/network/users.php?paged=1">»</a></span></div>		<input type="hidden" name="mode" value="list">
			<div class="view-switch">
				<a href="/wp-admin/network/users.php?mode=list" class="current"><img id="view-switch-list" src="http://wcphx2012.dev/wp-includes/images/blank.gif" width="20" height="20" title="List View" alt="List View"></a>
				<a href="/wp-admin/network/users.php?mode=excerpt"><img id="view-switch-excerpt" src="http://wcphx2012.dev/wp-includes/images/blank.gif" width="20" height="20" title="Excerpt View" alt="Excerpt View"></a>
			</div>

			<br class="clear">
		</div>
		<table class="wp-list-table widefat fixed users-network" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
						<input type="checkbox">
					</th>
					<th scope="col" id="username" class="manage-column column-username sortable desc" style=""><a href="http://wcphx2012.dev/wp-admin/network/users.php?orderby=login&amp;order=asc"><span>Package Name</span><span class="sorting-indicator"></span></a></th>
					<th scope="col" id="name" class="manage-column column-name sortable desc" style=""><a href="http://wcphx2012.dev/wp-admin/network/users.php?orderby=name&amp;order=asc"><span>Status</span><span class="sorting-indicator"></span></a></th>
				</tr>
			</thead>

			<tfoot>
				<tr>
					<th scope="col" class="manage-column column-cb check-column" style=""><input type="checkbox"></th>
					<th scope="col" class="manage-column column-username sortable desc" style=""><a href="http://wcphx2012.dev/wp-admin/network/users.php?orderby=login&amp;order=asc"><span>Package Name</span><span class="sorting-indicator"></span></a></th>
					<th scope="col" class="manage-column column-name sortable desc" style=""><a href="http://wcphx2012.dev/wp-admin/network/users.php?orderby=name&amp;order=asc"><span>Status</span><span class="sorting-indicator"></span></a></th>
				</tr>
			</tfoot>

			<tbody id="the-list">
				<tr class="alternate">
					<th scope="row" class="check-column">
						<input type="checkbox" id="blog_1" name="allusers[]" value="1">
					</th>
					<td class="username column-username">							<img alt="" src="http://1.gravatar.com/avatar/f5f999aaf3cf4aab2614f5d28e294ab8?s=32&amp;d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D32&amp;r=G" class="avatar avatar-32 photo" height="32" width="32"><strong><a href="http://wcphx2012.dev/wp-admin/network/profile.php" class="edit">john</a> - Super Admin</strong>
						<br>
						<div class="row-actions"><span class="edit"><a href="http://wcphx2012.dev/wp-admin/network/profile.php">Edit</a></span></div>						</td>
					<td class="name column-name"> </td><td class="email column-email"><a href="mailto:john@9seeds.com">john@9seeds.com</a></td><td class="registered column-registered">2013/01/18</td><td class="blogs column-blogs"><span class="site-1"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=1">wcphx2012.dev</a> <small class="row-actions"><span class="edit"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=1">Edit</a> | </span><span class="view"><a class="" href="http://wcphx2012.dev">View</a></span></small></span><br><span class="site-1"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=2">wcphx2012.dev/testsite/</a> <small class="row-actions"><span class="edit"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=2">Edit</a> | </span><span class="view"><a class="" href="http://wcphx2012.dev/testsite">View</a></span></small></span><br><span class="site-1"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=3">wcphx2012.dev/newsite/</a> <small class="row-actions"><span class="edit"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=3">Edit</a> | </span><span class="view"><a class="" href="http://wcphx2012.dev/newsite">View</a></span></small></span><br><span class="site-1"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=4">wcphx2012.dev/plugin-test/</a> <small class="row-actions"><span class="edit"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=4">Edit</a> | </span><span class="view"><a class="" href="http://wcphx2012.dev/plugin-test">View</a></span></small></span><br>						</td>
				</tr>
				<tr class="">
					<th scope="row" class="check-column">
						<input type="checkbox" id="blog_2" name="allusers[]" value="2">
					</th>
					<td class="username column-username">							<img alt="" src="http://1.gravatar.com/avatar/99a8934349cabf1241a1f319c5fb6f05?s=32&amp;d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D32&amp;r=G" class="avatar avatar-32 photo" height="32" width="32"><strong><a href="http://wcphx2012.dev/wp-admin/network/user-edit.php?user_id=2&amp;wp_http_referer=%2Fwp-admin%2Fnetwork%2Fusers.php" class="edit">justin</a></strong>
						<br>
						<div class="row-actions"><span class="edit"><a href="http://wcphx2012.dev/wp-admin/network/user-edit.php?user_id=2&amp;wp_http_referer=%2Fwp-admin%2Fnetwork%2Fusers.php">Edit</a> | </span><span class="delete"><a href="http://wcphx2012.dev/wp-admin/network/users.php?_wpnonce=2d713f7d7f&amp;action=deleteuser&amp;id=2&amp;_wp_http_referer=%2Fwp-admin%2Fnetwork%2Fusers.php" class="delete">Delete</a></span></div>						</td>
					<td class="name column-name"> </td><td class="email column-email"><a href="mailto:justin@9seeds.com">justin@9seeds.com</a></td><td class="registered column-registered">2013/01/18</td><td class="blogs column-blogs"><span class="site-1"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=1">wcphx2012.dev</a> <small class="row-actions"><span class="edit"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=1">Edit</a> | </span><span class="view"><a class="" href="http://wcphx2012.dev">View</a></span></small></span><br><span class="site-1"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=3">wcphx2012.dev/newsite/</a> <small class="row-actions"><span class="edit"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=3">Edit</a> | </span><span class="view"><a class="" href="http://wcphx2012.dev/newsite">View</a></span></small></span><br><span class="site-1"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=2">wcphx2012.dev/testsite/</a> <small class="row-actions"><span class="edit"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=2">Edit</a> | </span><span class="view"><a class="" href="http://wcphx2012.dev/testsite">View</a></span></small></span><br>						</td>
				</tr>
				<tr class="alternate">
					<th scope="row" class="check-column">
						<input type="checkbox" id="blog_3" name="allusers[]" value="3">
					</th>
					<td class="username column-username">							<img alt="" src="http://0.gravatar.com/avatar/e6544ccbc7effac6fff171b87b42654e?s=32&amp;d=http%3A%2F%2F0.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D32&amp;r=G" class="avatar avatar-32 photo" height="32" width="32"><strong><a href="http://wcphx2012.dev/wp-admin/network/user-edit.php?user_id=3&amp;wp_http_referer=%2Fwp-admin%2Fnetwork%2Fusers.php" class="edit">ronb</a></strong>
						<br>
						<div class="row-actions"><span class="edit"><a href="http://wcphx2012.dev/wp-admin/network/user-edit.php?user_id=3&amp;wp_http_referer=%2Fwp-admin%2Fnetwork%2Fusers.php">Edit</a> | </span><span class="delete"><a href="http://wcphx2012.dev/wp-admin/network/users.php?_wpnonce=2d713f7d7f&amp;action=deleteuser&amp;id=3&amp;_wp_http_referer=%2Fwp-admin%2Fnetwork%2Fusers.php" class="delete">Delete</a></span></div>						</td>
					<td class="name column-name"> </td><td class="email column-email"><a href="mailto:ron@9seeds.com">ron@9seeds.com</a></td><td class="registered column-registered">2013/01/18</td><td class="blogs column-blogs"><span class="site-1"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=1">wcphx2012.dev</a> <small class="row-actions"><span class="edit"><a href="http://wcphx2012.dev/wp-admin/network/site-info.php?id=1">Edit</a> | </span><span class="view"><a class="" href="http://wcphx2012.dev">View</a></span></small></span><br>						</td>
				</tr>
			</tbody>
		</table>
		<div class="tablenav bottom">

			<div class="alignleft actions">
				<select name="action2">
					<option value="-1" selected="selected">Bulk Actions</option>
					<option value="delete">Delete</option>
					<option value="spam">Mark as Spam</option>
					<option value="notspam">Not Spam</option>
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