<?php

function verifyPost() {
        if( !isset( $_POST['packagePurchaseName'] ) ||
                !isset( $_POST['packagePurchaseEmail'] ) ||
                '' == $_POST['packagePurchaseName'] ||
                '' == $_POST['packagePurchaseEmail']
           )
            return false;
        
        return true;
        
        // isset($_POST['packagePurchaseNonce']) && wp_verify_nonce($_POST['packagePurchaseNonce'], plugin_basename(__FILE__))
    }