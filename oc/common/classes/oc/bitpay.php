<?php defined('SYSPATH') or die('No direct script access.');

/**
 * bitpay helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

class OC_Bitpay {

    /**
     * generates HTML for apy buton
     * @param  Model_Order $order 
     * @return string                 
     */
    public static function button(Model_Order $order)
    {
        if ( Core::config('payment.bitpay_apikey')!='' AND Theme::get('premium')==1 AND
            Auth::instance()->logged_in() AND $order->loaded())
        {           

            return View::factory('pages/bitpay/button',array('order'=>$order));
        }

        return '';
    }

    /**
     * from here to down are the functions provided by bitpay I've tried to modify them as little as possible.
     *  I added them as static functions so they can be used in a class and removed the global options, super ugly code...
     *  see https://github.com/bitpay/php-client 
     */

    /**
     * returns bitpay options, this is just sooo ugly, I mean they were using a global variable and I replaced it with this static..
     * @return array 
     */
    public static function options()
    {
        
        // Please look carefully through these options and adjust according to your installation.  
        // Alternatively, most of these options can be dynamically set upon calling the functions in bp_lib.

        // REQUIRED Api key you created at bitpay.com
        // example: $bpOptions['apiKey'] = 'L21K5IIUG3IN2J3';
        $bpOptions['apiKey'] = Core::config('payment.bitpay_apikey');

        // whether to verify POS data by hashing above api key.  If set to false, you should
        // have some way of verifying that callback data comes from bitpay.com
        // note: this option can only be changed here.  It cannot be set dynamically. 
        $bpOptions['verifyPos'] = true;

        // email where invoice update notifications should be sent
        $bpOptions['notificationEmail'] = '';

        // url where bit-pay server should send update notifications.  See API doc for more details.
        // example: $bpNotificationUrl = 'http://www.example.com/callback.php';
        $bpOptions['notificationURL'] = Route::url('default',array('controller'=>'bitpay','action'=>'ipn','id'=>'none'));

        // url where the customer should be directed to after paying for the order
        // example: $bpNotificationUrl = 'http://www.example.com/confirmation.php';
        $bpOptions['redirectURL'] = '';//Route::url('product-goal', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname));

        // This is the currency used for the price setting.  A list of other pricing
        // currencies supported is found at bitpay.com
        $bpOptions['currency'] = 'USD';

        // Indicates whether anything is to be shipped with
        // the order (if false, the buyer will be informed that nothing is
        // to be shipped)
        $bpOptions['physical'] = FALSE;

        // If set to false, then notificaitions are only
        // sent when an invoice is confirmed (according the the
        // transactionSpeed setting). If set to true, then a notification
        // will be sent on every status change
        $bpOptions['fullNotifications'] = false;

        // transaction speed: low/medium/high.   See API docs for more details.
        $bpOptions['transactionSpeed'] = 'high'; 

        // Logfile for use by the bpLog function.  Note: ensure the web server process has write access
        // to this file and/or directory!
        $bpOptions['logFile'] = '/bplog.txt';

        // Change to 'true' if you would like automatic logging of invoices and errors.
        // Otherwise you will have to call the bpLog function manually to log any information.
        $bpOptions['useLogging'] = false;

        return $bpOptions;
    }


    /**
     * ©2011,2012,2013,2014 BITPAY, INC.
     * 
     * Permission is hereby granted to any person obtaining a copy of this software
     * and associated documentation for use and/or modification in association with
     * the bitpay.com service.
     *
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
     * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
     * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
     * THE SOFTWARE.
     * 
     * Bitcoin PHP payment library using the bitpay.com service.
     *
     * Version 1.5, rich@bitpay.com
     * 
     */



    /**
     *
     * Handles post/get to BitPay via curl.
     *
     * @param string $url, string $apiKey, boolean $post
     * @return mixed $response
     * @throws Exception $e
     *
     */
    public static function bpCurl($url, $apiKey, $post = false) {
      $bpOptions = self::options();    

      if((isset($url) && trim($url) != '') && (isset($apiKey) && trim($apiKey) != '')) {
        try {
          $curl = curl_init();
          $length = 0;

          if ($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
            $length = strlen($post);
          }

          $uname = base64_encode($apiKey);

          if($uname) {
            $header = array(
                      'Content-Type: application/json',
                      'Content-Length: ' . $length,
                      'Authorization: Basic ' . $uname,
                      'X-BitPay-Plugin-Info: phplib1.5',
            );

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_PORT, 443);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1); // verify certificate
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // check existence of CN and verify that it matches hostname
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);

            $responseString = curl_exec($curl);

            if($responseString == false) {
              $response = array('error' => curl_error($curl));
              if($bpOptions['useLogging'])
                Kohana::$log->add(Log::ERROR, 'Error: ' . curl_error($curl));
            } else {
              $response = json_decode($responseString, true);
              if (!$response) {
                $response = array('error' => 'invalid json: '.$responseString);
                if($bpOptions['useLogging'])
                  Kohana::$log->add(Log::ERROR, 'Error - Invalid JSON: ' . $responseString);
              }
            }

            curl_close($curl);
            return $response;
          } else {
            curl_close($curl);
            if($bpOptions['useLogging'])
              Kohana::$log->add(Log::ERROR, 'Invalid data found in apiKey value passed to bpCurl. (Failed: base64_encode(apikey))');
            return array('error' => 'Invalid data found in apiKey value passed to bpCurl. (Failed: base64_encode(apikey))');
          }
        } catch (Exception $e) {
          @curl_close($curl);
          if($bpOptions['useLogging'])
            Kohana::$log->add(Log::ERROR, 'Error: ' . $e->getMessage());
        }
      } else {
        // Invalid parameter specified
        if($bpOptions['useLogging'])
          Kohana::$log->add(Log::ERROR, 'Error: You must supply non-empty url and apiKey parameters.');
        return array('error' => 'You must supply non-empty url and apiKey parameters.');
      }

    }

    /**
     *
     * Creates BitPay invoice via bpCurl.
     *
     * @param string $orderId, string $price, string $posData, array $options
     * @return array $response
     * @throws Exception $e
     *
     */
    public static function bpCreateInvoice($orderId, $price, $posData, $options = array()) {
      // $orderId: Used to display an orderID to the buyer. In the account summary view, this value is used to
      // identify a ledger entry if present. Maximum length is 100 characters.
      //
      // $price: by default, $price is expressed in the currency you set in bp_options.php.  The currency can be
      // changed in $options.
      //
      // $posData: this field is included in status updates or requests to get an invoice.  It is intended to be used by
      // the merchant to uniquely identify an order associated with an invoice in their system.  Aside from that, Bit-Pay does
      // not use the data in this field.  The data in this field can be anything that is meaningful to the merchant.
      // Maximum length is 100 characters.
      //
      // Note:  Using the posData hash option will APPEND the hash to the posData field and could push you over the 100
      //        character limit.
      //
      //
      // $options keys can include any of:
      //    'itemDesc', 'itemCode', 'notificationEmail', 'notificationURL', 'redirectURL', 'apiKey'
      //    'currency', 'physical', 'fullNotifications', 'transactionSpeed', 'buyerName',
      //    'buyerAddress1', 'buyerAddress2', 'buyerCity', 'buyerState', 'buyerZip', 'buyerEmail', 'buyerPhone'
      //
      // If a given option is not provided here, the value of that option will default to what is found in bp_options.php
      // (see api documentation for information on these options).

      $bpOptions = self::options();    

      try {
        $options = array_merge($bpOptions, $options);  // $options override any options found in bp_options.php
        $pos = array('posData' => $posData);

        if ($bpOptions['verifyPos']) 
          $pos['hash'] = self::bpHash(serialize($posData), $options['apiKey']);

        $options['posData'] = json_encode($pos);

        if(strlen($options['posData']) > 100)
          return array('error' => 'posData > 100 character limit. Are you using the posData hash?');

        $options['orderID'] = $orderId;
        $options['price'] = $price;

        $postOptions = array('orderID', 'itemDesc', 'itemCode', 'notificationEmail', 'notificationURL', 'redirectURL', 
                             'posData', 'price', 'currency', 'physical', 'fullNotifications', 'transactionSpeed', 'buyerName', 
                             'buyerAddress1', 'buyerAddress2', 'buyerCity', 'buyerState', 'buyerZip', 'buyerEmail', 'buyerPhone');
                             
        /* $postOptions = array('orderID', 'itemDesc', 'itemCode', 'notificationEmail', 'notificationURL', 'redirectURL', 
                             'posData', 'price', 'currency', 'physical', 'fullNotifications', 'transactionSpeed', 'buyerName', 
                             'buyerAddress1', 'buyerAddress2', 'buyerCity', 'buyerState', 'buyerZip', 'buyerEmail', 'buyerPhone',
                             'pluginName', 'pluginVersion', 'serverInfo', 'serverVersion', 'addPluginInfo');
        */
        // Usage information for support purposes. Do not modify.
        //$postOptions['pluginName']    = 'PHP Library';
        //$postOptions['pluginVersion'] = '1.3';
        //$postOptions['serverInfo']    = htmlentities($_SERVER['SERVER_SIGNATURE'], ENT_QUOTES);
        //$postOptions['serverVersion'] = htmlentities($_SERVER['SERVER_SOFTWARE'], ENT_QUOTES);
        //$postOptions['addPluginInfo'] = htmlentities($_SERVER['SCRIPT_FILENAME'], ENT_QUOTES);

        foreach($postOptions as $o) {
          if (array_key_exists($o, $options))
            $post[$o] = $options[$o];
        }

        $post = json_encode($post);

        $response = self::bpCurl('https://bitpay.com/api/invoice/', $options['apiKey'], $post);

        if($bpOptions['useLogging']) {
          Kohana::$log->add(Log::DEBUG, 'Create Invoice: '.$post);
          Kohana::$log->add(Log::DEBUG, 'Response: '.$response);
        }

        return $response;

      } catch (Exception $e) {
        if($bpOptions['useLogging'])
          Kohana::$log->add(Log::ERROR, 'Error: ' . $e->getMessage());
        return array('error' => $e->getMessage());
      }
    }

    /**
     *
     * Call from your notification handler to convert $_POST data to an object containing invoice data
     *
     * @param boolean $apiKey
     * @return mixed $json
     * @throws Exception $e
     *
     */
    public static function bpVerifyNotification($apiKey = false) {
      $bpOptions = self::options();

      try {
        if (!$apiKey) 
          $apiKey = $bpOptions['apiKey'];       

        $post = file_get_contents("php://input");

        if (!$post)
          return 'No post data';

        $json = json_decode($post, true);

        if (is_string($json))
          return $json; // error

        if (!array_key_exists('posData', $json))
          return 'no posData';

        $posData = json_decode($json['posData'], true);

        if($bpOptions['verifyPos'] and $posData['hash'] != self::bpHash(serialize($posData['posData']), $apiKey))
          return 'authentication failed (bad hash)';

        $json['posData'] = $posData['posData'];

        return $json;
      } catch (Exception $e) {
        if($bpOptions['useLogging'])
          Kohana::$log->add(Log::ERROR, 'Error: ' . $e->getMessage());
        return array('error' => $e->getMessage());
      }
    }


    /**
     *
     * Generates a base64 encoded keyed hash.
     *
     * @param string $data, string $key
     * @return string $hmac
     * @throws Exception $e
     *
     */
    public static function bpHash($data, $key) {
      $bpOptions = self::options();
      
      try {
        $hmac = base64_encode(hash_hmac('sha256', $data, $key, TRUE));
        return strtr($hmac, array('+' => '-', '/' => '_', '=' => ''));
      } catch (Exception $e) {
        if($bpOptions['useLogging'])
          Kohana::$log->add(Log::ERROR, 'Error: ' . $e->getMessage());
        return 'Error: ' . $e->getMessage();
      }
    }

    /**
     * 
     * Decodes JSON response and returns
     * associative array.
     * 
     * @param string $response
     * @return array $arrResponse
     * @throws Exception $e
     * 
     */
    public static function bpDecodeResponse($response) {
      $bpOptions = self::options();
      
      try {
        if (empty($response) || !(is_string($response)))
          return 'Error: decodeResponse expects a string parameter.';

        return json_decode($response, true);
      } catch (Exception $e) {
        if($bpOptions['useLogging'])
          Kohana::$log->add(Log::ERROR, 'Error: ' . $e->getMessage());
        return 'Error: ' . $e->getMessage();
      }
    }

    /**
     *
     * Retrieves a list of all supported currencies
     * and returns associative array.
     * 
     * @param none
     * @return array $currencies
     * @throws Exception $e
     * 
     */
    public static function bpCurrencyList() {

      $currencies = array();
        $rate_url = 'https://bitpay.com/api/rates';

      try {
          $clist = json_decode(file_get_contents($rate_url),true);

          foreach($clist as $key => $value)
              $currencies[$value['code']] = $value['name'];

          return $currencies;
      } catch (Exception $e) {
          Kohana::$log->add(Log::ERROR, 'Error: ' . $e->getMessage());
      }
    }

    /**
     * 
     * Retrieves the current rate based on $code.
     * The default code us USD, so calling the 
     * public static function without a parameter will return
     * the current BTC/USD price.
     * 
     * @param string $code
     * @return string $rate
     * @throws Exception $e
     * 
     */
    public static function bpGetRate($code = 'USD') {

        $rate_url = 'https://bitpay.com/api/rates';

      try {
          $clist = json_decode(file_get_contents($rate_url),true);

          foreach($clist as $key => $value) {
          if($value['code'] == $code)
                $rate = number_format($value['rate'], 2, '.', '');
          }
          
          return $rate;
      } catch (Exception $e) {
          Kohana::$log->add(Log::ERROR, 'Error: ' . $e->getMessage());
      }
    }



}