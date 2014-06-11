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


class Email extends OC_Email{




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

            //adding extra replaces
            $replace+= array('[SITE.NAME]'      =>  core::config('general.site_name'),
                             '[SITE.URL]'       =>  core::config('general.base_url'),
                             '[USER.NAME]'      =>  $to_name);

            if(!is_array($to))
                $replace += array('[USER.EMAIL]'=>$to);

            //adding anchor tags to any [URL.* match
            foreach ($replace as $key => $value) 
            {
                if( (strpos($key, '[URL.')===0 OR $key == '[SITE.URL]') AND $value!='')
                    $replace[$key] = '[url='.$value.']'.parse_url($value, PHP_URL_HOST).'[/url]';
            }

            $subject = str_replace(array_keys($replace), array_values($replace), $email->title);
            $body    = str_replace(array_keys($replace), array_values($replace), $email->description);

            return Email::send($to,$to_name,$subject,$body,$from,$from_name); 

        }
        else return FALSE;

    }


} //en email