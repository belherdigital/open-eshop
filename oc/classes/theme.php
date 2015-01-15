<?php defined('SYSPATH') or die('No direct script access.');
/**
 * theme functionality
 *
 * @package    OC
 * @category   Theme
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

class Theme extends OC_Theme{

    public static function license($l, $current_theme = NULL)
    {
        return TRUE;
    }

    public static function checker()
    {
        return TRUE;
    }

    /**
     * get from data array
     * @param  string $name key
     * @param mixed default value in case is not set
     * @return mixed
     */
    public static function get($name, $default = NULL)
    {
        if ($name == 'license')
            return time();
        else
            return parent::get($name, $default);
    }

}