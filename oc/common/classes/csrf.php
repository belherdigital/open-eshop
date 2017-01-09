<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Cross Site Request Forgery - basic system
 *
 * @see http://en.wikipedia.org/wiki/Cross-site_request_forgery
 *
 * @package    OC
 * @category   Text
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class CSRF {

        /**
         * Returns the token in the session or generates a new one
         *
         * @param   string  $namespace - semi-unique name for the token (support for multiple forms)
         * @return  string
         */
        public static function token($namespace='default')
        {
                $token = Session::instance()->get('csrf-token-'.$namespace);

                // Generate a new token if no token is found
                if ($token === NULL)
                {
                	$token = Text::random('alnum', rand(20, 30));
                    Session::instance()->set('csrf-token-'.$namespace, $token);
                }
                return $token;
        }

        /**
         * Generates the CSRF form input
         * @uses    Form
         * @param   string  $namespace
         * @return  string  generated HTML
         */
        public static function form($namespace='default')
        {
                return Form::hidden('csrf_'.$namespace, CSRF::token($namespace));
        }
        
        /**
         * Validation rule for checking a valid token
         *
         * @param   string  $namespace - the token string to check for
         * @return  bool
         */
        public static function valid($namespace=NULL)
        {
        	if ($namespace===NULL)
        		$namespace = URL::title(Request::current()->uri());
        	
        	return Request::$current->post('csrf_'.$namespace) === self::token($namespace);
        }
}