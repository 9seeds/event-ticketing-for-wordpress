<?php

/**
 * @since 2.0
 */
class WPET_Currency extends WPET_Module {

	/**
	 * @since 2.0
	 */
	public function getCurrencies() {
		add_filter( 'wpet_currencies', array( $this, 'defaultCurrencies' ), 9 ); //populate default currencies before the normal '10' level priority
		return apply_filters( 'wpet_currencies', array() );
	}

	public function format( $code, $number ) {
	   $currency = $this->getCurrency( $code );

	   $num = number_format( (double)$number, 2, $currency['dec_point'], $currency['thousands_sep'] );

	   switch( $currency['location'] ) {
	       case 'before':
		   $num = $currency['symbol'] . $num;
		   break;
	       case 'after':
		   $num = $num . ' ' . $currency['symbol'];
		   break;
	   }
	   return $num;
	}

	public function getCurrency( $code ) {
	    $currencies = $this->getCurrencies();
	    $currency = array();
	    foreach( $currencies AS $c ) {
		if( $code == $c['code'] ) {
		    $currency = $c;
		}
	    }
	    return $currency;
	}

	public function defaultCurrencies( $currencies ) {
		$c = array(
			array(
				'display' => 'United States Dollar',
				'code' => 'USD',
				'symbol' => '$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Afghan Afghani',
				'code' => 'AFN',
				'symbol' => 'Afs',
				'location' => 'after',
				'dec_point' => '.',
				'thousands_sep' => ','
		    ),
			array(
				'display' => 'Australian Dollars',
				'code' => 'AUD',
				'symbol' => 'A$',
				'location' => 'before',
				'decimal' => '.',
				'thousands' => ','
		    ),
			array(
				'display' => 'Brazilian Real (only for Brazilian users)',
				'code' => 'BRL',
				'symbol' => 'R$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Canadian Dollars',
				'code' => 'CAD',
				'symbol' => 'C$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Czech Koruna',
				'code' => 'CZK',
				'symbol' => 'Kč',
				'location' => 'after',
				'dec_point' => '.',
				'thousands_sep' => ','
		    ),
			array(
				'display' => 'Danish Krone',
				'code' => 'DKK',
				'symbol' => 'kr.',
				'location' => 'after',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Euros',
				'code' => 'EUR',
				'symbol' => '€',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Hong Kong Dollars',
				'code' => 'HKD',
				'symbol' => 'HK$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Hungarian Forint',
				'code' => 'HUF',
				'symbol' => 'Ft',
				'location' => 'after',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Israeli Shekel',
				'code' => 'ILS',
				'symbol' => '₪',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Malaysian Ringgits (only for Malaysian users)',
				'code' => 'MYR',
				'symbol' => 'RM',
				'location' => 'after',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Mexican Peso',
				'code' => 'MXN',
				'symbol' => 'Mex$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'New Zealand Dollars',
				'code' => 'NZD',
				'symbol' => '$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Norwegian Krone',
				'code' => 'NOK',
				'symbol' => 'kr',
				'location' => 'after',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Philippine Pesos',
				'code' => 'PHP',
				'symbol' => '₱',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Polish Zloty',
				'code' => 'PLN',
				'symbol' => 'zł',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Pounds Sterling',
				'code' => 'GBP',
				'symbol' => '£',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Singapore Dollar',
				'code' => 'SGD',
				'symbol' => '$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Swedish Krona',
				'code' => 'SEK',
				'symbol' => 'kr',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Swiss Franc',
				'code' => 'CHF',
				'symbol' => 'CHF',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Taiwan New Dollars',
				'code' => 'TWD',
				'symbol' => '$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Thai Baht',
				'code' => 'THB',
				'symbol' => '฿',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => 'Yen',
				'code' => 'JYP',
				'symbol' => '¥',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
		);

		foreach( $c AS $cur ) {
		    $currencies[] = $cur;
		}
		return $currencies;
	}

	/**
	 * @since 2.0
	 * @param string $name html name
	 * @param string $id html id
	 * @param string $selected_value selected currency code
	 * @param array $allowed allowed currency codes
	 */
	public function selectMenu( $name, $id, $selected_value, $allowed = array() ) {
	    $s = "<select name='{$name}' id='{$id}'>";

		$currencies = $this->getCurrencies();
		if ( ! empty( $allowed ) ) {
			foreach ( $currencies as $index => $currency_info ) {
				if ( ! in_array( $currency_info['code'], $allowed ) )
					unset( $currencies[$index] );
			}
		}
		
	    foreach( $currencies as $currency ) {
			$s .= "<option value='{$currency['code']}'";
			$s .= selected( $selected_value, $currency['code'], false ) ;
			$s .= ">{$currency['display']} ( {$currency['symbol']} )</option>\n";
	    }

	    $s .= '</select>';
	    return $s;
	}

}// end class