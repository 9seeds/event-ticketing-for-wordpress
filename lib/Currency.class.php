<?php

/**
 * @since 2.0 
 */
class WPET_Currency extends WPET_Module {
    
    private $mCurrencies = array();

	/**
	 * @since 2.0 
	 */
	public function __construct() {
		add_filter( 'wpet_currencies', array( $this, 'defaultCurrencies' ) );
		$this->mCurrencies = apply_filters( 'wpet_currencies', array() );
	}

	public function getCurrencies() {
	    return $this->mCurrencies;
	}
	
	public function defaultCurrencies( $currencies ) {
		$c = array(
		    array(
			'display' => 'United States Dollar',
			'code' => 'USD',
			'symbol' => '$',
			'location' => 'before'
		    ),
		    array(
			'display' => 'Czech koruna',
			'code' => 'CZK',
			'symbol' => 'Kč',
			'location' => 'after'
		    ),
		    array(
			'display' => 'Afghan afghani',
			'code' => 'AFN',
			'symbol' => 'Afs',
			'location' => 'after'
		    ),
		    
		);
		
		foreach( $c AS $cur ) {
		    $currencies[] = $cur;
		}
		return $currencies;
	}
	
	public function selectMenu( $name ) {
	    $s = "<select name='$name' id='$name'>";
	    
	    foreach( $this->getCurrencies() AS $currency ) {
		$s .= '<option value="' . $currency['code'] . '">';
		
		$s .= $currency['display'] . ' ( ' . $currency['symbol'] . ' )';
		
		$s .= '</option>';
	    }
	    
	    $s .= '</select>';
	    return $s;
	}

}// end class