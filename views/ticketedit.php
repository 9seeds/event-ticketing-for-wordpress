<form name="ticketInformation" method="post" action="">
    <table>
        <input type="hidden" name="ticketInformationNonce" id="ticketInformationNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />
        <input type="hidden" name ="tickethash" value="' . $ticketHash . '" />
        <input type="hidden" name ="packagehash" value="' . $packageHash . '" />
        <input type="hidden" name="ticket" value="<?php echo $_GET['ticket']; ?>" />
        <?php 
        foreach( $tickets AS $ticket ) { //var_dump($ticket->ticketOptions);
            foreach ($ticket->ticketOptions as $option) { //var_dump($option);?>
        <tr>
            <td><?php echo $option->displayName; ?>:</td>
            <td><?php 
            
            
            
            echo $option->displayForm(); 
            ?></td>
        </tr>
        <?php } 
        } ?>
        
        <tr>
            <td colspan="2"><input type="submit" class="button-primary" name="submitbutt" value="Save Ticket Information"></td>
        </tr>
    </table>
</form>
<!-- &ticket=442b06c578&paymentSuccessful=1 -->