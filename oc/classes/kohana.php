<?php defined('SYSPATH') or die('No direct script access.');

class Kohana extends Kohana_Core {

    /**
     * @var  boolean  True if Kohana is running from the command line
     */
    public static $is_cli = FALSE;

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
     * Provides auto-loading support of classes that follow Kohana's old class
     * naming conventions.
     *
     * This is included for compatibility purposes with older modules.
     *
     * @param   string  $class      Class name
     * @param   string  $directory  Directory to load from
     * @return  boolean
     */
    public static function auto_load_lowercase($class, $directory = 'classes')
    {
        // Transform the class name into a path
        $file = str_replace('_', DIRECTORY_SEPARATOR, $class);

        //try find file in lower
        $path = Kohana::find_file($directory, strtolower($file));
        
        //shit not found, try normal...
        if (!$path)
            $path = Kohana::find_file($directory, $file);

        //oh yeah baby fund you!
        if ($path)
        {
             // Load the class file
            require $path;

            // Class has been found
            return TRUE;
        }

        // Class is not in the filesystem @todo throw exception?    
        return FALSE;
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
