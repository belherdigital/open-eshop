<?php defined('SYSPATH') or die('No direct script access.');

class Kohana extends Kohana_Core {

    /**
     * original requested data
     * @var array
     */
    public static $_POST_ORIG;
    public static $_GET_ORIG;
    public static $_COOKIE_ORIG;

    /**
     * overrides default init
     * @param  array $settings 
     * @return void           
     */
    public static function init(array $settings = NULL)
    {
        //before cleaning getting a copy of the original in case we need it.
        self::$_GET_ORIG    = $_GET;
        self::$_COOKIE_ORIG = $_COOKIE;

        //we remove slashes if needed
        self::$_POST_ORIG = Kohana::stripslashes($_POST);
        

        parent::init($settings);

    }

    /**
     * Override
     * Recursively sanitizes an input variable:
     *
     * - Strips slashes if magic quotes are enabled
     * - Normalizes all newlines to LF
     *
     * @param   mixed   $value  any variable
     * @return  mixed   sanitized variable
     */
    public static function sanitize($value)
    {
        if (is_array($value) OR is_object($value))
        {
            foreach ($value as $key => $val)
            {
                // Recursively clean each value
                $value[$key] = Kohana::sanitize($val);
            }
        }
        elseif (is_string($value))
        {
            if (Kohana::$magic_quotes === TRUE)
            {
                // Remove slashes added by magic quotes
                $value = stripslashes($value);
            }

            if (strpos($value, "\r") !== FALSE)
            {
                // Standardize newlines
                $value = str_replace(array("\r\n", "\r"), "\n", $value);
            }

            //Added strip tags
            $value = strip_tags($value);
        }

        return $value;
    }


    /**
     * Override
     * Recursively stripslashes an input variable:
     *
     * - Strips slashes if magic quotes are enabled
     *
     * @param   mixed   $value  any variable
     * @return  mixed   stripslashes variable
     */
    public static function stripslashes($value)
    {
        if (Kohana::$magic_quotes === TRUE)
        {
            if (is_array($value) OR is_object($value))
            {
                foreach ($value as $key => $val)
                {
                    // Recursively clean each value
                    $value[$key] = Kohana::stripslashes($val);
                }
            }
            elseif (is_string($value))
            {
                // Remove slashes added by magic quotes
                $value = stripslashes($value);
            }
        }

        return $value;
    }



}
