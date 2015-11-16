<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Feed extends Controller {

	public function action_index()
	{
        $this->auto_render = FALSE;

		$info = array(
						'title' 	=> 'RSS '.htmlspecialchars(Core::config('general.site_name')),
						'pubDate' => date("D, d M Y H:i:s T"),
						'description' => __('Latest published'),
						'generator' 	=> 'Open eShop',
		); 
  		
  		$items = array();

  		//last products, you can modify this value at: general.feed_elements
        $products = DB::select('p.seotitle')
                ->select(array('c.seoname','category'),'p.title','p.description','p.created','p.updated')
                ->from(array('products', 'p'))
                ->join(array('categories', 'c'),'INNER')
                ->on('p.id_category','=','c.id_category')
                ->where('p.status','=',Model_Product::STATUS_ACTIVE)
                ->order_by('updated','desc')
                ->limit(Core::config('general.feed_elements'));

        //filter by category aor location
        if (Model_Category::current()->loaded())
            $products->where('p.id_category','=',Model_Category::current()->id_category);
        

       
        $products = $products->as_object()->cached()->execute();

        foreach($products as $p)
        {
            $url= Route::url('product',  array('category'=>$p->category,'seotitle'=>$p->seotitle));

            $items[] = array(
			                	'title'         => htmlspecialchars($p->title,ENT_QUOTES),
                                'link'          => $url,
                                'pubDate'       => Date::mysql2unix($p->updated),
                                'description'   => htmlspecialchars(Text::removebbcode($p->description),ENT_QUOTES),
			              );
        }
  
  		$xml = Feed::create($info, $items);

  		$this->response->headers('Content-type','text/xml');
        $this->response->body($xml);
	
	}

    public function action_blog()
    {
        $this->auto_render = FALSE;

        $info = array(
                        'title'     => 'RSS Blog '.htmlspecialchars(Core::config('general.site_name')),
                        'pubDate' => date("D, d M Y H:i:s T"),
                        'description' => __('Latest post published'),
                        'generator'     => 'Open Classifieds',
        ); 
        
        $items = array();

        $posts = new Model_Post();
        $posts = $posts->where('status','=', 1)
                ->order_by('created','desc')
                ->where('id_forum','IS',NULL)
                ->limit(Core::config('general.feed_elements'))
                ->cached()
                ->find_all();
           

        foreach($posts as $post)
        {
            $url= Route::url('blog',  array('seotitle'=>$post->seotitle));

            $items[] = array(
                                'title'         => preg_replace('/&(?!\w+;)/', '&amp;', $post->title),
                                'link'          => $url,
                                'pubDate'       => Date::mysql2unix($post->created),
                                'description'   => Text::removebbcode(preg_replace('/&(?!\w+;)/', '&amp;',$post->description)),
                          );
        }
  
        $xml = Feed::create($info, $items);

        $this->response->headers('Content-type','text/xml');
        $this->response->body($xml);
    
    }

    public function action_forum()
    {
        $this->auto_render = FALSE;

        $info = array(
                        'title'     => 'RSS Forum '.htmlspecialchars(Core::config('general.site_name')),
                        'pubDate' => date("D, d M Y H:i:s T"),
                        'description' => __('Latest post published'),
                        'generator'     => 'Open Classifieds',
        ); 
        
        $items = array();

        $topics = new Model_Topic();

        if(Model_Forum::current()->loaded())
            $topics->where('id_forum','=',Model_Forum::current()->id_forum);
        else
            $topics->where('id_forum','!=',NULL);//any forum
        
        $topics = $topics->where('status','=', Model_Topic::STATUS_ACTIVE)
                ->where('id_post_parent','IS',NULL)
                ->order_by('created','desc')
                ->limit(Core::config('general.feed_elements'))
                ->cached()
                ->find_all();
           
        foreach($topics as $topic)
        {
            $url= Route::url('forum-topic',  array('seotitle'=>$topic->seotitle,'forum'=>$topic->forum->seoname));

            $items[] = array(
                                'title'         => preg_replace('/&(?!\w+;)/', '&amp;', $topic->title),
                                'link'          => $url,
                                'pubDate'       => Date::mysql2unix($topic->created),
                                'description'   => Text::removebbcode(preg_replace('/&(?!\w+;)/', '&amp;',$topic->description)),
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
            $products = new Model_product();
            $total_products = $products->count_all();

            $last_product = $products->select('created')->order_by('created','desc')->limit(1)->find();
            $last_product = $last_product->created;

            $products = new Model_product();
            $first_product = $products->select('created')->order_by('created','asc')->limit(1)->find();
            $first_product = $first_product->created;

            $views = new Model_Visit();
            $total_views = $views->count_all();

            $users = new Model_User();
            $total_users = $users->count_all();

            $info = array(
                            'site_url'      => Core::config('general.base_url'),
                            'site_name'     => Core::config('general.site_name'),
                            'site_description' => Core::config('general.site_description'),
                            'created'       => $first_product,   
                            'updated'       => $last_product,   
                            'email'         => Core::config('email.notify_email'),
                            'version'       => Core::VERSION,
                            'theme'         => Core::config('appearance.theme'),
                            'theme_mobile'  => Core::config('appearance.theme_mobile'),
                            'charset'       => Kohana::$charset,
                            'timezone'      => Core::config('i18n.timezone'),
                            'locale'        => Core::config('i18n.locale'),
                            'currency'      => '',
                            'products'      => $total_products,
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
