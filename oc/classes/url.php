<?php defined('SYSPATH') or die('No direct script access.');
/**
 * URL helper class.
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Chema <chema@garridodiaz.com>
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
        if ($ascii_only === NULL)
            $ascii_only = ( in_array(i18n::$locale, array('hi_IN','ar','ur_PK','ru_RU','bn_BD','ml_IN','ja_JP')) )? FALSE:TRUE;

                
        return parent::title($title, $separator, $ascii_only);
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
