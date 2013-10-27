<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api extends Controller {




    public function action_license()
    {
        $this->auto_render = FALSE;
        $license = $this->request->param('id');
        $domain  = Core::post('domain');
        if ($license!=NULL AND $domain!=NULL)
            $result = Model_License::verify($license,$domain); 
        else
            $result = FALSE;

        $this->response->headers('Content-type','application/javascript');
        $this->response->body(json_encode($result));
    }


    /**
     * after does nothing since we send an XML
     */
    public function after(){}


} // End feed
