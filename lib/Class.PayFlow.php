<?php
/**
* @uses Paypal Payflow Class in PHP5
* @author Richard Castera
* @link http://www.richardcastera.com/projects/paypal-payflow-api-wrapper-class
* @version 0.01
* @copyright Richard Castera 2010 Copyright
* @access Public
* @see http://www.php.net/manual/en/book.session.php
* @license GNU LESSER GENERAL Public LICENSE
*/
 
class PayFlow {
  
  
  /**
* @uses Your merchant login ID that you created when you registered for the account.
* @access Private
* @var String
*/
private $vendor = '';

  
  /**
* @uses The ID provided to you by the authorized PayPal Reseller who registered you for the Payflow SDK.
* @access Private
* @var String
*/
  private $partner = '';

  
  /**
* @uses This value is the ID of the user authorized to process transactions.
* @access Private
* @var String
*/
  private $user = '';
  
  
  /**
* @uses The password that you defined while registering for the account.
* @access Private
* @var String
*/
  private $password = '';
  
  
  /**
* @uses The environment - (test or live).
* @access Private
* @var String
*/
  private $environment = '';
  
  
  /**
* @uses The type of billing transaction - (single or recurring).
* @access Private
* @var String
*/
  private $billingType = '';
  
  
  /**
* @uses Contains the URLS for submitting a transaction.
* @access Private
* @var Array
*/
  private $gatewayURL = array (
    'live'=>'https://payflowpro.paypal.com',
    'test'=>'https://pilot-payflowpro.paypal.com',
  );
  
  
  /**
* @uses Contains the keys for submitting a transaction to PayPal.
* @access Private
* @var Array
*/
  private $NVP = array();
  
  
 /**
* @uses Contains an array of values returned from processing the transaction.
* @access Private
* @var Array
*/
  private $response = '';
  
  
  
  
  
  /**
* @uses Constructor - User paramters to provide the merchant authentication required for access to the payment gateway.
* @access Public
* @param String $vendor - Your merchant login ID that you created when you registered for the account.
* @param String $partner - The ID provided to you by the authorized PayPal Reseller who registered you for the Payflow SDK.
* @param String $user - If you set up one or more additional users on the account, this value is the ID of the user authorized to process transactions. If, however, you have not set up additional users on the account, USER has the same value as VENDOR.
* @param String $password - The password that you defined while registering for the account.
* @param String $billingType - Type of billing transaction this will be 'single' or 'recurring'.
* @return None.
* @example $PayFlow = new PayFlow('VENDER', 'PARTNER', 'USER', 'PASSWORD', 'single');
*/
  public function __construct($vendor = '', $partner = '', $user = '', $password = '', $billingType = 'single') {
    $this->vendor = $this->truncateChars($vendor, 64);
$this->partner = $this->truncateChars($partner, 64);
    $this->user = $this->truncateChars($user, 64);
$this->password = $this->truncateChars($password, 32);
    $this->billingType = $billingType;
    
    // Setup some default values.
    $this->setupDefaults();
  }
  
  
  /**
* @uses Destructor.
* @access Public
* @param None.
* @return None.
* @example unset($obj);
*/
  public function __destruct() {
    unset($this);
  }


