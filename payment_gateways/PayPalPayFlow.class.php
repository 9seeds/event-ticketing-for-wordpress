<?php
/**
 * PayPal Express built-in WPEVT payment gateway
 * 
 * @since 1.4
 * @author Ben Lobaugh 
 */
add_filter('wpevt_register_payment_gateway', 'wpevt_pg_paypal_payflow');

function wpevt_pg_paypal_payflow($gateways) {
    if (!class_exists('PayPalPayFlow')) {
        require_once(WPEVT_DIR . '/lib/nvp.php');
        require_once(WPEVT_DIR . '/lib/paypal.php');

        class PayPalPayFlow extends WPEVT_PaymentGateway {

            public function saveSettings() {

                $data = array(
                    "paypalAPIUser" => trim($_POST["paypalAPIUser"]),
                    "paypalAPIPwd" => trim($_POST["paypalAPIPwd"]),
                    "paypalAPISig" => trim($_POST["paypalAPISig"]),
                    "paypalEnv" => trim($_POST["paypalEnv"]),
                    "paypalCurrency" => $_POST["paypalCurrency"]
                );

                parent::saveSettings($data);
            }

            public function button() {
                return '<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" name="paymentButton" />';
            }
            
           

            // http://www.richardcastera.com/projects/code/paypal-payflow-api-wrapper-class
            public function processPayment($args) {

                $o = $args['o'];



                $p = WPEVT::instance()->gateway()->getSettings();


                require_once(WPEVT_DIR . '/lib/Class.PayFlow.php');
                /**
                 * Create new Single Transaction.
                 */
                $PayFlow = new PayFlow('blobaughtest', 'PayPal', 'blobaughtest  ', 'Zaq12wsx', 'single');

                $PayFlow->setEnvironment('test');
                $PayFlow->setTransactionType('S');
                $PayFlow->setPaymentMethod('C');
                $PayFlow->setPaymentCurrency('USD');

                $PayFlow->setAmount('15.00', FALSE);
                $PayFlow->setCCNumber('378282246310005');
                $PayFlow->setCVV('4685');
                $PayFlow->setExpiration('1112');
                $PayFlow->setCreditCardName('Richard Castera');

                $PayFlow->setCustomerFirstName('Richard');
                $PayFlow->setCustomerLastName('Castera');
                $PayFlow->setCustomerAddress('589 8th Ave Suite 10');
                $PayFlow->setCustomerCity('New York');
                $PayFlow->setCustomerState('NY');
                $PayFlow->setCustomerZip('10018');
                $PayFlow->setCustomerCountry('US');
                $PayFlow->setCustomerPhone('212-123-1234');
                $PayFlow->setCustomerEmail('richard.castera@gmail.com');
                $PayFlow->setPaymentComment('New Regular Transaction');
                $PayFlow->setPaymentComment2('Product 233');

                if ($PayFlow->processTransaction()):
                    echo('Transaction Processed Successfully!');
                else:
                    echo('Transaction could not be processed at this time.');
                endif;

                echo('<h2>Name Value Pair String:</h2>');
                echo('<pre>');
                print_r($PayFlow->debugNVP('array'));
                echo('</pre>');

                echo('<h2>Response From Paypal:</h2>');
                echo('<pre>');
                print_r($PayFlow->getResponse());
                echo('</pre>');

                unset($PayFlow);
               
                }

                // We get to this function through RETURNURL
                public function processPaymentReturn($ticket_id, $o ) { echo 'paymentreturn';
                // Make sure the proper items are set
                if(isset($_GET["token"]) && isset($_GET["PayerID"]) ) {
                //we will be using these two variables to execute the "DoExpressCheckoutPayment"
                //Note: we haven't received any payment yet.

                $token = $_GET["token"];
                $payer_id = $_GET["PayerID"];

                $purchase = get_page_by_title( $ticket_id, OBJECT, 'wpevt_purchase' );

                // Update the ticket with the PayPal details for posterity
                $post_content = unserialize( $purchase->post_content );

                $post_content['token'] = $_GET['token'];
                $post_content['PayerID'] = $_GET['PayerID'];

                $purchase->post_content = serialize( $post_content );

                wp_update_post( $purchase );

                $p = $o["paypalInfo"];
                $p = WPEVT::instance()->gateway()->getSettings();
                $cred = array("apiuser" => $p["paypalAPIUser"], "apipwd" => $p["paypalAPIPwd"], "apisig" => $p["paypalAPISig"]);
                $method = "DoExpressCheckoutPayment";
                $env = $p["paypalEnv"];
                $nvp = array(
                'TOKEN' => $_GET['token'],
                'PAYERID' => $_GET['PayerID'],
                'AMT' => number_format( $post_content['total'], 2 ),
                "PAYMENTACTION" => 'Sale',
                "CURRENCYCODE" => $p["paypalCurrency"],
                'RETURNURL' => $post_content['ticket_url'] . '&paymentSuccessful=' . $post_content['purchase_id'],
                'CANCELURL' => $post_content['ticket_url']
                );
                $nvpStr = nvp($nvp);
                $resp = PPHttpPost($method, $nvpStr, $cred, $env);

                echo '<pre>'; var_dump( $resp ); echo '</pre>';
                if( "SUCCESS" == strtoupper($resp["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($resp["ACK"]) ) {
                // Update post with payment info
                $post_content['TIMESTAMP'] = $resp['TIMESTAMP'];
                $post_content['CORRELATIONID'] = $resp['CORRELATIONID'];
                $post_content['PAYMENTTYPE'] = $resp['PAYMENTTYPE'];
                $post_content['ACK'] = $resp['ACK'];
                $post_content['ORDERTIME'] = $resp['ORDERTIME'];
                $post_content['AMT'] = $resp['AMT'];
                $post_content['FEEAMT'] = $resp['FEEAMT'];
                $post_content['TAXAMT'] = $resp['TAXAMT'];
                $post_content['PAYMENTSTATUS'] = $resp['PAYMENTSTATUS'];

                $purchase->post_content = serialize( $post_content );

                wp_update_post( $purchase );
                header('Location: '. $post_content['ticket_url'] . '&paymentSuccessful=1');
                }

                }
                }
                public function settingsForm( $data ) {
                ?>

                <table class="form-table">			
                    <tr valign="top" id="tags">
                        <th scope="row"><label for="paypalEnv">Environment: </label></th>
                        <td><select id="paypalEnv" name="paypalEnv">
                                <option value="live" <?php selected($data["paypalEnv"], "live"); ?>>Live</option>
                                <option value="sandbox" <?php selected($data["paypalEnv"], "sandbox"); ?>>Sandbox (for testing)</option>
                            </select></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="paypalAPIUser">API User: </label></th>
                        <td><input id="paypalAPIUser" type="text" maxlength="110" size="45" name="paypalAPIUser" value="<?php echo $data["paypalAPIUser"]; ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="paypalAPIPwd">API Password: </label></th>
                        <td><input id="paypalAPIPwd" type="text" maxlength="110" size="24" name="paypalAPIPwd" value="<?php echo $data["paypalAPIPwd"]; ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="paypalAPISig">API Signature: </label></th>
                        <td><input id="paypalAPISig" type="text" maxlength="110" size="75" name="paypalAPISig" value="<?php echo $data["paypalAPISig"]; ?>" /></td>

                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="paypalCurrency">Currency: </label></th>
                        <td>
                            <select id="paypalCurrency" name="paypalCurrency">
                                <option value="USD" <?php selected($data["paypalCurrency"], "USD"); ?>>U.S. Dollars ($)</option>
                                <option value="AUD" <?php selected($data["paypalCurrency"], "AUD"); ?>>Australian Dollars (A $)</option>
                                <option value="CAD" <?php selected($data["paypalCurrency"], "CAD"); ?>>Canadian Dollars (C $)</option>
                                <option value="EUR" <?php selected($data["paypalCurrency"], "EUR"); ?>>Euros (€)</option>
                                <option value="GBP" <?php selected($data["paypalCurrency"], "GBP"); ?>>Pounds Sterling (£)</option>
                                <option value="JYP" <?php selected($data["paypalCurrency"], "JYP"); ?>>Yen (¥)</option>
                                <option value="NZD" <?php selected($data["paypalCurrency"], "NZD"); ?>>New Zealand Dollar ($)</option>
                                <option value="CHF" <?php selected($data["paypalCurrency"], "CHF"); ?>>Swiss Franc</option>
                                <option value="HKD" <?php selected($data["paypalCurrency"], "HKD"); ?>>Hong Kong Dollar ($)</option>
                                <option value="SGD" <?php selected($data["paypalCurrency"], "SGD"); ?>>Singapore Dollar ($)</option>
                                <option value="SEK" <?php selected($data["paypalCurrency"], "SEK"); ?>>Swedish Krona</option>
                                <option value="DKK" <?php selected($data["paypalCurrency"], "DKK"); ?>>Danish Krone</option>
                                <option value="PLN" <?php selected($data["paypalCurrency"], "PLN"); ?>>Polish Zloty</option>
                                <option value="NOK" <?php selected($data["paypalCurrency"], "NOK"); ?>>Norwegian Krone</option>
                                <option value="HUF" <?php selected($data["paypalCurrency"], "HUF"); ?>>Hungarian Forint</option>
                                <option value="CZK" <?php selected($data["paypalCurrency"], "CZK"); ?>>Czech Koruna</option>
                                <option value="ILS" <?php selected($data["paypalCurrency"], "ILS"); ?>>Israeli Shekel</option>
                                <option value="MXN" <?php selected($data["paypalCurrency"], "MXN"); ?>>Mexican Peso</option>
                                <option value="BRL" <?php selected($data["paypalCurrency"], "BRL"); ?>>Brazilian Real (only for Brazilian users)</option>
                                <option value="MYR" <?php selected($data["paypalCurrency"], "MYR"); ?>>Malaysian Ringgits (only for Malaysian users)</option>
                                <option value="PHP" <?php selected($data["paypalCurrency"], "PHP"); ?>>Philippine Pesos</option>
                                <option value="TWD" <?php selected($data["paypalCurrency"], "TWD"); ?>>Taiwan New Dollars</option>
                                <option value="THB" <?php selected($data["paypalCurrency"], "THB"); ?>>Thai Baht</option>
                            </select>
                        </td>
                    </tr>


                </table>
                <?php
            }

        }

        // end class
        $gateways[] = new PayPalPayFlow('PayPal PayFlow', 'PayPalPayFlow');
    } // end if( !class_exists( 'PayPalExpress' ) ) 
    return $gateways;
}

// end function