<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page extends Controller {

    /**
     *
     * Display single page
     * @throws HTTP_Exception_404
     */
    public function action_view()
    {
        $seotitle = $this->request->param('seotitle',NULL);
        
        if ($seotitle!==NULL)
        {
            $page = Model_Content::get($seotitle);

            if ($page->loaded())
            {
                Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
                Breadcrumbs::add(Breadcrumb::factory()->set_title($page->title));

                $this->template->title            = $page->title;
                $this->template->meta_description = $page->description;

                $this->template->bind('content', $content);
                $this->template->content = View::factory('page',array('page'=>$page));
            }
            //not found in DB
            else
            {
                //throw 404
                throw new HTTP_Exception_404();
            }

        }
        else//this should never happen
        {
            //throw 404
            throw new HTTP_Exception_404();
        }
    }


} // End Page controller
