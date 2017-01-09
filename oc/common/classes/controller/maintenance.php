<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Maintenance extends Kohana_Controller {

	public function action_index()
	{
        //in case there's no maintenance go back to the home.
        if (Core::config('general.maintenance')!='1')
            HTTP::redirect(Route::url('default'));

        $this->response->status(503);
        $this->response->headers('Retry-After','555'); // header('Retry-After: 555');
 
        $this->response->body(View::factory('pages/error/503')->render());
	}

} // End 
