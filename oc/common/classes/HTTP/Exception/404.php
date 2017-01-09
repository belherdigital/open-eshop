<?php defined('SYSPATH') or die('No direct script access.');


class HTTP_Exception_404 extends Kohana_HTTP_Exception_404 {
 
    /**
     * Generate a Response for the 404 Exception.
     *
     * The user should be shown a nice 404 page.
     * 
     * @return Response
     */
    public function get_response()
    {
        $url = Route::get('error')->uri(array('action'  => 404,
                                              'message' => Base64::encode_to_url($this->getMessage())));

        $body = Request::factory($url)->execute();

        return Response::factory()->status(404)
                        ->body($body);
    }
}