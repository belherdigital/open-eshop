<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Feed extends Controller {

	public function action_index()
	{
        $this->auto_render = FALSE;

		$info = array(
						'title' 	=> 'RSS '.Core::config('general.site_name'),
						'pubDate' => date("D, d M Y H:i:s T"),
						'description' => __('Latest published'),
						'generator' 	=> 'Open eShop',
		); 
  		
  		$items = array();

  		//last products, you can modify this value at: general.feed_elements
        $products = DB::select('p.seotitle')
                ->select(array('c.seoname','category'),'p.title','p.description','p.created')
                ->from(array('products', 'p'))
                ->join(array('categories', 'c'),'INNER')
                ->on('p.id_category','=','c.id_category')
                ->where('p.status','=',Model_Product::STATUS_ACTIVE)
                ->order_by('created','desc')
                ->limit(Core::config('general.feed_elements'));

        //filter by category aor location
        if (Controller::$category!==NULL)
        {
            if (Controller::$category->loaded())
                $products->where('p.id_category','=',Controller::$category->id_category);
        }

       
        $products = $products->as_object()->cached()->execute();

        foreach($products as $p)
        {
            $url= Route::url('product',  array('category'=>$p->category,'seotitle'=>$p->seotitle));

            $items[] = array(
			                	'title' 	    => preg_replace('/&(?!\w+;)/', '&amp;', $p->title),
			                	'link' 	        => $url,
			                	'pubDate'       => Date::mysql2unix($p->created),
			                	'description'   => Text::removebbcode(preg_replace('/&(?!\w+;)/', '&amp;',$p->description)),
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
                        'title'     => 'RSS Blog '.Core::config('general.site_name'),
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
                            'site_name'     => Core::config('general.site_name'),
                            'site_url'      => Core::config('general.base_url'),
                            'created'       => $first_product,   
                            'updated'       => $last_product,   
                            'email'         => Core::config('email.notify_email'),
                            'version'       => Core::version,
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
