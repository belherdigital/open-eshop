<?php defined('SYSPATH') or die('No direct script access.');

class Controller_FAQ extends Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('FAQ'))->set_url(Route::url('faq')));

    }

    public function action_index()
    {
        //if they want to see a single post
        $seotitle = $this->request->param('seotitle',NULL);
        if ($seotitle!==NULL)
            return $this->action_view($seotitle);


        //template header
        $this->template->title            = __(' Frequently Asked Questions - FAQ');
        $this->template->meta_description = $this->template->title;

        $this->template->styles = array('css/faq.css' => 'screen');
        $this->template->scripts['footer'] = array('js/faq.js');
        
        //FAQ CMS 
        $faqs =  new Model_Content();
        $faqs = $faqs->where('type','=','help')->where('status','=','1')->find_all();

        
        $this->template->bind('content', $content);

        if (strlen(core::config('general.faq_disqus'))>0 )
            $disqus = View::factory('pages/disqus',array('disqus'=>core::config('general.faq_disqus')));
         else 
            $disqus = '';
        
        $this->template->content = View::factory('pages/faq/listing',array('faqs'=>$faqs,'disqus'=>$disqus));
        
    }


   /**
     *
     * Display single faq
     * @throws HTTP_Exception_404
     */
    public function action_view($seotitle)
    {
       
        $faq = Model_Content::get_by_title($seotitle,'help');

        if ($faq->loaded())
        {
            Breadcrumbs::add(Breadcrumb::factory()->set_title($faq->title));

            $this->template->title            = $faq->title.' - '.__(' Frequently Asked Questions - FAQ');
            $this->template->meta_description = $faq->description.' - '.__(' Frequently Asked Questions - FAQ');

            $this->template->bind('content', $content);

            if ($faq->status == 1 AND strlen(core::config('general.faq_disqus'))>0 )
                $disqus = View::factory('pages/disqus',array('disqus'=>core::config('general.faq_disqus')));
            else 
                $disqus = '';

            $this->template->content = View::factory('pages/faq/single',array('faq'=>$faq,'disqus'=>$disqus));
        }
        //not found in DB
        else
        {
            //throw 404
            throw HTTP_Exception::factory(404,__('Page not found'));
        }

    }

} // End FAQ
