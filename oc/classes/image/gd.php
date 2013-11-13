<?php defined('SYSPATH') OR die('No direct script access.');

class Image_GD extends Kohana_Image_GD {


    /**
     * Checks if GD is enabled and bundled. Bundled GD is required for some
     * methods to work. Exceptions will be thrown from those methods when GD is
     * not bundled.
     *
     * @return  boolean
     */
    public static function check()
    {
        parent::check();

        // Chema-> theres a bug here sometimes returns 0 as version :(
        if (defined('GD_BUNDLED'))
        {

            // Get the version via a constant, available in PHP 5.
            Image_GD::$_bundled = (GD_BUNDLED==0)?1:GD_BUNDLED;
        }
        else
        {
            // Get the version information
            $info = gd_info();

            // Extract the bundled status
            Image_GD::$_bundled = (bool) preg_match('/\bbundled\b/i', $info['GD Version']);
        }

       
    }
}