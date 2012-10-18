<?php
/**
 * PayPal Express built-in WPEVT payment gateway
 * 
 * @since 1.4
 * @author Ben Lobaugh 
 */

add_filter( 'wpevt_register_payment_gateway', 'wpevt_pg_paypal_express' );


function wpevt_pg_paypal_express( $gateways ) { 
    if( !class_exists( 'PayPalExpress' ) ) {
        class PayPalExpress extends WPEVT_PaymentGateway {

            public function saveSettings() {
                
                $data = array(
                    "paypalAPIUser" => trim( $_POST["paypalAPIUser"] ),
                    "paypalAPIPwd" => trim( $_POST["paypalAPIPwd"] ),
                    "paypalAPISig" => trim( $_POST["paypalAPISig"] ),
                    "paypalEnv" => trim( $_POST["paypalEnv"] ),
                    "paypalCurrency" => $_POST["paypalCurrency"]
                );
                
                parent::saveSettings( $data );
            }
            
            public function button() {
                return '<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" name="paymentButton" />';
            }
            
            
            public function settingsForm( $data ) {
                ?>

			<table class="form-table">			
			<tr valign="top" id="tags">
				<th scope="row"><label for="paypalEnv">Environment: </label></th>
				<td><select id="paypalEnv" name="paypalEnv">
					<option value="live" <?php selected($data["paypalEnv"], "live" ); ?>>Live</option>
					<option value="sandbox" <?php selected($data["paypalEnv"], "sandbox" ); ?>>Sandbox (for testing)</option>
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
						<option value="USD" <?php selected($data["paypalCurrency"], "USD" ); ?>>U.S. Dollars ($)</option>
						<option value="AUD" <?php selected($data["paypalCurrency"], "AUD" ); ?>>Australian Dollars (A $)</option>
						<option value="CAD" <?php selected($data["paypalCurrency"], "CAD" ); ?>>Canadian Dollars (C $)</option>
						<option value="EUR" <?php selected($data["paypalCurrency"], "EUR" ); ?>>Euros (€)</option>
						<option value="GBP" <?php selected($data["paypalCurrency"], "GBP" ); ?>>Pounds Sterling (£)</option>
						<option value="JYP" <?php selected($data["paypalCurrency"], "JYP" ); ?>>Yen (¥)</option>
						<option value="NZD" <?php selected($data["paypalCurrency"], "NZD" ); ?>>New Zealand Dollar ($)</option>
						<option value="CHF" <?php selected($data["paypalCurrency"], "CHF" ); ?>>Swiss Franc</option>
						<option value="HKD" <?php selected($data["paypalCurrency"], "HKD" ); ?>>Hong Kong Dollar ($)</option>
						<option value="SGD" <?php selected($data["paypalCurrency"], "SGD" ); ?>>Singapore Dollar ($)</option>
						<option value="SEK" <?php selected($data["paypalCurrency"], "SEK" ); ?>>Swedish Krona</option>
						<option value="DKK" <?php selected($data["paypalCurrency"], "DKK" ); ?>>Danish Krone</option>
						<option value="PLN" <?php selected($data["paypalCurrency"], "PLN" ); ?>>Polish Zloty</option>
						<option value="NOK" <?php selected($data["paypalCurrency"], "NOK" ); ?>>Norwegian Krone</option>
						<option value="HUF" <?php selected($data["paypalCurrency"], "HUF" ); ?>>Hungarian Forint</option>
						<option value="CZK" <?php selected($data["paypalCurrency"], "CZK" ); ?>>Czech Koruna</option>
						<option value="ILS" <?php selected($data["paypalCurrency"], "ILS" ); ?>>Israeli Shekel</option>
						<option value="MXN" <?php selected($data["paypalCurrency"], "MXN" ); ?>>Mexican Peso</option>
						<option value="BRL" <?php selected($data["paypalCurrency"], "BRL" ); ?>>Brazilian Real (only for Brazilian users)</option>
						<option value="MYR" <?php selected($data["paypalCurrency"], "MYR" ); ?>>Malaysian Ringgits (only for Malaysian users)</option>
						<option value="PHP" <?php selected($data["paypalCurrency"], "PHP" ); ?>>Philippine Pesos</option>
						<option value="TWD" <?php selected($data["paypalCurrency"], "TWD" ); ?>>Taiwan New Dollars</option>
						<option value="THB" <?php selected($data["paypalCurrency"], "THB" ); ?>>Thai Baht</option>
					</select>
				</td>
			</tr>
			
			
			</table>
                 <?php
            }
        } // end class
        $gateways[] = new PayPalExpress( 'PayPal Express', 'PayPalExpress');
    } // end if( !class_exists( 'PayPalExpress' ) ) 
    return $gateways;
} // end function