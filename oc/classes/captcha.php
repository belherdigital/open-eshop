<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * ultra light captcha class
 *
 * @package Open Classifieds
 * @subpackage Core
 * @category Helper
 * @author Chema Garrido <chema@open-classifieds.com>
 * @license GPL v3
 */

class Captcha extends OC_Captcha{


    /**
     * check if its valid or not
     * @param string $name for the session
     * @return boolean
     */
    public static function check($name = '', $ajax = FALSE)
    { 
        //for OE
        if (core::config('general.captcha') != NULL AND core::config('general.captcha') == FALSE) // Captchas disabled on OE
            return TRUE;
        
        // verify with recaptcha if enabled
        if (Core::config('general.recaptcha_active'))
        {
            if (self::recaptcha_verify())
                return TRUE;
            else
                return FALSE;   
        }
        
        if (Session::instance()->get('captcha_'.$name) == strtolower(core::post('captcha'))) 
        {
            if ($ajax === FALSE)
                Session::instance()->set('captcha_'.$name, '');
                
            return TRUE;
        }
        else return FALSE;
        
    }
}