<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sociual auth class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@open-classifieds.com>, Slobodan <slobodan@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */


class Social {

    public static function get()
    {
        $config = json_decode(core::config('social.config'),TRUE);
        return (!is_array($config))? array():$config;
    }

    public static function get_providers()
    {
        $providers = self::get();

        return (isset($providers['providers']))?$providers['providers']:array();
    }

    public static function include_vendor()
    {
        require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Auth','php');
        require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Endpoint','php');
    }


}