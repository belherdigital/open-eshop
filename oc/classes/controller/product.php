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
         if (!Auth::instance()->logged_in())
            $this->redirect(Route::get('oc-panel')->uri());

        $user = Auth::instance()->get_user();
        
        $order = new Model_Order();
        $order->where('id_order','=',$this->request->param('id'))
                    //->where('status','=',Model_Order::STATUS_PAID)
                    ->where('id_user','=',$user->id_user)
                    ->limit(1)->find();

        if ($order->loaded())
        {
            $product = $order->product;

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
            
            $this->template->bind('content', $content);
            $this->template->content = View::factory('pages/product/goal',array('product'=>$product,'thanks_message'=>$thanks_message,'order'=>$order,'price_paid'=>$order->amount));

        }
        else
        {
            Alert::set(Alert::INFO, __('Order not found.'));
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
            if (Theme::get('premium')!=1)
                $this->redirect($product->url_demo);

            if(core::config('product.count_visits')==1)
            {
                //count how many visits has
                $hits = new Model_Visit();
                $hits = $hits->where('id_product','=', $product->id_product)->count_all();
            }
            else
                $hits = 0;
           
            $this->template->title            = $product->title. ' - '.__('Demo').' - '.$product->category->name;
            $this->template->meta_description = __('Demo').', '.__('preview').','.$product->description;

            $this->template->bind('product', $product);

            //get all the products same category with demo
            $products = $product->category->products
                        ->where('url_demo','IS NOT',NULL)
                        ->where('url_demo','!=','')
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
            $this->template->scripts['footer'][] = '//cdn.jsdelivr.net/jquery.infinitescroll/2.0b2/jquery.infinitescroll.js';
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
        }
        

        //base title
        if ($category!==NULL)
        {
            //category image
            if(( $icon_src = $category->get_icon() )!==FALSE )
                Controller::$image = $icon_src;

            $this->template->title = $category->name;
            
            if ($category->description != '') 
                $this->template->meta_description = $category->description;	            
            else 
                $this->template->meta_description = $category->name.' '.__('sold in').' '.Core::config('general.site_name');

        }
        else
        {
            $this->template->title = __('all');
            $this->template->meta_description = __('List of all products in').' '.Core::config('general.site_name');
        }

        
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
                    'category'          => ($category!==NULL)?$category->seoname:URL::title(__('all')),
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
        $this->template->meta_description   = __('Search in').' '.Core::config('general.site_name');

        $this->template->styles = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js');

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


    //buy product redirects to checkout
    public function action_buy()
    {
        if (!Auth::instance()->logged_in())
            $this->redirect(Route::get('oc-panel')->uri());

        $user = Auth::instance()->get_user();

        $id_product = $this->request->param('id');
        
        if (is_numeric($id_product))
        {
            $product = new Model_Product($id_product);

            if ($product->loaded() AND $product->status == Model_Product::STATUS_ACTIVE)
            {
                //generates a new order if none was existent
                $order = Model_Order::new_order($user, $product);

                //its paid plan?
                if ($product->final_price()>0)
                {
                    // redirect to checkout payment
                    $this->redirect(Route::url('default', array('controller' =>'product','action'=>'checkout' ,'id' => $order->id_order)));
                }
                else
                {                        
                    //mark as paid
                    $order->confirm_payment();
                    //if theres download redirect him to the file 
                    if ($product->has_file()==TRUE)
                        $this->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'download','id'=>$order->id_order)));
                    else
                        $this->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'orders')));
                }
            }
        }
        
        //default redirect
        $this->redirect(Route::get('oc-panel')->uri());
    }


    /**
     * pay!
     */
    public function action_checkout()
    {
        if (!Auth::instance()->logged_in())
            $this->redirect(Route::get('oc-panel')->uri());

        $user = Auth::instance()->get_user();

        //resend confirmation email
        if (is_numeric($id_order = $this->request->param('id')))
        {
            $order = new Model_Order($id_order);
            
            if ($order->loaded() AND $order->id_user == $user->id_user AND $order->status == Model_Order::STATUS_CREATED)
            {              
                //hack jquery paymill
                Paymill::jquery();
  
                //verify the coupon and check order against user information, if its different update order info and maybe price!
                $order->check_pricing();
                
                $this->template->title   = __('Checkout');
                Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));
                    
                $this->template->content = View::factory('pages/product/checkout',array('order'   => $order,
                                                                                        'user'    => $user,
                                                                                        'product' => $order->product));
            }
            else
            {
                Alert::set(Alert::WARNING, __('Order not found or already paid'));
                $this->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'orders')));
            }
        }
        else
        {
            Alert::set(Alert::ERROR, __('Order not found'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'orders')));
        }
        

    }
}