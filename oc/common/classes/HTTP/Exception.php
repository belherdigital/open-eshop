<?php defined('SYSPATH') or die('No direct script access.');


class HTTP_Exception extends Kohana_HTTP_Exception {
 
    /**
     * Generate a Response for all Exceptions without a more specific override
     * 
     * The user should see a nice error page, however, if we are in development
     * mode we should show the normal Kohana error page.
     * 
     * @return Response
     */
    public function get_response()
    {        
 
        if (Kohana::$environment === Kohana::DEVELOPMENT)
        {
            // Show the normal Kohana error page.
            return parent::get_response();
        }
        else
        {
            //not saving 404 as error
            if ($this->getCode()!=404)
                Kohana::$log->add(Log::ERROR, parent::text($this));

            // Generate a nicer looking "Oops" page.
            $view = View::factory('pages/error/default', array('message'=>$this->getMessage()) );
 
            $response = Response::factory()
                ->status($this->getCode())
                ->body($view->render());
 
            return $response;
        }
    }
}