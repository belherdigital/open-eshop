<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Text helper class
 *
 * @package    OC
 * @category   Text
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class Text extends OC_Text {

    /**
     * Receives a description as a string to replace all baned word
     * with replacement provided.
     * array of baned words and replacement is get fromconfig
     * @param string text
     * @return string 
     */
    public static function banned_words($text)
    {

        if (core::config('general.banned_words') != NULL AND core::config('general.banned_words') != '')
        {
            $banned_words = explode(',',core::config('general.banned_words'));
            $banned_words = array_map('trim', $banned_words);
            
            // with provided array of baned words, replacement and string to be replaced
            // returns string with replaced words
            return str_replace($banned_words, core::config('general.banned_words_replacement'), $text);
        }
        else
            return $text;
    }

}