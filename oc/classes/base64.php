<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Base64 Helper
 *
 * @package    OC
 * @category   Helper
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Base64 {

    const CHARS_BAD = '+/=';
    const CHARS_OK = '-_ ';
    
    /**
     * gets an string form a url and replaces de old good chars to the bad ones, so we get the orig content
     * @param string $url
     * @return string 
     */
    public static function fix_from_url($url)
    {
        $base64 = strtr($url, self::CHARS_OK, self::CHARS_BAD);

        return $base64;
    }
    
	/**
	 * 
	 * replaces bad characters for the url to be good ones
	 * @param string $base64
	 * @return string
	 */
    public static function fix_to_url($base64)
    {
        $url = trim(strtr($base64, self::CHARS_BAD, self::CHARS_OK));

        return $url;
    }

    /**
     * 
     * Encodes base64 and fixes the url
     * @param string $str
     * @return string
     */
    public static function encode_to_url($str)
    {
        $url = self::fix_to_url(base64_encode($str));
                
        return $url;
    }
    
    /**
     * decodes the base 64 and puts it back to the original
     * @param string $url
     * @return string
     */
    public static function decode_from_url($url)
    {
        $str = base64_decode(self::fix_from_url($url));
        
        return $str;
    }
    
} // End Base64