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
				'display' => __( 'United States Dollar', 'wpet' ),
				'code' => 'USD',
				'symbol' => '$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Afghan Afghani', 'wpet' ),
				'code' => 'AFN',
				'symbol' => 'Afs',
				'location' => 'after',
				'dec_point' => '.',
				'thousands_sep' => ','
		    ),
			array(
				'display' => __( 'Australian Dollars', 'wpet' ),
				'code' => 'AUD',
				'symbol' => 'A$',
				'location' => 'before',
				'decimal' => '.',
				'thousands' => ','
		    ),
			array(
				'display' => __( 'Brazilian Real (only for Brazilian users)', 'wpet' ),
				'code' => 'BRL',
				'symbol' => 'R$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Canadian Dollars', 'wpet' ),
				'code' => 'CAD',
				'symbol' => 'C$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Czech Koruna', 'wpet' ),
				'code' => 'CZK',
				'symbol' => 'Kč',
				'location' => 'after',
				'dec_point' => '.',
				'thousands_sep' => ','
		    ),
			array(
				'display' => __( 'Danish Krone', 'wpet' ),
				'code' => 'DKK',
				'symbol' => 'kr.',
				'location' => 'after',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Euros', 'wpet' ),
				'code' => 'EUR',
				'symbol' => '€',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Hong Kong Dollars', 'wpet' ),
				'code' => 'HKD',
				'symbol' => 'HK$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Hungarian Forint', 'wpet' ),
				'code' => 'HUF',
				'symbol' => 'Ft',
				'location' => 'after',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Israeli Shekel', 'wpet' ),
				'code' => 'ILS',
				'symbol' => '₪',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Malaysian Ringgits (only for Malaysian users)', 'wpet' ),
				'code' => 'MYR',
				'symbol' => 'RM',
				'location' => 'after',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Mexican Peso', 'wpet' ),
				'code' => 'MXN',
				'symbol' => 'Mex$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'New Zealand Dollars', 'wpet' ),
				'code' => 'NZD',
				'symbol' => '$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Norwegian Krone', 'wpet' ),
				'code' => 'NOK',
				'symbol' => 'kr',
				'location' => 'after',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Philippine Pesos', 'wpet' ),
				'code' => 'PHP',
				'symbol' => '₱',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Polish Zloty', 'wpet' ),
				'code' => 'PLN',
				'symbol' => 'zł',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Pounds Sterling', 'wpet' ),
				'code' => 'GBP',
				'symbol' => '£',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Singapore Dollar', 'wpet' ),
				'code' => 'SGD',
				'symbol' => '$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Swedish Krona', 'wpet' ),
				'code' => 'SEK',
				'symbol' => 'kr',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Swiss Franc', 'wpet' ),
				'code' => 'CHF',
				'symbol' => 'CHF',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Taiwan New Dollars', 'wpet' ),
				'code' => 'TWD',
				'symbol' => '$',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Thai Baht', 'wpet' ),
				'code' => 'THB',
				'symbol' => '฿',
				'location' => 'before',
				'dec_point' => '.',
				'thousands_sep' => ','
			),
			array(
				'display' => __( 'Yen', 'wpet' ),
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