  /**
* @uses Sets up default transaction information.
* @access Private
* @param None.
* @return None.
* @example $this->setupDefaults();
*/
  private function setupDefaults() {
    $defaults = array(
      'VENDOR'=>$this->vendor,
      'PARTNER'=>$this->partner,
      'USER'=>$this->user,
      'PWD'=>$this->password,
      'CUSTIP'=>$_SERVER['REMOTE_ADDR'],
      'VERBOSITY'=>'MEDIUM',
    );
    
    $this->NVP = array_merge($this->NVP, $defaults);
  }
  
  
  /**
* @uses Sets the Environment of the transaction.
* @access Public
* @param String $environment - Available values: ('test', 'live').
* @return None.
* @example $PayFlow->setEnvironment('test');
*/
  public function setEnvironment($environment = 'test') {
    if(strtolower($environment) == 'test') {
      $this->environment = $this->gatewayURL['test'];
    }
    else {
      $this->environment = $this->gatewayURL['live'];
    }
  }
  
  
  /**
* @uses Returns the Environment of the transaction.
* @access Public
* @param None.
* @return String - The environment set.
* @example $PayFlow->getEnvironment();
*/
  public function getEnvironment() {
    return $this->environment;
  }
  
  
  /**
* @uses Sets the type of transaction.
* @access Public
* @param String $transactionType - Available values: S = Sale transaction, R = Recurring, C = Credit, A = Authorization, D = Delayed Capture, V = Void, F = Voice Authorization, I = Inquiry, N = Duplicate transaction
* @return None.
* @example $PayFlow->setTransactionType('S');
*/
  public function setTransactionType($transactionType = 'S') {
    $type = array(
      'TRXTYPE'=>strtoupper($transactionType),
    );
  
    $this->NVP = array_merge($this->NVP, $type);
  }
  
  
  /**
* @uses Sets the Payment Method.
* @access Public
* @param String $paymentMethod - Available values: A = Automated clearinghouse, C = Credit card, D = Pinless debit, K = Telecheck, P = PayPal.
* @return None.
* @example $PayFlow->setPaymentMethod('C');
*/
  public function setPaymentMethod($paymentMethod = 'C') {
    $method = array(
      'TENDER'=>strtoupper($paymentMethod),
    );
  
    $this->NVP = array_merge($this->NVP, $method);
  }
  
  
  /**
* @uses Sets the Payment Currency.
* @access Public
* @param String $paymentCurrency - Available values: 'USD', 'EUR', 'GBP', 'CAD', 'JPY', 'AUD'.
* @return None.
* @example $PayFlow->setPaymentCurrency('USD');
*/
  public function setPaymentCurrency($paymentCurrency = 'USD') {
    $currency = array(
      'CURRENCY'=>strtoupper($paymentCurrency),
    );
  
    $this->NVP = array_merge($this->NVP, $currency);
  }
  
  
  /**
* @uses Sets the Profile Action for recurring payments.
* @access Public
* @param String $profileAction - Available values: A = Add, M = Modify, R = Reactivate, C = Cancel, I = Inquiry, P = Payment.
* @return None.
* @example $PayFlow->setProfileAction('A');
*/
  public function setProfileAction($profileAction = 'A') {
    $action = array(
      'ACTION'=>strtoupper($profileAction),
    );
  
    $this->NVP = array_merge($this->NVP, $action);
  }
  
  
  /**
* @uses Sets the Profile name for recurring payments.
* @access Public
* @param String $profileName - Non-unique Name for the profile (user-specified). Can be used to search for a profile.
* @return None.
* @example $PayFlow->setProfileName('RegularSubscription');
*/
  public function setProfileName($profileName = 'RecurringTransaction') {
    $name = array(
      'PROFILENAME'=>strtoupper($profileName),
    );
  
    $this->NVP = array_merge($this->NVP, $name);
  }
  
  
  /**
* @uses Sets the Profile Start Date for recurring payments.
* @access Public
* @param String $profileStartDate - Beginning date for the recurring billing cycle used to calculate when payments should be made. Use tomorrow's date or a date in the future. Format: MMDDYYYY.
* @return None.
* @example $PayFlow->setProfileStartDate('01072011');
*/
  public function setProfileStartDate($profileStartDate = '') {
    if($profileStartDate == '') {
      $profileStartDate = date('mdY', strtotime("+1 day"));;
    }
    $date = array(
      'START'=>strtoupper($profileStartDate),
    );
  
    $this->NVP = array_merge($this->NVP, $date);
  }
  
  
  /**
* @uses Sets the Profile Pay Period for recurring payments.
* @access Public
* @param String $profilePayPeriod - Specifies how often the payment occurs. Available values: including all capital letters.
* WEEK: Weekly - Every week on the same day of the week as the first payment.
* BIWK: Every Two Weeks - Every other week on the same day of the week as the first payment.
* SMMO: Twice Every Month - The 1st and 15th of the month. Results in 24 payments per year. SMMO can start on 1st to 15th of the month, second payment 15 days later or on the last day of the month.
* FRWK: Every Four Weeks - Every 28 days from the previous payment date beginning with the first payment date. Results in 13 payments per year.
* MONT: Monthly - Every month on the same date as the first payment. Results in 12 payments per year.
* QTER: Quarterly - Every three months on the same date as the first payment.
* SMYR: Twice Every Year - Every six months on the same date as the first payment.
* YEAR: Yearly - Every 12 months on the same date as the first payment.
* @return None.
* @example $PayFlow->setProfileStartDate('MONT');
*/
  public function setProfilePayPeriod($profilePayPeriod = 'MONT') {
    $period = array(
      'PAYPERIOD'=>strtoupper($profilePayPeriod),
    );
  
    $this->NVP = array_merge($this->NVP, $period);
  }
  
  
  /**
* @uses Sets the Profile Term for recurring payments.
* @access Public
* @param String $profileTerm - Number of payments to be made over the life of the agreement. A value of 0 means that payments should continue until the profile is deactivated.
* @return None.
* @example $PayFlow->setProfileTerm(0);
*/
  public function setProfileTerm($profileTerm = 0) {
    $term = array(
      'TERM'=>strtoupper($profileTerm),
    );
  
    $this->NVP = array_merge($this->NVP, $term);
  }
  
  
  /**
* @uses Sets the Amount of the transaction. Up to 15 digits with a decimal point (no dollar symbol)
* @access Public
* @param String/Integer/Float - $amount - 150.00.
* @param Boolean - $wholeAmt - True to remove decimal valuesfalse, to keep it.
* @return None.
* @example $PayFlow->setAmount(150.00);
*/
  public function setAmount($amount = 0, $wholeAmt) {
    $amt = array(
      'AMT'=>$this->cleanAmt($amount, $wholeAmt),
    );
  
    $this->NVP = array_merge($this->NVP, $amt);
  }


