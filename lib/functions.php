<?php

function verifyPost() {
    
        if( !isset( $_POST['packagePurchaseName'] ) ||
                !isset( $_POST['packagePurchaseEmail'] ) ||
                '' == $_POST['packagePurchaseName'] ||
                '' == $_POST['packagePurchaseEmail']
           )
            return false;
        
        $all_zero = false;
        if( is_array( $_POST['packagePurchase'] ) ) {
            foreach( $_POST['packagePurchase'] AS $p ) {
                if( $p > 0 )
                    $all_zero = true;
            }
            return $all_zero;
        } else if( 0 == $_POST['packagePurchase'] ) {
            return false;
        }
        
        return true;
        
        // isset($_POST['packagePurchaseNonce']) && wp_verify_nonce($_POST['packagePurchaseNonce'], plugin_basename(__FILE__))
    }