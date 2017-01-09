<?php
class FraudLabsPro {
    private $apiKey;

    public function __construct($apiKey=''){
        if(!preg_match('/^[A-Z0-9]{32}$/', $apiKey))
            throw new exception('FraudLabsPro: Invalid API key provided.');

        $this->apiKey = $apiKey;
    }

    public function check($fields = array()){
        if(!is_array($fields))
            throw new exception('FraudLabsPro->check(): Invalid parameters.');

        $_fields = array(
            'ipAddress'         => 'ip',
            'billingCity'       => 'bill_city',
            'billingZIPCode'    => 'bill_zip_code',
            'billingState'      => 'bill_state',
            'billingCountry'    => 'bill_country',
            'shippingAddress'   => 'ship_addr',
            'shippingCity'      => 'ship_city',
            'shippingZIPCode'   => 'ship_zip_code',
            'shippingState'     => 'ship_state',
            'shippingCountry'   => 'ship_country',
            'emailAddress'      => '',
            'username'          => '',
            'password'          => '',
            'creditCardNumber'  => '',
            'phone'             => 'user_phone',
            'bankName'          => 'bank_name',
            'bankPhone'         => 'bank_phone',
            'avsResult'         => 'avs',
            'cvvResult'         => 'cvv',
            'orderId'           => 'user_order_id',
            'amount'            => 'amount',
            'quantity'          => 'quantity',
            'currency'          => 'currency',
            'department'        => 'department',
            'paymentMode'       => 'payment_mode',
            'flpChecksum'       => 'flp_checksum'
        );

        $queries = array(
            'key'       => $this->apiKey,
            'format'    => 'json',
        );

        foreach($fields as $key=>$value){
            if(!in_array($key, array_keys($_fields)))
                continue;

            // Clean up credit card number, phone number
            if(in_array($key, array('creditCardNumber', 'phone', 'bankPhone')))
                $value = preg_replace('/\D/', '', $value);

            if($key == 'emailAddress'){
                if(!filter_var($value, FILTER_VALIDATE_EMAIL))
                    throw new exception('FraudLabsPro->check(): [emailAddress] Invalid email address provided.');

                $queries['email_domain'] = substr($value, strpos($value, '@')+1);
                $queries['email_hash'] = $this->hashed($value);
                continue;
            }

            if($key == 'username'){
                $queries['username_hash'] = $this->hashed($value);
                continue;
            }

            if($key == 'password'){
                $queries['password_hash'] = $this->hashed($value);
                continue;
            }

            if($key == 'creditCardNumber'){
                $queries['bin_no'] = substr($value, 0, 6);
                $queries['card_hash'] = $this->hashed($value);
                continue;
            }

            if($key == 'billingCountry'){
                if(!$this->isCountryCode($value))
                    throw new exception('FraudLabsPro->check(): [billCountry] Invalid country code.');
            }

            if($key == 'shippingCountry'){
                if(!$this->isCountryCode($value))
                    throw new exception('FraudLabsPro->check(): [shippingCountry] Invalid country code.');
            }

            $queries[$_fields[$key]] = $value;
        }

        $response = $this->http('https://api.fraudlabspro.com/v1/order/screen', $queries);

        if(!is_null($json = json_decode($response)))
            return $json;

        return false;
    }

    public function feedback($fields = array()){
        if(!is_array($fields))
            throw new exception('FraudLabsPro->feedback(): Invalid parameters.');

        $_fields = array(
            'apiKey'    => 'key',
            'id'        => 'id',
            'action'    => 'action',
        );

        $queries = array(
            'key'=>$this->apiKey,
            'format'=>'json',
            'id'=>'',
            'action'=>'',
        );

        foreach($fields as $key=>$value){
            if(!in_array($key, array_keys($_fields)))
                continue;

            if($key == 'action'){
                if(!in_array($value, array('APPROVE', 'REJECT', 'IGNORE')))
                    throw new exception('FraudLabsPro->feedback(): Invalid action.');
            }

            $queries[$_fields[$key]] = $value;
        }

        $response = $this->http('https://api.fraudlabspro.com/v1/order/feedback', $queries);

        if(!is_null($json = json_decode($response)))
            return $json;

        return false;
    }

