<?php defined('SYSPATH') or die('No direct script access.');

/**
* product class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Chema Garrido <chema@open-classifieds.com>, Slobodan Josifovic <slobodan@open-classifieds.com>
* @license GPL v3
*/

class Controller_Product extends Controller{
	
	
	public function action_view()
	{
        //to load the minimal view of the product
        if (core::get('ext',$this->request->param('ext'))=='1')
        {

            $this->before('main-minimal');
            $product_view = 'pages/product/minimal';
            $this->template->styles = array('css/style-minimal.css' => 'screen');
            $this->template->scripts['footer'] = array('js/minimal.js');
        }
        else
           $product_view = 'pages/product/single'; 

        $product = new Model_product();
        $product->where('seotitle','=',$this->request->param('seotitle'))
            ->where('status','=',Model_Product::STATUS_ACTIVE)
            ->limit(1)->find();

        if ($product->loaded())
        {
            //product image
            if($product->get_first_image() !== NULL)
                Controller::$image = $product->get_first_image();   

            //stripe button @todo not working we dont receive the stripeEmail as post
            // if ( Core::config('payment.stripe_private')!='' AND Core::config('payment.stripe_public')!='' )
            // {
            //     $this->template->scripts['footer'][] = 'https://checkout.stripe.com/checkout.js';
            //     $this->template->scripts['footer'][] = Route::url('default',array('controller'=>'stripe','action'=>'javascript','id'=>$product->seotitle)).'?t='.time();
            // }    
            
            $hits = $product->count_hit();

            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
            Breadcrumbs::add(Breadcrumb::factory()->set_title($product->category->name)->set_url(Route::url('list',array('category'=>$product->category->seoname))));
            Breadcrumbs::add(Breadcrumb::factory()->set_title($product->title));
           
            $this->template->title            = $product->title.' - '.$product->category->name;
            $this->template->meta_description = $product->description;

            $this->template->bind('content', $content);
            $this->template->bind('product', $product);

            $skins = NULL;
            if ($product->skins!='')
                $skins = explode(',',$product->skins);
            if (!is_array($skins) OR count($skins)<=0)
                $skins = NULL;

            $this->template->bind('skins', $skins);

            //number of orders
            $number_of_orders = $product->number_of_orders();

            $this->template->content = View::factory($product_view,array('product'=>$product,
                                                                         'hits'=>$hits, 
                                                                         'images'=>$images = $product->get_images(),
                                                                         'skins'=>$skins,
                                                                         'number_orders'=>$number_of_orders));

		}
		else
		{
			Alert::set(Alert::INFO, __('Product not found.'));
            $this->redirect(Route::url('default'));
		}
	}

