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

class OC_Captcha{

    /**
     * Used to know if we already used the google recaptcha api somewhere so we don't call it twice
     * 
     * @var boolean
     */
    private static $included_recaptcha_lib = FALSE;

    /**
	 * generates the image for the captcha
	 * @param string $name, used in the session
	 * @param int $width
	 * @param int $height
	 * @param string $baseList
	 */
    public static function image($name='',$width=120,$height=40,$baseList = '123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ')
    {
        //echo $name.' orig= '.Session::instance()->get('captcha_'.$name).'<br>';

        $length = mt_rand(3,5);//code length
        $lines  = mt_rand(1,5);//to make the image dirty
        $image  = @imagecreate($width, $height) or die('Cannot initialize GD!');
        $code   = ''; //code generated saved at session

        //base image with dirty lines
        for( $i=0; $i<$lines; $i++ ) 
        {
           imageline($image,
                 mt_rand(0,$width), mt_rand(0,$height),
                 mt_rand(0,$width), mt_rand(0,$height),
                 imagecolorallocate($image, mt_rand(150,255), mt_rand(150,255), mt_rand(150,255)));
        }

        //writting the chars
        for( $i=0, $x=0; $i<$length; $i++ ) 
        {
           $actChar = substr($baseList, rand(0, strlen($baseList)-1), 1);
           $x += 10 + mt_rand(0,10);
           imagechar($image, mt_rand(4,5), $x, mt_rand(5,20), $actChar,
              imagecolorallocate($image, mt_rand(0,155), mt_rand(0,155), mt_rand(0,155)));
           $code .= strtolower($actChar);
        }
        
        Session::instance()->set('captcha_'.$name, $code);   

        //die( 'changed= '.Session::instance()->get('captcha_'.$name));
        
        // prevent client side caching
        header("Expires: Wed, 1 Jan 1997 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", FALSE);
        header("Pragma: no-cache");
        header('Content-Type: image/jpeg');
        imagejpeg($image);
        imagedestroy($image);    
        
    }

    
    /**
	 *
	 * @param string $name for the session
	 */
    public static function url($name='')
    {
        return Route::url('default',array('controller'  => 'captcha', 'action' => 'image', 'id'=>$name)); 
    }
    
    /**
	 * check if its valid or not
	 * @param string $name for the session
	 * @return boolean
	 */
    public static function check($name = '', $ajax = FALSE)
    { 
        //d(strtolower(core::post('captcha')));
        //d(Session::instance()->get('captcha_'.$name));
        //d(Session::instance()->get('captcha_'.$name) == strtolower(core::post('captcha')));

        //for OC
        if (core::config('advertisement.captcha') != NULL AND core::config('advertisement.captcha') == FALSE) // Captcha disabled on OC
            return TRUE;

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
    
    /**
	 * javascript that allow us to reload the iamge in case you can't read it
	 * @return string javascript
	 */
    public static function reload_image()
    {
        return '<script type="text/javascript">
		function reloadImg(id) {
        var elements = document.getElementsByClassName(id);
        var date = new Date();
        for (var i=0; i < elements.length; i++) {
                src = elements[i].src;
                elements[i].src = src + "&v=" + date.getTime();
        }
		return false;
		}</script>';
    }

    /**
	 * generates the HTML image tag to add in a form
	 * @param string $name unique name for the image
	 * @return string html tag
	 */
    public static function image_tag($name='')
    {
        return self::reload_image().
                '<img alt="captcha" id="captcha_img_'.$name.'" class="captcha_img_'.$name.'" style="cursor: pointer;" title="Click to refresh"
				onClick="return reloadImg(\'captcha_img_'.$name.'\');" src="'.captcha::url($name).'">';
    }
    
    /**
     * generates the captcha code for the form
     * @return string or boolean
     */
    public static function recaptcha_display()
    {
        if (Core::config('general.recaptcha_sitekey') == '')
            return FALSE;

        if (self::$included_recaptcha_lib === FALSE)
        {
            $html  = '<script src="https://www.google.com/recaptcha/api.js?onload=recaptchaCallBack&render=explicit" async defer></script>'."\n";
            $html .= '<script>'."\n";
            $html .= 'var recaptcha1; var recaptcha2; var recaptcha3; var recaptcha4;
                      var recaptchaCallBack = function() {
                          if (document.getElementById("recaptcha1") !== null) {
                              recaptcha1 = grecaptcha.render("recaptcha1", {"sitekey" : "'.Core::config('general.recaptcha_sitekey').'"});
                          }
                          if (document.getElementById("recaptcha2") !== null) {
                              recaptcha2 = grecaptcha.render("recaptcha2", {"sitekey" : "'.Core::config('general.recaptcha_sitekey').'", "size" : "compact"});
                          }
                          if (document.getElementById("recaptcha3") !== null) {
                              recaptcha3 = grecaptcha.render("recaptcha3", {"sitekey" : "'.Core::config('general.recaptcha_sitekey').'", "size" : "compact"});
                          }
                          if (document.getElementById("recaptcha4") !== null) {
                              recaptcha4 = grecaptcha.render("recaptcha4", {"sitekey" : "'.Core::config('general.recaptcha_sitekey').'"});
                          }
                      };'."\n";
            $html .= '</script>'."\n";

            self::$included_recaptcha_lib = TRUE;

            return $html;
        }

        return FALSE;
    }

    /**
     * verify recaptcha
     * @return boolean
     */
    public static function recaptcha_verify()
    {
        if (Core::config('general.recaptcha_secretkey') == '') // reCaptcha secret key not configured
            return TRUE;
        
        $params = array('secret' => Core::config('general.recaptcha_secretkey'),
                        'response' => Core::post('g-recaptcha-response'),
                        'remoteip' => Request::$client_ip);
        
        $response = Request::factory('https://www.google.com/recaptcha/api/siteverify')->method(Request::POST)->post($params)->execute()->body();
        $response = json_decode($response);
        
        if ($response->success)
            return TRUE;
        
        return FALSE;
    }
}
