
<table border="1">
    <tr>
	<th>Attendee</th>
	<th>Event</th>
    </tr>
    
    <?php
    foreach( $data AS $d ) {
	?>
    <tr>
	<td><?php echo $d['name'];?></td>
	<td><?php echo $d['event'];?></td>
    </tr>
    
	<?php
    }
    ?>
</table>