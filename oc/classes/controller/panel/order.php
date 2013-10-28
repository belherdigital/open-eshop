<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Order extends Auth_Crud {

	/**
	* @var $_index_fields ORM fields shown in index
	*/
	protected $_index_fields = array('id_order','id_user','paymethod','amount','status');
	
	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'order';

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
                Model_Order::sale(NULL,$user,$product,NULL,core::post('paymethod'),core::post('pay_date'),core::post('amount'),core::post('currency'));

                //redirect to orders
                Alert::set(Alert::SUCCESS, __('Order created'));
                $this->request->redirect(Route::url('oc-panel',array('controller'=>'order','action'=>'index')));

            }

        }

        $products = new Model_Product();
        $products = $products->find_all();
                         
        $this->template->content = View::factory('oc-panel/pages/order/create',array('products'  =>$products,
                                                                                        'currency'  =>Model_Product::get_currency()));                            
       
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
                        
                        $user = Model_User::create_email($email,substr($email, 0,strpos($email, '@')));

                        $product = new Model_Product();
                        $product->where('seotitle','=',$product_seotitle)->limit(1)->find();
                        
                        if ($product->loaded())
                            Model_Order::sale(NULL,$user,$product,NULL,'Paypal',$pay_date,$amount,$currency);                                                
                    }
                    
                    $i++;
                }
            }
            fclose($handle);

            //redirect to orders
            Alert::set(Alert::SUCCESS, __('Import correct'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'order','action'=>'index')));

        }

        //template header
        $this->template->title  = __('Import Orders');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Import Orders')));

        $this->template->content = View::factory('oc-panel/pages/order/import');

    }

}