    /**
     * action product goal after buying
     * @return void 
     */
    public function action_goal()
    {

        $product = new Model_product();
        $product->where('seotitle','=',$this->request->param('seotitle'))
            ->where('status','=',Model_Product::STATUS_ACTIVE)
            ->limit(1)->find();

        if ($product->loaded())
        {
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
            Breadcrumbs::add(Breadcrumb::factory()->set_title($product->category->name)->set_url(Route::url('list',array('category'=>$product->category->seoname))));
            Breadcrumbs::add(Breadcrumb::factory()->set_title($product->title)->set_url(Route::url('product',array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))));
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Thanks')));
            $this->template->title            = $product->title.' - '.$product->category->name;
            $this->template->meta_description = $product->description;

            $thanks_message = NULL;

            if (core::config('payment.thanks_page')!='')
            {
                $thanks_message = Model_Content::get_by_title(core::config('payment.thanks_page'));
            }

            $order = NULL;
            $price_paid = 0;

            //in case we have the order on the URL
            if ($this->request->param('order'))
            {
                $order = new Model_Order($this->request->param('order'));
              
                if (!$order->loaded())
                    $order = NULL;
                else
                    $price_paid = $order->amount;
            }
            //we dont have the order in the URL
            elseif (Auth::instance()->logged_in())
            {
                $user = Auth::instance()->get_user();
                $order = new Model_Order();
                $order->where('id_user','=',$user->id_user)
                    ->where('id_product','=',$product->id_product)
                    ->where('status','=',Model_Order::STATUS_PAID)
                    ->order_by('created','desc')->limit(1)->find();
                if (!$order->loaded())
                    $order = NULL;
                else
                    $price_paid = $order->amount;
            }
            //from paypal @ paypal form seted
            else
                $price_paid = Session::instance()->get_once('goal_'.$product->id_product,$product->final_price());

            $this->template->bind('content', $content);
            $this->template->content = View::factory('pages/product/goal',array('product'=>$product,'thanks_message'=>$thanks_message,'order'=>$order,'price_paid'=>$price_paid));

        }
        else
        {
            Alert::set(Alert::INFO, __('Product not found.'));
            $this->redirect(Route::url('default'));
        }
    }

    /**
     * action product reviews after buying
     * @return void 
     */
    public function action_reviews()
    {

        $this->template->styles = array('css/review.css' => 'screen');
        $product = new Model_product();

        $product->where('seotitle','=',$this->request->param('seotitle'))
            ->where('status','=',Model_Product::STATUS_ACTIVE)
            ->limit(1)->find();

        if ($product->loaded())
        {
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
            Breadcrumbs::add(Breadcrumb::factory()->set_title($product->category->name)->set_url(Route::url('list',array('category'=>$product->category->seoname))));
            Breadcrumbs::add(Breadcrumb::factory()->set_title($product->title)->set_url(Route::url('product',array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))));
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Reviews')));
            $this->template->title            = __('Reviews').' '.$product->title.' - '.$product->category->name;
            $this->template->meta_description = $product->description;

            $reviews = new Model_Review();
            $reviews = $reviews->where('id_product','=',$product->id_product)
                                ->where('status', '=', Model_Review::STATUS_ACTIVE)->find_all();


            $skins = NULL;
            if ($product->skins!='')
                $skins = explode(',',$product->skins);
            if (!is_array($skins) OR count($skins)<=0)
                $skins = NULL;

            //product image
            if($product->get_first_image() !== NULL)
                Controller::$image = $product->get_first_image();   

            $this->template->bind('content', $content);
            $this->template->content = View::factory('pages/product/review',array('product'=>$product,'reviews'=>$reviews, 'skins'=>$skins));

        }
        else
        {
            Alert::set(Alert::INFO, __('Product not found.'));
            $this->redirect(Route::url('default'));
        }
    }

    public function action_demo()
    {
        $this->before('demo');

        $product = new Model_product();
        $product->where('seotitle','=',$this->request->param('seotitle'))
            ->where('status','=',Model_Product::STATUS_ACTIVE)
            ->limit(1)->find();

        if ($product->loaded())
        {
            //count how many visits has
            $hits = new Model_Visit();
            $hits = $hits->where('id_product','=', $product->id_product)->count_all();
           
            $this->template->title            = $product->title. ' - '.__('Demo').' - '.$product->category->name;
            $this->template->meta_description = __('Demo').', '.__('preview').','.$product->description;

            $this->template->bind('product', $product);

            //get all the products same category
            $products = $product->category->products
                        ->where('status','=',Model_Product::STATUS_ACTIVE)
                        ->cached()->find_all();
            $this->template->bind('products', $products);

            $skins = NULL;
            if ($product->skins!='')
                $skins = explode(',',$product->skins);
            if (!is_array($skins) OR count($skins)<=0)
                $skins = NULL;

            $this->template->bind('skins', $skins);

            $skin = core::get('skin');
            $this->template->bind('skin', $skin);     

            //product image
            if($product->get_first_image() !== NULL)
                Controller::$image = $product->get_first_image();      
        }
        else
        {
            Alert::set(Alert::INFO, __('Product not found.'));
            $this->redirect(Route::url('default'));
        }
    }

    public function action_listing()
    {
        if(Theme::get('infinite_scroll'))
        {
            $this->template->scripts['footer'][] = 'http://cdn.jsdelivr.net/jquery.infinitescroll/2.0b2/jquery.infinitescroll.js';
            $this->template->scripts['footer'][] = 'js/listing.js';
        }
        $this->template->scripts['footer'][] = 'js/sort.js';
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
        
        /**
         * we get the model of category from controller to filter and generate urls titles etc...
         */
        $category = NULL;
        $category_parent = NULL;
     
        if (Model_Category::current()->loaded())
        {
            $category = Model_Category::current();
            //adding the category parent
            if ($category->id_category_parent!=1 AND $category->parent->loaded())
                $category_parent = $category->parent;

            //category image
            if(file_exists(DOCROOT.'images/categories/'.$category->seoname.'.png'))
                Controller::$image = URL::base().'images/categories/'.$category->seoname.'.png';
        }
        

        //base title
        if ($category!==NULL)
            $this->template->title = $category->name;
        else
            $this->template->title = __('all');

        
        if ($category_parent!==NULL)
        {
            $this->template->title .=' ('.$category_parent->name.')';
            Breadcrumbs::add(Breadcrumb::factory()->set_title($category_parent->name)
                ->set_url(Route::url('list', array('category'=>$category_parent->seoname))));
        }
            
        
        if ($category!==NULL)
            Breadcrumbs::add(Breadcrumb::factory()->set_title($category->name)
                ->set_url(Route::url('list', array('category'=>$category->seoname))));
        

        //user recognition 
        $user = (Auth::instance()->get_user() == NULL) ? NULL : Auth::instance()->get_user();

        $products = new Model_Product();
        
        //filter by category 
        if ($category!==NULL)
        {
            $products->where('id_category', 'in', $category->get_siblings_ids());
        }

        //only published products
        $products->where('status', '=', Model_Product::STATUS_ACTIVE);
    
        $res_count = $products->count_all();
        // check if there are some advet.-s
        if ($res_count > 0)
        {
   
            // pagination module
            $pagination = Pagination::factory(array(
                    'view'              => 'pagination',
                    'total_items'       => $res_count,
                    'items_per_page'    => core::config('general.products_per_page'),
            ))->route_params(array(
                    'controller'        => $this->request->controller(),
                    'action'            => $this->request->action(),
                    'category'          => ($category!==NULL)?$category->seoname:NULL,
            ));
           
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__("Page ").$pagination->current_page));


            /**
             * order depending on the sort parameter
             */
            switch (core::request('sort',core::config('general.sort_by'))) 
            {
                //title z->a
                case 'title-asc':
                    $products->order_by('title','asc')->order_by('created','desc');
                    break;
                //title a->z
                case 'title-desc':
                    $products->order_by('title','desc')->order_by('created','desc');
                    break;
                //cheaper first
                case 'price-asc':
                    $products->order_by('price','asc')->order_by('created','desc');
                    break;
                //expensive first
                case 'price-desc':
                    $products->order_by('price','desc')->order_by('created','desc');
                    break;
                //featured
                case 'featured':
                default:
                    $products->order_by('featured','desc')->order_by('created','desc');
                    break;
                //oldest first
                case 'published-asc':
                    $products->order_by('created','asc');
                    break;
                //newest first
                case 'published-desc':
                    $products->order_by('created','desc');
                    break;
            }

            //we sort all products with few parameters
            $products = $products->limit($pagination->items_per_page)
                                ->offset($pagination->offset)
                                ->find_all();

            // array of categories sorted for view
            $data = array('products'     => $products,
                     'pagination'   => $pagination, 
                     'user'         => $user, 
                     'category'     => $category,);
        }
        else
        {
            // array of categories sorted for view
            $data = array('products'     => NULL,
                         'pagination'   => NULL, 
                          'user'        => $user, 
                         'category'     => $category,);
        }
        
        
        
        $this->template->bind('content', $content);
        $this->template->content = View::factory('pages/product/listing',$data);

    }

    public function action_search()
    {
        //template header
        $this->template->title              = __('Advanced Search');
        $this->template->meta_description   = __('Advanced Search');

        $this->template->styles = array('http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js');

        //breadcrumbs
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title ));

        $categories       = Model_Category::get_as_array();
        $order_categories = Model_Category::get_multidimensional();

        $pagination = NULL;
        $products   = NULL;

        if($this->request->query())
        {
            $products = new Model_Product();
        
            $category = NULL;
            //filter by category 
            if (core::get('category')!==NULL)
            {
                $category = new Model_Category();
                $category->where('seoname','=',core::get('category'))->limit(1)->find();
                if ($category->loaded())
                    $products->where('id_category', 'IN', $category->get_siblings_ids());
            }

            //filter by title description 
            if (core::get('search')!==NULL AND strlen(core::get('search'))>=3)
            {
                $products
                    ->where_open()
                    ->where('title', 'like', '%'.core::get('search').'%')
                    ->or_where('description', 'like', '%'.core::get('search').'%')
                    ->where_close();
            }

            //filter by price
            if (is_numeric(core::get('price-min')) AND is_numeric(core::get('price-max')))
            {
                $products->where('price', 'BETWEEN', array(core::get('price-min'),core::get('price-max')));
            }


            //only published products
            $products->where('status', '=', Model_Product::STATUS_ACTIVE);
        
            $res_count = $products->count_all();
            // check if there are some advet.-s
            if ($res_count > 0)
            {
       
                // pagination module
                $pagination = Pagination::factory(array(
                        'view'              => 'pagination',
                        'total_items'       => $res_count,
                        'items_per_page'    => core::config('general.products_per_page'),
                ))->route_params(array(
                        'controller'        => $this->request->controller(),
                        'action'            => $this->request->action(),
                        'category'          => ($category!==NULL)?$category->seoname:NULL,
                ));
               
                Breadcrumbs::add(Breadcrumb::factory()->set_title(__("Page ").$pagination->current_page));

                //we sort all products with few parameters
                $products = $products->order_by('created','desc')
                                    ->limit($pagination->items_per_page)
                                    ->offset($pagination->offset)
                                    ->find_all();
            }
        }


        $this->template->bind('content', $content);
        
        $this->template->content = View::factory('pages/search', array('categories'=>$categories,
                                                                        'order_categories'=>$order_categories,
                                                                        'products'=>$products,
                                                                        'pagination'=>$pagination));



    
    }
}