<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Home extends Auth_Controller {

    
	public function action_index()
	{
        //if not god redirect him to the normal profile page
        if (Auth::instance()->get_user()->id_role!=Model_Role::ROLE_ADMIN)
            HTTP::redirect(Route::url('oc-panel',array('controller'  => 'profile','action'=>'index')));  

        $this->template->title = __('Welcome');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));

        $rss_url = 'http://feeds.feedburner.com/RssBlogOpenEshop';
        $rss = Feed::parse($rss_url,10);

		$this->template->content = View::factory('oc-panel/home',array('rss' => $rss));
	}
    

	

}