  /**
* @uses Sets the Customer's Credit Card Number. Between 13 and 16 digits without spaces.
* @access Public
* @param String $number - The Credit Card Number. Dashes will be striped.
* @return None.
* @example $PayFlow->setCCNumber('1234-1234-1234-1234');
*/
  public function setCCNumber($number = '') {
    $cc = array(
      'ACCT'=>$this->cleanCCNumber($number),
    );
  
    $this->NVP = array_merge($this->NVP, $cc);
  }
  
  
  /**
* @uses Sets the Customer's Credit Card Expiration Date. MMYY
* @access Public
* @param String $expiration - The Customer's Credit Card Expiration Date
* @return None.
* @example $PayFlow->setExpiration('0312');
*/
  public function setExpiration($expiration = '0000') {
    $exp = array(
      'EXPDATE'=>$this->cleanExpDate($expiration),
    );
  
    $this->NVP = array_merge($this->NVP, $exp);
  }


  /**
* @uses Sets the Customer's card code. The three- or four-digit number on the back of a credit card (on the front for American Express).
* @access Public
* @param String $cvv - The Customer's Credit Card Security Code
* @return None.
* @example $PayFlow->setCVV('0000');
*/
  public function setCVV($cvv = '') {
    $security = array(
      'CVV2'=>$cvv,
    );
  
    $this->NVP = array_merge($this->NVP, $security);
  }

  
  /**
* @uses Sets the Customer's Credit Card Name. Up to 50 characters
* @access Public
* @param String $cardName - The First Name associated with the Customer's Billing Address.
* @return None.
* @example $PayFlow->setCreditCardName('Richard');
*/
  public function setCreditCardName($cardName = '') {
    $name = array(
      'NAME'=>$this->truncateChars($cardName, 50),
    );
  
    $this->NVP = array_merge($this->NVP, $name);
  }
  
  
  /**
* @uses Sets the First Name associated with the Customer's Billing Address. Up to 30 characters (no symbols)
* @access Public
* @param String $firstName - The First Name associated with the Customer's Billing Address.
* @return None.
* @example $PayFlow->setCustomerFirstName('Richard');
*/
  public function setCustomerFirstName($firstName = '') {
    $first = array(
      'FIRSTNAME'=>$this->truncateChars($firstName, 30),
    );
  
    $this->NVP = array_merge($this->NVP, $first);
  }


