<div id="eventTicketing">
    
    <table>
        <tr>
            <th>Ticket Holder</th>
            <td><?php echo $ticket['name']; ?></td>
        </tr>
        
        <tr>
            <th>Event</th>
            <td><?php echo $ticket['event']; ?></td>
        </tr>
        
        <tr>
            <th>Event Dates</th>
            <td>
                <?php
                $item = $ticket['items'][0];                
                echo "{$item['start']} - {$item['end']}";
                ?>
            </td>
        </tr>
    </table>
    
</div>