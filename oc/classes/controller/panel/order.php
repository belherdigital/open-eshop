<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Order extends Auth_Crud {

	/**
	* @var $_index_fields ORM fields shown in index
	*/
	protected $_index_fields = array('id_order','id_user','id_product', 'paymethod','amount','status');
	
	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'order';

    /**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var array
     */
    public $crud_actions = array('create','update');

    /**
     *
     * Loads a basic list info
     * @param string $view template to render 
     */
    public function action_index($view = NULL)
    {
        $this->template->title = __('Orders');

        $this->template->styles = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                                    'js/oc-panel/crud/index.js',
                                                    'js/oc-panel/stats/dashboard.js');

        $orders = new Model_Order();
        $orders = $orders->where('status', '=', Model_Order::STATUS_PAID);

        //filter email
        if (core::request('email')!==NULL)
        {
            $user = new Model_User();
            $user->where('email','=',core::request('email'))->limit(1)->find();
            if ($user->loaded())
                $orders = $orders->where('id_user', '=', $user->id_user);
        }

        //filter date
        if (!empty(Core::request('from_date')) AND !empty(Core::request('to_date')))
        {
            //Getting the dates range
            $from_date = Core::request('from_date',strtotime('-1 month'));
            $to_date   = Core::request('to_date',time());

            $orders = $orders->where('pay_date','between',array($from_date,$to_date));
        }

        //filter coupon
        if (is_numeric(core::request('id_coupon')))
        {
            $orders = $orders->where('id_coupon', '=', core::request('id_coupon'));
        }

        //filter product
        if (is_numeric(core::request('id_product')))
        {
            $orders = $orders->where('id_product', '=', core::request('id_product'));
        }
        

        $items_per_page = core::request('items_per_page',10);

        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'total_items'    => $orders->count_all(),
                    'items_per_page' => $items_per_page,
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
        ));

        $pagination->title($this->template->title);

        $orders = $orders->order_by('pay_date','desc')
        ->limit($items_per_page)
        ->offset($pagination->offset)
        ->find_all();

        $pagination = $pagination->render();

        $products = new Model_Product();
        $products = $products->find_all();
        
        $this->render('oc-panel/pages/order/index', array('orders' => $orders,
            'pagination'=>$pagination,
            'products'=>$products));
    }    

    /**
     * overwrites the default crud index
     * @param  string $view nothing since we don't use it
     * @return void      
     */
    public function action_create()
    {
        //template header
        $this->template->title  = __('New Order');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('New Order')));

        if($this->request->post())
        {
            $product = new Model_Product(core::post('product'));

            if($product->loaded())
            {
                $user = Model_User::create_email(core::post('email'),core::post('name'));

                $order = Model_Order::new_order($user, $product);
                $order->confirm_payment(core::post('paymethod'), NULL,core::post('pay_date'),core::post('amount'),core::post('currency'));

                //adding the notes
                $order->notes = core::post('notes');
                $order->save();

                //redirect to orders
                Alert::set(Alert::SUCCESS, __('Order created'));
                $this->redirect(Route::url('oc-panel',array('controller'=>'order','action'=>'index')));

            }

        }

        $products = new Model_Product();
        $products = $products->find_all();
                         
        $this->template->content = View::factory('oc-panel/pages/order/create',array('products'  =>$products,
                                                                                        'currency'  =>Model_Product::get_currency()));                            
       
    }

    /**
     * CRUD controller: UPDATE
     */
    public function action_update()
    {
        $this->template->title = __('Update').' '.__($this->_orm_model).' '.$this->request->param('id');
    
        $form = new FormOrm($this->_orm_model,$this->request->param('id'));
        
        if ($this->request->post())
        {
            if ( $success = $form->submit() )
            {
                $form->save_object();

                //if fraud or refunded....disable licenses!! AND commissions
                if ($form->object->status == Model_Order::STATUS_FRAUD OR $form->object->status == Model_Order::STATUS_REFUND)
                {
                    foreach ($form->object->licenses->find_all() as $l) 
                    {
                        $l->status = Model_License::STATUS_NOACTIVE;
                        $l->save();
                    }
                    //change affiliate commision
                    if ($form->object->affiliate->loaded())
                    {
                        $form->object->affiliate->status = $form->object->status;
                        $form->object->affiliate->save();
                    }
                }

                Alert::set(Alert::SUCCESS, __('Item updated').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1">'
                    .__('Delete All').'</a>');
                $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
            }
            else
            {
                Alert::set(Alert::ERROR, __('Check form for errors'));
            }
        }

        $licenses = new Model_License();
        $licenses = $licenses->where('id_order','=',$this->request->param('id'))->find_all();
    
        return $this->render('oc-panel/pages/order/update', array('form' => $form,'licenses'=>$licenses));
    }



    public function action_import()
    {    
        if($this->request->post())
        {
            ini_set('auto_detect_line_endings', true);

            $csv = $_FILES['file_source']['tmp_name'];
   
            if (($handle = fopen($csv, "r")) !== FALSE) 
            {
                $i = 0;
                while(($data = fgetcsv($handle, 0, ";")) !== false)
                {
                    //avoid first line
                    if ($i!=0)
                    {
                        list($email,$pay_date,$product_seotitle,$amount,$currency) = $data;
                        $pay_date = Date::from_format($pay_date, 'd/m/yy', 'Y-m-d H:i:s');
                        $user = Model_User::create_email($email,substr($email, 0,strpos($email, '@')));

                        $product = new Model_Product();
                        $product->where('seotitle','=',$product_seotitle)->limit(1)->find();
                        
                        if ($product->loaded())
                        {
                            $order = Model_Order::new_order($user, $product);
                            $order->confirm_payment('import', NULL,$pay_date,$amount,$currency);
                        }
                    }
                    
                    $i++;
                }
            }
            fclose($handle);

            //redirect to orders
            Alert::set(Alert::SUCCESS, __('Import correct'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'order','action'=>'index')));

        }

        //template header
        $this->template->title  = __('Import Orders');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Import Orders')));

        $this->template->content = View::factory('oc-panel/pages/order/import');

    }

}