  /**
* @uses Sets the Last Name associated with the Customer's Billing Address. Up to 30 characters (no symbols)
* @access Public
* @param String $lastName - The Last Name associated with the Customer's Billing Address.
* @return None.
* @example $PayFlow->setCustomerLastName('Castera');
*/
  public function setCustomerLastName($lastName = '') {
    $last = array(
      'LASTNAME'=>$this->truncateChars($lastName, 30),
    );
  
    $this->NVP = array_merge($this->NVP, $last);
  }
  
  
  /**
* @uses Sets the Customer's Billing address. Up to 30 characters (no symbols)
* @access Public
* @param String $customerAddress - The Customer's Billing address.
* @return None.
* @example $PayFlow->setCustomerAddress('589 8th Ave. Suite 10');
*/
  public function setCustomerAddress($customerAddress = '') {
    $address = array(
      'STREET'=>$this->truncateChars($customerAddress, 30),
    );
  
    $this->NVP = array_merge($this->NVP, $address);
  }


  /**
* @uses Sets the Customer's Billing City. Up to 20 characters (no symbols)
* @access Public
* @param String $customerCity - The Customer's Billing City.
* @return None.
* @example $PayFlow->setCustomerCity('New York');
*/
  public function setCustomerCity($customerCity = '') {
    $city = array(
      'CITY'=>$this->truncateChars($customerCity, 20),
    );
  
    $this->NVP = array_merge($this->NVP, $city);
  }


  /**
* @uses Sets the Customer's Billing State. Up to 2 characters (no symbols) a valid two-character state code.
* @access Public
* @param String $customerState - The Customer's Billing State.
* @return None.
* @example $PayFlow->setCustomerState('NY');
*/
  public function setCustomerState($customerState = '') {
    $state = array(
      'STATE'=>$this->truncateChars($customerState, 2),
    );
  
    $this->NVP = array_merge($this->NVP, $state);
  }


  /**
* @uses Sets the Customer's Billing Zip. Up to 9 characters (no symbols).
* @access Public
* @param String $customerZip - The Customer's Billing Zip.
* @return None.
* @example $PayFlow->setCustomerZip('10018');
*/
  public function setCustomerZip($customerZip = '') {
    $zip = array(
      'ZIP'=>$this->truncateChars($customerZip, 9),
    );
  
    $this->NVP = array_merge($this->NVP, $zip);
  }


  /**
* @uses Sets the Customer's Billing Country. Up to 4 characters (no symbols).
* @access Public
* @param String $customerCountry - The Customer's Billing Country.
* @return None.
* @example $PayFlow->setCustomerCountry('US');
*/
  public function setCustomerCountry($customerCountry = '') {
    $country = array(
      'COUNTRY'=>$this->truncateChars($customerCountry, 4),
    );
  
    $this->NVP = array_merge($this->NVP, $country);
  }


