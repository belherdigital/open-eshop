<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Paypal class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@open-classifieds.com>, Slobodan <slobodan@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Paypal {
	
	/**
     * for form generation
     */
    const url_sandbox_gateway    = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    const url_gateway            = 'https://www.paypal.com/cgi-bin/webscr';

    /**
     * For IPN validation
     */
    const ipn_sandbox_url      	= 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    const ipn_url              	= 'https://www.paypal.com/cgi-bin/webscr';


    /**
     * validates the data at paypal c&p from https://www.x.com/developers/PayPal/documentation-tools/code-sample/216623
     * @note impossible to test on sandbox, paypal wont work.
     * I really dislike this code but seems to work...
     * @return boolean
     */
    public static function validate_ipn()
    {
        //on local testing always OK!
        if(Kohana::$environment === Kohana::DEVELOPMENT)
            return TRUE;

        if (core::config('payment.sandbox'))
            $ipn_url  = self::ipn_sandbox_url;
        else
            $ipn_url  = self::ipn_url;

        // STEP 1: Read POST data
 
        // reading posted data from directly from $_POST causes serialization 
        // issues with array data in POST
        // reading raw POST data from input stream instead. 
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
          $keyval = explode ('=', $keyval);
          if (count($keyval) == 2)
             $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';
        if(function_exists('get_magic_quotes_gpc')) {
           $get_magic_quotes_exists = true;
        } 
        foreach ($myPost as $key => $value) {        
           if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
                $value = urlencode(stripslashes($value)); 
           } else {
                $value = urlencode($value);
           }
           $req .= "&$key=$value";
        }
         
         
        // STEP 2: Post IPN data back to paypal to validate
        $ch = curl_init($ipn_url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
         
        if( !($res = curl_exec($ch)) ) {
            // error_log("Got " . curl_error($ch) . " when processing IPN data");
            curl_close($ch);
            exit;
        }
        curl_close($ch);
         
        // STEP 3: Inspect IPN validation result and act accordingly
        if (strcmp ($res, "VERIFIED") == 0) 
        {
            return TRUE;
        }
        // Verfication result was invalid.  Log it.
        elseif(strcmp ($res, "INVALID") == 0)
        {
            Kohana::$log->add(Log::ERROR, 'Paypal invalid payment error. Result: '.$res.' Data: '. json_encode($_POST));
            return FALSE;
        }
        // Unknown result. Log it.
        else
        {
            Kohana::$log->add(Log::ERROR, 'Unknown result from IPN verification. Result: '.$res.' Data: '. json_encode($_POST));
            return FALSE;
        }

    }


    /**
     * returns allowed Paypal currencies
     * @return array currencies
     */
	public static function get_currency()
	{
		return array(
						'Australian Dollars'								=>  'AUD',
						'Canadian Dollars' 									=>	'CAD',
						'Euros' 											=>	'EUR',
						'Pounds Sterling' 									=>	'GBP',
						'Yen' 												=>	'JPY',
						'U.S. Dollars' 										=>	'USD',
						'New Zealand Dollar' 								=>	'NZD',
						'Swiss Franc' 										=>	'CHF',
						'Hong Kong Dollar' 									=>	'HKD',
						'Singapore Dollar' 									=>	'SGD',
						'Swedish Krona' 									=>	'SEK',
						'Danish Krone' 										=>	'DKK',
						'Polish Zloty' 										=>	'PLN',
						'Norwegian Krone' 									=>	'NOK',
						'Hungarian Forint' 									=>	'HUF',
						'Czech Koruna' 										=>	'CZK',
						'Israeli Shekel' 									=>	'ILS',
						'Mexican Peso' 										=>	'MXN',
						'Brazilian Real (only for Brazilian users)' 		=>	'BRL',
						'Malaysian Ringgits (only for Malaysian users)'		=>	'MYR',
						'Philippine Pesos' 									=>	'PHP',
						'Taiwan New Dollars' 								=>	'TWD',
						'Thai Baht' 										=>	'THB',
                        'Russian Ruble'                                     =>  'RUB',
		);

	}
}
