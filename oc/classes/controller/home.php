<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Controller {

	public function action_index()
	{

        if (core::config('general.landing_page')!='')
        {
            $page = Model_Content::get(core::config('general.landing_page'));

            if ($page->loaded())
            {
                $this->template->title            = $page->title;
                $this->template->meta_description = $page->description;

                $this->template->bind('content', $content);
                $this->template->content = View::factory('page',array('page'=>$page));
            }
        }
        else
        {
    	    //template header
    	    $this->template->title            = '';
    	    // $this->template->meta_keywords    = 'keywords';
    	    $this->template->meta_description = Core::config('general.site_description');
    	    
    	    
            $products = new Model_Product();
            $products = $products->where('status','=',Model_Product::STATUS_ACTIVE)->limit(Theme::get('num_home_products', 21))->cached()->find_all();


    		$categs = Model_Category::get_category_count();
    	
            $this->template->bind('content', $content);
            
            $this->template->content = View::factory('pages/home',array('products'=>$products, 
            															'categs'=>$categs,
            															));
    	}
	}

	// public function action_parent_cat() 

} // End Welcome
