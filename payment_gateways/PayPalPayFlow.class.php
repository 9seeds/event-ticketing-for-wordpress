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
                    "payflowVendor" => trim($_POST["payflowVendor"]),
                    "payflowPartner" => trim($_POST["payflowPartner"]),
                    "payflowUser" => trim($_POST["payflowUser"]),
                    "payflowPassword" => trim($_POST["payflowPassword"]),
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
                // show payment info form
                 // echo '<pre>'; print_r( $args ); echo '</pre>';
                ?>
                <div id="eventTicketing">
                    <form method="post" action="">
                        <input type="hidden" name="args" value="<?php echo serialize($args); ?>" />
                        <input type="hidden" name="hasPaymentInfo" value="1" />
                        <input type="hidden" name="paymentReturn" value="<?php echo $args['purchase_id']; ?>" />
                        <input type="hidden" name="event" value="<?php echo $args['event']; ?>"/>
                        <input type="hidden" name="total" value="<?php echo $args['total']; ?>" />
                        <input type="hidden" name="purchase_post_id" value="<?php echo $args['purchase_post_id']; ?>"/>

                        <table>
                            <tr>
                                <th><label for="nameOnCard">Name on card:</label></th>
                                <td><input type="text" id="nameOnCard" name="nameOnCard" /></td>
                            </tr>

                            <tr>
                                <th><label for="cardNumber">Card number:</label></th>
                                <td><input type="text" id="cardNumber" name="cardNumber" /></td>
                            </tr>

                            <tr>
                                <th><label for="cardSecurityCode">Card security code:</label></th>
                                <td><input type="text" id="cardSecurityCode" name="cardSecurityCode" /></td>
                            </tr>

                            <tr>
                                <th>Expiration:</th>
                                <td>
                                    <select name="expirationMonth">
                                        <?php for ($i = 1; $i <= 12; $i++) { ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>

                                    <select name="expirationYear">
                                        <?php
                                        $current_year = date('Y', time());
                                        $end_year = $current_year + 10;
                                        for ($current_year; $current_year <= $end_year; $current_year++) {
                                            ?>
                                            <option value="<?php echo $current_year; ?>"><?php echo $current_year; ?></option>
                <?php } ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2"><input type="submit" name="submit_payment_info" value="Submit Payment Info" /></td>
                            </tr>

                        </table>


                    </form>
                </div>
                <?php
            }

            // We get to this function through RETURNURL
            public function processPaymentReturn($ticket_id, $o) {
               
                // Make sure the proper items are set

                $p = WPEVT::instance()->gateway()->getSettings();
                echo '<pre>';
                print_r($p);
                echo '</pre>';

                require_once(WPEVT_DIR . '/lib/Class.PayFlow.php');
                /**
                 * Create new Single Transaction.
                 */
                $PayFlow = new PayFlow( $p['payflowVendor'], $p['payflowPartner'], $p['payflowUser'], $p['payflowPassword'] , 'single');

                $PayFlow->setEnvironment('test');
                $PayFlow->setTransactionType('S');
                $PayFlow->setPaymentMethod('C');
                $PayFlow->setPaymentCurrency('USD');

                $PayFlow->setAmount( number_format( $_POST['total'], 2 ), FALSE);
                $PayFlow->setCCNumber( $_POST['cardNumber']);
                $PayFlow->setCVV( $_POST['cardSecurityCode'] );
                
                
                $card_expiration = $_POST['expirationMonth'] . substr( $_POST['expirationYear'], 2 );
               
                $PayFlow->setExpiration('1112');
                $PayFlow->setCreditCardName('Richard Castera');

//                    $PayFlow->setCustomerFirstName('Richard');
//                    $PayFlow->setCustomerLastName('Castera');
//                    $PayFlow->setCustomerAddress('589 8th Ave Suite 10');
//                    $PayFlow->setCustomerCity('New York');
//                    $PayFlow->setCustomerState('NY');
//                    $PayFlow->setCustomerZip('10018');
//                    $PayFlow->setCustomerCountry('US');
//                    $PayFlow->setCustomerPhone('212-123-1234');
//                    $PayFlow->setCustomerEmail('richard.castera@gmail.com');
//                    $PayFlow->setPaymentComment('New Regular Transaction');
//                    $PayFlow->setPaymentComment2('Product 233');

                if ($PayFlow->processTransaction()) {
                    //echo('Transaction Processed Successfully!');
                    $purchase = get_page_by_title( $ticket_id, OBJECT, 'wpevt_purchase' );
                    
                    // Update the ticket with the PayPal details for posterity
                    $post_content = unserialize( $purchase->post_content );
                    
                    $post_content['payflowResponse'] = $PayFlow->getResponse();
                    $post_content['payflowDebug'] = $PayFlow->debugNVP('array');
                    
                    $purchase->post_content = serialize( $post_content );
                    
                    wp_update_post( $purchase );
                    
                    header('Location: '. $post_content['ticket_url'] . '&paymentSuccessful=1');
                } else {
                    echo('Transaction from PayPal could not be processed at this time.');
                    $purchase = get_page_by_title( $ticket_id, OBJECT, 'wpevt_purchase' );
                    
                    // Update the ticket with the PayPal details for posterity
                    $post_content = unserialize( $purchase->post_content );
                    
                    $post_content['payflowResponse'] = $PayFlow->getResponse();
                    $post_content['payflowDebug'] = $PayFlow->debugNVP('array');
                    
                    $purchase->post_content = serialize( $post_content );
                    
                    wp_update_post( $purchase );
                }

//                echo('<h2>Name Value Pair String:</h2>');
//                echo('<pre>');
//                print_r($PayFlow->debugNVP('array'));
//                echo('</pre>');
//
//                echo('<h2>Response From Paypal:</h2>');
//                echo('<pre>');
//                print_r($PayFlow->getResponse());
//                echo('</pre>');
            }

            public function settingsForm($data) {
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
                        <th scope="row"><label for="payflowVendor">Vendor: </label></th>
                        <td><input id="payflowVendor" type="text" maxlength="110" size="45" name="payflowVendor" value="<?php echo @$data["payflowVendor"]; ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="payflowPartner">Partner: </label></th>
                        <td><input id="payflowPartner" type="text" maxlength="110" size="24" name="payflowPartner" value="<?php echo @$data["payflowPartner"]; ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="payflowUser">User: </label></th>
                        <td><input id="payflowUser" type="text" maxlength="110" size="75" name="payflowUser" value="<?php echo @$data["payflowUser"]; ?>" /></td>

                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="payflowPassword">Password: </label></th>
                        <td><input id="payflowPassword" type="text" maxlength="110" size="75" name="payflowPassword" value="<?php echo @$data["payflowPassword"]; ?>" /></td>

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