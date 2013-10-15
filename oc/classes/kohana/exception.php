<?php defined('SYSPATH') or die('No direct script access.');

/**
* Custom exception handler for typical 404/500 error
*
* @package    OC
* @category   Exception
* @author     Lysender && Chema <chema@garridodiaz.com>
* @copyright  (c) 2009-2013 Open Classifieds Team
* @license    GPL v3
*/


class Kohana_Exception extends Kohana_Kohana_Exception
{
 
    public static function handler(Exception $e)
    {
        if (Kohana::$environment !== Kohana::PRODUCTION)
        {
            parent::handler($e);
        }
        else
        {
            try
            {
                //not saving 404 as error
                if ($e->getCode()!=404)
                    Kohana::$log->add(Log::ERROR, parent::text($e));
 
                $params = array
                (
                    'action'  => 500,
                    'origuri'   => rawurlencode(Arr::get($_SERVER, 'REQUEST_URI')),
                    'message' => rawurlencode($e->getMessage())
                );
 
                if ($e instanceof HTTP_Exception)
                {
                    $params['action'] = $e->getCode();
                }

                //d($params);
 
                // Error sub-request.
                echo Request::factory(Route::get('error')->uri($params))
                    ->execute()
                    ->send_headers()
                    ->body();
            }
            catch (Exception $e)
            {
                // Clean the output buffer if one exists
                ob_get_level() and ob_clean();
 
                // Display the exception text
                echo parent::text($e);
 
                // Exit with an error status
                exit(1);
            }
        }
    }
}
