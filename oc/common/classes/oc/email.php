<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Simple email class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@open-classifieds.com>, Slobodan <slobodan@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */


class OC_Email {

    /**
     * sends an email using our configs
     * @param  string/array $to       array(array('name'=>'chema','email'=>'chema@'),)
     * @param  [type] $to_name   [description]
     * @param  [type] $subject   [description]
     * @param  [type] $body      [description]
     * @param  [type] $reply     [description]
     * @param  [type] $replyName [description]
     * @param  [type] $file      [description]
     * @return boolean
     */
    public static function send($to,$to_name='',$subject,$body,$reply,$replyName,$file = NULL)
    {
        //multiple to but theres none...
        if (is_array($to) AND count($to)==0)
            return FALSE;

        $body = Text::nl2br($body);

        //get the unsubscribe link
        
        $email_encoded = NULL;
        //is sent to a single user get hash to auto unsubscribe
        if (!is_array($to) OR count($to)==1)
        {
            //from newsletter sent
            if (isset($to[0]['email']))
                $email_encoded = $to[0]['email'];
            else
                $email_encoded = $to;

            //encodig the email for extra security
            $encrypt = new Encrypt(Core::config('auth.hash_key'), MCRYPT_MODE_NOFB, MCRYPT_RIJNDAEL_128);
            $email_encoded = Base64::fix_to_url($encrypt->encode($email_encoded));
        }

        $unsubscribe_link = Route::url('oc-panel',array('controller'=>'auth','action'=>'unsubscribe','id'=>$email_encoded));

        //get the template from the html email boilerplate
        $body = View::factory('email',array('title'=>$subject,'content'=>$body,'unsubscribe_link'=>$unsubscribe_link))->render();

        //sendign via elasticemail
        if (Core::config('email.elastic_active')==TRUE)
        {
            return ElasticEmail::send($to,$to_name, $subject, $body, $reply, $replyName);
        }
        else
        {
            require_once Kohana::find_file('vendor', 'php-mailer/phpmailer','php');
            
            $mail= new PHPMailer();
            $mail->CharSet = Kohana::$charset;

            if(core::config('email.smtp_active') == TRUE)
            { 
                require_once Kohana::find_file('vendor', 'php-mailer/smtp','php');
                
                $mail->IsSMTP();
                $mail->Timeout = 5;

                //SMTP HOST config
                if (core::config('email.smtp_host')!="")
                    $mail->Host       = core::config('email.smtp_host');              // sets custom SMTP server
                

                //SMTP PORT config
                if (core::config('email.smtp_port')!="")
                    $mail->Port       = core::config('email.smtp_port');              // set a custom SMTP port
                

                //SMTP AUTH config
                if (core::config('email.smtp_auth') == TRUE)
                {
                    $mail->SMTPAuth   = TRUE;                                                  // enable SMTP authentication
                    $mail->Username   = core::config('email.smtp_user');              // SMTP username
                    $mail->Password   = core::config('email.smtp_pass');              // SMTP password                        
                }

                // sets the prefix to the server
                $mail->SMTPSecure = core::config('email.smtp_secure');                  
                    
            }

            $mail->From       = core::config('email.notify_email');
            $mail->FromName   = core::config('email.notify_name');
            $mail->Subject    = $subject;
            $mail->MsgHTML($body);

            if($file !== NULL) 
                $mail->AddAttachment($file['tmp_name'],$file['name']);

            $mail->AddReplyTo($reply,$replyName);//they answer here

            if (is_array($to))
            {
                foreach ($to as $contact) 
                    $mail->AddBCC($contact['email'],$contact['name']);               
            }
            else
                $mail->AddAddress($to,$to_name);

            $mail->IsHTML(TRUE); // send as HTML

            if(!$mail->Send()) 
            {//to see if we return a message or a value bolean
                Alert::set(Alert::ALERT,"Mailer Error: " . $mail->ErrorInfo);
                return FALSE;
            } 
            else 
                return TRUE;
        }

        return FALSE;
 
    }

