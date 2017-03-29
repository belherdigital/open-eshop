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
        
      
            $url = 'https://api.elasticemail.com/v2/email/send';

            $post = array('from' => $from,
            'fromName' => $from_name,
            'apikey' => Core::config('email.elastic_username'),
            'subject' => $subject,
            'to' => $to,
            'bodyHtml' => $body_html,
            'isTransactional' => false);
            
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $post,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 10
            ));
            
            $result=curl_exec ($ch);
            curl_close ($ch);
        

            $result = json_decode($result); //decode the response
               
                if ($result->success === false) { //checking response
                    Log::instance()->add(Log::ERROR, $result); //write error to log if sending failed
                    return FALSE;
                }

            return TRUE;
        //return ($result === false) ? FALSE : TRUE;
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