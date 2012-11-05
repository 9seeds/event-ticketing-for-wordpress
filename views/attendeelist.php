<table class="widefat">
            <thead>
            <tr>
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