    /**
     * sends an email using content from model_content
     * @param  string $to        
     * @param  string $to_name   
     * @param  string $from      
     * @param  string $from_name 
     * @param  string $content   seotitle from Model_Content
     * @param  array $replace   key value to replace at subject and body
     * @param  array $file      file to attach to email
     * @return boolean            s
     */
    public static function content($to, $to_name='', $from = NULL, $from_name =NULL, $content, $replace, $file=NULL)
    {
        
        $email = Model_Content::get_by_title($content,'email');

        //content found
        if ($email->loaded())
        { 
            if ($replace===NULL) 
                $replace = array();

            if ($from === NULL)
                $from = $email->from_email;

            if ($from_name === NULL )
                $from_name = core::config('general.site_name');

            if (isset($file) AND self::is_valid_file($file))
                $file_upload = $file;
            else
                $file_upload = NULL;

            //adding extra replaces
            $replace+= array('[SITE.NAME]'      =>  core::config('general.site_name'),
                             '[SITE.URL]'       =>  core::config('general.base_url'),
                             '[USER.NAME]'      =>  $to_name);

            if(!is_array($to))
                $replace += array('[USER.EMAIL]'=>$to);

            //adding anchor tags to any [URL.* match
            foreach ($replace as $key => $value) 
            {
                if(strpos($key, '[URL.')===0 OR $key == '[SITE.URL]'  AND $value!='')
                    $replace[$key] = '<a href="'.$value.'">'.parse_url($value, PHP_URL_HOST).'</a>';
            }

            $subject = str_replace(array_keys($replace), array_values($replace), $email->title);
            $body    = str_replace(array_keys($replace), array_values($replace), $email->description);

            return Email::send($to,$to_name,$subject,$body,$from,$from_name, $file_upload); 
        }
        else 
            return FALSE;

    }

    /**
     * returns true if file is of valid type.
     * Its used to check file sent to user from advert usercontact
     * @param array file
     * @return BOOL 
     */
    public static function is_valid_file($file)
    {
        //catch file
        $file = $_FILES['file'];
        //validate file
        if( $file !== NULL)
        {     
            if ( 
                ! Upload::valid($file) OR
                ! Upload::not_empty($file) OR
                ! Upload::type($file, array('jpg', 'jpeg', 'png', 'pdf','doc','docx')) OR
                ! Upload::size($file,'3M'))
                {
                    return FALSE;
                }
            return TRUE;
        }
    }



    /**
     * Send Elastic Email using cURL (libcurl) in PHP
     *
     */
    public static function ElasticEmail($to,$to_name='', $subject, $body_html, $from, $from_name) {
        
        // Initialize cURL
        $ch = curl_init();
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, 'https://api.elasticemail.com/mailer/send');
        curl_setopt($ch, CURLOPT_POST, 1);

        // Parameter data
        // $data = array( 
        //     'username'  => Core::config('email.elastic_username'), 
        //     'api_key'   => Core::config('email.elastic_username'), 
        //     'from'      => $from, 
        //     'from_name' => $from_name, 
        //     'to'        => $to, 
        //     'is_html'   => "true", 
        //     'subject'   => $subject, 
        //     'body'      => $body 
        // );

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

        // Return the response or NULL on failure
        //  return ($result === false) ? NULL : $result;
        
        // Alternative error checking return
        // return ($result === false) ? 'Curl error: ' . curl_error($ch): $result;
    }


    /**
     * returns an array of administrators and moderators
     * @return array
     */
    public static function get_notification_emails()
    {
        $arr = array();

        $users = new Model_User();
        $users = $users->where('id_role','in',array(Model_Role::ROLE_ADMIN,Model_Role::ROLE_MODERATOR))
                ->where('status','=',Model_User::STATUS_ACTIVE)
                ->where('subscriber','=',1)
                ->cached()->find_all();

        foreach ($users as $user) 
        {
            $arr[] = array('name'=>$user->name,'email'=>$user->email);
        }

        return $arr;
    }
  


} //end email