<?php defined('SYSPATH') or die('No direct script access.');
/**
 * URL helper class.
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class URL extends Kohana_URL {


    /**
     * Convert a phrase to a URL-safe title. Overwriten original to ascii only depending on language
     *
     *     echo URL::title('My Blog Post'); // "my-blog-post"
     *
     * @param   string   $title       Phrase to convert
     * @param   string   $separator   Word separator (any single character)
     * @param   boolean  $ascii_only  Transliterate to ASCII?
     * @return  string
     * @uses    UTF8::transliterate_to_ascii
     */
    public static function title($title, $separator = '-', $ascii_only = NULL)
    {
        /**
         * this hack is to add tohse languages that are not in ascii, so we add them to the array
         * @var boolean
         */
        // if ($ascii_only === NULL)
        //     $ascii_only = ( in_array(i18n::$locale, array('hi_IN','ar','ur_PK','ru_RU','bn_BD','ml_IN','ja_JP')) )? FALSE:TRUE;

        $ascii_only = (mb_detect_encoding($title,'ASCII')===FALSE)? FALSE:TRUE;
        
        return parent::title($title, $separator, $ascii_only);
    }

    /**
     * Fetches an absolute site URL based on a URI segment.
     *
     *     echo URL::site('foo/bar');
     *
     * @param   string  $uri        Site URI to convert
     * @param   mixed   $protocol   Protocol string or [Request] class to use protocol from
     * @param   boolean $index      Include the index_page in the URL
     * @return  string
     * @uses    URL::base
     */
    public static function site($uri = '', $protocol = NULL, $index = TRUE)
    {
        // Chop off possible scheme, host, port, user and pass parts
        $path = preg_replace('~^[-a-z0-9+.]++://[^/]++/?~', '', trim($uri, '/'));

        // Encode all non-ASCII characters, as per RFC 1738
        if(mb_detect_encoding($path,'ASCII')===TRUE)
        {
            $path = parent::title($path, '-', TRUE);
        }

        // Concat the URL
        return URL::base($protocol, $index).$path;
    }

    /**
     * returns the current url we are visiting with querystring included
     * @return [type] [description]
     */
    public static function current()
    {
        $query_string = ($_SERVER['QUERY_STRING']!='')? '?'.$_SERVER['QUERY_STRING']:'';

        return URL::base().Request::current()->uri().$query_string;
    }


} // End url
