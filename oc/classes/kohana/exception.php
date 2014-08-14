<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Kohana exception class. Translates exceptions using the [I18n] class.
 *
 * @package    Kohana
 * @category   Exceptions
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_Exception extends Kohana_Kohana_Exception {



    /**
     * Get a Response object representing the exception
     *
     * @uses    Kohana_Exception::text
     * @param   Exception  $e
     * @return  Response
     */
    public static function response(Exception $e)
    {

        if (Kohana::$environment === Kohana::DEVELOPMENT)
        {
            // Show the normal Kohana error page.
            return parent::response($e);
        }
        else
        {
            // Lets log the Exception, Just in case it's important!
            Kohana::$log->add(Log::ERROR, parent::text($e));

            // Generate a nicer looking "Oops" page.
            $view = View::factory('pages/error/default', array('message'=>$e->getMessage()) );
 
            $response = Response::factory()
                ->status(500)
                ->body($view->render());
 
            return $response;
        }
        
    }

}