  /**
* @uses Sets the Customer's Billing Phone. Up to 20 digits (no letters) Ex. 123-123-1234 - Dashes will be stripped.
* @access Public
* @param String $customerPhone - The Customer's Billing Phone.
* @return None.
* @example $PayFlow->setCustomerPhone('212-123-4567');
*/
  public function setCustomerPhone($customerPhone = '000-000-0000') {
    $phone = array(
      'PHONENUM'=>$this->truncateChars($this->cleanPhoneNumber($customerPhone), 20),
    );
  
    $this->NVP = array_merge($this->NVP, $phone);
  }
  
  
  /**
* @uses Sets the Customer's Email Address. Up to 60 characters.
* @access Public
* @param String $customerEmail - The Customer's Email Address.
* @return None.
* @example $PayFlow->setCustomerEmail('richard.castera@gmail.com');
*/
  public function setCustomerEmail($customerEmail = '') {
    $email = array(
      'EMAIl'=>$this->truncateChars($customerEmail, 60),
    );
  
    $this->NVP = array_merge($this->NVP, $email);
  }
  
  
  /**
* @uses Sets the Payment Description. This is just a commment regarding the transaction for your reference.
* @access Public
* @param String $description - The Payment description.
* @return None.
* @example $PayFlow->setPaymentComment('Purchased product number 34324');
*/
  public function setPaymentComment($description = '') {
    $desc = array(
      'COMMENT1'=>$this->truncateChars($description, 128),
    );
  
    $this->NVP = array_merge($this->NVP, $desc);
  }
  
  
  /**
* @uses Sets the Payment Description. This is just a commment regarding the transaction for your reference.
* @access Public
* @param String $description - The Payment description.
* @return None.
* @example $PayFlow->setPaymentComment2('Purchased product number 34324');
*/
  public function setPaymentComment2($description = '') {
    $desc = array(
      'COMMENT2'=>$this->truncateChars($description, 128),
    );
  
    $this->NVP = array_merge($this->NVP, $desc);
  }
  
  
  /**
* @uses Sets a Merchant-defined field to submit to Paypal.
* @access Public
* @param String $name - The name of the custom field.
* @param String $value - The value of the custom field.
* @return None.
* @example $PayFlow->setCustomField('KEY', 'VALUE');
*/
  public function setCustomField($name = '', $value = '') {
    $custom = array(
      $name=>(string)$value,
    );
  
    $this->NVP = array_merge($this->NVP, $custom);
  }
  
  
  /**
* @uses This get the NVP's that will be sent to Paypal.
* @access Private
* @param None.
* @return String - A string of NVP's.
* @example $this->getNVP();
*/
  private function getNVP() {
    $post = '';
    foreach($this->NVP as $key=>$value) {
      $post .= "$key=" . $value . "&";
    }
    return (string)rtrim($post, "& ");
  }
  
  
  /**
* @uses Sends the request to Paypal for processing.
* @access Public
* @param None.
* @return Boolean - True if the transaction was successful False, if not.
* @example $PayFlow->processTransaction();
*/
  public function processTransaction() {
    // Uses the CURL library for php to establish a connection,
    // submit the post, and record the response.
    if(function_exists('curl_init') && extension_loaded('curl')) {
      $request = curl_init($this->getEnvironment()); // Initiate curl object
      curl_setopt($request, CURLOPT_HTTPHEADER, $this->getHeaders($this->NVP));
      curl_setopt($request, CURLOPT_HEADER, 1); // Set to 0 to eliminate header info from response
      curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
      curl_setopt($request, CURLOPT_TIMEOUT, 45); // times out after 45 secs
      curl_setopt($request, CURLOPT_FORBID_REUSE, TRUE); //forces closure of connection when done
      curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // Uncomment this line if you get no gateway response.
      curl_setopt($request, CURLOPT_POST, 1); //data sent as POST
      curl_setopt($request, CURLOPT_POSTFIELDS, $this->getNVP()); // Use HTTP POST to send the data
      $postResponse = curl_exec($request); // Execute curl post and store results in $post_response
  
      // Additional options may be required depending upon your server configuration
      // you can find documentation on curl options at http://www.php.net/curl_setopt
      curl_close($request); // close curl object
      
      // Get the response.
      $this->response = $postResponse;
      
      $this->response = $this->parseResults($this->response);
    
      if(isset($this->response['RESULT']) && $this->response['RESULT'] == 0) {
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
    else {
      return FALSE;
    }
  }
  

  /**
* @uses Generates the headers we need to send to Payflow.
* @access Private
* @param Array - $params - The NVP value pairs.
* @return Array - Header information for cURL.
* @example $this->getHeaders($params);
*/
private function getHeaders($params){
$headers[] = "Content-Type: text/namevalue";
$headers[] = "X-VPS-Timeout: 30";
$headers[] = "X-VPS-VIT-Client-Certification-Id: 33baf5893fc2123d8b191d2d011b7fdc"; // This header requirement will be removed
$headers[] = "X-VPS-Request-ID: " . $this->getRequestId($params);
return $headers;
}


  /**
* @uses Generates a unique request id with the credit card number, amount, and timestamp.
* @access Private
* @param Array - $params - The NVP value pairs.
* @return String - Unique id.
* @example $this->getRequestId($params);
*/
private function getRequestId($params){
if(isset($params['ACCT'])) {
$requestId = md5($params['ACCT'] . $params['AMT'] . date('YmdGis') . "1");
return $requestId;
}
    else {
return md5(time());
}
}
  
  
  /**
* @uses Gets the response from Paypal.
* @access Public
* @param None.
* @return Array/String - Returns an array of Paypal's response or empty string if not return.
* @example $PayFlow->getResponse();
*/
  public function getResponse() {
    if($this->response) {
      return $this->response;
    }
    else {
      return '';
    }
  }
  
  
  /**
* @uses Parses the response from Paypal.
* @access Private
* @param Array - $result - The NVP result from the transaction.
* @return Array/String - Returns an array of Paypal's response or empty string if not return.
* @example $PayFlow->getResponse();
*/
  private function parseResults($result) {
    if(empty($result)) {
      return '';
    }
    
    $response = array();
    $result = strstr($result, 'RESULT');
    $value = explode('&', $result);
    foreach($value as $token) {
      $key = explode('=', $token);
      $response[$key[0]] = $key[1];
    }
    return $response;
  }
    
  
  /**
* @uses Formats the monetary amount sent to Paypal.
* @access Private
* @param String/Integer/Float $amount - The amount to clean.
* @param Boolean $wholeAmt - True to remove cents false, to keep it.
* @return Integer/Float - Returns the monetary amount formatted based on the $wholeAmt parameter.
* @example $this->cleanAmt();
*/
  private function cleanAmt($amount = 0, $wholeAmt = FALSE) {
    if($wholeAmt) {
      $amount = preg_replace('/[^0-9.]/', '', trim($amount));
      return (int)$amount;
    }
    else {
      $amount = preg_replace('/[^0-9.]/', '', trim($amount));
      return (float)$amount;
    }
  }


  /**
* @uses Removes all characters from the credit card number except for numbers.
* @access Private
* @param String $cc - The crdeit card number.
* @return String - Returns the credit card number with only numeric characters.
* @example $this->cleanCCNumber('5412-2232-2323-3443');
*/
  private function cleanCCNumber($cc = '') {
    $cc = preg_replace('/[^0-9]/', '', trim($cc));
    return (string)$cc;
  }


  /**
* @uses Removes all characters from the telephone number except for numbers and dashes.
* @access Private
* @param String $phone - The phone number.
* @return String - Returns the phone number with dashes.
* @example $this->cleanPhoneNumber('718-232-2323');
*/
  private function cleanPhoneNumber($phone = '') {
    $phone = preg_replace('/[^0-9]/', '', trim($phone));
    return (string)$phone;
  }


  /**
* @uses Removes all characters from the Expiration date except for numbers, slashes and dashes.
* @access Private
* @param String $exp - The expiration date.
* @return String - Returns the expiration date formatted for Paypal.
* @example $this->cleanExpDate('05/10');
*/
  private function cleanExpDate($exp = '') {
    $exp = preg_replace('/[^0-9]/', '', trim($exp));
    return (string)$exp;
  }
  
  
  /**
* @uses Used to truncate values.
* @access Private
* @param String $string - The string to truncate.
* @param Integer $limit - The amount to truncate.
* @return Returns the string truncated.
* @example $this->truncateChars('Richard Castera', 10);
*/
  private function truncateChars($string = '', $limit = 0) {
      $output = '';
    for($i = 0; $i <= $limit AND $i < strlen($string); $i++){
      $output .= $string[$i];
    }
    return (string)trim($output);
  }
  
  
  /**
* @uses Used to debug values that will be sent to Paypal.
* @access Public
* @param String $type - Valid values are 'array' or 'string'.
* @return This returns either and array of the NVP's or a string based on the parameter chosen.
* @example $PayFlow->debugNVP('array');
*/
  public function debugNVP($type = 'array') {
    if($type == 'array') {
      return $this->NVP;
    }
    else {
      return $this->getNVP();
    }
  }
}
?>