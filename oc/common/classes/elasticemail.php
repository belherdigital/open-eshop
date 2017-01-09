<?php
/**
 * Simple elastic email class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 */


class ElasticEmail {


    /**
     * Send Elastic Email using cURL (libcurl) in PHP
     *
     */
    public static function send($to,$to_name='', $subject, $body_html, $from, $from_name) 
    {
        
        // Initialize cURL
        $ch = curl_init();
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, 'https://api.elasticemail.com/mailer/send');
        curl_setopt($ch, CURLOPT_POST, 1);

        //multiple recipients in elasctic sent as BCC internally
        if (is_array($to))
        {
            $to_aux = '';
            foreach ($to as $contact) 
                 $to_aux .= $contact['name'].' <'.$contact['email'].'>;';

             $to = $to_aux;
        }
        elseif($to_name!='')
            $to = $to_name . ' <'.$to.'>;';
        
        $data = 'username='.urlencode(Core::config('email.elastic_username')).
                '&api_key='.urlencode(Core::config('email.elastic_username')).
                '&from='.urlencode($from).
                '&from_name='.urlencode($from_name).
                '&to='.urlencode($to).
                '&subject='.urlencode($subject).
                '&body_html='.urlencode($body_html);
        
        // Set parameter data to POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // Header data
            $header = "Content-Type: application/x-www-form-urlencoded\r\n";
            $header .= "Content-Length: ".strlen($data)."\r\n\r\n";

        // Set header
        curl_setopt($ch, CURLOPT_HEADER, $header);

        //timeout
        curl_setopt($ch,CURLOPT_TIMEOUT, 2);
        
        // Set to receive server response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Set cURL to verify SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        // Get result
        $result = curl_exec($ch);
        
        // Close cURL
        curl_close($ch);
        
        return ($result === false) ? FALSE : TRUE;
    }

    /**
     * subscribes a user to a list
     * @param  string $listname
     * @param  string $email  
     * @param  string $name
     * @return json   
     */
    public static function subscribe($listname, $email, $name)
    {
        if ( Core::config('email.elastic_active')==TRUE )
        {
            $url = 'https://api.elasticemail.com/v2/list/addcontacts?apikey='.Core::config('email.elastic_username').'&listname='.$listname.'&emails='.$email;
            return Core::curl_get_contents($url,2);
        }
        return FALSE;
    }


    /**
     * unsubscribes a user from a list
     * @param  string $listname
     * @param  string $email  
     * @return json   
     */
    public static function unsubscribe($listname, $email)
    {
        if ( Core::config('email.elastic_active')==TRUE )
        {
            $url = 'https://api.elasticemail.com/v2/list/removecontacts?apikey='.Core::config('email.elastic_username').'&listname='.$listname.'&emails='.$email;
            return Core::curl_get_contents($url,2);
        }
        return FALSE;
    }

} //end email