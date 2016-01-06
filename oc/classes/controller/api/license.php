<?php defined('SYSPATH') or die('No direct script access.');


class Controller_Api_License extends Api_Controller {

    /**
     * Handle GET requests.
     */
    public function action_domain()
    {
        try
        {
            $result = FALSE;

            if ( ($license = $this->request->param('id'))!=NULL )
            {
                //getting domain from referrer
                if ( ($domain = $this->request->referrer())!==NULL) 
                    $domain = parse_url($domain, PHP_URL_HOST);
                else//TODO remove in few versions, we should use only referrer
                    $domain  = Core::request('domain');

                if ($license!=NULL AND $domain!=NULL)
                    $result = Model_License::verify($license,$domain);                     
            }

            $this->rest_output(array('valid' => $result));
           
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
    }

    /**
     * Handle GET requests.
     */
    public function action_device()
    {
        try
        {
            $result = FALSE;

            if ( ($license = $this->request->param('id'))!=NULL )
            {
                $device_id  = Core::request('device_id');

                if ($license!=NULL AND $device_id!=NULL)
                    $result = Model_License::verify_device($license,$device_id);                     
            }

            $this->rest_output(array('valid' => $result));
           
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
    }



} // END