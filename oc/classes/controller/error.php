<?php defined('SYSPATH') or die('No direct script access.');
/**
* Error controller
*
* @package    OC
* @category   Controller
* @author     Chema <chema@garridodiaz.com>
* @copyright  (c) 2009-2013 Open Classifieds Team
* @license    GPL v3
*/
class Controller_Error extends Controller
{
    /**
     * @var string
     */
    protected $_requested_page;
 
    /**
     * @var string
     */
    protected $_message;
 
    /**
     * Pre determine error display logic
     */
    public function before($template = NULL)
    {
        parent::before();
 
        // Sub requests only!
        if ( ! $this->request->is_initial() )
        {
            if ($message = rawurldecode($this->request->param('message')))
            {
                $this->_message = $message;
            }
 
            if ($requested_page = rawurldecode($this->request->param('origuri')))
            {
                $this->_requested_page = $requested_page;
            }
        }
        else
        {
            // This one was directly requested, don't allow
            $this->request->action(404);
 
            // Set the requested page accordingly
            $this->_requested_page = Arr::get($_SERVER, 'REQUEST_URI');
        }
 
        $this->response->status((int) $this->request->action());
    }
 
    /**
     * Serves HTTP 404 error page
     */
    public function action_404()
    {
        $this->template->meta_description = 'The requested page '.$this->_requested_page.' not found';
        $this->template->meta_keywords = 'not found, 404';
        $this->template->title = 'Page '.$this->_requested_page.' not found';
 
        $this->template->content = View::factory('pages/error/404')
            ->set('error_message', $this->_message)
            ->set('requested_page', $this->_requested_page);
    }
 
    /**
     * Serves HTTP 500 error page
     */
    public function action_500()
    {
        $this->auto_render = FALSE;

        $this->template = View::factory('pages/error/500');
        $this->response->body($this->template->render());
    }



}