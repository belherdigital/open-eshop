<?php defined('SYSPATH') or die('No direct script access.');

/**
* product class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Chema Garrido <chema@garridodiaz.com>, Slobodan Josifovic <slobodan.josifovic@gmail.com>
* @license GPL v3
*/

class Controller_Product extends Controller{
	
	
	public function action_view()
	{
        //to load the minimal view of the product
        if (core::get('ext',$this->request->param('ext'))=='1')
        {

            $this->before('main-minimal');
            $product_view = 'pages/product-minimal';
            $this->template->styles = array('css/style-minimal.css' => 'screen');
            $this->template->scripts['footer'] = array('js/minimal.js');
        }
        else
           $product_view = 'pages/product'; 
            

        $product = new Model_product();
        $product->where('seotitle','=',$this->request->param('seotitle'))
            ->where('status','=',Model_Product::STATUS_ACTIVE)
            ->limit(1)->find();

        if ($product->loaded())
        {
            
            //adding a visit only if not the owner
            if(!Auth::instance()->logged_in())
                $visitor_id = NULL;
            else
                $visitor_id = Auth::instance()->get_user()->id_user;

            if ($product->id_user!=$visitor_id)
                $new_hit = DB::insert('visits', array('id_product', 'id_user', 'ip_address'))
                        ->values(array($product->id_product, $visitor_id, ip2long(Request::$client_ip)))
                        ->execute();

            //count how many visits has
            $hits = new Model_Visit();
            $hits = $hits->where('id_product','=', $product->id_product)->count_all();


            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
            Breadcrumbs::add(Breadcrumb::factory()->set_title($product->category->name)->set_url(Route::url('list',array('category'=>$product->category->seoname))));
            Breadcrumbs::add(Breadcrumb::factory()->set_title($product->title));
           
            $this->template->title            = $product->title;
            $this->template->meta_description = $product->description;

            $this->template->bind('content', $content);
            
            $this->template->content = View::factory($product_view,array('product'=>$product,'hits'=>$hits));

		}
		else
		{
			Alert::set(Alert::INFO, __('Product not found.'));
            $this->request->redirect(Route::url('default'));
		}
	}

    public function action_listing()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
        
        /**
         * we get the model of category from controller to filter and generate urls titles etc...
         */

        $category = NULL;
        $category_parent = NULL;
        if (Controller::$category!==NULL)
        {
            if (Controller::$category->loaded())
            {
                $category = Controller::$category;
                //adding the category parent
                if ($category->id_category_parent!=1 AND $category->parent->loaded())
                    $category_parent = $category->parent;
            }
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

            //we sort all products with few parameters
            $products = $products->order_by('created','desc')
                                ->limit($pagination->items_per_page)
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
        $this->template->content = View::factory('pages/listing',$data);

    }

    public function action_search()
    {
        //template header
        $this->template->title              = __('Advanced Search');
        $this->template->meta_description   = __('Advanced Search');

        $this->template->styles = array('css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('js/bootstrap-datepicker.js');

        //breadcrumbs
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title ));

        list($categories,$order_categories)  = Model_Category::get_all();

        $pagination = NULL;
        $products   = NULL;

        if($this->request->query())
        {
            $products = new Model_Product();
        
            //filter by category 
            if (core::get('category')!==NULL)
            {
                $category = new Model_Category();
                $category->where('seoname','=',core::get('category'))->limit(1)->find();
                if ($category->loaded())
                    $products->where('id_category', '=', $category->get_siblings_ids());
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