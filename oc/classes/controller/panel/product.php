<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Product extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('title','price',);

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'product';

	/**
     * overwrites the default crud index
     * @param  string $view nothing since we don't use it
     * @return void      
     */
    public function action_create()
    {
    	  
        //template header
        $this->template->title  = __('New Product');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('New Product')));
        $this->template->styles              = array('css/sortable.css' => 'screen',
        											 'css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('js/bootstrap-datepicker.js',
        											 'js/oc-panel/products.js',
        											 'js/jquery-sortable-min.js');
        											 
        											 

        list($cats,$order)  = Model_Category::get_all();
        $obj_product = new Model_Product();

        // get currencies from product, returns array
        $currency = $obj_product::get_currency(); 

        $this->template->content = View::factory('oc-panel/pages/products/create',array('categories'		=>$cats,
        																				'order_categories' 	=>$order,
        																				'currency'			=>$currency));

        if($product = $this->request->post())
        {
        	// $new_product = new Model_Product();
        	$id_user = Auth::instance()->get_user()->id_user;
        	
        	// set custom values from POST
        	foreach ($product as $field => $value) 
        	{
        		if($field != 'submit')
        			$obj_product->$field = $value;
        	}
        	$seotitle = $obj_product->gen_seotitle($product['title']);
        	// set non POST values
        	$obj_product->id_user = $id_user;
        	$obj_product->seotitle = $seotitle;
        	$obj_product->status = 1;

        	// save product or trow exeption
        	try 
        	{
        		$obj_product->save();
        		Alert::set(Alert::SUCCESS, __('Product is created.'));
        	} 
        	catch (Exception $e) 
        	{
        		throw new HTTP_Exception_500($e->getMessage());
        	}

        	// images
        	if(isset($_FILES))
        	{
        		foreach ($_FILES as $image) 
        		{ 
        			$image = $obj_product->save_image($image);
        		}
        	}

        	$this->request->redirect(Route::url('oc-panel', array('controller'=>'product','action'=>'index')));
        	
        }

    }

}
