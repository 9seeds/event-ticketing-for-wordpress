<table class="widefat">
            <thead>
            <tr>
                <th>&nbsp;</th>
                <th>Sold Time</th>
                <th>Package Type</th>
                <th>Coupon</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
            </thead>
            
        
        
            <tbody>
        <?php
        foreach( $results AS $r ) {
            $ticket = unserialize( $r->post_content );
            ?>
            <tr>
                <td>
                    <a href="<?php echo $ticket['ticket_url']; ?>">Link</a> | 
                    <a href="<?php echo admin_url( 'admin.php?page=ticketattendeeedit&delete=' . $r->ID ); ?>">Delete</a>
                </td>
                
                <td>
                    <?php echo $r->post_modified; ?>
                </td>
                
                <td>
                    Package Type
                </td>
                
                <td>
                    <?php echo $ticket['items'][0]['coupon']; ?>
                </td>
                
                <td>
                    <?php echo $ticket['name']; ?>
                </td>
                
                <td>
                    <?php echo $ticket['email']; ?>
                </td>
            </tr>
            
        <?php }
        ?>
            </tbody>
        </table>