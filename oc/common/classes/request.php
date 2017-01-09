<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Request. Uses the [Route] class to determine what
 * [Controller] to send the request to.
 *
 * @package    Kohana
 * @category   Base
 * @author      Chema <chema@open-classifieds.com>
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */
class Request extends Kohana_Request {


    /**
     * sets a param for the request
     *
     *     $request->set_param('id','5');
     *
     * @param   string   $key      Key of the value
     * @param   mixed    $value   value
     */
    public function set_param($key , $value )
    {
        $this->_params[$key] = $value;
    }

}