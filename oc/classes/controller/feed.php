<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Feed extends Controller {

	public function action_index()
	{
        $this->auto_render = FALSE;

		$info = array(
						'title' 	=> 'RSS '.Core::config('general.site_name'),
						'pubDate' => date("D, d M Y H:i:s T"),
						'description' => __('Latest published'),
						'generator' 	=> 'Open Classifieds',
		); 
  		
  		$items = array();

  		//last ads, you can modify this value at: general.feed_elements
        $ads = DB::select('a.seotitle')
                ->select(array('c.seoname','category'),'a.title','a.description','a.published')
                ->from(array('ads', 'a'))
                ->join(array('categories', 'c'),'INNER')
                ->on('a.id_category','=','c.id_category')
                ->where('a.status','=',Model_Ad::STATUS_PUBLISHED)
                ->order_by('published','desc')
                ->limit(Core::config('general.feed_elements'));

        //filter by category aor location
        if (Controller::$category!==NULL)
        {
            if (Controller::$category->loaded())
                $ads->where('a.id_category','=',Controller::$category->id_category);
        }

        if (Controller::$location!==NULL)
        {
            if (Controller::$location->loaded())
                $ads->where('a.id_location','=',Controller::$location->id_location);
        }

        $ads = $ads->as_object()->cached()->execute();

        foreach($ads as $a)
        {
            $url= Route::url('ad',  array('category'=>$a->category,'seotitle'=>$a->seotitle));

            $items[] = array(
			                	'title' 	    => preg_replace('/&(?!\w+;)/', '&amp;', $a->title),
			                	'link' 	        => $url,
			                	'pubDate'       => Date::mysql2unix($a->published),
			                	'description'   => Text::removebbcode(preg_replace('/&(?!\w+;)/', '&amp;',$a->description)),
			              );
        }
  
  		$xml = Feed::create($info, $items);

  		$this->response->headers('Content-type','text/xml');
        $this->response->body($xml);
	
	}


    public function action_info()
    {

        //try to get the info from the cache
        $info = Core::cache('action_info',NULL);

        //not cached :(
        if ($info === NULL)
        {
            $ads = new Model_Ad();
            $total_ads = $ads->count_all();

            $last_ad = $ads->select('published')->order_by('published','desc')->limit(1)->find();
            $last_ad = $last_ad->published;

            $ads = new Model_Ad();
            $first_ad = $ads->select('published')->order_by('published','asc')->limit(1)->find();
            $first_ad = $first_ad->published;

            $views = new Model_Visit();
            $total_views = $views->count_all();

            $users = new Model_User();
            $total_users = $users->count_all();

            $info = array(
                            'site_name'     => Core::config('general.site_name'),
                            'site_url'      => Core::config('general.base_url'),
                            'created'       => $first_ad,   
                            'updated'       => $last_ad,   
                            'email'         => Core::config('email.notify_email'),
                            'version'       => Core::version,
                            'theme'         => Core::config('appearance.theme'),
                            'theme_mobile'  => Core::config('appearance.theme_mobile'),
                            'charset'       => Core::config('i18n.charset'),
                            'timezone'      => Core::config('i18n.timezone'),
                            'locale'        => Core::config('i18n.locale'),
                            'currency'      => '',
                            'ads'           => $total_ads,
                            'views'         => $total_views,
                            'users'         => $total_users,
            );

            Core::cache('action_info',$info);
        }
       

        $this->response->headers('Content-type','application/javascript');
        $this->response->body(json_encode($info));

    }


    /**
     * after does nothing since we send an XML
     */
    public function after(){}


} // End feed
