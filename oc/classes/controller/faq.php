<?php defined('SYSPATH') or die('No direct script access.');

class Controller_FAQ extends Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('FAQ'))->set_url(Route::url('faq')));

    }

    public function action_index()
    {
        //if they want to see a single post
        $seotitle = $this->request->param('seotitle',NULL);
        if ($seotitle!==NULL)
            return $this->action_view($seotitle);


        //template header
        $this->template->title            = __('FAQ');
        $this->template->meta_description = __('FAQ');

        $this->template->styles = array('css/faq.css' => 'screen');
        $this->template->scripts['footer'] = array('js/faq.js');
        
        //FAQ CMS 
        $faqs =  new Model_Content();
        $faqs = $faqs->where('type','=','help')->where('status','=','1')->find_all();

        
        $this->template->bind('content', $content);
        
        $this->template->content = View::factory('pages/faq/listing',array('faqs'=>$faqs));
        
    }


   /**
     *
     * Display single page
     * @throws HTTP_Exception_404
     */
    public function action_view($seotitle)
    {
       
        $page = Model_Content::get($seotitle,'help');

        if ($page->loaded())
        {
            Breadcrumbs::add(Breadcrumb::factory()->set_title($page->title));

            $this->template->title            = $page->title;
            $this->template->meta_description = $page->description;

            $this->template->bind('content', $content);

            if ($page->status == 1 AND strlen(core::config('general.faq_disqus'))>0 )
            {
                $disqus = View::factory('pages/disqus',
                                array('disqus'=>core::config('general.faq_disqus')))
                        ->render();
            }
            else 
                $disqus = '';

            $this->template->content = View::factory('pages/faq/single',array('page'=>$page,'disqus'=>$disqus));
        }
        //not found in DB
        else
        {
            //throw 404
            throw new HTTP_Exception_404();
        }

    }

} // End FAQ
