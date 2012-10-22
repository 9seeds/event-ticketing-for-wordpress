<?php

echo '<div id="eventTicketing">'; // wrap all output in this div for styling
//This will catch any errors thrown in the paypal() method.
            //Have to use session because paypal() has to happen quite early to allow for the paypal redirect

            if ( isset( $_POST['couponSubmitButton'] ) || !verifyPost() ) { //|| isset($_SESSION["ticketingError"]) && strlen($_SESSION["ticketingError"])) {
                
               // echo '<div class="ticketingerror">' . $_SESSION["ticketingError"] . '</div>';
                echo '<div class="ticketingerror">Invalid form, please check your input</div>';
               // unset($_SESSION["ticketingError"]);
            }
            //check for special packages in the session...err...transient thing
            //echo '<pre>'.print_r($_SESSION,true).'</pre>';
            if (isset($_COOKIE["event-ticketing-cookie"])) {
                $transient = get_transient($_COOKIE["event-ticketing-cookie"]);
                if ($transient instanceof package) {
                    $o["packageProtos"][$transient->packageId] = $transient;
                }
            }
            echo '<form action="" method="post">';
            echo '<input type="hidden" name="packagePurchaseNonce" id="packagePurchaseNonce" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
            echo '<div>Please enter a name and email address for your confirmation and tickets</div>';
            $packagePurchaseName = ( isset( $_POST['packagePurchaseName'] ) ) ? $_POST['packagePurchaseName']: '';
            $packagePurchaseEmail = ( isset( $_POST['packagePurchaseEmail'] ) )? $_POST['packagePurchaseEmail']: '';
            echo '<ul class="ticketPurchaseInfo"><li><label for="packagePurchaseName">Name:</label><input name="packagePurchaseName" size="35" value="' . $packagePurchaseName . '"></li><li><label for="packagePurchaseEmail">Email:</label><input name="packagePurchaseEmail" size="35" value="' . $packagePurchaseEmail . '"></li></ul>';
            echo '<div id="packages">';
            echo '<table>';
            echo '<tr>';
            echo '<th>Description</th>';
            echo '<th>Price</th>';
            if ($o["displayPackageQuantity"]) {
                echo '<th>Remaining</th>';
            }
            echo '<th>Quantity</th>';
            echo '</tr>';
            foreach ($o["packageProtos"] as $k => $v) {
                //determine remaining tickets so we don't display selectors that allow too many tickets to be sold
                //overall attendance max takes precendece over individual package quantity limitation
                $totalTicketsSold = ( isset( $o["packageQuantities"] ) && isset( $o["packageQuantities"]["totalTicketsSold"] ) )? $o["packageQuantities"]["totalTicketsSold"]: 0;
                $totalRemaining = $o["eventAttendance"] - $totalTicketsSold;
                if ($v->packageQuantity) {
                    $subtractor = ( isset( $o["packageQuantities"] ) && isset( $o["packageQuantities"][$v->packageId] ) )? $o["packageQuantities"][$v->packageId]: 0;
                    $packageRemaining = $v->packageQuantity - $subtractor;
                    $packageCounter = ($packageRemaining * $v->ticketQuantity) < $totalRemaining ? $packageRemaining : floor($totalRemaining / $v->ticketQuantity);
                    $packageCounter = $packageCounter > 10 ? 10 : $packageCounter;
                } else {
                    if (!$v->ticketQuantity)
                        $v->ticketQuantity = 1;

                    $packageRemaining = floor($totalRemaining / $v->ticketQuantity);
                    $packageCounter = $packageRemaining > 10 ? 10 : $packageRemaining;
                }

                if ($packageCounter > 0 && $v->validDates() && $v->active !== false) {
                    echo '<tr>';
                    echo '<td><div class="packagename"><strong>' . $v->packageName . '</strong></div><div class="packagedescription">' . $v->packageDescription . '</div></td>';
                    echo '<td>' . (is_numeric($v->price) ? eventTicketingSystem::currencyFormat($v->price) : eventTicketingSystem::currencyFormat(0)) . '</td>';
                    if ($o["displayPackageQuantity"]) {
                        echo '<td>' . $packageRemaining . ' left</td>';
                    }
                    echo '<td><select name="packagePurchase[' . $v->packageId . ']">';
                    for ($i = 0; $i <= $packageCounter; $i++) {
                        echo '<option>' . $i . '</option>';
                    }
                    echo '</select></td>';
                    echo '</tr>';
                }
            }
            echo '<tr class="coupon"><td colspan="2"><label for="couponCode">Coupon Code:</label><input class="input" name="couponCode"></td><td colspan="' . ($o["displayPackageQuantity"] == 1 ? "2" : "1") . '"><input type="submit" name="couponSubmitButton" value="Apply Coupon"></td></tr>';
            
            $paymentButton = WPEVT::instance()->gateway()->button();
            
            echo '<tr class="paypalbutton"><td colspan="' . ($o["displayPackageQuantity"] == 1 ? "4" : "3") . '">' . $paymentButton . '<div class="purchaseInstructions" >Choose your tickets and pay for them at PayPal. You will fill in your ticket information after your purchase is completed.</div></td></tr>';
            echo '</table>';
            echo '</div>'; // id="packages"
            echo '</form>';
        echo '</div>';