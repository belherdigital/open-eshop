<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Home extends Auth_Controller {

    
	public function action_index()
	{
        //if not god redirect him to the normal profile page
        if (Auth::instance()->get_user()->id_role!=Model_Role::ROLE_ADMIN)
            Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'profile','action'=>'index')));  


        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Welcome')));
		$this->template->title = 'Welcome';


        //try to get the RSS from the cache
        $rss_url = 'http://feeds.feedburner.com/OpenClassifieds';
        $rss = Core::cache($rss_url,NULL,3*24*60*60);

        //not cached :(
        if ($rss === NULL)
        {
            $rss = Feed::parse($rss_url,10);
            Core::cache($rss_url,$rss,3*24*60*60);
        }


		$this->template->content = View::factory('oc-panel/home',array('rss' => $rss));
	}
    

	

}