    private function isCountryCode($cc){
        if(!$cc)
            return false;

        return in_array($cc, array('AD', 'AE', 'AF', 'AG', 'AI', 'AL', 'AM', 'AN', 'AO', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AW', 'AX', 'AZ', 'BA', 'BB', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BL', 'BM', 'BN', 'BO', 'BQ', 'BR', 'BS', 'BT', 'BV', 'BW', 'BY', 'BZ', 'CA', 'CC', 'CD', 'CF', 'CG', 'CH', 'CI', 'CK', 'CL', 'CM', 'CN', 'CO', 'CR', 'CS', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 'DE', 'DJ', 'DK', 'DM', 'DO', 'DZ', 'EC', 'EE', 'EG', 'EH', 'ER', 'ES', 'ET', 'FI', 'FJ', 'FK', 'FM', 'FO', 'FR', 'GA', 'GB', 'GD', 'GE', 'GF', 'GG', 'GH', 'GI', 'GL', 'GM', 'GN', 'GP', 'GQ', 'GR', 'GS', 'GT', 'GU', 'GW', 'GY', 'HK', 'HM', 'HN', 'HR', 'HT', 'HU', 'ID', 'IE', 'IL', 'IM', 'IN', 'IO', 'IQ', 'IR', 'IS', 'IT', 'JE', 'JM', 'JO', 'JP', 'KE', 'KG', 'KH', 'KI', 'KM', 'KN', 'KP', 'KR', 'KW', 'KY', 'KZ', 'LA', 'LB', 'LC', 'LI', 'LK', 'LR', 'LS', 'LT', 'LU', 'LV', 'LY', 'MA', 'MC', 'MD', 'ME', 'MF', 'MG', 'MH', 'MK', 'ML', 'MM', 'MN', 'MO', 'MP', 'MQ', 'MR', 'MS', 'MT', 'MU', 'MV', 'MW', 'MX', 'MY', 'MZ', 'NA', 'NC', 'NE', 'NF', 'NG', 'NI', 'NL', 'NO', 'NP', 'NR', 'NU', 'NZ', 'OM', 'PA', 'PE', 'PF', 'PG', 'PH', 'PK', 'PL', 'PM', 'PN', 'PR', 'PS', 'PT', 'PW', 'PY', 'QA', 'RE', 'RO', 'RS', 'RU', 'RW', 'SA', 'SB', 'SC', 'SD', 'SE', 'SG', 'SH', 'SI', 'SJ', 'SK', 'SL', 'SM', 'SN', 'SO', 'SR', 'SS', 'ST', 'SV', 'SX', 'SY', 'SZ', 'TC', 'TD', 'TF', 'TG', 'TH', 'TJ', 'TK', 'TL', 'TM', 'TN', 'TO', 'TR', 'TT', 'TV', 'TW', 'TZ', 'UA', 'UG', 'UM', 'US', 'UY', 'UZ', 'VA', 'VC', 'VE', 'VG', 'VI', 'VN', 'VU', 'WF', 'WS', 'XK', 'YE', 'YT', 'ZA', 'ZM', 'ZW'));
    }

    private function hashed($s, $prefix='fraudlabspro_'){
        $hash = $prefix . $s;

        for($i=0; $i<65536; $i++)
            $hash = sha1($prefix . $hash);

        return $hash;
    }

    protected function http($url, $fields = array()){
        if(!function_exists('curl_init'))
            throw new exception('FraudLabsPro: cURL extension is not enabled.');

        if(!is_array($fields))
            return false;

        $url .= '?' . http_build_query($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_USERAGENT, 'FraudLabsPro API Client 1.5.0');

        $response = curl_exec($ch);

        if(empty($response) || curl_error($ch) || curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200){
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        return $response;
    }
}
?>